<?php

if (!function_exists('successResponse')) {
    /**
     * Returns a success response with a message, data, and HTTP status code.
     *
     * @param string message A string parameter that represents the message to be
     * returned to the response. This message is usually used to provide additional
     * information about the response data or to inform the client about the status of
     * the request.
     * @param mixed data The  parameter is a mixed type variable that represents
     * the data that will be returned to the response. It can be any type of data, such
     * as an array, object, string, integer, boolean, etc.
     *
     * @return array A response with a success flag set to true, a message, some data, no
     * error, and an HTTP status code of 200 (OK).
     */
    function successResponse(
        string $message,
        mixed $data = null,
        string $token = null,
        array $additional_data = null
    ): array
    {
        return array_filter([
            'success' => true,
            'message' => $message,
            'token' => $token,
            'data' => $data,
            'additional_data' => $additional_data,
        ]);
    }
}
