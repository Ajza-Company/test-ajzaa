<?php

namespace App\Http\Controllers\api\v1\Supplier;

use App\Models\Product;
use App\Enums\RoleEnum;
use App\Enums\SuccessMessagesEnum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Services\Supplier\Product\S_CreateProductService;
use App\Http\Requests\v1\Supplier\Product\StoreProductRequest;
use App\Repositories\Supplier\Store\Find\S_FindStoreInterface;
use App\Http\Resources\v1\Supplier\Product\S_ShortProductResource;
use App\Http\Resources\v1\Supplier\StoreProduct\S_ShortStoreProductResource;
use App\Http\Requests\v1\Supplier\Product\S_UpdateProductRequest;
use App\Http\Requests\v1\Supplier\Product\S_CreateProductRequest;
use App\Repositories\Admin\Product\Find\A_FindProductInterface;
use App\Services\Supplier\Product\A_UpdateProductService;
use App\Services\Supplier\Product\A_DeleteProductService;
use App\Http\Resources\v1\Admin\Product\A_ShortProductResource;
use App\Services\Supplier\Product\A_CreateProductService;
use App\Repositories\Admin\Product\Fetch\A_FetchProductInterface;
use Illuminate\Http\Response;

class S_ProductController extends Controller
{

    public function __construct(private S_FindStoreInterface $findStore,
                                private A_CreateProductService $createProduct,
                                private A_FetchProductInterface $fetchProduct,
                                private A_UpdateProductService $updateProduct,
                                private A_DeleteProductService $deleteProduct,
                                private A_FindProductInterface $findProduct)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $store_id)
    {
        try {
            $store = $this->findStore->find(decodeString($store_id));
            return S_ShortStoreProductResource::collection(
                $store
                    ?->storeProducts()
                    ->whereHas('product.localized')
                    ->with(['product' => ['localized']])
                    ->filter(\request())
                    ->adaptivePaginate());
        } catch (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e) {
            return response()->json(['message' => "Store not found"], Response::HTTP_NOT_FOUND);
        }
    }
    public function store(S_CreateProductRequest $request,string $store_id)
    {
        $data = $request->validated();
        $store = $this->findStore->find(decodeString($store_id));
        $category =isset($data['category_id']) ? $data['category_id'] : $store->category->category_id;
        $data['category_id']=$category;
        $data['is_active'] = 0;
        $data['is_default'] = 0;
        return $this->createProduct->create($data,$store->id);
    }

    public function show(string $id,string $product)
    {
        $product =  $this->findProduct->find(decodeString($product));

        return A_ShortProductResource::make($product);
    }

    public function update(S_UpdateProductRequest $request, string $id,string $product)
    {
        $product =  $this->findProduct->find(decodeString($product));

        return $this->updateProduct->update($request->validated(),$product);
    }

    public function destroy(string $id ,string $product )
    {
        $product =  $this->findProduct->find(decodeString($product));

        return $this->deleteProduct->delete($product);
    }
}
