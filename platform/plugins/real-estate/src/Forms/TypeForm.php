<?php

namespace Botble\RealEstate\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\RealEstate\Http\Requests\TypeRequest;
use Botble\RealEstate\Models\Type;
use Botble\RealEstate\Forms\Fields\FontawesomeSelectField;
use Throwable;

class TypeForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws Throwable
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Type)
            ->setValidatorClass(TypeRequest::class)
            ->addCustomField('fontawesomeSelect', FontawesomeSelectField::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('slug', 'text', [
                'label'      => trans('core/base::forms.slug'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 120,
                ],
            ])
            ->add('commission', 'number', [
                'label'         => 'Commission (%)',
                'label_attr'    => ['class' => 'control-label'],
                'attr'          => [
                    'placeholder' => '5%',
                ],
                'default_value' => 0,
            ])
            ->add('order', 'number', [
                'label'         => trans('core/base::forms.order'),
                'label_attr'    => ['class' => 'control-label'],
                'attr'          => [
                    'placeholder' => trans('core/base::forms.order_by_placeholder'),
                ],
                'default_value' => 0,
            ])
            ->add('icon', 'fontawesomeSelect', [
                'label'         => trans('plugins/real-estate::feature_groups.form.icon'),
                'label_attr'    => ['class' => 'control-label'],
                'attr'          => [
                    'placeholder'  => trans('plugins/real-estate::feature_groups.form.icon'),
                    'data-counter' => 60,
                ],
                'default_value' => 'fas fa-check',
            ])
            ->add('is_fixable', 'onOff', [
                'label'         => trans('core/base::forms.is_parking'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->add('is_Accessory', 'onOff', [
                'label'         => trans('Is Accessory'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->setBreakFieldPoint('status');
    }
}
