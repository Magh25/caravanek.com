<?php 
namespace Botble\RealEstate\Forms;

use Assets;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\RealEstate\Forms\Fields\AddonsField;
use Botble\Location\Repositories\Interfaces\CityInterface;
use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Enums\PropertyPeriodEnum;
use Botble\RealEstate\Enums\PropertyTypeEnum;
use Botble\RealEstate\Forms\Fields\ParkingSpace;
use Botble\RealEstate\Forms\Fields\Unitstype;
use Botble\RealEstate\Http\Requests\PropertyRequest;
use Botble\RealEstate\Models\Property;
use Botble\RealEstate\Repositories\Interfaces\CategoryInterface;
use Botble\RealEstate\Repositories\Interfaces\CurrencyInterface;
use Botble\RealEstate\Repositories\Interfaces\FacilityInterface;
use Botble\RealEstate\Repositories\Interfaces\FeatureInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\RealEstate\Repositories\Interfaces\TypeInterface;
use Illuminate\Support\Facades\DB; 
use RealEstateHelper;
use Throwable; 
class PropertyForm extends FormAbstract
{
    /**
     * @var FacilityInterface
     */
    protected $facilityRepository;

    /**
     * @var PropertyInterface
     */
    protected $propertyRepository;

    /**
     * @var FeatureInterface
     */
    protected $featureRepository;

    /**
     * @var CurrencyInterface
     */
    protected $currencyRepository;

    /**
     * @var CityInterface
     */
    protected $cityRepository;

    /**
     * @var CategoryInterface
     */
    protected $categoryRepository;

    /**
     * @var TypeInterface
     */
    protected $typeRepository;

    /**
     * PropertyForm constructor.
     * @param PropertyInterface $propertyRepository
     * @param FeatureInterface $featureRepository
     * @param CurrencyInterface $currencyRepository
     * @param CityInterface $cityRepository
     * @param CategoryInterface $categoryRepository
     * @param TypeInterface $typeRepository
     * @param FacilityInterface $facilityRepository
     */
    public function __construct(
        PropertyInterface $propertyRepository,
        FeatureInterface $featureRepository,
        CurrencyInterface $currencyRepository,
        CityInterface $cityRepository,
        CategoryInterface $categoryRepository,
        TypeInterface $typeRepository,
        FacilityInterface $facilityRepository
    ) {
        parent::__construct();
        $this->propertyRepository = $propertyRepository;
        $this->featureRepository = $featureRepository;
        $this->currencyRepository = $currencyRepository;
        $this->cityRepository = $cityRepository;
        $this->categoryRepository = $categoryRepository;
        $this->facilityRepository = $facilityRepository;
        $this->typeRepository = $typeRepository;
    }

