<?php

namespace App\Http\Controllers\api\v1\Admin;

use App\Models\User;
use App\Models\Wallet;
use App\Enums\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Services\Frontend\F_WalletService;
use App\Notifications\SendDynamicNotification;
use App\Services\Admin\User\A_CreateUserService;
use App\Services\Admin\User\A_DeleteUserService;
use App\Services\Admin\User\A_UpdateUserService;
use App\Http\Requests\v1\Admin\User\CreateUserRequest;
use App\Http\Requests\v1\Admin\User\UpdateUserRequest;
use App\Http\Requests\v1\Admin\User\A_DebitUserRequest;
use App\Http\Requests\v1\Admin\User\A_CreditUserRequest;
use App\Http\Resources\v1\Admin\User\A_ShortUserResource;
use App\Repositories\Admin\User\Fetch\A_FetchUserInterface;
use App\Repositories\Supplier\User\Find\S_FindUserInterface;
use App\Http\Requests\v1\Admin\User\A_notificationUserRequest;
use App\Http\Resources\v1\Supplier\Permission\S_ShortPermissionResource;

class A_UserController extends Controller
{
    /**
     *
     * @param A_FetchUserInterface $fetchUser
     * @param F_WalletService $wallet
     * @param S_FindUserInterface $findUser
     * @param A_UpdateUserService $updateUser
     * @param A_DeleteUserService $deleteUser
     * @param A_CreateUserService $createUser
     */
    public function __construct(private A_FetchUserInterface $fetchUser,
                                private F_WalletService $wallet,
                                private S_FindUserInterface $findUser,
                                private A_UpdateUserService $updateUser,
                                private A_DeleteUserService $deleteUser,
                                private A_CreateUserService $createUser)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $type = request()->type ?? 'Supplier';
        return A_ShortUserResource::collection($this->fetchUser->fetch(isLocalized:false, withCount: ['orders'], role: $type));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
       return $this->createUser->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->findUser->find(decodeString($id));
        return A_ShortUserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = $this->findUser->find(decodeString($id));

        return $this->updateUser->update($request->validated(),$user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = $this->findUser->find(decodeString($id));

        return $this->deleteUser->delete($user);
    }

    public function getAdminPermission() {
        $permissions =  Permission::select(['id', 'name', 'group_name', 'friendly_name'])->where('role_name','Admin')->get();

        return S_ShortPermissionResource::collection($permissions);
    }

    public function blockUser(string $id)  {

        $user = $this->findUser->find(decodeString($id));

        $user->tokens()->delete();
        $user->userFcmTokens()->delete();

        $user->update([
            'is_active'=>!$user->is_active
        ]);
        return response()->json(successResponse(message: trans(SuccessMessagesEnum::UPDATED)));
    }

    public function credit(string $id ,A_CreditUserRequest $request) {
        $amount = $request['amount'];

        $description = $request['description'];

        $metadata = $request['metadata']??[];

        $user = $this->findUser->find(decodeString($id));

        if(!$user->hasRole(RoleEnum::CLIENT)){
            return response()->json(errorResponse(
                message: 'user must to be client'),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $wallet = $user->wallet;

        if(!$wallet){
           $wallet =  Wallet::create([
                'user_id' => $user->id
            ]);
        }
        return $this->wallet->credit($wallet ,$amount ,$description ,$metadata);
    }

    public function debit(string $id ,A_DebitUserRequest $request) {
        $amount = $request['amount'];
        $description = $request['description'];
        $metadata = $request['metadata']??[];
        $user = $this->findUser->find(decodeString($id));
        if(!$user->hasRole(RoleEnum::CLIENT)){
            return response()->json(errorResponse(
                message: 'user must to be client'),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if(!$user->wallet){
            Wallet::create([
                'user_id' => $user->id
            ]);
        }
        return $this->wallet->debit($user->wallet ,$amount ,$description ,$metadata);
    }

    public function sendNotification(A_notificationUserRequest $request,string $id ) {
        $data = $request->validated();
        $user = $this->findUser->find(decodeString($id));

        $user->notify(new SendDynamicNotification(
            title: $data['title'],
            message: $data['message']
        ));
        return response()->json(successResponse(message: trans(SuccessMessagesEnum::SENT)));
    }
}
