<?php

namespace api\Services;

// Require the necessary models
require_once __DIR__ . '/../Models/ResponseModel.php';
require_once __DIR__ . '/../Models/ErrorModel.php';

use api\Models\Error;
use api\Models\Response;

/**
 * Error Handler
 * This class handles errors and sends a JSON response with the error message.
 */

class ErrorHandler {

    /**
     * This function handles the error and sends a JSON response with the error message.
     *
     * @param string $errorCode The error code.
     * @param string $details The error details.
     * @return never send a JSON response with the error message.
     */
    public static function handle($errorCode, $details = ""): never {
        // Create new error object
        if ($details === "") {
            $error = new Error($errorCode);
        } else {
            $error = new Error($errorCode, $details);
        }

        // Create response
        $response = new Response(
            data: [],
            statusCode: $error->getStatusCode(),
            message: $error->getMessage(),
            details: $error->getDetails()
        );
        $response->send();
    }

    /**
     * This function handles the error and sends a JSON response with the error message.
     *
     * @param array $zermeloData The response from the Zermelo API.
     * @return never send a JSON response with the error message.
     */
    public static function handleZermeloError($zermeloData): never {
        // Create a new error model
        $error = new Error(
            "ZERMELO_API_ERROR",
        );

        // Set the error details from the Zermelo API response
        $error->setStatusCode($zermeloData['status']);
        $error->setDetailsFromZermeloResponse($zermeloData);

        // Send the error response
        $response = new Response(
            data: [],
            statusCode: $error->getStatusCode(),
            message: $error->getMessage(),
            details: $error->getDetails()
        );
        $response->send();
    }
}
