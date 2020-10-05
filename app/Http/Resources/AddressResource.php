<?php

namespace App\Http\Resources;

use App\Models\Address;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /** @var Address */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $ad = $this->resource;

        return [
            'id'             => $ad->id,
            'user_uuid'      => $ad->user_uuid,
            'address_line_1' => $ad->address_line_1,
            'address_line_2' => $ad->address_line_2,
            'locations'      => LocationResource::collection($ad->getLocationsFlatTree())
        ];
    }
}
