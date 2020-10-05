<?php

namespace App\Repositories;

use App\Models\LocationType;
use Illuminate\Database\Eloquent\Collection;

class LocationTypeRepository
{
    /**
     * @param array $ids
     * @return LocationType[]|Collection
     */
    public function findAllByIds(array $ids): Collection
    {
        return LocationType::query()
            ->whereIn('id', $ids)->get();
    }
}
