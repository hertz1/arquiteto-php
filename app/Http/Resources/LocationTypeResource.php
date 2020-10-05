<?php

namespace App\Http\Resources;

use App\Models\LocationType;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationTypeResource extends JsonResource
{
    /** @var LocationType */
    public $resource;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $lt = $this->resource;
        return [
            'id'   => $lt->id,
            'name' => $lt->name
        ];
    }
}
