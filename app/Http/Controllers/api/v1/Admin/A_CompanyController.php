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
        return A_ShortCompanyResource::collection($this->fetchCompany->fetch(
            null,
            true,
            ['user', 'stores' => function($query) {
                $query->ordered(); // استخدام الترتيب الجديد للمتاجر
            }, 'stores.area', 'stores.hours','category'],
            ['stores', 'usersPivot']
        )->sortBy('order')); // إضافة الترتيب حسب عمود order
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
        try {
            $company = $this->findCompany->find(decodeString($id));
            
            if (!$company) {
                return response()->json([
                    'status' => false,
                    'message' => 'Company not found'
                ], 404);
            }

            return A_ShortCompanyResource::make($company->load([
                'user', 'stores.area', 'stores.hours', 'category', 'locales'
            ]));
        } catch (\Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching company details',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(A_UpdateCompanyRequest $request, string $id)
    {
        try {
            $company = $this->findCompany->find(decodeString($id));
            
            if (!$company) {
                return response()->json([
                    'status' => false,
                    'message' => 'Company not found'
                ], 404);
            }

            $data = $request->validated();
            
            // Separate basic data from locales
            $basicData = collect($data)->except(['locales'])->toArray();
            
            // Update company data (excluding locales)
            $company->update($basicData);
            
            // Handle localized data if provided
            if (isset($data['locales'])) {
                foreach ($data['locales'] as $locale => $localeData) {
                    $company->locales()->updateOrCreate(
                        ['locale' => $locale],
                        $localeData
                    );
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Company updated successfully',
                'data' => A_ShortCompanyResource::make($company->fresh()->load([
                    'user', 'stores.area', 'stores.hours', 'category', 'locales'
                ]))
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating company',
                'error' => $ex->getMessage()
            ], 500);
        }
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

    /**
     * Update company order
     */
    public function updateOrder(Request $request)
    {
        try {
            $request->validate([
                'companies' => 'required|array',
                'companies.*.id' => 'required|integer|exists:companies,id',
                'companies.*.order' => 'required|integer|min:0'
            ]);

            DB::beginTransaction();

            foreach ($request->companies as $companyData) {
                Company::where('id', $companyData['id'])
                    ->update(['order' => $companyData['order']]);
            }

            DB::commit();

            return response()->json(successResponse(message: 'Company order updated successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(errorResponse(
                message: 'Failed to update company order',
                error: $e->getMessage()
            ), 500);
        }
    }
}
