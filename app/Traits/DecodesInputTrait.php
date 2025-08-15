<?php

namespace App\Traits;

trait DecodesInputTrait
{
    /**
     * Decode input value for a given field, handling nested keys and arrays.
     *
     * @param string $field The field name (can be nested using dot notation)
     * @return void
     */
    protected function decodeInput(string $field): void
    {
        /*if (str_contains($field, '.*')) {
            $this->decodeArrayInput($field);
            return;
        }*/

        if (str_contains($field, '.*') && substr_count($field, '.') === 1) {
            $this->decodeSimpleArrayInput($field);
            return;
        }

        if (str_contains($field, '.*.')) {
            $this->decodeArrayInput($field);
            return;
        }

        // For nested keys like 'personal.car_brand_id', use the full path to get value
        $value = $this->input($field);

        if ($value && decodeString($value)) {
            $decoded = decodeString($value);

            // Use arraySet to set the value at the correct nested path
            $data = $this->all(); // Get all request data as array
            $this->arraySet($data, $field, $decoded);
            $this->replace($data); // Replace the request data
        }
    }

    /**
     * Decode simple array input values.
     *
     * @param string $field The field name with wildcard (e.g., 'product_ids.*')
     * @return void
     */
    protected function decodeSimpleArrayInput(string $field): void
    {
        // Get the base array name by removing the .*
        $arrayKey = str_replace('.*', '', $field);

        // Get the array from input
        $array = $this->input($arrayKey, []);

        if (!is_array($array)) {
            return;
        }

        // Process each item in the array
        foreach ($array as $index => $value) {
            if ($value && function_exists('decodeString')) {
                $decoded = decodeString($value);
                $array[$index] =(int) $decoded;
            }
        }
        
        // Update the request with the modified array
        $this->merge([$arrayKey => $array]);
    }

    protected function arraySet(&$array, $path, $value)
    {
        $keys = explode('.', $path);

        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;
    }

    /**
     * Decode input values for array fields with wildcards.
     *
     * @param string $field The field name with wildcard (e.g., 'sentences.*.voice_id')
     * @return void
     */
    protected function decodeArrayInput(string $field): void
{
    // Handle case where the wildcard is in the middle of the path
    $pathParts = explode('.*', $field);
    $beforeWildcard = $pathParts[0]; // e.g., 'company.localized'
    $afterWildcard = $pathParts[1]; // e.g., '.local_id'

    // Handle nested paths before the wildcard
    $baseKeys = explode('.', $beforeWildcard);
    $arrayKey = $baseKeys[0]; // e.g., 'company'

    // Get the array from input
    $data = $this->input($arrayKey, []);

    // If we have a nested path like 'company.localized'
    if (count($baseKeys) > 1) {
        $currentData = $data;
        $pointer = &$currentData;

        // Navigate to the nested array
        for ($i = 1; $i < count($baseKeys); $i++) {
            if (!isset($pointer[$baseKeys[$i]]) || !is_array($pointer[$baseKeys[$i]])) {
                return; // Invalid path
            }
            $pointer = &$pointer[$baseKeys[$i]];
        }

        // Get the field key from after the wildcard
        $fieldKey = ltrim($afterWildcard, '.'); // e.g., 'local_id'

        // Process each item in the array
        foreach ($pointer as $index => $item) {
            $value = $item[$fieldKey] ?? null;

            if ($value && function_exists('decodeString')) {
                $decoded = decodeString($value);
                $pointer[$index][$fieldKey] = $decoded;
            }
        }

        // Update the request with the modified data
        $this->merge([$arrayKey => $currentData]);
    } else {
        // Handle simple case (no nesting before wildcard)
        // This is your existing implementation
        $fieldKey = ltrim($afterWildcard, '.'); // e.g., 'voice_id'

        // Process each item in the array
        foreach ($data as $index => $item) {
            $value = $item[$fieldKey] ?? null;

            if ($value && function_exists('decodeString')) {
                $decoded = decodeString($value);
                $data[$index][$fieldKey] = $decoded;
            }
        }

        // Update the request with the modified array
        $this->merge([$arrayKey => $data]);
    }
}

}
