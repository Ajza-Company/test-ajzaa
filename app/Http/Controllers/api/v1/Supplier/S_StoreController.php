<?php

namespace App\Http\Controllers\api\v1\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Supplier\Store\S_CreateStoreRequest;
use App\Http\Requests\v1\Supplier\Store\S_UpdateStoreRequest;
use App\Http\Resources\v1\Supplier\Store\S_ShortStoreResource;
use App\Http\Resources\v1\Supplier\Store\S_StoreResource;
use App\Repositories\Supplier\Store\Find\S_FindStoreInterface;
use App\Services\Supplier\Store\S_CreateStoreService;
use App\Services\Supplier\Store\S_UpdateStoreService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class S_StoreController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param S_CreateStoreService $createStore
     * @param S_FindStoreInterface $findStore
     * @param S_UpdateStoreService $updateStore
     */
    public function __construct(
        private S_CreateStoreService $createStore,
        private S_FindStoreInterface $findStore,
        private S_UpdateStoreService $updateStore)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = userCompany();

        if(!$category) {
            return response()->json(['message' => 'Company not found'], Response::HTTP_NOT_FOUND);
        }

        return S_ShortStoreResource::collection(
            userCompany()
                ?->stores()
                ->with(['company' => ['localized'], 'area' => ['localized', 'state' => ['localized']],'category'])
                ->adaptivePaginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(S_CreateStoreRequest $request)
    {
        return $this->createStore->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $store_id)
    {
        return S_StoreResource::make(userCompany()->stores()->find(decodeString($store_id))->load('company', 'company.localized', 'hours'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(S_UpdateStoreRequest $request, string $store_id)
    {
        $store = $this->findStore->find(decodeString($store_id));
        return $this->updateStore->update($request->validated(), $store);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
