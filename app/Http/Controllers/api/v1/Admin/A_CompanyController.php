<?php

namespace App\Http\Controllers\api\v1\Admin;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Http\Requests\v1\Admin\Company\A_UpdateCompanyRequest;
use App\Models\Company;
use App\Repositories\Frontend\Company\Find\F_FindCompanyInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\Company\CreateCompanyServices;
use App\Http\Requests\v1\Admin\Company\A_CreateCompanyRequest;
use App\Http\Resources\v1\Admin\Company\A_ShortCompanyResource;
use App\Repositories\Admin\Company\Fetch\A_FetchCompanyInterface;
use Illuminate\Support\Facades\DB;

class A_CompanyController extends Controller
{
    /**
     *
     * @param A_FetchCompanyInterface $fetchCompany
     * @param CreateCompanyServices $createCompany
     * @param F_FindCompanyInterface $findCompany
     */
    public function __construct(private A_FetchCompanyInterface $fetchCompany,
                                private CreateCompanyServices $createCompany,
                                private F_FindCompanyInterface $findCompany)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return A_ShortCompanyResource::collection($this->fetchCompany->fetch(withCount: ['stores', 'usersPivot'], with: ['user', 'stores','stores.area', 'stores.hours','category']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(A_CreateCompanyRequest $request)
    {
        $data = $request->validated();
        return $this->createCompany->create($data);
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
    public function update(A_UpdateCompanyRequest $request, string $id)
    {
        $company = $this->findCompany->find(decodeString($id));
        return $company->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
                $company = $this->findCompany->find(decodeString($id));
                $company->delete();
            DB::commit();
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::DELETED)));
        }catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::DELETE),
                error: $ex->getMessage()),
                500);
        }
    }

    public function active(string $id)
    {
        $id = decodeString($id);

        $company = Company::find($id);
        $company->is_active = !$company->is_active;
        $company->save();
        $company->users()->update(['is_active' => $company->is_active]);

        if (!$company->is_active) {
            foreach ($company->users as $user) {
                $user->tokens()->delete();
                $user->userFcmTokens()->delete();
            }
        }
        return response()->json(successResponse(message: trans(SuccessMessagesEnum::UPDATED)));
    }
}
