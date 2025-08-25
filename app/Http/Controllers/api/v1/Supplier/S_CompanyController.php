<?php

namespace App\Http\Controllers\api\v1\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Supplier\Company\S_UpdateCompanyRequest;
use App\Http\Resources\v1\Supplier\Company\S_CompanyResource;
use App\Services\Supplier\Company\S_UpdateCompanyService;

class S_CompanyController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param S_UpdateCompanyService $updateCompanyService
     */
    public function __construct(
        private S_UpdateCompanyService $updateCompanyService
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        return S_CompanyResource::make(userCompany());
    }

    /**
     * Update company and user
     */
    public function update(S_UpdateCompanyRequest $request)
    {
        $company = userCompany();
        return $this->updateCompanyService->update($request->validated(), $company);
    }
}
