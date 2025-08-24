<?php

namespace App\Http\Controllers\api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Admin\Store\A_UpdateStoreRequest;
use App\Http\Resources\v1\Admin\Company\A_ShortCompanyResource;
use App\Http\Resources\v1\Admin\Store\A_ShortStoreResource;
use App\Http\Resources\v1\Admin\Store\A_StoreResource;
use App\Models\Store;
use App\Repositories\Admin\Store\Find\A_FindStoreInterface;
use App\Services\Admin\Store\A_UpdateStoreService;
use Illuminate\Http\Request;

class A_StoreController extends Controller
{
    /**
     *
     * @param A_FindStoreInterface $findStore
     * @param A_UpdateStoreService $updateStoreService
     */
    public function __construct(
        private A_FindStoreInterface $findStore,
        private A_UpdateStoreService $updateStoreService
    ) {

    }

    /**
     * Display a listing of stores.
     */
    public function index()
    {
        return A_StoreResource::collection(
            Store::with(['company.localized', 'area.localized', 'category.category.localized'])
                ->ordered() // استخدام الترتيب الجديد
                ->filter(\request())
                ->adaptivePaginate()
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(A_UpdateStoreRequest $request, string $id)
    {
        $store = $this->findStore->find(decodeString($id));
        return $this->updateStoreService->update($request->validated(), $store);
    }

    /**
     * Update stores order
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'stores' => 'required|array',
            'stores.*.id' => 'required|exists:stores,id',
            'stores.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->stores as $storeData) {
            Store::where('id', $storeData['id'])
                ->update(['sort_order' => $storeData['sort_order']]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Stores order updated successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $store = $this->findStore->find(decodeString($id));
        return A_StoreResource::make($store->load([
            'company.localized', 
            'area.localized', 
            'category.category.localized',
            'hours'
        ]));
    }

    /**
     * Toggle store active status
     */
    public function active(string $id)
    {
        $store = $this->findStore->find(decodeString($id));
        $store->is_active = !$store->is_active;
        $store->save();

        return response()->json([
            'status' => true,
            'message' => 'Store status updated successfully',
            'data' => A_StoreResource::make($store->fresh()->load([
                'company.localized', 
                'area.localized', 
                'category.category.localized',
                'hours'
            ]))
        ]);
    }
}
