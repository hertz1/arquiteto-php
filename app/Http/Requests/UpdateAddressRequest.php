<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;

class UpdateAddressRequest extends AddressRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update', $this->route('address'));
    }
}
