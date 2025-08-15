<?php

namespace App\Http\Controllers\api\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\PromoCode\CreatePromoCodeServices;
use App\Services\Admin\PromoCode\DeletePromoCodeServices;
use App\Http\Requests\v1\Admin\PromoCode\A_CreatePromoCodeRequest;
use App\Http\Resources\v1\Admin\PromoCode\A_ShortPromoCodeResource;
use App\Repositories\Admin\PromoCode\Fetch\A_FetchPromoCodeInterface;
use App\Repositories\Admin\PromoCode\Find\A_FindPromoCodeInterface;

class A_PromoCodeController extends Controller
{
    /**
     *
     * @param A_FetchPromoCodeInterface $fetchPromoCode
     * @param CreatePromoCodeServices $createPromoCode
     * @param DeletePromoCodeServices $deletePromoCode
     * @param A_FindPromoCodeInterface $findPromoCode
     */
    public function __construct(private A_FetchPromoCodeInterface $fetchPromoCode,
                                private CreatePromoCodeServices $createPromoCode,
                                private DeletePromoCodeServices $deletePromoCode,
                                private A_FindPromoCodeInterface $findPromoCode)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return A_ShortPromoCodeResource::collection($this->fetchPromoCode->fetch(isLocalized:false));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(A_CreatePromoCodeRequest $request)
    {
        $data = $request->validated();
        return $this->createPromoCode->create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $promoCode =  $this->findPromoCode->find(decodeString($id));
        return A_ShortPromoCodeResource::make($promoCode);
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
        $promoCode =  $this->findPromoCode->find(decodeString($id));
        return $this->deletePromoCode->delete($promoCode);
    }
}
