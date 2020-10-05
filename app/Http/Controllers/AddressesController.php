<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAddressRequest;
use App\Http\Requests\DeleteAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Services\AddressService;
use Illuminate\Http\Response;
use Throwable;

class AddressesController extends Controller
{
    protected AddressService $service;

    public function __construct(AddressService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return AddressResource::collection(
            $this->service->getAuthenticatedUserAddresses()
        );
    }

    /**
     * @param CreateAddressRequest $request
     * @return Response
     * @throws Throwable
     */
    public function create(CreateAddressRequest $request): Response
    {
        $address = $this->service->createAddressForAuthenticatedUser($request->validated());

        return response(new AddressResource($address), Response::HTTP_CREATED);
    }

    /**
     * @param UpdateAddressRequest $request
     * @param Address $address
     * @return Response
     * @throws Throwable
     */
    public function update(UpdateAddressRequest $request, Address $address): Response
    {
        $address = $this->service->updateAddressForAuthenticatedUser($address, $request->validated());

        return response(new AddressResource($address), Response::HTTP_OK);
    }

    /**
     * @param DeleteAddressRequest $request
     * @param Address $address
     * @return Response
     * @throws Throwable
     */
    public function delete(DeleteAddressRequest $request, Address $address): Response
    {
        $this->service->deleteAddressForAuthenticatedUser($address);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
