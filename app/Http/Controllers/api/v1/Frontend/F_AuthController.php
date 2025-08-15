<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Enums\SuccessMessagesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\User\UserResource;
use App\Http\Resources\v1\RepSales\RepSalesResource;
use App\Services\Frontend\Auth\F_SendOtpCodeService;
use App\Services\Frontend\Auth\F_SetupAccountService;
use App\Services\Frontend\Auth\F_CreateAccountService;
use App\Services\Frontend\Auth\F_VerifyOtpCodeService;
use App\Http\Requests\v1\Frontend\Auth\F_SendOtpCodeRequest;
use App\Http\Requests\v1\Frontend\Auth\F_SetupAccountRequest;
use App\Http\Requests\v1\Frontend\Auth\F_CreateAccountRequest;
use App\Http\Requests\v1\Frontend\Auth\F_UpdateAccountRequest;
use App\Http\Requests\v1\Frontend\Auth\F_VerifyOtpCodeRequest;
use App\Repositories\Frontend\Company\Find\F_FindCompanyInterface;

class F_AuthController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param F_SendOtpCodeService $sendOtpCode
     * @param F_VerifyOtpCodeService $verifyOtpCode
     * @param F_CreateAccountService $createAccount
     * @param F_SetupAccountService $setupAccount
     * @param F_FindCompanyInterface $findCompany
     */
    public function __construct(
        private F_SendOtpCodeService   $sendOtpCode,
        private F_VerifyOtpCodeService $verifyOtpCode,
        private F_CreateAccountService $createAccount,
        private F_SetupAccountService $setupAccount,
        private F_FindCompanyInterface $findCompany)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function sendOtp(F_SendOtpCodeRequest $request)
    {
        return $this->sendOtpCode->send($request->validated());
    }

    /**
     * Display a listing of the resource.
     */
    public function verifyOtp(F_VerifyOtpCodeRequest $request)
    {
        return $this->verifyOtpCode->verify($request->validated());
    }

    /**
     * Display a listing of the resource.
     */
    public function createAccount(F_CreateAccountRequest $request)
    {
        return $this->createAccount->create($request->validated());
    }

    /**
     * Display a listing of the resource.
     */
    public function setupAccount(F_SetupAccountRequest $request)
    {
        return $this->setupAccount->setup(auth('api')->user(), $request->validated());
    }

    /**
     * Display a listing of the resource.
     */
    public function updateProfile(F_UpdateAccountRequest $request)
    {
        $user = auth('api')->user();
        $user->update($request->validated());
        return UserResource::make($user->refresh()->load('roles'));
    }

    /**
     * Display a listing of the resource.
     */
    public function me()
    {
        return UserResource::make(auth('api')->user()->load('roles'));
    }

    /**
     * Login with id Function
     *
     * @return JsonResponse
     */
    public function loginCompanyWithID(string $company_id)
    {
        $company = $this->findCompany->find(decodeString($company_id));
        $user = $company->user->load('roles','stores');

        return response()->json(successResponse(
            message: trans(SuccessMessagesEnum::LOGGEDIN),
            data: RepSalesResource::make($user),
            token: $user->createToken('virtual_auth_token')->plainTextToken
        ));

    }

}
