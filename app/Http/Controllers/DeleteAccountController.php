<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeleteAccountController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return response()->json(successResponse(message: trans('general.delete_account')));
    }
}
