<?php

namespace App\Services\Supplier\Team;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Repositories\Frontend\User\Create\F_CreateUserInterface;
use App\Repositories\Supplier\Store\Find\S_FindStoreInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class S_CreateTeamMemberService
{
    /**
     * Create a new instance.
     *
     * @param F_CreateUserInterface $createUser
     * @param S_FindStoreInterface $findStore
     */
    public function __construct(private F_CreateUserInterface $createUser,
                                private S_FindStoreInterface $findStore,)
    {
    }

    /**
     * Create a new offer.
     *
     * @param array $data
     * @return JsonResponse
     */
    public function create(array $data): JsonResponse
    {
        \DB::beginTransaction();
        try {
            $store = $this->findStore->find($data['store_id']);

            $user = $this->createUser->create([
                'name' =>  $data['data']['name'],
                'full_mobile' =>  $data['data']['full_mobile'],
                'is_registered' => true,
                'company_id' => $store->company_id,
                'password' => Hash::make( $data['data']['password'])
            ]);

            $store->storeUsers()->create(['user_id' => $user->id]);

            $user->syncPermissions($data['permissions']);

            \DB::commit();
            return response()->json(successResponse(trans(SuccessMessagesEnum::CREATED)));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(trans(ErrorMessageEnum::CREATE), $ex->getMessage()), 500);
        }
    }
}
