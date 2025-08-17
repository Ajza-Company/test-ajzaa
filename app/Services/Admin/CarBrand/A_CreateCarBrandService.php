<?php

namespace App\Services\Admin\CarBrand;

use App\Models\CarBrand;
use App\Models\CarBrandLocale;
use App\Models\Locale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class A_CreateCarBrandService
{
    /**
     * Create a new car brand
     */
    public function create(array $data): array
    {
        try {
            DB::beginTransaction();

            // Handle logo upload and processing
            $logoPath = null;
            
            // 🔍 DEBUG: Service Level Debugging
            \Illuminate\Support\Facades\Log::info('=== A_CreateCarBrandService DEBUG ===');
            \Illuminate\Support\Facades\Log::info('Input data keys:', ['keys' => array_keys($data)]);
            \Illuminate\Support\Facades\Log::info('Logo exists in data:', ['exists' => isset($data['logo'])]);
            
            if (isset($data['logo'])) {
                \Illuminate\Support\Facades\Log::info('Logo data type:', ['type' => gettype($data['logo'])]);
                
                if (is_object($data['logo'])) {
                    \Illuminate\Support\Facades\Log::info('Logo data class:', ['class' => get_class($data['logo'])]);
                }
                
                \Illuminate\Support\Facades\Log::info('Logo is UploadedFile:', ['is_uploaded_file' => $data['logo'] instanceof \Illuminate\Http\UploadedFile]);
            }
            
            if (isset($data['logo']) && $data['logo'] instanceof \Illuminate\Http\UploadedFile) {
                // Debug: Log the logo data
                \Illuminate\Support\Facades\Log::info('✅ Logo is valid UploadedFile instance');
                \Illuminate\Support\Facades\Log::info('Logo filename:', ['filename' => $data['logo']->getClientOriginalName()]);
                \Illuminate\Support\Facades\Log::info('Logo size:', ['size' => $data['logo']->getSize()]);
                \Illuminate\Support\Facades\Log::info('Logo mime type:', ['mime' => $data['logo']->getMimeType()]);
                \Illuminate\Support\Facades\Log::info('Logo extension:', ['extension' => $data['logo']->getClientOriginalExtension()]);
                
                // Check if it's a valid uploaded file
                if ($data['logo']->isValid()) {
                    \Illuminate\Support\Facades\Log::info('✅ Logo file is valid, processing...');
                    $logoPath = $this->processAndStoreLogo($data['logo']);
                    \Illuminate\Support\Facades\Log::info('✅ Logo processed successfully:', ['path' => $logoPath]);
                } else {
                    \Illuminate\Support\Facades\Log::error('❌ Logo validation failed:', ['error' => $data['logo']->getError()]);
                }
            } else {
                \Illuminate\Support\Facades\Log::warning('❌ No logo provided or logo is not an UploadedFile');
                if (isset($data['logo'])) {
                    \Illuminate\Support\Facades\Log::warning('Logo data found but wrong type:', [
                        'type' => gettype($data['logo']),
                        'value' => is_string($data['logo']) ? $data['logo'] : 'Non-string value'
                    ]);
                }
            }
            
            \Illuminate\Support\Facades\Log::info('Final logo path:', ['logo_path' => $logoPath]);
            \Illuminate\Support\Facades\Log::info('=== END Service DEBUG ===');

            // Create car brand
            $carBrand = CarBrand::create([
                'external_id' => $data['external_id'] ?? null,
                'is_active' => $data['is_active'] ?? true,
                'logo' => $logoPath
            ]);

            // Get English locale
            $englishLocale = Locale::where('locale', 'en')->first();
            if ($englishLocale) {
                // Create English localized data
                CarBrandLocale::create([
                    'car_brand_id' => $carBrand->id,
                    'name' => $data['name'],
                    'locale_id' => $englishLocale->id
                ]);
            }

            // Get Arabic locale and create Arabic record if name_ar is provided
            if (isset($data['name_ar']) && !empty($data['name_ar'])) {
                $arabicLocale = Locale::where('locale', 'ar')->first();
                if ($arabicLocale) {
                    CarBrandLocale::create([
                        'car_brand_id' => $carBrand->id,
                        'name' => $data['name_ar'],
                        'locale_id' => $arabicLocale->id
                    ]);
                }
            }

            DB::commit();

            return [
                'status' => true,
                'message' => 'Car brand created successfully',
                'data' => $carBrand->load('localized')
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded logo if exists
            if ($logoPath) {
                Storage::disk('public')->delete($logoPath);
            }

            return [
                'status' => false,
                'message' => 'Failed to create car brand: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Process and store logo with consistent dimensions
     */
    private function processAndStoreLogo($logoFile): string
    {
        try {
            \Illuminate\Support\Facades\Log::info('=== processAndStoreLogo START ===');
            
            // Generate unique filename with original extension
            $extension = $logoFile->getClientOriginalExtension();
            $filename = 'car-brand-' . uniqid() . '.' . $extension;
            $path = 'car-brands/logos/' . $filename;
            
            \Illuminate\Support\Facades\Log::info('Processing logo:', [
                'extension' => $extension,
                'filename' => $filename,
                'path' => $path,
                'temp_path' => $logoFile->getPathname(),
                'real_path' => $logoFile->getRealPath()
            ]);

            // Ensure directory exists
            $fullDirectory = storage_path('app/public/car-brands/logos');
            if (!file_exists($fullDirectory)) {
                mkdir($fullDirectory, 0755, true);
                \Illuminate\Support\Facades\Log::info('Directory created: ' . $fullDirectory);
            }

            // Store the image using Laravel's storage system
            $stored = Storage::disk('public')->putFileAs(
                'car-brands/logos',
                $logoFile,
                $filename
            );

            \Illuminate\Support\Facades\Log::info('Storage result:', [
                'stored' => $stored,
                'expected_path' => $path,
                'file_exists' => Storage::disk('public')->exists($path)
            ]);

            if ($stored) {
                \Illuminate\Support\Facades\Log::info('✅ Logo stored successfully at: ' . $path);
                return $path;
            } else {
                throw new \Exception('Failed to store logo file');
            }
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('❌ Logo storage failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \Exception('Failed to store logo: ' . $e->getMessage());
        }
    }
}
