<?php

namespace App\Http\Controllers\api\v1\Admin;

use App\Enums\RoleEnum;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Enums\SuccessMessagesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Admin\Product\F_UpdateProductRequest;
use App\Http\Requests\v1\Admin\Product\F_CreateProductRequest;
use App\Repositories\Admin\Product\Find\A_FindProductInterface;
use App\Services\Admin\Product\A_UpdateProductService;
use App\Services\Admin\Product\A_DeleteProductService;
use App\Http\Resources\v1\Admin\Product\A_ShortProductResource;
use App\Services\Admin\Product\A_CreateProductService;
use App\Repositories\Admin\Product\Fetch\A_FetchProductInterface;

class A_ProductController extends Controller
{

    /**
     * Create a new instance.
     *
     * @param A_CreateProductService $createProduct
     * @param A_FetchProductInterface $fetchProduct
     * @param A_UpdateProductService $updateProduct
     * @param A_DeleteProductService $deleteProduct
     * @param A_FindProductInterface $findProduct
     */
    public function __construct(
        private A_CreateProductService $createProduct,
        private A_FetchProductInterface $fetchProduct,
        private A_UpdateProductService $updateProduct,
        private A_DeleteProductService $deleteProduct,
        private A_FindProductInterface $findProduct
        )
    {

    }

    public function index()
    {
        return A_ShortProductResource::collection(
            $this->fetchProduct->fetch(with:[
                'variant',
                'variant.variantCategory',
                'variant.variantCategory.localized',
                'localized',
                'category.localized',
                'storeProduct'
            ])
        );
    }

    public function store(F_CreateProductRequest $request)
    {
        $data = $request->validated();

        $data['is_active']=true;

        return $this->createProduct->create($data);
    }

    public function show(string $id)
    {
        $product =  $this->findProduct->find(decodeString($id));

        return A_ShortProductResource::make($product);
    }

    public function update(F_UpdateProductRequest $request, string $id)
    {
        $product =  $this->findProduct->find(decodeString($id));

        return $this->updateProduct->update($request->validated(),$product);
    }

    public function destroy(string $id)
    {
        $product =  $this->findProduct->find(decodeString($id));

        return $this->deleteProduct->delete($product);
    }

    public function active(string $id){
        $id = decodeString($id);

        $product = Product::find($id);
        $product->is_active = !$product->is_active;
        $product->save();
        return $product;
    }

}
