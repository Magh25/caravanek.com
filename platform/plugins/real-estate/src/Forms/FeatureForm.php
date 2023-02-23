<?php

namespace Botble\RealEstate\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\RealEstate\Forms\Fields\FontawesomeSelectField;
use Botble\RealEstate\Http\Requests\FeatureRequest;
use Botble\RealEstate\Repositories\Interfaces\FeatureGroupsInterface;
use Botble\RealEstate\Repositories\Interfaces\TypeInterface;
use Botble\RealEstate\Models\Feature;
use Throwable;

class FeatureForm extends FormAbstract
{
    /**
    * @var FeatureGroupsInterface
    */
    protected $featureGroupsRepository;
    /**
    * @var CategoryInterface
    */
    protected $typeRepository;


    public function __construct(
        FeatureGroupsInterface $featureGroupsRepository,
        TypeInterface $typeRepository
    ) {
        parent::__construct(); 
        $this->featureGroupsRepository = $featureGroupsRepository;
        $this->typeRepository = $typeRepository;
    }
    /**
     * @return mixed|void
     * @throws Throwable
     */
    public function buildForm()
    {
        $featureGroups = $this->featureGroupsRepository->pluck('re_feature_groups.name', 're_feature_groups.id');

        $types = array('checkbox'=>'Checkbox','text'=>'Text','select'=>'Select');

        $ptypes = $this->typeRepository->allBy([], [], ['re_property_types.id', 're_property_types.name']); 

        $selectedTypes = [];
        if ($this->getModel()) {
            $selectedTypes = $this->getModel()->types()->pluck('re_property_types.id')->all();
        }

        $this
            ->setupModel(new Feature)
            ->setValidatorClass(FeatureRequest::class)
            ->addCustomField('fontawesomeSelect', FontawesomeSelectField::class)
            ->add('name', 'text', [
                'label'      => trans('plugins/real-estate::feature.form.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::feature.form.name'),
                    'data-counter' => 120,
                ],
            ])
            ->add('type', 'select', [
                'label'      => trans('plugins/real-estate::feature.form.type'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => $types,
                'attr'  => [
                    'class'  => 'ctm-feature-select form-control',
                ],
            ])
            ->add('select_options', 'textarea', [
                'label'      => trans('plugins/real-estate::feature.form.select_options'),
                'label_attr' => ['class' => 'control-label ctm-feature-select-label'],
                'attr'       => [
                    'rows'         => 2,
                    'placeholder'  => trans('plugins/real-estate::feature.form.select_options_placeholder'),
                    'data-counter' => 200,
                    'class'  => 'ctm-feature-select-option form-control', 
                ],
            ]) 
            
            ->addMetaBoxes([
                'types'   => [
                    'title'    => trans('plugins/real-estate::property.form.property_types'),
                    'priority' => 1,
                    'content'  => view('plugins/real-estate::partials.form-types',
                        compact('selectedTypes', 'ptypes'))->render(),
                ] 
            ])
            ->add('group', 'select', [
                'label'      => trans('plugins/real-estate::feature.form.group'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => $featureGroups, 
            ])
            
            ->add('show_on_listing', 'select', [
                'label'         =>  trans('plugins/real-estate::feature.form.show_on_listing'), 
                'label_attr'    => ['class' => 'control-label'],
                'choices' => [0=>'No',1=>'Yes'],
            ])
            ->add('order', 'text', [
                'label'      => trans('plugins/real-estate::feature.form.order'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'data-counter' => 120,
                ],
            ])
            ->add('icon', 'fontawesomeSelect', [
                'label'         => trans('plugins/real-estate::feature.form.icon'),
                'label_attr'    => ['class' => 'control-label'],
                'attr'          => [
                    'placeholder'  => trans('plugins/real-estate::feature.form.icon'),
                    'data-counter' => 60,
                ],
                'default_value' => 'fas fa-check',
            ]);
    }
}
