<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Upload files to a specified directory.
 *
 * This module provides functions to upload files to a specified directory.
 * It creates the directory if it does not exist and stores the files in the directory.
 */

if (!function_exists('uploadFiles')) {
    /**
     * Upload multiple files to a specified directory.
     *
     * @param string $directory The directory where the files will be uploaded to.
     * @param array|UploadedFile $files An array of files to be uploaded.
     * @param string|null $disk The disk on which the files should be stored.
     *
     * @return array An array of file paths.
     *
     * @example
     * $filePath = uploadFiles("/", $request->someFile);
     * $files = uploadFiles("/", $request->files());
     */
    function uploadFiles(string $directory, array|UploadedFile $files, string $disk = null): array
    {
        // Remove extra slashes in directory path
        $directory = trim($directory, '/');

        // Create the destination directory if not exists
        $isDirectoryExists = File::exists($directory . '/');
        if (!$isDirectoryExists) {
            // Create the directory with 0777 permissions and recursive
            File::makeDirectory($directory, 0777, true, true);
        }

        // Support passing single file to the function
        if ($files instanceof UploadedFile) {
            $files = [$files];
        }

        $filesArray = [];
        foreach ($files as $file) {
            // Store each file and add the file path to the array
            $filesArray[] = storeFile($directory, $file, $disk);
        }

        return $filesArray;
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
