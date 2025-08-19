<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * Upload files to a specified directory.
 *
 * This module provides functions to upload files to a specified directory.
 * It creates the directory if it does not exist and stores the files in the directory.
 */

if (!function_exists('uploadFiles')) {
    /**
     * Upload files to storage
     *
     * @param mixed $file
     * @param string $path
     * @param string $disk
     * @return string|null
     */
    function uploadFiles($file, string $path = 'uploads', string $disk = 'public'): ?string
    {
        if (!$file) {
            return null;
        }

        try {
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs($path, $fileName, $disk);
            
            return $filePath;
        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload a single file to a specified directory.
     *
     * @param string $directory The directory where the file will be uploaded to.
     * @param UploadedFile $file The file to be uploaded.
     * @param string|null $disk The disk on which the file should be stored.
     *
     * @return string The file path.
     */
    function uploadFile(string $directory, UploadedFile $file, string $disk = null): string
    {
        // Remove extra slashes in directory path
        $directory = trim($directory, '/');

        // Create the destination directory if not exists
        $isDirectoryExists = File::exists($directory . '/');
        Log::alert('$isDirectoryExists: ' . $isDirectoryExists);
        if (!$isDirectoryExists) {
            // Create the directory with 0777 permissions and recursive
            File::makeDirectory($directory, 0777, true, true);
        }

        // Store the file and return the file path
        return storeFile($directory, $file, $disk);
    }

    /**
     * Store an uploaded file in local storage.
     *
     * @param string $directory The directory where the file will be stored.
     * @param UploadedFile $file The file to be stored.
     * @param string|null $disk The disk on which the file should be stored.
     *
     * @return string The file path.
     */
    function storeFile(string $directory, UploadedFile $file, string $disk = null): string
    {
        // Store file in local storage and return the file path
        Log::alert('is file an instance of UploadedFile: ' . $file instanceof UploadedFile);
        $disk = $disk ?? config('filesystems.default');
        Log::alert('Disk: ' . $disk);
        $path = Storage::disk($disk )->put($directory, $file);
        Log::alert('Uploaded File: ' . $path);
        return str_replace('public/', '', $path);
    }

    /**
     * Delete a file from storage
     *
     * @param string $path The file path to delete, relative to storage
     * @param string|null $disk The storage disk to use
     * @return bool Whether the deletion was successful
     */
    function deleteFile(string $path, string $disk = null): bool
    {
        try {
            $disk = $disk ?? config('filesystems.default');
            $fullPath = 'public/' . $path;

            if (Storage::disk($disk)->exists($fullPath)) {
                $result = Storage::disk($disk)->delete($fullPath);
                return $result;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
