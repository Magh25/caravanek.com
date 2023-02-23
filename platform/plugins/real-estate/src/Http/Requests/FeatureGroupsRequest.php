<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\Support\Http\Requests\Request;

class FeatureGroupsRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
    **/
    public function rules()
    {
        switch (request()->route()->getName()) {
            case 'feature_groups.create':
                return [
                    'name' => 'required',
                    'slug' => 'required|unique:re_feature_groups',
                    'description' => 'required',
                    'icon' => '',
                ];
            default:
                return [
                    'name' => 'required',
                ];
        }
    }
}
