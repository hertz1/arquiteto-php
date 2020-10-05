<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_uuid'         => 'required|max:36',
            'address_line_1'    => 'required|max:256',
            'address_line_2'    => 'max:256',
            'locations'         => 'required|array',
            'locations.country' => 'required|array'
        ];
    }
}
