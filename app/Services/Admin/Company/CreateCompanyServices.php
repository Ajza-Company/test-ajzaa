<?php

namespace App\Services\Admin\Company;

use App\Http\Resources\v1\Supplier\Store\S_StoreResource;
use App\Models\CompanyLocale;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Repositories\Supplier\Store\Create\S_CreateStoreInterface;
use App\Repositories\Supplier\StoreHour\Insert\S_InsertStoreHourInterface;
use App\Services\Admin\Category\A_CreateCategoryService;
use Carbon\Carbon;
use Illuminate\Http\Response;
use App\Enums\ErrorMessageEnum;
use Illuminate\Http\JsonResponse;
use App\Enums\SuccessMessagesEnum;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use App\Repositories\Frontend\User\Create\F_CreateUserInterface;
use App\Repositories\Admin\Company\Create\F_CreateCompanyInterface;
use Throwable;

class CreateCompanyServices
{
    /**
     * Create a new instance.
     *
     * @param F_CreateUserInterface $createUser
     * @param F_CreateCompanyInterface $createCompany
     * @param S_CreateStoreInterface $createStore
     * @param S_InsertStoreHourInterface $insertStoreHour
     */
    public function __construct(private F_CreateUserInterface $createUser,
                                private F_CreateCompanyInterface $createCompany,
                                private S_CreateStoreInterface $createStoreInterface,
                                private A_CreateCategoryService $createCategory,
                                private S_InsertStoreHourInterface $insertStoreHour)
    {

    }

    /**
     *
     * @param array $data
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function create(array $data): JsonResponse
    {
        \DB::beginTransaction();
        try {
            if (!isValidPhone($data['user']['full_mobile'])) {
                return response()->json(errorResponse(message: 'Invalid number detected! Let’s try a different one.'),Response::HTTP_BAD_REQUEST);
            }

            $user = $this->createUser($data['user']);

            \Log::info('create company user: '.json_encode($user));
            $data['company']['car_brand_id'] = json_encode($data['company']['car_brand_id']);
            $company = $this->createCompany($data['company'],$user);
            \Log::info('create company company: '.json_encode($company));

            $data['store']['company_id'] = $company->id;
            $data['store']['category_id'] = $data['company']['category_id'];
            \Log::info('create company store: '.json_encode($data['store']));
            $this->createStore($data['store'], $user->id);

            \DB::commit();
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::CREATED)));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CREATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function createUser($data) {
        $avatar = null;
        if (isset($data['avatar'])) {
            $avatar = uploadFile('user/avatar', $data['avatar']);
        }

        $user = $this->createUser->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'full_mobile' => $data['full_mobile'],
            'avatar' => $avatar,
            'is_registered' => true,
            'gender' => $data['gender'],
            'password' => Hash::make($data['password']),
            'preferred_language' => $data['preferred_language'] ?? app()->getLocale(),
        ]);

        $role = Role::where('name', 'Supplier')->first();
        $user->syncRoles([$role]);

        $permissions = Permission::pluck('name');
        $user->syncPermissions($permissions);

        return $user;
    }

    private function createCompany($data , $user) {
        $logo = null;
        $coverImage = null;
        $commercialRegisterFile = null;
        if (isset($data['logo'])) {
            $logo = uploadFile('company/logo', $data['logo'], 'public');
        }
        if (isset($data['cover_image'])) {
            $coverImage = uploadFile('company/cover_image', $data['cover_image'], 'public');
        }
        if (isset($data['commercial_register_file'])) {
            $commercialRegisterFile = uploadFile('company/commercial_register_file', $data['commercial_register_file'], 'public');
        }

        $company = $this->createCompany->create([
            'country_id'=>$data['country_id'],
            'user_id'=>$user->id,
            'email'=>$data['email'],
            'logo'=>$logo,
            'cover_image'=>$coverImage,
            'category_id'=>$data['category_id'],
            'commercial_register'=>$data['commercial_register'],
            'vat_number'=>$data['vat_number'],
            'commercial_register_file'=>$commercialRegisterFile,
            'car_brand_id'=>$data['car_brand_id'],
        ]);

        foreach($data['localized'] as $local){
            CompanyLocale::create([
                'locale_id'=>$local['local_id'],
                'name'=>$local['name'],
                'company_id'=>$company->id
            ]);
        }
        return $company;
    }

    /**
     *
     * @param array $data
     * @param int|null $user_id
     * @return mixed
     */
    public function createStore(array $data, int $user_id = null): mixed
    {
        \Log::info('start create store');

        $data['data']['is_active'] = false;

        $store = $this->createStoreInterface->create([
            'company_id' => $data['company_id'] ?? userCompany()->id,
            ...$data['data']
        ]);

        StoreCategory::create([
            'store_id' => $store->id,
            'category_id' => $data['category_id']
        ]);

        \Log::info('store: ' . $store);

        $this->insertStoreHour->insert($this->prepareBulkInsert($data['hours'], $store));

        \DB::table('store_users')->insert([
            'store_id' => $store->id,
            'user_id' => $user_id ?? auth('api')->id(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return $store;
    }

    /**
     *
     * @param array $hours
     * @param Store $store
     * @return array
     */
    private function prepareBulkInsert(array $hours, Store $store): array
    {
        $resultArr = [];

        foreach ($hours as $hour) {

            $resultArr[] = [
                "store_id" => $store->id,
                'day' => $hour['day'],
                "open_time" => $hour['open_time'],
                "close_time" => $hour['close_time'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        return $resultArr;
    }
}
