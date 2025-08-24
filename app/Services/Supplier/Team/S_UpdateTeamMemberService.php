<?php

namespace App\Services\Supplier\Team;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Repositories\Supplier\User\Find\S_FindUserInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class S_UpdateTeamMemberService
{
    /**
     * Create a new instance.
     *
     * @param S_FindUserInterface $findUser
     */
    public function __construct(private S_FindUserInterface $findUser)
    {
    }

    /**
     *
     * @param array $data
     * @param string $userId
     * @return JsonResponse
     */
    public function update(array $data, string $userId): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = $this->findUser->find(decodeString($userId));

            if (isset($data['permissions'])) {
                $user->syncPermissions($data['permissions']);
            }

            // Extract data from the nested structure
            $userData = [];
            if (isset($data['data'])) {
                $userData = $data['data'];
            } else {
                // If no nested data, use the top-level data
                $userData = array_diff_key($data, ['permissions' => true]);
            }

            if (!empty($userData)) {
                $user->update($userData);
            }

            DB::commit();
            return response()->json(successResponse(trans(SuccessMessagesEnum::UPDATED)));
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(errorResponse(trans(ErrorMessageEnum::UPDATE), $ex->getMessage()), 500);
        }
    }
}
