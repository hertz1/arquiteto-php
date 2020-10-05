<?php

namespace App\Http\Resources;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /** @var Location */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $location = $this->resource;
        return [
            'id'   => $location->id,
            'name' => $location->name,
            'type' => new LocationTypeResource($location->type)
        ];
    }
}
