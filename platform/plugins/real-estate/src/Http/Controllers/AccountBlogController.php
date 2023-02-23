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
use Botble\RealEstate\Forms\AccountBlogForm;
use Botble\RealEstate\Http\Requests\AccountPropertyRequest;
use Botble\RealEstate\Models\Account;
use Botble\RealEstate\Repositories\Interfaces\AccountActivityLogInterface;
use Botble\RealEstate\Repositories\Interfaces\AccountInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\RealEstate\Repositories\Interfaces\BlogInterface;
use Botble\RealEstate\Services\SaveFacilitiesService;
use Botble\RealEstate\Tables\AccountPropertyTable; 
use Botble\RealEstate\Tables\PostAccountTable;
use EmailHandler;
use Exception;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RealEstateHelper;
use SeoHelper;
use Theme;




use Botble\ACL\Models\User; 
use Botble\Base\Events\DeletedContentEvent; 
use Botble\Base\Traits\HasDeleteManyItemsTrait;
use Botble\Blog\Forms\PostForm;
use Botble\Blog\Http\Requests\PostRequest;
use Botble\Blog\Models\Post;
use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Blog\Repositories\Interfaces\TagInterface;
use Botble\Blog\Services\StoreCategoryService;
use Botble\Blog\Services\StoreTagService;
use Botble\Blog\Tables\PostTable; 
use Illuminate\Contracts\View\Factory; 
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;
use RvMedia;



class AccountBlogController extends Controller
{
    use HasDeleteManyItemsTrait;
 
    protected $postRepository;
 
    protected $tagRepository;
 
    protected $categoryRepository;


    protected $accountRepository;
    protected $activityLogRepository;

   
    public function __construct(
        Repository $config,
        PostInterface $postRepository,
        TagInterface $tagRepository,
        CategoryInterface $categoryRepository,

        AccountInterface $accountRepository,
        AccountActivityLogInterface $accountActivityLogRepository
    ) {
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
        $this->categoryRepository = $categoryRepository;
        
        $this->accountRepository = $accountRepository;
        $this->activityLogRepository = $accountActivityLogRepository;
        Assets::setConfig($config->get('plugins.real-estate.assets'));
    }

    

    

    public function index(Request $request, AccountPropertyTable $propertyTable, PostAccountTable $dataTable)
    { 

        SeoHelper::setTitle(trans('plugins/real-estate::account-property.properties'));

        $user = auth('account')->user();

        if ($request->isMethod('get') && view()->exists(Theme::getThemeNamespace('views.real-estate.account.table.index'))) {
            return Theme::scope('real-estate.account.table.index', ['user' => $user, 'propertyTable' => $dataTable])
                ->render();
        }

        return $dataTable->render('plugins/real-estate::account.table.base');
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

        if (view()->exists(Theme::getThemeNamespace('views.real-estate.account.forms.index'))) {
            $user = auth('account')->user();
            $form = $formBuilder->create(AccountBlogForm::class);
            $form->setFormOption('template', Theme::getThemeNamespace() . '::views.real-estate.account.forms.base');
            return Theme::scope('real-estate.account.forms.index',
                ['user' => $user, 'formBuilder' => $formBuilder, 'form' => $form])->render();
        }

        return $formBuilder->create(AccountBlogForm::class)->renderForm();
    }

