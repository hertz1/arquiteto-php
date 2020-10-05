<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\Location;
use App\Models\LocationType;
use App\Services\AddressService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class AddressRepository
{
    protected LocationRepository     $locationRepository;
    protected LocationTypeRepository $locationTypeRepository;

    public function __construct(
        LocationRepository     $locationRepository,
        LocationTypeRepository $locationTypeRepository
    )
    {
        $this->locationRepository     = $locationRepository;
        $this->locationTypeRepository = $locationTypeRepository;
    }

    /**
     * Find all address of an user by its UUID
     *
     * @param string $uuid
     * @return Address[]|Collection
     */
    public function findAddressByUserUuid(string $uuid): Collection
    {
        return Address::query()
            ->with(['location.ancestors.type', 'location.type:id,name'])
            ->whereUserUuid($uuid)
            ->get();
    }

    /**
     * Delete an address and its locations
     *
     * @param Address $address
     * @throws Throwable
     */
    public function deleteAddressAndLocations(Address $address): void
    {
        $rootLocationTypeForDeletion = Location::getRootLocationTypeForDeletionByCountry(
            $address->getCountry()->name
        );

        /** @var Location $nodeToDelete */
        $nodeToDelete = $address->location->ancestors->first(fn (Location $node) =>
           $node->location_type_id === $rootLocationTypeForDeletion
        );

        DB::transaction(fn () => $nodeToDelete->delete());
    }

    /**
     * Persist the address and its locations on the database
     *
     * @param Address $address
     * @param array $locations
     * @param AddressService $addressService
     * @return mixed
     * @throws Throwable
     */
    public function persistAddressAndLocations(Address $address, array $locations, AddressService $addressService)
    {
        return DB::transaction(function () use ($address, $locations) {
            $associatedLocation = $this->buildAddressLocationTree($locations);

            $address->location()->associate($associatedLocation);
            $address->save();

            return $address;
        });
    }

    /**
     * Build the address location tree and return the latest location.
     *
     * @param $requestLocations
     * @return Location
     */
    protected function buildAddressLocationTree($requestLocations): Location
    {
        $country = $this->locationRepository->getCountryById($requestLocations['country']['id']);

        $locationTypeOrder = $this->locationTypeRepository
                                  ->findAllByIds(Location::getLocationTypeOrderByCountry($country->name));

        return $locationTypeOrder->reduce(function (?Location $parent, LocationType $locationType) use ($requestLocations) {
            $name = Str::snake($locationType->name);
            $requestLocation = $requestLocations[$name];

            $node = isset($requestLocation['id']) ? Location::find($requestLocation['id'])
                : new Location([
                    'name' => $requestLocation['name'],
                    'location_type_id' => $locationType->id
                ]);

            if ($parent)
                $parent->appendNode($node);

            return $node;
        });
    }
}
