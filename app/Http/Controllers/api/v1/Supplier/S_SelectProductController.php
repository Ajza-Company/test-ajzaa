<?php

namespace App\Http\Controllers\api\v1\Supplier;

use App\Http\Requests\v1\Supplier\Product\UpdatePriceProductRequest;
use App\Http\Requests\v1\Supplier\Product\UpdateQuantityProductRequest;
use App\Services\Supplier\Product\S_UpdateProductPriceService;
use App\Services\Supplier\Product\S_UpdateProductQuantityService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Services\Supplier\Product\S_CreateSelectProductService;
use App\Http\Requests\v1\Supplier\Product\StoreProductRequest;
use App\Repositories\Supplier\Store\Find\S_FindStoreInterface;
use App\Http\Resources\v1\Supplier\Product\S_ShortProductResource;
use App\Http\Resources\v1\Supplier\StoreProduct\S_ShortStoreProductResource;

class S_SelectProductController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param S_FindStoreInterface $findStore
     */
    public function __construct(private S_FindStoreInterface $findStore,
                                private S_UpdateProductQuantityService $updateProductQuantity,
                                private S_UpdateProductPriceService $updateProductPrice,
                                private S_CreateSelectProductService $createProduct)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $store_id)
    {
        $store = $this->findStore->find(decodeString($store_id));
        return S_ShortStoreProductResource::collection(
            $store
                ->storeProducts()
                ->whereHas('product.localized')
                ->with(['product' => ['localized']])
                ->filter(\request())
                ->adaptivePaginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request,string $store_id)
    {
        $store = $this->findStore->find(decodeString($store_id));

        return $this->createProduct->create($request->validated(),$store);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateQuantity(UpdateQuantityProductRequest $request){
        return $this->updateProductQuantity->updateQuantity($request->validated());
    }

    public function updatePrice(UpdatePriceProductRequest $request){
        return $this->updateProductPrice->updatePrice($request->validated());
    }
}