    /**
     * @return mixed|void
     * @throws Throwable
     */
    public function buildForm()
    {
        Assets::addStyles(['datetimepicker'])
            ->addScripts(['input-mask'])
            ->addScriptsDirectly([
                'vendor/core/plugins/real-estate/js/real-estate.js',
                'vendor/core/plugins/real-estate/js/components.js',
            ])
            ->addStylesDirectly('vendor/core/plugins/real-estate/css/real-estate.css');

        if (!$this->formHelper->hasCustomField('addons')) {
            $this->formHelper->addCustomField('addons', AddonsField::class);
        }
        $this->formHelper->addCustomField('spaces', ParkingSpace::class);
        $this->formHelper->addCustomField('unitstype', Unitstype::class);

        $currencies = $this->currencyRepository->pluck('re_currencies.title', 're_currencies.id');
        $cities = $this->cityRepository->allBy(['status' => BaseStatusEnum::PUBLISHED], ['state', 'country'],
            ['cities.name', 'cities.state_id', 'cities.country_id', 'cities.id']);

        $cityChoices = [];

        foreach ($cities as $city) {
            if ($city->state->status != BaseStatusEnum::PUBLISHED || $city->country->status != BaseStatusEnum::PUBLISHED) {
                continue;
            }
            $cityChoices[$city->id] = $city->name . ($city->state->name ? ' (' . $city->state->name . ')' : '');
        }

        $categories = [];
        foreach($this->categoryRepository->all() as $cat){
            $categories[$cat->id] = $cat->name;
        }

        //$categories = $this->categoryRepository->pluck('re_categories.name', 're_categories.id');
        $types[null] = '--select--';
        foreach($this->typeRepository->all() as $type){
            $types[$type->id] = $type->name;
        }
       // $types = [''=>__('[ Choose ]')]+$this->typeRepository->pluck('re_property_types.name', 're_property_types.id');
        
        $selectedSpaces = [];

        $selectedFeatures = [];
        $addons = [];
        $selectedAddons = [];
        $_addons = @DB::select("SELECT * FROM re_addon WHERE status = 'published' ORDER BY name ASC");
        foreach($_addons as $a){
            $addons[$a->id] = $a->name.' (SAR'.number_format($a->price,2,'.',',').')'; 
        }

        $spaces = [];
        $selectedSpace = [];
        // $_addons = @DB::select("SELECT * FROM re_space WHERE status = 'published' ORDER BY name ASC"); 
        // foreach($_addons as $a){ 
        //     $space[$a->id] = $a->name.' (SAR'.number_format($a->price,2,'.',',').')'; 
        // }


        $unit = [];
        $selectedUnit = [];
        // $_addons = @DB::select("SELECT * FROM re_space WHERE status = 'published' ORDER BY name ASC"); 
        // foreach($_addons as $a){ 
        //     $space[$a->id] = $a->name.' (SAR'.number_format($a->price,2,'.',',').')'; 
        // }

        $latitude = '';
        $longitude = '';        
        $features = [];
        $feature_categories = [];

        $hide_category = false; 
 
        $mdl = false;
        $fixabe_type_ids = array_map(function($r){ return $r->id; },DB::select("SELECT id FROM re_property_types WHERE is_fixable = 1"));
       
        if(!empty($this->getModel())){

            $mdl = $this->getModel();
            $type_id = (int)$mdl->type_id;
            $lang_code = !empty($_REQUEST['language']) ? $_REQUEST['language'] : session('editing_language');

            $selectedAddons = (array)@$mdl->addons;
            $selectedSpaces = (array)@$mdl->spaces;
            $selectedUnit = (array)@$mdl->unitstype;
            // $selectedUnit = json_decode($selectedUnit[0],true);
   

            $hide_category = @DB::table('re_property_types')->select('is_fixable')->where('id', '=', (int)$type_id)->get()->toArray()[0]->is_fixable == 1; 
             
            $selectedFeatures = $mdl->features()->pluck('re_features.id')->all();
            $latitude = $mdl->latitude;
            $longitude = $mdl->longitude;
        
            $_features = DB::table('re_features') 
            ->join('re_feature_types', 're_features.id', '=', 're_feature_types.feature_id')
            ->join('re_feature_groups', 're_feature_groups.id', '=', 're_features.group') 
            ->select('re_features.*','re_feature_groups.name as group_name','re_feature_groups.order as gorder')
            ->where('re_feature_types.type_id', '=', $type_id)
            ->orderBy('re_feature_groups.order', 'ASC')
            ->get()->toArray();
            
            $feature_lang = $feature_grp_lang = [];
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

            $f_values = [];
            $feature_categories = DB::table('feature_categories')->select('*')->where('property_id', '=', $mdl->id)->get()->toArray();
            foreach($feature_categories as $vl){
                $f_values[(int)$vl->feature_id] = trim($vl->value); 
            }

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

                $feature->value = isset($f_values[(int)$feature->id]) ? $f_values[(int)$feature->id] : '';

                if( !isset($features[$feature->group]))
                    $features[$feature->group] = ['name'=>$feature->group_name];

                $features[$feature->group]['fields'][$feature->id] =$feature;
            }

            foreach($features as $fid => $ftr){
                usort($ftr['fields'],function($r1,$r2){ return (int)$r1->order > (int)$r2->order; });
                $features[$fid] = (object)$ftr;
            }
            
        }

        $timeChoices = [];
        for($ti = 0; $ti<=23 ; $ti++){
            $_ti = $ti < 9 ? '0'.$ti : $ti;

            $_ti2 = $_ti == 0 ? 12 : ($_ti > 12 ? $_ti-12 : $_ti);
            $_ti2 = (int)$_ti2 < 10 ? '0'.(int)$_ti2 : $_ti2;
            
            $_tl = $ti < 12 ? $_ti2.":00 AM" : $_ti2.":00 PM"; 
            $timeChoices[$_ti.":00:00"] = $_tl;
        }
       
        $facilities = $this->facilityRepository->allBy([], [], ['re_facilities.id', 're_facilities.name']);
        $selectedFacilities = [];
        if ($this->getModel()) {
            $selectedFacilities = $this->getModel()->facilities()->select('re_facilities.id', 'distance')->get();
        }


       

 

