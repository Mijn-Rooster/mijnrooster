<?php

namespace api\Services;

require_once __DIR__ . '/../Models/ResponseModel.php';
require_once __DIR__ . '/../Models/ErrorModel.php';

use api\Models\Error;
use api\Models\Response;

class ErrorHandler {
    /**
     * This function handles the error and sends a JSON response with the error message.
     *
     * @param string $errorCode The error code.
     * @param string $message The error message. If not set, the default message for the error code will be used.
     * @param string $details The error details. Optional.
     * @param int $responseCode The HTTP response code. Default is 500.
     * @return string A JSON response with the error message.
     */
    public static function handle($errorCode, $details = "") {
        // Create new error object
        $error = new Error($errorCode, $details);

        // Create response
        $response = new Response(
            $error->getStatusCode(),
            $error->getMessage(),
            $error->getDetails()
        );
        $response->send();

        exit;
    }

}
