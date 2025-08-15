<?php

namespace App\Http\Controllers\api\v1\Admin;

use App\Enums\SuccessMessagesEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SendDynamicNotification;
use App\Http\Requests\v1\Admin\User\A_notificationUserRequest;
use Illuminate\Support\Facades\Notification;

class A_NotificationController extends Controller
{

    public function __construct()
    {
    }


    public function __invoke(A_notificationUserRequest $request)
    {
        $data = $request->validated();
        $users = User::getUserFilter($request)
            ->when($request->has('users') && !$data['is_select_all'], fn ($query) => $query->whereIn('id', $data['users']))
            ->get();

        Notification::send($users, new SendDynamicNotification(
            title: $data['title'],
            message: $data['message']
        ));

        return response()->json(successResponse(message: trans(SuccessMessagesEnum::SENT)));
    }

}
