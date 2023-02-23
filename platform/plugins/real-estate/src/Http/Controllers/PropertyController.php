<?php

namespace Botble\RealEstate\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Forms\PropertyForm;
use Botble\RealEstate\Http\Requests\PropertyRequest;
use Botble\RealEstate\Repositories\Interfaces\FeatureInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\RealEstate\Services\SaveFacilitiesService;
use Botble\RealEstate\Tables\PropertyTable;
use Botble\RealEstate\Models\Account;
use Botble\RealEstate\Models\PropertiesFeatureCategories;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

use RealEstateHelper;
use Throwable;

class PropertyController extends BaseController
{
    /**
     * @var PropertyInterface $propertyRepository
     */
    protected $propertyRepository;

    /**
     * @var FeatureInterface
     */
    protected $featureRepository;

    /**
     * PropertyController constructor.
     * @param PropertyInterface $propertyRepository
     * @param FeatureInterface $featureRepository
     */
    public function __construct(
        PropertyInterface $propertyRepository,
        FeatureInterface $featureRepository
    ) {
        $this->propertyRepository = $propertyRepository;
        $this->featureRepository = $featureRepository;
    }
    
    /**
     * @param PropertyTable $dataTable
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function index(PropertyTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/real-estate::property.name'));

        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/real-estate::property.create'));
        

        return $formBuilder->create(PropertyForm::class)->renderForm();
    }

    /**
     * @param PropertyRequest $request
     * @param BaseHttpResponse $response
     * @param SaveFacilitiesService $saveFacilitiesService
     * @return BaseHttpResponse
     * @throws FileNotFoundException
     */
    public function store(PropertyRequest $request, BaseHttpResponse $response, SaveFacilitiesService $saveFacilitiesService)
    {
        $propertyData = $request->all();
        $features = !empty($propertyData['features']) ? $propertyData['features'] : [];
     
        $request->except(['features']);   
        $data = $request->merge([
            'expire_date' => now()->addDays(RealEstateHelper::propertyExpiredDays()),
            'images'      => json_encode(array_filter($request->input('images', []))),
            'author_type' => Account::class
        ]);  
        $property = $this->propertyRepository->getModel();
        $property = $property->fill($request->input());
        // $property->moderation_status = $request->input('moderation_status');
        $property->never_expired = $request->input('never_expired');
        $property->save();

        event(new CreatedContentEvent(PROPERTY_MODULE_SCREEN_NAME, $request, $property));

        // if ($property) {
            // $property->features()->sync($request->input('features', []));
            // $saveFacilitiesService->execute($property, $request->input('facilities', []));
        // }
 
            $features = !empty($propertyData['features']) ? $propertyData['features'] : [];
        $this->UpdatePropertiesFeatureCategories($property->id,$features);
 
        return $response
            ->setPreviousUrl(route('property.index'))
            ->setNextUrl(route('property.edit', $property->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, Request $request, FormBuilder $formBuilder)
    {
        $property = $this->propertyRepository->findOrFail($id, ['features', 'author']);
        
        page_title()->setTitle(trans('plugins/real-estate::property.edit') . ' "' . $property->name . '"');


        event(new BeforeEditContentEvent($request, $property));

        return $formBuilder->create(PropertyForm::class, ['model' => $property])->renderForm();
    }

    public function UpdatePropertiesFeatureCategories($property_id,$data)
    {
        if($property_id !== null)
        \DB::table('feature_categories')->where('property_id', $property_id)->delete();
       
        $features = [];
        foreach($data as $fid => $value){
            $features[] = [
                'property_id' => $property_id,
                'feature_id' => $fid,
                'value' => trim($value),
            ];
            \DB::table('feature_categories')->insert(
                [
                    'property_id' => $property_id,
                    'feature_id' => $fid,
                    'value' => trim($value),
                ]
           );
        }

         
        // foreach ($features as $ftr) {
        //     $featureCategories = new PropertiesFeatureCategories($ftr);
        //     $featureCategories->save();
        // }   
        return true;
    }
 
    /**
     * @param int $id
     * @param PropertyRequest $request
     * @param BaseHttpResponse $response
     * @param SaveFacilitiesService $facilitiesService
     * @return BaseHttpResponse
     * @throws FileNotFoundException
    **/
    public function update($id, PropertyRequest $request, BaseHttpResponse $response, SaveFacilitiesService $saveFacilitiesService)
    {  
        $data = $request->all();
        $features = !empty($data['features']) ? $data['features'] : [];
    
        $request->except(['features']);
        $property = $this->propertyRepository->findOrFail($id);

        do_action(ACTION_BEFORE_UPDATE_PROPERTY, $request, $property);

        $property->fill($request->except(['expire_date']));
        $property->author_type = Account::class;
        $property->images = json_encode(array_filter($request->input('images', [])));
        $property->moderation_status = $request->input('moderation_status');
        $property->never_expired = $request->input('never_expired');

        $this->propertyRepository->createOrUpdate($property);

        event(new UpdatedContentEvent(PROPERTY_MODULE_SCREEN_NAME, $request, $property));
 
        $this->UpdatePropertiesFeatureCategories($property->id,$features);
 
        return $response
            ->setPreviousUrl(route('property.index'))
            ->setNextUrl(route('property.edit', $property->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy($id, Request $request, BaseHttpResponse $response)
    {
      
        try {
            $property = $this->propertyRepository->findOrFail($id);
            $property->features()->detach();
            $this->propertyRepository->delete($property);

            event(new DeletedContentEvent(PROPERTY_MODULE_SCREEN_NAME, $request, $property));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } 
        catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.cannot_delete'));
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
         $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $property = $this->propertyRepository->findOrFail($id);
            $property->features()->detach();
            $this->propertyRepository->delete($property);

            event(new DeletedContentEvent(PROPERTY_MODULE_SCREEN_NAME, $request, $property));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return String
     */
    public function get_features_by_category($id, BaseHttpResponse $response){

        $_features = DB::table('re_features') 
        ->join('re_feature_types', 're_features.id', '=', 're_feature_types.feature_id')
        ->join('re_feature_groups', 're_feature_groups.id', '=', 're_features.group')
        
        ->select('re_features.*','re_feature_groups.name as group_name','re_feature_groups.order as gorder')
        ->where('re_feature_types.type_id', '=', $id)
        ->orderBy('re_feature_groups.order', 'ASC')
        ->get()->toArray();

        $lang_code = !empty($_REQUEST['ref_lang']) ? $_REQUEST['ref_lang'] : session('editing_language');
       
        $feature_lang = [];
        if( !empty($_features) ){
            $_feature_lang = DB::select("SELECT `reference_id`, `field`, `value`  FROM `language_meta` WHERE `reference_type` = 'Botble\\\RealEstate\\\Models\\\Feature' AND `lang_meta_code` = '$lang_code' AND `reference_id` IN (".implode(",",array_map(function($f){ return $f->id; },$_features)).")");
            foreach($_feature_lang as $fl){
                $feature_lang[$fl->reference_id][$fl->field] = $fl->value;
            }

            $_feature_grp_lang = \DB::select("SELECT `reference_id`, `field`, `value`  FROM `language_meta` WHERE `reference_type` = 'Botble\\\RealEstate\\\Models\\\FeatureGroups' AND `lang_meta_code` = '$lang_code' AND field LIKE 'name' AND `reference_id` IN (".implode(",",array_unique(array_map(function($f){ return (int)$f->group; },$_features))).")");
            foreach($_feature_grp_lang as $fl){
                $feature_grp_lang[$fl->reference_id] = $fl->value;
            }
        
        }
        foreach($_features as $fidx => $frow){
            if( isset($feature_lang[$frow->id]['name']) )
                $_features[$fidx]->name = $feature_lang[$frow->id]['name'];
            if( isset($feature_grp_lang[$frow->group]) )
                $_features[$fidx]->group_name = $feature_grp_lang[$frow->group];
        }

        $features = [];
        foreach($_features as $feature){

            $options = [];
            $_options = !empty($feature->select_options) ? explode(',',$feature->select_options) : [];
            foreach($_options as $o){
                $okey = $oval = trim($o);
                if( strpos($o,':') !== false ){
                    $oval = explode(":",$oval);
                    $okey = trim($oval[0]);
                    $oval = trim(@$oval[1]);
                }
                if( $oval != '')
                    $options[$okey] = $oval; 
            }

            $feature->select_options = $options;

            $feature->value = '';

            if( !isset($features[$feature->group]))
                $features[$feature->group] = ['name'=>$feature->group_name];

            $features[$feature->group]['fields'][$feature->id] =$feature;
        }

        foreach($features as $fid => $ftr){
            usort($ftr['fields'],function($r1,$r2){ return (int)$r1->order > (int)$r2->order; });
            $features[$fid] = (object)$ftr;
        }
        if( count($features) ){
        ob_start(); 
        foreach ($features as $group){ ?>

        <div class="feature_groups form-body">
            <h5><?php echo $group->name ?></h5>
            <div class="fields row">
            <?php foreach ($group->fields as $feature) {?>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group mb-3">
                        <label for="feature_<?php echo $feature->id;?>" class="control-label <?php echo $feature->type != 'checkbox' ? 'tp' :''?>"><?php echo $feature->name;?></label> 
                        <?php if( $feature->type == 'select') {?>
                            <select class="form-control" id="feature_<?php echo $feature->id;?>" name="features[<?php echo $feature->id;?>]">
                                <option value="">[ None ]</option>
                                <?php foreach ($feature->select_options as $oid => $oval){ ?>
                                    <option value="<?php echo $oid;?>" <?php echo ($feature->value == $oid) ? "selected":"";?>><?php echo $oval;?></option>
                                <?php } ?>
                            </select>
                        <?php }else if( $feature->type == 'checkbox') { ?>
                            
                            <div class="onoffswitch">
                                <input type="hidden" name="features[<?php echo $feature->id;?>]" value="N" />
                                <input type="checkbox" id="feature_<?php echo $feature->id;?>" name="features[<?php echo $feature->id;?>]" <?php echo ($feature->value == "Y") ? "checked":"";?> class="onoffswitch-checkbox" value="Y" />

                                <label class="onoffswitch-label" for="feature_<?php echo $feature->id;?>">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>

                        <?php } else { ?>
                            <input class="form-control" id="feature_<?php echo $feature->id;?>" type="text" name="features[<?php echo $feature->id;?>]" value="<?php echo $feature->value;?>" />
                        <?php } ?>
                    </div>
                </div>
            <?php } ?> 
            </div>
        </div>
        <?php } $html = ob_get_contents();
        } else
            $html = false; 
        ob_end_clean();
        return $response->setData($html);
    
    }
} 

if(!function_exists('pr')) {
    function pr($p, $func="print_r",$r=false)    {
        if(!$func) $func='print_r';
         $bt = debug_backtrace();
        $caller = array_shift($bt);
        $file_line = "<strong>" . $caller['file'] . "(line " . $caller['line'] . ")</strong>\n";
        if(!$r)    { //if print
            echo '<pre>';
            print_r($file_line);
            $func($p);
            echo '</pre> ';
        } else { //if return
            ob_start();
            echo '<pre>';
            print_r($file_line);
            $func($p);
            echo '<pre>';
            $d = ob_get_contents();
            ob_end_clean();
            if(filter_var($r, FILTER_VALIDATE_EMAIL)) {
                $headers = 'From: webmaster@example.com' . "\r\n" .
                'Reply-To: webmaster@example.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
                mail($r, 'Debug Output', $d, $headers);
            }
            return $d;
        }
    }
} 