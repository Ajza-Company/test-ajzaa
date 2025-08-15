<?php

namespace App\Http\Controllers\api\v1\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\General\Notification\G_NotificationResource;
use Illuminate\Http\Request;

class G_NotificationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $notifications = auth('api')->user()->notifications()->latest()->adaptivePaginate();
        auth('api')->user()->unreadNotifications->markAsRead();
        return G_NotificationResource::collection($notifications);
    }
}
