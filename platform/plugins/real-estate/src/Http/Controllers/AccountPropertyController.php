<?php

namespace Botble\RealEstate\Http\Controllers;

use Assets;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Forms\AccountPropertyForm;
use Botble\RealEstate\Http\Requests\AccountPropertyRequest;
use Botble\RealEstate\Models\Account;
use Botble\RealEstate\Models\Calendar;

use Botble\RealEstate\Repositories\Interfaces\AccountActivityLogInterface;
use Botble\RealEstate\Repositories\Interfaces\AccountInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\RealEstate\Services\SaveFacilitiesService;
use Botble\RealEstate\Tables\AccountPropertyTable;
use EmailHandler;
use Exception;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RealEstateHelper;
use SeoHelper;
use Theme;
use Illuminate\Support\Facades\Log; 

use DateTime; // inside Controller Class

class AccountPropertyController extends Controller
{
    /**
     * @var AccountInterface
     */
    protected $accountRepository;

    /**
     * @var PropertyInterface
     */
    protected $propertyRepository;

    /**
     * @var AccountActivityLogInterface
     */
    protected $activityLogRepository;

    /**
     * PublicController constructor.
     * @param Repository $config
     * @param AccountInterface $accountRepository
     * @param PropertyInterface $propertyRepository
     * @param AccountActivityLogInterface $accountActivityLogRepository
     */
    public function __construct(
        Repository $config,
        AccountInterface $accountRepository,
        PropertyInterface $propertyRepository,
        AccountActivityLogInterface $accountActivityLogRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->propertyRepository = $propertyRepository;
        $this->activityLogRepository = $accountActivityLogRepository;

        Assets::setConfig($config->get('plugins.real-estate.assets'));
    }

    /**
     * @param Request $request
     * @param AccountPropertyTable $propertyTable
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View|\Response
     * @throws \Throwable
     */
    public function index(Request $request, AccountPropertyTable $propertyTable)
    {
        SeoHelper::setTitle(trans('plugins/real-estate::account-property.properties'));

        $user = auth('account')->user();

        if ($request->isMethod('get') && view()->exists(Theme::getThemeNamespace('views.real-estate.account.table.index'))) {
            return Theme::scope('real-estate.account.table.index', ['user' => $user, 'propertyTable' => $propertyTable])
                ->render();
        }

        return $propertyTable->render('plugins/real-estate::account.table.base');
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @throws \Throwable
     */
    public function create(FormBuilder $formBuilder)
    {
        if (!auth('account')->user()->canPost()) {
            return back()->with(['error_msg' => trans('plugins/real-estate::package.add_credit_alert')]);
        }

        SeoHelper::setTitle(trans('plugins/real-estate::account-property.write_property'));

        if (view()->exists(Theme::getThemeNamespace('views.real-estate.account.forms.index_property'))) {
            $user = auth('account')->user();
            $form = $formBuilder->create(AccountPropertyForm::class);
            $form->setFormOption('template', Theme::getThemeNamespace() . '::views.real-estate.account.forms.base');
            return Theme::scope('real-estate.account.forms.index_property',
                ['user' => $user, 'formBuilder' => $formBuilder, 'form' => $form])->render();
        }

        return $formBuilder->create(AccountPropertyForm::class)->renderForm();
    }

    /**
     * @param AccountPropertyRequest $request
     * @param BaseHttpResponse $response
     * @param AccountInterface $accountRepository
     * @param SaveFacilitiesService $saveFacilitiesService
     * @return BaseHttpResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(
        AccountPropertyRequest $request,
        BaseHttpResponse $response,
        AccountInterface $accountRepository,
        SaveFacilitiesService $saveFacilitiesService
    ) {
        if (!auth('account')->user()->canPost()) {
            return back()->with(['error_msg' => trans('plugins/real-estate::package.add_credit_alert')]);
        }
        
        $request->merge(['expire_date' => now()->addDays(RealEstateHelper::propertyExpiredDays())]);
        
        $property = $this->propertyRepository->getModel();
        
       
        $property->fill(array_merge($request->input(), [
            'author_id'   => auth('account')->id(),
            'author_type' => Account::class,
        ]));
        
        if (setting('enable_post_approval', 1) == 0) {
            $property->moderation_status = ModerationStatusEnum::APPROVED;
        } 



        // ---------------------------------
        if($property->type->is_fixable == 1){
            $property->brand = null;
            $property->made_in = null;
            $property->color = null;
            $property->weight = null;
            $property->length = null;
            $property->width = null; 
            $property->unitstype = json_encode($request->unitstype);
            $property->spaces = json_encode($request->spaces);
            $property->addons = json_encode($request->addons); 
        }  
        if(!isset($request->except(['expire_date'])['unitstype'])){
            $property->unitstype = null;
        }
        if(!isset($request->except(['expire_date'])['spaces'])){
            $property->spaces = null;
        }
        if(!isset($request->except(['expire_date'])['addons'])){
            $property->addons = null;
        }
        // ---------------------------------


        $property = $this->propertyRepository->createOrUpdate($property);
        // dd($request->input());
        
        if ($property) {
            $property->features()->sync($request->input('features', []));

            $saveFacilitiesService->execute($property, $request->input('facilities', []));
        }

        event(new CreatedContentEvent(PROPERTY_MODULE_SCREEN_NAME, $request, $property));

        $this->activityLogRepository->createOrUpdate([
            'action'         => 'create_property',
            'reference_name' => $property->name,
            'reference_url'  => route('public.account.properties.edit', $property->id),
        ]);

        $account = $accountRepository->findOrFail(auth('account')->id());
        $account->credits--;
        $account->save();

        EmailHandler::setModule(REAL_ESTATE_MODULE_SCREEN_NAME)
            ->setVariableValues([
                'post_name'   => $property->name,
                'post_url'    => route('property.edit', $property->id),
                'post_author' => $property->author->name,
            ])
            ->sendUsingTemplate('new-pending-property');

        return $response
            ->setPreviousUrl(route('public.account.properties.index'))
            ->setNextUrl(route('public.account.properties.edit', $property->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @return string
     *
     * @throws \Throwable
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $property = $this->propertyRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth('account')->id(),
            'author_type' => Account::class,
        ]);

        if (!$property) {
            abort(404);
        }

        event(new BeforeEditContentEvent($request, $property));

        SeoHelper::setTitle(trans('plugins/real-estate::property.edit') . ' "' . $property->name . '"');

        if (view()->exists(Theme::getThemeNamespace('views.real-estate.account.forms.index'))) {
            $user = auth('account')->user();
            $form = $formBuilder->create(AccountPropertyForm::class, ['model' => $property]);
            $form->setFormOption('template', Theme::getThemeNamespace() . '::views.real-estate.account.forms.base');
            return Theme::scope('real-estate.account.forms.index',
                ['user' => $user, 'formBuilder' => $formBuilder, 'form' => $form])->render();
        }

        return $formBuilder
            ->create(AccountPropertyForm::class, ['model' => $property])
            ->renderForm();
    }

    /**
     * @param int $id
     * @param AccountPropertyRequest $request
     * @param BaseHttpResponse $response
     * @param SaveFacilitiesService $saveFacilitiesService
     * @return BaseHttpResponse
     *
     */
    public function update(
        $id,
        AccountPropertyRequest $request,
        BaseHttpResponse $response,
        SaveFacilitiesService $saveFacilitiesService
    ) {
        $property = $this->propertyRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth('account')->id(),
            'author_type' => Account::class,
        ]);

