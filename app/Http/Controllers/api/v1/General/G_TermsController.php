<?php

namespace App\Http\Controllers\api\v1\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Admin\Setting\A_UpdateTermsRequest;
use Illuminate\Http\Request;

class G_TermsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function terms(string $name)
    {
        if (file_exists(storage_path('app/settings/' . $name . '.txt'))) {
            $terms = file_get_contents(storage_path('app/settings/' . $name . '.txt'));
            return response()->json(['terms' => $terms]);
        }

        return response()->json(['error' => 'Terms not found'], 404);
    }

    /**
     * Update terms and conditions in storage.
     */
    public function updateTerms(string $name, A_UpdateTermsRequest $request)
    {
        if (!in_array($name, ['rep_terms', 'client_terms', 'privacy_partner', 'privacy_client','company_terms'])) {
            return response()->json(['error' => 'Invalid name'], 400);
        }
        $directory = storage_path('app/settings');
        $filePath = $directory . '/' . $name . '.txt';

        // Create directory if it doesn't exist
        if (!file_exists($directory)) {
            if (!mkdir($directory, 0755, true)) {
                return response()->json(['error' => 'Failed to create directory'], 500);
            }
        }

        // Write content to file
        if (file_put_contents($filePath, $request->terms)) {
            return response()->json(['message' => 'Terms updated successfully']);
        }

        return response()->json(['error' => 'Failed to update terms'], 500);
    }
}
