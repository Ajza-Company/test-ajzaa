<?php

namespace App\Http\Controllers\api\v1\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Supplier\Company\S_CompanyResource;

class S_CompanyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        return S_CompanyResource::make(userCompany());
    }
}
