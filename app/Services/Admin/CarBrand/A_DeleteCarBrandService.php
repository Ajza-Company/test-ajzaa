<?php

namespace App\Services\Admin\CarBrand;

use App\Models\CarBrand;
use Illuminate\Support\Facades\Storage;

class A_DeleteCarBrandService
{
    /**
     * Delete car brand
     */
    public function delete(CarBrand $carBrand): array
    {
        try {
            // Delete logo if exists
            if ($carBrand->logo) {
                Storage::disk('public')->delete($carBrand->logo);
            }

            // Delete the car brand (this will cascade to locales and models)
            $carBrand->delete();

            return [
                'status' => true,
                'message' => 'Car brand deleted successfully'
            ];

        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Failed to delete car brand: ' . $e->getMessage()
            ];
        }
    }
}
