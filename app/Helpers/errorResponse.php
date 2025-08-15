<?php

if (!function_exists('errorResponse')) {
    /**
     * Returns a success response with a message, data, and HTTP status code.
     *
     * @param string $message message A string parameter that represents the message to be
     * returned to the response. This message is usually used to provide additional
     * information about the response data or to inform the client about the status of
     * the request.
     * @param mixed $error data The  parameter is a mixed type variable that represents
     * the data that will be returned to the response. It can be any type of data, such
     * as an array, object, string, integer, boolean, etc.
     *
     * @return array A response with a success flag set to true, a message, some data, no
     * error, and an HTTP status code of 200 (OK).
     */
    function errorResponse(
        string $message,
        mixed $error = null,
        mixed $data = null,
    ): array
    {
        return array_filter([
            'success' => false,
            'message' => $message,
            'data' => $data,
            'error' => $error,
        ], fn($value) => !is_null($value) && $value !== '');
    }
}
