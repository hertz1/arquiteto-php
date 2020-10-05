<?php

namespace App\Services;

use App\Models\Address;
use App\Repositories\AddressRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AddressService
{
    protected AddressRepository $repository;

    public function __construct(AddressRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all the addresses of the authenticated user
     *
     * @return Address[]|Collection
     */
    public function getAuthenticatedUserAddresses(): Collection
    {
        $user_uuid = Auth::id();

        return $this->repository->findAddressByUserUuid($user_uuid);
    }

    /**
     * Create a new address for the authenticated user
     *
     * @param array $data
     * @return Address
     * @throws Throwable
     */
    public function createAddressForAuthenticatedUser(array $data): Address
    {
        $address = new Address($data);

        return $this->repository->persistAddressAndLocations($address, $data['locations'], $this);
    }

    /**
     * Update an address for the authenticated user
     *
     * @param Address $address
     * @param array $data
     * @return Address
     * @throws Throwable
     */
    public function updateAddressForAuthenticatedUser(Address $address, array $data): Address
    {
        $address->fill($data);

        return $this->repository->persistAddressAndLocations($address, $data['locations'], $this);
    }

    /**
     * @param Address $address
     * @throws Throwable
     */
    public function deleteAddressForAuthenticatedUser(Address $address): void
    {
        $this->repository->deleteAddressAndLocations($address);
    }
}
