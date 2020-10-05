<?php

namespace App\Repositories;

use App\Models\Location;
use Kalnoy\Nestedset\Collection;

class LocationRepository
{
    /**
     * @param array $ids
     * @return Location[]|Collection
     */
    public function findAllByIds(array $ids): Collection
    {
        return Location::query()
            ->with('type')
            ->whereIn('id', $ids)->get();
    }
    /**
     * Get a country by ID
     *
     * @param int $countryId
     * @return Location
     */
    public function getCountryById(int $countryId): Location
    {
        return Location::query()
            ->countryId($countryId)
            ->first();
    }
}
