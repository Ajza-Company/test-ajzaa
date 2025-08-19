<?php

namespace App\Http\Controllers\api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Admin\Slider\F_CreateSliderRequest;
use App\Http\Resources\v1\Admin\Slider\A_ShortSliderResource;
use App\Models\SliderImage;
use App\Services\Admin\Slider\F_CreateSliderService;
use App\Services\Admin\Slider\F_DeleteSliderService;
use Illuminate\Http\Request;
use Throwable;

class A_SliderController extends Controller
{
    /**
     *
     * @param F_CreateSliderService $createSlider
     */
    public function __construct(private F_CreateSliderService $createSlider, private F_DeleteSliderService $deleteSlider)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = SliderImage::where('is_active', true)
            ->orderBy('order')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => A_ShortSliderResource::collection($sliders)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function store(F_CreateSliderRequest $request)
    {
        return $this->createSlider->create();
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroy(string $id)
    {
        $slider =  SliderImage::findOrFail(decodeString($id));
        return $this->deleteSlider->delete($slider);
    }
}
