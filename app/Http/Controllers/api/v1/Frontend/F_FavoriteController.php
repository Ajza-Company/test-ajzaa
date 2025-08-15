<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Frontend\Favorite\F_CreateFavoriteRequest;
use App\Http\Resources\v1\Frontend\Favorite\F_FavoriteResource;
use App\Http\Resources\v1\Frontend\Product\F_ShortProductResource;
use App\Services\Frontend\Favorite\F_CreateFavoriteService;
use App\Services\Frontend\Favorite\F_DeleteFavoriteService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class F_FavoriteController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param F_CreateFavoriteService $createProductFavorite
     * @param F_DeleteFavoriteService $deleteProductFavorite
     */
    public function __construct(
        private F_CreateFavoriteService $createProductFavorite,
        private F_DeleteFavoriteService $deleteProductFavorite)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return F_FavoriteResource::collection(
            auth('api')->user()
                ->favorites()
                ->whereHas('storeProduct.product.localized')
                ->with(['storeProduct' => function ($query) {
                    $query->with(['product' => function ($q) {
                        $q->whereHas('localized')->with(['localized']);
                    }, 'offer', 'favorite' => function ($q) {
                        $q->where('user_id', auth('api')->id());
                    }]);
                }])
                ->latest()
                ->adaptivePaginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(F_CreateFavoriteRequest $request)
    {
        return $this->createProductFavorite->create(auth('api')->user(), $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(?string $store_product_id = null)
    {
        return $this->deleteProductFavorite->delete(auth('api')->user(), $store_product_id);
    }
}
