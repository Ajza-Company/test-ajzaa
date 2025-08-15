<?php

namespace App\Services\Frontend\Address;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Models\User;
use App\Repositories\Frontend\Address\Create\F_CreateAddressInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class F_CreateAddressService
{
    /**
     * Create a new instance.
     *
     * @param F_CreateAddressInterface $createAddress
     */
    public function __construct(private F_CreateAddressInterface $createAddress)
    {

    }

    /**
     *
     * @param User $user
     * @param array $data
     *
     * @return JsonResponse
     */
    public function create(User $user, array $data): JsonResponse
    {
        try {
            if (isset($data['is_default']) && $data['is_default']) {
                $user->addresses()->update(['is_default' => false]);
            }

            if (isset($data['metadata'])) {
                $data['metadata'] = json_encode($data['metadata']);
            }

            $data = [
                'user_id' => $user->id,
                ...$data
            ];

            $this->createAddress->create($data);

            return response()->json(successResponse(message: trans(SuccessMessagesEnum::CREATED)));
        } catch (\Exception $ex) {
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CREATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