    /**
     * @param PostRequest $request
     * @param BaseHttpResponse $response
     * @param AccountInterface $accountRepository
     * @param SaveFacilitiesService $saveFacilitiesService
     * @return BaseHttpResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(
        PostRequest $request,
        BaseHttpResponse $response,
        StoreTagService $tagService,
        StoreCategoryService $categoryService,
        AccountInterface $accountRepository,
        SaveFacilitiesService $saveFacilitiesService
    ) {
        if (!auth('account')->user()->canPost()) {
            return back()->with(['error_msg' => trans('plugins/real-estate::package.add_credit_alert')]);
        }

        $request->merge(['expire_date' => now()->addDays(RealEstateHelper::propertyExpiredDays())]);
        // dd(request()->file('image'));
        // ----------------------------------
        // $folder = \Botble\Media\Models\MediaFolder::create([
        //     'name' => 'Example',
        //     'slug' => 'example',
        // ]);
        // $fileUpload = new \Illuminate\Http\UploadedFile(database_path('files/example.png'), 'example.png', RvMedia::getDefaultImage(), null, true);
        // $image = \RvMedia::handleUpload($fileUpload, $folder->id);
         
        // return $this->image ? RvMedia::getImageUrl($this->image, 'thumb', false, RvMedia::getDefaultImage()) : null;
     
        // -----------------------------------

        
        $property = $this->postRepository->getModel();

        $property->fill(array_merge($request->input(), [
            'author_id'   => auth('account')->id(),
            'author_type' => Account::class,
        ]));
 
        // $property->status = ModerationStatusEnum::PENDING;
        
        $property->fill($request->except(['expire_date']));
        if(isset($request->image)){ 
            $image = rv_media_handle_upload(request()->file('image'), 0, 'news');
            $property->image = $image['data']->url;
            
        }else{
            
            $property->image  = 'news/3.jpg'; 
            

         }


        $property = $this->postRepository->createOrUpdate($property); 

        event(new CreatedContentEvent(POST_MODULE_SCREEN_NAME, $request, $property));
 

        $tagService->execute($request, $property);

        $categoryService->execute($request, $property);

        // $this->activityLogRepository->createOrUpdate([
        //     'action'         => 'create_property',
        //     'reference_name' => $property->name,
        //     'reference_url'  => route('public.account.blogs.edit', $property->id),
        // ]);
 

        // EmailHandler::setModule(REAL_ESTATE_MODULE_SCREEN_NAME)
        //     ->setVariableValues([
        //         'post_name'   => $property->name,
        //         'post_url'    => route('property.edit', $property->id),
        //         'post_author' => $property->author->name,
        //     ])
        //     ->sendUsingTemplate('new-pending-property');

        return $response
            ->setPreviousUrl(route('public.account.blogs.index'))
            ->setNextUrl(route('public.account.blogs.edit', $property->id))
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
        $property = $this->postRepository->findOrFail($id);

        // $property = $this->postRepository->getFirstBy([
        //     'id'          => $id,
        //     'author_id'   => auth('account')->id(),
        //     'author_type' => Account::class,
        // ]);

        if (!$property) {
            abort(404);
        }

        event(new BeforeEditContentEvent($request, $property));

        SeoHelper::setTitle(trans('plugins/blog::posts.edit') . ' "' . $property->name . '"');

        if (view()->exists(Theme::getThemeNamespace('views.real-estate.account.forms.index'))) {
            $user = auth('account')->user();
            $form = $formBuilder->create(AccountBlogForm::class, ['model' => $property]);
            $form->setFormOption('template', Theme::getThemeNamespace() . '::views.real-estate.account.forms.base');
            return Theme::scope('real-estate.account.forms.index',
                ['user' => $user, 'formBuilder' => $formBuilder, 'form' => $form])->render();
        }

        return $formBuilder
            ->create(AccountBlogForm::class, ['model' => $property])
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
        PostRequest $request,
        BaseHttpResponse $response,
        StoreTagService $tagService,
        StoreCategoryService $categoryService,
        SaveFacilitiesService $saveFacilitiesService
    ) {
        $property = $this->postRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth('account')->id(),
            'author_type' => Account::class,
        ]);

        if (!$property) {
            abort(404);
        }

        // dd($request->except(['expire_date']));
        $property->fill($request->except(['expire_date']));
        if(isset($request->image)){ 
            $image = rv_media_handle_upload(request()->file('image'), 0, 'news');
            $property->image = $image['data']->url;
        }else{
             
        }

        $this->postRepository->createOrUpdate($property);

 
        $saveFacilitiesService->execute($property, $request->input('facilities', []));

        event(new UpdatedContentEvent(POST_MODULE_SCREEN_NAME, $request, $property));

        $tagService->execute($request, $property);

        $categoryService->execute($request, $property);
        // $this->activityLogRepository->createOrUpdate([
        //     'action'         => 'update_property',
        //     'reference_name' => $property->name,
        //     'reference_url'  => route('public.account.properties.edit', $property->id),
        // ]);

        return $response
            ->setPreviousUrl(route('public.account.blogs.index'))
            ->setNextUrl(route('public.account.blogs.edit', $property->id))
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
        $property = $this->postRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth('account')->id(),
            'author_type' => Account::class,
        ]);

        if (!$property) {
            abort(404);
        }

        $this->postRepository->delete($property);

        // $this->activityLogRepository->createOrUpdate([
        //     'action'         => 'delete_property',
        //     'reference_name' => $property->name,
        // ]);

        return $response->setMessage(__('Delete property successfully!'));
    }






    public function postLike(
        Request $request,
        BaseHttpResponse $response
    )
    {
        $property = $this->postRepository->getFirstBy([
            'id'          => $request['post_id'], 
        ]);
        // dd($property->likes);
        
        $property->update([
            'likes' =>  $property->likes =$property->likes+1
        ]);

        // $review = $this->commentRepository->createOrUpdate($request->input());
        // $review = Post::create($request->input());
         

        return $response->setMessage(__('Added like successfully!'));
    }







     /**
     * Get list tags in db
     *
     * @return array
     */
    public function getAllTags()
    {
        return $this->tagRepository->pluck('name');
    }
    

    /**
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function renew($id, BaseHttpResponse $response)
    {
        $job = $this->postRepository->findOrFail($id);

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
}
