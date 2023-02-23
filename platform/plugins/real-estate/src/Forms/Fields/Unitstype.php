<?php

namespace Botble\RealEstate\Forms\Fields; 
use Illuminate\Support\Arr;
use Kris\LaravelFormBuilder\Fields\FormField;

class Unitstype extends FormField
{ 
    /**
     * @return string
    **/
    protected function getTemplate()
    {
        return 'plugins/real-estate::forms.fields.unitstype';
    }

    /**
     * @param array $options
     * @param bool $showLabel
     * @param bool $showField
     * @param bool $showError
     * @return string
     */
    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        $options['attr'] = Arr::set($options['attr'], 'class', Arr::get($options['attr'], 'class') . 'form-control'); 
        return parent::render($options, $showLabel, $showField, $showError);
    }
}
