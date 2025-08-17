<?php

namespace App\Http\Controllers\api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Admin\CarBrand\A_CarBrandResource;
use App\Http\Resources\v1\Admin\CarBrand\A_ShortCarBrandResource;
use App\Repositories\Admin\CarBrand\Fetch\A_FetchCarBrandInterface;
use App\Http\Requests\v1\Admin\CarBrand\CreateCarBrandRequest;
use App\Http\Requests\v1\Admin\CarBrand\UpdateCarBrandRequest;
use App\Services\Admin\CarBrand\A_CreateCarBrandService;
use App\Services\Admin\CarBrand\A_DeleteCarBrandService;
use App\Services\Admin\CarBrand\A_UpdateCarBrandService;
use App\Repositories\Admin\CarBrand\Find\A_FindCarBrandInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class A_CarBrandController extends Controller
{
    /**
     *
     * @param A_FindCarBrandInterface $findCarBrand
     * @param A_FetchCarBrandInterface $fetchCarBrand
     * @param A_CreateCarBrandService $createCarBrand
     * @param A_UpdateCarBrandService $updateCarBrand
     * @param A_DeleteCarBrandService $deleteCarBrand
     */
    public function __construct(
        private A_FindCarBrandInterface $findCarBrand,
        private A_FetchCarBrandInterface $fetchCarBrand,
        private A_CreateCarBrandService $createCarBrand,
        private A_UpdateCarBrandService $updateCarBrand,
        private A_DeleteCarBrandService $deleteCarBrand
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return A_ShortCarBrandResource::collection(
            $this->fetchCarBrand->fetch(
                paginate: true,
                with: ['localized'],
                withCount: ['carModels']
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCarBrandRequest $request)
    {
        // 🔍 DEBUG: Car Brand Creation
        Log::info('=== CREATE CAR BRAND REQUEST ===');
        Log::info('Request method:', ['method' => $request->method()]);
        Log::info('Request URL:', ['url' => $request->url()]);
        Log::info('Content-Type:', ['content_type' => $request->header('Content-Type')]);
        Log::info('Request data:', ['data' => $request->all()]);
        Log::info('Files:', ['files' => $request->allFiles()]);
        Log::info('Logo file exists:', ['exists' => $request->hasFile('logo')]);
        
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            Log::info('Logo file details:', [
                'original_name' => $logoFile->getClientOriginalName(),
                'mime_type' => $logoFile->getMimeType(),
                'size' => $logoFile->getSize(),
                'is_valid' => $logoFile->isValid(),
                'extension' => $logoFile->getClientOriginalExtension(),
                'path' => $logoFile->getPathname()
            ]);
        } else {
            Log::warning('No logo file found in request');
        }
        
        Log::info('Request is multipart:', ['is_multipart' => $request->isMethod('POST') && str_contains($request->header('Content-Type'), 'multipart')]);
        Log::info('Validated data:', ['validated' => $request->validated()]);
        Log::info('=== END DEBUG ===');
        
        return $this->createCarBrand->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $carBrand = $this->findCarBrand->find(decodeString($id));
        return A_CarBrandResource::make($carBrand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCarBrandRequest $request, string $id)
    {
        // 🔍 DEBUG: Car Brand Update
        Log::info('=== UPDATE CAR BRAND REQUEST ===');
        Log::info('Request method:', ['method' => $request->method()]);
        Log::info('Request URL:', ['url' => $request->url()]);
        Log::info('Car Brand ID:', ['id' => $id, 'decoded_id' => decodeString($id)]);
        Log::info('Content-Type:', ['content_type' => $request->header('Content-Type')]);
        Log::info('Request data:', ['data' => $request->all()]);
        Log::info('Files:', ['files' => $request->allFiles()]);
        Log::info('Logo file exists:', ['exists' => $request->hasFile('logo')]);
        
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            Log::info('Logo file details:', [
                'original_name' => $logoFile->getClientOriginalName(),
                'mime_type' => $logoFile->getMimeType(),
                'size' => $logoFile->getSize(),
                'is_valid' => $logoFile->isValid(),
                'extension' => $logoFile->getClientOriginalExtension(),
                'path' => $logoFile->getPathname()
            ]);
        } else {
            Log::warning('No logo file found in update request');
        }
        
        Log::info('Validated data:', ['validated' => $request->validated()]);
        Log::info('=== END UPDATE DEBUG ===');
        
        $carBrand = $this->findCarBrand->find(decodeString($id));
        return $this->updateCarBrand->update($request->validated(), $carBrand);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $carBrand = $this->findCarBrand->find(decodeString($id));
        return $this->deleteCarBrand->delete($carBrand);
    }

    /**
     * Toggle active status
     */
    public function toggleActive(string $id)
    {
        $carBrand = $this->findCarBrand->find(decodeString($id));
        $carBrand->update(['is_active' => !$carBrand->is_active]);
        
        return response()->json([
            'status' => true,
            'message' => 'Car brand status updated successfully',
            'data' => A_ShortCarBrandResource::make($carBrand)
        ]);
    }
}
