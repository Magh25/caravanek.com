<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\RealEstate\Http\Requests\PropertyRequest as BaseRequest;

class AccountPropertyRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'            => 'required',
            'type_id'            => 'required',
            'description'     => 'max:350',
            'content'         => 'required',
            'number_bedroom'  => 'numeric|min:0|max:10000|nullable',
            'number_bathroom' => 'numeric|min:0|max:10000|nullable',
            'number_floor'    => 'numeric|min:0|max:10000|nullable',
            'price'           => 'numeric|min:0|nullable',
        ];
    }
}
