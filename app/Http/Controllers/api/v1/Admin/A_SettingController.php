<?php

namespace App\Http\Controllers\api\v1\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\Setting\F_CreateSettingService;
use App\Http\Requests\v1\Admin\Setting\A_CreateSettingRequest;
use App\Http\Requests\v1\Admin\Setting\A_UpdateSettingRequest;
use App\Http\Resources\v1\Admin\Setting\A_ShortSettingResource;

class A_SettingController extends Controller
{

    public function __construct(private F_CreateSettingService $createSetting)
{
}

    public function index()  {
        $setting = Setting::latest()->first();

        if (!$setting) {
            return [
                'setting' => []
            ];
        }

       return A_ShortSettingResource::make($setting);
    }

    public function store(A_CreateSettingRequest $request) {
        $setting = $request->validated();

        return $this->createSetting->create($setting['setting']);
    }

}
