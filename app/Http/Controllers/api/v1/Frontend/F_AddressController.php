<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Frontend\Address\F_CreateAddressRequest;
use App\Http\Requests\v1\Frontend\Address\F_UpdateAddressRequest;
use App\Http\Resources\v1\Frontend\Address\F_AddressResource;
use App\Services\Frontend\Address\F_CreateAddressService;
use App\Services\Frontend\Address\F_DeleteAddressService;
use App\Services\Frontend\Address\F_UpdateAddressService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class F_AddressController extends Controller
{
    /**
     *
     * @param F_CreateAddressService $createAddress
     * @param F_UpdateAddressService $updateAddress
     * @param F_DeleteAddressService $deleteAddress
     */
    public function __construct(
        private F_CreateAddressService $createAddress,
        private F_UpdateAddressService $updateAddress,
        private F_DeleteAddressService $deleteAddress)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return F_AddressResource::collection(auth('api')->user()->addresses()->latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(F_CreateAddressRequest $request)
    {
        return $this->createAddress->create(auth('api')->user(), $request->validated());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(F_UpdateAddressRequest $request, string $id)
    {
        return $this->updateAddress->update(auth('api')->user(), $request->validated(), $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->deleteAddress->delete(auth('api')->user(), $id);
    }
}