        if (!$property) {
            abort(404);
        }

        $property->fill($request->except(['expire_date']));
        // dd($request->except(['expire_date']));
        
        // ---------------------------------
        if($property->type->is_fixable == 1){
            $property->brand = null;
            $property->made_in = null;
            $property->color = null;
            $property->weight = null;
            $property->length = null;
            $property->width = null; 
        } 
        // dd($property);
        // dd($request->except(['expire_date']));
        if(!isset($request->except(['expire_date'])['unitstype'])){
            $property->unitstype = null;
        }
        if(!isset($request->except(['expire_date'])['spaces'])){
            $property->spaces = null;
        }
        if(!isset($request->except(['expire_date'])['addons'])){
            $property->addons = null;
        }
        // ---------------------------------
        $this->propertyRepository->createOrUpdate($property);

        $property->features()->sync($request->input('features', []));

        $saveFacilitiesService->execute($property, $request->input('facilities', []));

        event(new UpdatedContentEvent(PROPERTY_MODULE_SCREEN_NAME, $request, $property));

        $this->activityLogRepository->createOrUpdate([
            'action'         => 'update_property',
            'reference_name' => $property->name,
            'reference_url'  => route('public.account.properties.edit', $property->id),
        ]);

        return $response
            ->setPreviousUrl(route('public.account.properties.index'))
            ->setNextUrl(route('public.account.properties.edit', $property->id))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function destroy($id, BaseHttpResponse $response)
    {
        $property = $this->propertyRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth('account')->id(),
            'author_type' => Account::class,
        ]);

        if (!$property) {
            abort(404);
        }

        $this->propertyRepository->delete($property);

        $this->activityLogRepository->createOrUpdate([
            'action'         => 'delete_property',
            'reference_name' => $property->name,
        ]);

        return $response->setMessage(__('Delete property successfully!'));
    }

    /**
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function renew($id, BaseHttpResponse $response)
    {
        $job = $this->propertyRepository->findOrFail($id);

        $account = auth('account')->user();

        if ($account->credits < 1) {
            return $response->setError(true)->setMessage(__('You don\'t have enough credit to renew this property!'));
        }

        $job->expire_date = $job->expire_date->addDays(RealEstateHelper::propertyExpiredDays());
        $job->save();

        $account->credits--;
        $account->save();

        return $response->setMessage(__('Renew property successfully'));
    }
    
    
    









   
    public function calendar($id, FormBuilder $formBuilder, Request $request, BaseHttpResponse $response)
    {
        $property = $this->propertyRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth('account')->id(),
            'author_type' => Account::class,
        ]);

        if (!$property) {
            abort(404);
        }
 
        SeoHelper::setTitle(trans('calendar')); 
        if (view()->exists(Theme::getThemeNamespace('views.real-estate.account.settings.index'))) {
            // dd("ss");
            $calendar_days = Calendar::where('re_properties_id', $property->id)->get();

            
    
                // $start = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
                // $end = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');
        
                // $data = events::whereDate('start', '>=', $start)->whereDate('end',   '<=', $end)->get(['id','title','start', 'end']);
                $events = array();
                foreach($calendar_days as $calendar){
                    $events[] = [
                        'id' => $calendar->id,
                        'title' => $calendar->price,
                        'start' => $calendar->date,
                        'end' => $calendar->date, 
                    ];
                }
                
                    //         return $response->setData(['data' => $Event ]);
                    //         return $response->setMessage(__('Renew property successfully'));
          
            return Theme::scope('real-estate.account.forms.calendar', ['events' => $events, 'property' =>  $property ])->render();
        } 
        return view('plugins/real-estate::account.settings.index');
 
    }


    

    public function calendar_create($id, FormBuilder $formBuilder, Request $request,  BaseHttpResponse $response)
    {
        $property = $this->propertyRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth('account')->id(),
            'author_type' => Account::class,
        ]);

        if (!$property) {
            abort(404);
        } 
        if(request()->ajax()) 
        {

            $insertArr = [ 
                        're_properties_id' => $property->id,
                        'price' => $request->price,
                        'date' => $request->start,
                        // 'status' => $request->status,   
                        ];

            Log::info("--------magh - calendar_create-----");
            Log::info($request->toArray());

            if($request->start == $request->end){
                $where = Calendar::where('re_properties_id' ,$property->id)->where('date', $request->start)->get();
            
                if($where->count() > 0){
        
                    $Calendar =  Calendar::where('re_properties_id', $property->id)->where('date', $request->start)->update(['price' => $request->price]);
        
                    return $response->setData([
                        'data' => 'erorr' ,
                        'where' => $where->toArray() 
                    ]);
                }
                $event = Calendar::insert($insertArr); 
                
                return $response->setMessage(__('successfully'));  
            }else{
    
                $startDate = new DateTime($request->start);
                $endDate   = new DateTime($request->end);
                
                $daysDifference = ($startDate->diff($endDate)->days);

                Log::info("-------- magh - for - calendar_create-----");
                Log::info($daysDifference);
                // for($i=1; $i >= $daysDifference; $i++ ){

                // }
                return $response->setData([
                    'data' => 'null' ,
                    'where' => null 
                ]);
            }
        }

  Log::info("--------magh - calendar_create with render-----");
  Log::info($request->toArray());

        return Theme::scope('real-estate.account.forms.calendar', ['events' => $events, 'property' =>  $property ])->render();
        // return Response::json($event);


    }


    public function calendar_update($id, FormBuilder $formBuilder, Request $request,  BaseHttpResponse $response){
        $property = $this->propertyRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth('account')->id(),
            'author_type' => Account::class,
        ]);

        if (!$property) {
            abort(404);
        } 
       
        // $start = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
        // $end = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');
 
  
        $Arr = [  
            'price' => (string)$request->title,
            'date' => (string)$request->start,
            'status' => 1,   
            ];




        $data = Calendar::where('id', $request->id)->where('re_properties_id', $id)->update($Arr);
          
        return $response->setMessage(__('Renew property successfully'));

    }



    public function calendar_delete($id, FormBuilder $formBuilder, Request $request,  BaseHttpResponse $response){
        
        
        Log::info("--------magh - delete-----");
        Log::info($request->toArray());
        $event = Calendar::where('id',$request->id_calecdar)->delete();

        return $response->setData($event);
        // return $response->setMessage(__('Renew property successfully'));
    }


    // public function index()
    // {
    //     if(request()->ajax()) 
    //     {
 
    //      $start = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
    //      $end = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');
 
    //      $data = Event::whereDate('start', '>=', $start)->whereDate('end',   '<=', $end)->get(['id','title','start', 'end']);
    //      return Response::json($data);
    //     }
    //     return view('fullcalendar');
    // }
    
   
    // public function create(Request $request)
    // {  
    //     $insertArr = [ 'title' => $request->title,
    //                    'start' => $request->start,
    //                    'end' => $request->end
    //                 ];
    //     $event = Event::insert($insertArr);   
    //     return Response::json($event);
    // }
     
 
    // public function update(Request $request)
    // {   
    //     $where = array('id' => $request->id);
    //     $updateArr = ['title' => $request->title,'start' => $request->start, 'end' => $request->end];
    //     $event  = Event::where($where)->update($updateArr);
 
    //     return Response::json($event);
    // } 
 
 
    // public function destroy(Request $request)
    // {
    //     $event = Event::where('id',$request->id)->delete();
   
    //     return Response::json($event);
    // }    


    




    

}
