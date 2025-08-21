<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Frontend\Wallet\F_ShortWalletResource;
use App\Http\Resources\v1\Frontend\Wallet\F_WalletResource;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InterPayController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function callback(Request $request)
    {
        Log::channel('InterPay')->info(json_encode($request->all()));
        return response()->json(['message' => 'Callback received']);
    }
}
