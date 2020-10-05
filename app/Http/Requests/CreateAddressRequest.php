<?php

namespace App\Http\Requests;

class CreateAddressRequest extends AddressRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $newAddressUserUuid = $this->get('user_uuid');

        return $this->user()->uuid === $newAddressUserUuid;
    }
}
