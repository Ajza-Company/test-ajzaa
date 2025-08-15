<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Frontend\Locale\F_LocaleResource;
use App\Models\Locale;
use Illuminate\Http\Request;

class F_LocaleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return F_LocaleResource::collection(Locale::where('is_active', true)->get());
    }
}