        $this
            ->setupModel(new Property)
            ->setValidatorClass(PropertyRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('plugins/real-estate::property.form.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::property.form.name'),
                    'data-counter' => 120,
                ],
            ])
            ->add('rowOpen0', 'html', [
                'html' => '<div class="row">',
            ])
            
            ->add('type_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::property.form.type'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => $types,
                'wrapper'    => [
                    'class' => 'form-group mb-1 col-md-4',
                ],
                'attr' => [
                    'class' =>'form-control select-full'.(!empty($mdl->id) ? ' editing-property' : '') ,
                    'data-typids' => implode(',',$fixabe_type_ids),
                ]
                
            ])
            ->add('category_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::property.form.category'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control select-search-full category_id_properties',
                ], 
                'choices'    => $categories,
                'wrapper'    => [
                    'class' => 'form-group mb-1 col-md-4 fixed_type_field '.($hide_category ? ' hidden':''),
                ],
            ])
            ->add('is_featured', 'onOff', [
                'wrapper'    => [
                    'class' => 'form-group mb-1 col-md-4 pt-4 ps-5',
                ],
                'attr'       => [
                    'class' => 'form-control',
                ],
                'label'         => trans('core/base::forms.is_featured'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])

            ->add('rowClose0', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen12', 'html', [
                'html' => '<div class="row">',
            ])
            
            ->add('moderation_status', 'customSelect', [
                 'label'      => trans('plugins/real-estate::property.moderation_status'),
                 'label_attr' => ['class' => 'control-label required'],
                 'attr'       => [
                     'class' => 'form-control select-full',
                 ],
                 'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-6',
                ],
                 'choices'    => ModerationStatusEnum::labels(),
             ]) 
             ->add('author_id', 'autocomplete', [
                 'label'      => trans('plugins/real-estate::property.account'),
                 'label_attr' => [
                     'class' => 'control-label',
                 ],
                 'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-6',
                ],
                 'attr'       => [
                     'id'       => 'author_id',
                     'data-url' => route('account.list'),
                 ],
                 'choices'    => $this->getModel()->author_id ?
                     [
                        $this->model->author->id => $this->model->author->name,
                     ]
                     :
                     ['' => trans('plugins/real-estate::property.select_account')],
            ])
            ->add('rowClose12', 'html', [
                'html' => '</div>',
            ])
            
            // ->add('description', 'textarea', [
            //     'label'      => trans('core/base::forms.description'),
            //     'label_attr' => ['class' => 'control-label'],
            //     'attr'       => [
            //         'rows'         => 4,
            //         'placeholder'  => trans('core/base::forms.description_placeholder'),
            //         'data-counter' => 350,
            //     ],
            // ])
            ->add('content', 'editor', [
                'label'      => trans('core/base::forms.description'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'rows'            => 4,
                    'with-short-code' => true,
                ],
            ])
            ->add('rowOpen2', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('price', 'text', [
                'label'      => trans('plugins/real-estate::property.form.price'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-1 col-md-3',
                ],
                'attr'       => [
                    'id'          => 'price-number',
                    'placeholder' => trans('plugins/real-estate::property.form.price'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('monthly_price', 'text', [
                'label'      => trans('plugins/real-estate::property.form.monthly_price'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-1 col-md-3',
                ],
                'attr'       => [
                    'id'          => 'monthly_price-number',
                    'placeholder' => trans('plugins/real-estate::property.form.monthly_price'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('currency_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::property.form.currency'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-1 col-md-3',
                ],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => $currencies,
            ])
            ->add('number_bedroom', 'number', [
                'label'      =>  trans('plugins/real-estate::property.form.sleeps') ,
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-1 col-md-3 fixed_type_field '.($hide_category ? ' hidden':''),
                ],
                'attr'       => [
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            
            ->add('no_of_spaces', 'number', [
                'label'      => trans('plugins/real-estate::property.form.no_of_spaces'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-1 col-md-3 none_fixed_type_field '.(!$hide_category ? ' hidden':''),
                ],
                'default_value' => 1,
                'attr'       => [
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            
            
            ->add('square', 'number', [
                'label'      => trans('plugins/real-estate::property.form.square', ['unit' => setting('real_estate_square_unit', 'm²') ? '(' . setting('real_estate_square_unit', 'm²') . ')' : null]),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-3',
                ],
                'attr'       => [
                    'placeholder' => trans('plugins/real-estate::property.form.square'),
                ],
            ])
            /*
            ->add('period', 'customSelect', [
                'label'      => trans('plugins/real-estate::property.form.period'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group period-form-group mb-1 col-md-3',
                ],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                ],
                'choices'    => PropertyPeriodEnum::labels(),
            ])
            */
            ->add('rowClose2', 'html', [
                'html' => '</div>',
            ])
            ->add('images[]', 'mediaImages', [
                'label'      => trans('plugins/real-estate::property.form.images'),
                'label_attr' => ['class' => 'control-label'],
                'values'     => $this->getModel()->id ? $this->getModel()->images : [],
            ])
            ->add('city_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::property.form.city'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                ],
                'choices'    => $cityChoices,
            ])
            ->add('location', 'text', [
                'label'      => trans('plugins/real-estate::property.form.location'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::property.form.location'),
                    'data-counter' => 300,
                ],
            ])
            
            ->add('rowOpen20', 'html', [
                'html' => '<div class="row none_fixed_type_field '.(!$hide_category ? ' hidden' : '').'">',
            ])
            
            ->add('arriving_time', 'customSelect', [
                 'label'      => trans('plugins/real-estate::property.arriving_time'),
                 'label_attr' => ['class' => 'control-label'],
                 'attr'       => [
                     'class' => 'form-control select-search-full',
                 ],
                 'wrapper'    => [
                    'class' => 'form-group mb-1 col-md-3',
                ],
                 'choices'    => $timeChoices,
             ])
             ->add('departing_time', 'customSelect', [
                 'label'      => trans('plugins/real-estate::property.departing_time'),
                 'label_attr' => ['class' => 'control-label'],
                 'attr'       => [
                     'class' => 'form-control select-search-full',
                 ],
                 'wrapper'    => [
                    'class' => 'form-group mb-1 col-md-3',
                ],
                 'choices'    => $timeChoices,
             ])

            ->add('rowClose20', 'html', [
                'html' => '</div>',
            ])
            
            ->add('addons', 'addons', [
                 'label'      => trans('plugins/real-estate::property.addons'),
                 'label_attr' => ['class' => 'control-label'],
                 'attr'       => [
                     'class' => 'form-control select-search-full',
                     'multiple' => true,
                 ],
                 'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-12 none_fixed_type_field '.(!$hide_category ? ' hidden' : ''),
                ],
                'choices'    => $addons,
                'values' => $selectedAddons,
            ])

            
             
            ->add('spaces', 'spaces', [
                'label'      => trans('plugins/real-estate::property.parking_space'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                    'multiple' => true,
                ],
                'wrapper'    => [
                   'class' => 'form-group mb-3 col-md-12 none_fixed_type_field '.(!$hide_category ? ' hidden' : ''),
               ], 
                 'choices'    => $spaces,
               'values' => $selectedSpaces,
            ])

            // ->add('unitstype', 'unitstype', [
            //     'label'      => trans('plugins/real-estate::property.unit_type'),
            //     'label_attr' => ['class' => 'control-label'],
            //     'attr'       => [
            //         'class' => 'form-control select-search-full',
            //         'multiple' => true,
            //     ],
            //     'wrapper'    => [
            //        'class' => 'form-group mb-3 col-md-12 none_fixed_type_field '.(!$hide_category ? ' hidden' : ''),
            //     ], 
            //     'choices'    => $unit,
            //     'values' => $selectedUnit,
            // ])

           

            /*
            ->add('rowOpen', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('latitude', 'text', [
                'label'      => trans('plugins/real-estate::property.form.latitude'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-6',
                ],
                'attr'       => [
                    'placeholder'  => 'Ex: 1.462260',
                    'data-counter' => 25,
                ],
                'help_block' => [
                    'tag'  => 'a',
                    'text' => trans('plugins/real-estate::property.form.latitude_helper'),
                    'attr' => [
                        'href'   => 'https://www.latlong.net/convert-address-to-lat-long.html',
                        'target' => '_blank',
                        'rel'    => 'nofollow',
                    ],
                ],
            ])
            ->add('longitude', 'text', [
                'label'      => trans('plugins/real-estate::property.form.longitude'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-6',
                ],
                'attr'       => [
                    'placeholder'  => 'Ex: 103.812530',
                    'data-counter' => 25,
                ],
                'help_block' => [
                    'tag'  => 'a',
                    'text' => trans('plugins/real-estate::property.form.longitude_helper'),
                    'attr' => [
                        'href'   => 'https://www.latlong.net/convert-address-to-lat-long.html',
                        'target' => '_blank',
                        'rel'    => 'nofollow',
                    ],
                ],
            ])
            ->add('rowClose', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen1', 'html', [
                'html' => '<div class="row">',
            ])
            
            ->add('number_bathroom', 'number', [
                'label'      => trans('plugins/real-estate::property.form.number_bathroom'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-3',
                ],
                'attr'       => [
                    'placeholder' => trans('plugins/real-estate::property.form.number_bathroom'),
                ],
            ])
            ->add('number_floor', 'number', [
                'label'      => trans('plugins/real-estate::property.form.number_floor'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-3',
                ],
                'attr'       => [
                    'placeholder' => trans('plugins/real-estate::property.form.number_floor'),
                ],
            ])
            
            ->add('rowClose1', 'html', [
                'html' => '</div>',
            ])
            */
            
            ->add('never_expired', 'onOff', [
                'label'         => trans('plugins/real-estate::property.never_expired'),
                'label_attr'    => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group hidden',
                ],
                'default_value' => true,
            ])

            /*
            ->add('auto_renew', 'onOff', [
                'label'         => trans('plugins/real-estate::property.renew_notice',
                    ['days' => RealEstateHelper::propertyExpiredDays()]),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
                'wrapper'       => [
                    'class' => 'form-group auto-renew-form-group' . (!$this->getModel()->id || $this->getModel()->never_expired == true ? ' hidden' : null),
                ],
            ])
            */
            ->addMetaBoxes([
                    'google_map'   => [
                    'title'    => trans('plugins/real-estate::property.form.latitude_longitude'),
                    'content'  => view('plugins/real-estate::partials.form-properties-map',compact('latitude','longitude')),
                    'priority' => 1,
                ], 
            ])
            // form-properties-features
            ->addMetaBoxes([
                // 'facilities' => [
                //     'title'    => trans('plugins/real-estate::property.distance_key'),
                //     'content'  => view('plugins/real-estate::partials.form-facilities',
                //         compact('facilities', 'selectedFacilities')),
                //     'priority' => 0,
                // ],
                'properties_feature'   => [
                    'title'    => trans('plugins/real-estate::property.form.properties_features'),
                    'content'  => view('plugins/real-estate::partials.form-properties-features',
 
                        compact('features','feature_categories')),
                    'priority' => 1,
                ],
                // 'features'   => [
                //     'title'    => trans('plugins/real-estate::property.form.features'),
                //     'content'  => view('plugins/real-estate::partials.form-features',
                //         compact('selectedFeatures', 'features'))->render(),
                //     'priority' => 1,
                // ]
            ])
            // ->setBreakFieldPoint('moderation_status');
            ->add('rowOpen3', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('brand', 'text', [
                'label'      => trans('brand'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-1 col-md-4 fixed_type_field '.($hide_category ? ' hidden':''),
                ],
                
                'attr'       => [
                    'class'       => 'form-control ',
                ],
            ])
            ->add('made_in', 'text', [
                'label'      => trans('made in'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-1 col-md-4 fixed_type_field '.($hide_category ? ' hidden':''),
                ],
                
                'attr'       => [
                    'class'       => 'form-control ',
                ],
            ])
            ->add('color', 'text', [
                'label'      => trans('color'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-1 col-md-4 fixed_type_field '.($hide_category ? ' hidden':''),
                ],
                
                'attr'       => [
                    'class'       => 'form-control ',
                ],
            ])
            ->add('weight', 'text', [
                'label'      => trans('weight'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-1 col-md-4 fixed_type_field '.($hide_category ? ' hidden':''),
                ],
                
                'attr'       => [
                    'class'       => 'form-control ',
                ],
            ])
            ->add('length', 'text', [
                'label'      => trans('length'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-1 col-md-4 fixed_type_field '.($hide_category ? ' hidden':''),
                ],
                
                'attr'       => [
                    'class'       => 'form-control ',
                ],
            ])
            ->add('width', 'text', [
                'label'      => trans('width'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-1 col-md-4 fixed_type_field '.($hide_category ? ' hidden':''),
                ],
                
                'attr'       => [
                    'class'       => 'form-control ',
                ],
            ])
            
            ->add('rowClose3', 'html', [
                'html' => '</div>',
            ])
            // brand		
            // made_in
            // color		
            // weight		
            // length		
            // width		
            ;
    }
}
