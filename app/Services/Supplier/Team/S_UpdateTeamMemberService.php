<?php

namespace App\Services\Supplier\Team;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Repositories\Supplier\User\Find\S_FindUserInterface;
use Illuminate\Http\JsonResponse;

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
        \DB::beginTransaction();
        try {
            $user = $this->findUser->find(decodeString($userId));

            if (isset($data['permissions'])) {
                $user->syncPermissions($data['permissions']);
            }

            $user->update($data['data']);

            \DB::commit();
            return response()->json(successResponse(trans(SuccessMessagesEnum::UPDATED)));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(trans(ErrorMessageEnum::UPDATE), $ex->getMessage()), 500);
        }
    }
}
