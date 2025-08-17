<?php

namespace App\Services\Admin\CarBrand;

use App\Models\CarBrand;
use App\Models\CarBrandLocale;
use App\Models\Locale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class A_UpdateCarBrandService
{
    /**
     * Update car brand
     */
    public function update(array $data, CarBrand $carBrand): array
    {
        try {
            DB::beginTransaction();

            // Handle logo upload and processing
            $logoPath = null;
            if (isset($data['logo']) && $data['logo'] instanceof \Illuminate\Http\UploadedFile) {
                // Check if it's a valid uploaded file
                if ($data['logo']->isValid()) {
                    // Delete old logo if exists
                    if ($carBrand->logo) {
                        Storage::disk('public')->delete($carBrand->logo);
                    }
                    
                    $logoPath = $this->processAndStoreLogo($data['logo']);
                    
                    \Illuminate\Support\Facades\Log::info('Logo updated successfully: ' . $logoPath);
                } else {
                    \Illuminate\Support\Facades\Log::error('Logo validation failed: ' . $data['logo']->getError());
                }
            }

            // Update car brand
            $updateData = [
                'external_id' => $data['external_id'] ?? $carBrand->external_id,
                'is_active' => $data['is_active'] ?? $carBrand->is_active,
            ];
            
            // Only update logo if a new one was uploaded
            if ($logoPath) {
                $updateData['logo'] = $logoPath;
            }
            
            $carBrand->update($updateData);

            // Update localized data
            if (isset($data['name']) || isset($data['name_ar'])) {
                // Update English name
                if (isset($data['name'])) {
                    $englishLocale = Locale::where('locale', 'en')->first();
                    if ($englishLocale) {
                        CarBrandLocale::updateOrCreate(
                            [
                                'car_brand_id' => $carBrand->id,
                                'locale_id' => $englishLocale->id
                            ],
                            ['name' => $data['name']]
                        );
                    }
                }

                // Update Arabic name
                if (isset($data['name_ar'])) {
                    $arabicLocale = Locale::where('locale', 'ar')->first();
                    if ($arabicLocale) {
                        CarBrandLocale::updateOrCreate(
                            [
                                'car_brand_id' => $carBrand->id,
                                'locale_id' => $arabicLocale->id
                            ],
                            ['name' => $data['name_ar']]
                        );
                    }
                }
            }

            DB::commit();

            return [
                'status' => true,
                'message' => 'Car brand updated successfully',
                'data' => $carBrand->load('localized')
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'Failed to update car brand: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Process and store logo with consistent dimensions
     */
    private function processAndStoreLogo($logoFile): string
    {
        try {
            // Generate unique filename with original extension
            $extension = $logoFile->getClientOriginalExtension();
            $filename = 'car-brand-' . uniqid() . '.' . $extension;
            $path = 'car-brands/logos/' . $filename;

            // Store the image using Laravel's storage system
            $stored = Storage::disk('public')->putFileAs(
                'car-brands/logos',
                $logoFile,
                $filename
            );

            if ($stored) {
                \Illuminate\Support\Facades\Log::info('Logo stored successfully at: ' . $path);
                return $path;
            } else {
                throw new \Exception('Failed to store logo file');
            }
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Logo storage failed: ' . $e->getMessage());
            throw new \Exception('Failed to store logo: ' . $e->getMessage());
        }
    }
}
