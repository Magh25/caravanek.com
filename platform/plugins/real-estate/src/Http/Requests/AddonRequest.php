<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\Support\Http\Requests\Request;

class AddonRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
    **/
    public function rules()
    {
        switch (request()->route()->getName()) {
            case 'addon.create':
                return [
                    'name' => 'required',
                    'price' => 'required',
                    'description' => 'required',
                    'status' => 'required',
                ];
            default:
                return [
                    'name' => 'required',
                ];
        }
    }
}
