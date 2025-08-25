<?php

namespace App\Services\Supplier\Company;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Http\Resources\v1\Supplier\Company\S_CompanyResource;
use App\Models\Company;
use App\Models\CompanyLocale;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class S_UpdateCompanyService
{
    /**
     * Update company and user
     *
     * @param array $data
     * @param Company $company
     * @return JsonResponse
     */
    public function update(array $data, Company $company): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Update company data
            if (isset($data['company'])) {
                $this->updateCompany($company, $data['company']);
            }

            // Update user data
            if (isset($data['user'])) {
                $this->updateUser($company->user, $data['user']);
            }

            DB::commit();

            return response()->json(
                successResponse(
                    message: trans(SuccessMessagesEnum::UPDATED),
                    data: S_CompanyResource::make($company->load('locales', 'user', 'category', 'country'))
                )
            );

        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(
                errorResponse(
                    message: trans(ErrorMessageEnum::UPDATE),
                    error: $ex->getMessage()
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Update company data
     */
    private function updateCompany(Company $company, array $companyData): void
    {
        // Handle basic company data
        $basicData = Arr::except($companyData, ['localized', 'logo', 'cover_image', 'commercial_register_file']);
        $company->update($basicData);

        // Handle localized data
        if (isset($companyData['localized'])) {
            $this->updateCompanyLocales($company, $companyData['localized']);
        }

        // Handle logo
        if (isset($companyData['logo'])) {
            $this->handleImageUpload($company, 'logo', $companyData['logo']);
        }

        // Handle cover image
        if (isset($companyData['cover_image'])) {
            $this->handleImageUpload($company, 'cover_image', $companyData['cover_image']);
        }

        // Handle commercial register file
        if (isset($companyData['commercial_register_file'])) {
            $this->handleFileUpload($company, 'commercial_register_file', $companyData['commercial_register_file']);
        }
    }

    /**
     * Update company locales
     */
    private function updateCompanyLocales(Company $company, array $localizedData): void
    {
        foreach ($localizedData as $locale) {
            CompanyLocale::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'locale_id' => $locale['local_id']
                ],
                [
                    'name' => $locale['name'],
                    'description' => $locale['description'] ?? null
                ]
            );
        }
    }

    /**
     * Handle image upload
     */
    private function handleImageUpload(Company $company, string $field, $file): void
    {
        // Delete old file if exists
        if ($company->$field) {
            Storage::disk('public')->delete($company->$field);
        }

        // Upload new file
        $path = uploadFile("company-{$company->id}-{$field}", $file);
        $company->update([$field => $path]);
    }

    /**
     * Handle file upload
     */
    private function handleFileUpload(Company $company, string $field, $file): void
    {
        // Delete old file if exists
        if ($company->$field) {
            Storage::disk('public')->delete($company->$field);
        }

        // Upload new file
        $path = uploadFile("company-{$company->id}-{$field}", $file);
        $company->update([$field => $path]);
    }

    /**
     * Update user data
     */
    private function updateUser(User $user, array $userData): void
    {
        // Handle basic user data - exclude password_confirmation from database update
        $basicData = Arr::except($userData, ['avatar', 'password', 'password_confirmation']);
        $user->update($basicData);

        // Handle avatar
        if (isset($userData['avatar'])) {
            $this->handleUserAvatarUpload($user, $userData['avatar']);
        }

        // Handle password
        if (isset($userData['password'])) {
            $user->update(['password' => Hash::make($userData['password'])]);
        }
    }

    /**
     * Handle user avatar upload
     */
    private function handleUserAvatarUpload(User $user, $file): void
    {
        // Delete old avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Upload new avatar
        $path = uploadFile("user-{$user->id}-avatar", $file);
        $user->update(['avatar' => $path]);
    }
}
