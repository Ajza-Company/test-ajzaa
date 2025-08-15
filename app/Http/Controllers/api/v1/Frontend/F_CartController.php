<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Frontend\Cart\CartRequest;
use App\Services\Frontend\Cart\F_CartService;

class F_CartController extends Controller
{
    /**
     * show a new instance.
     *
     * @param F_CartService $Cart
     */
    public function __construct(private F_CartService $Cart)
    {
    }

    /**
     * show a newly resource in storage.
     */
    public function show(CartRequest $request)
    {        
        return $this->Cart->show($request->validated(), auth('api')->user());
    }
}
