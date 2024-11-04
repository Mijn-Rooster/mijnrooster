<?php

namespace api\Models;

class Error
{
    private $statusCode;
    private $message;
    private $details;

    private static $errorDefinitions = [
        'AUTH_DENIED' => [
            'statusCode' => 401,
            'message' => 'Access denied',
            'details' => 'You are not authorized to access this resource',
        ],
        'AUTH_MISSING_TOKEN' => [
            'statusCode' => 401,
            'message' => 'Access denied',
            'details' => 'A token is required to perform this action',
        ],
        'AUTH_INVALID_TOKEN' => [
            'statusCode' => 401,
            'message' => 'Access denied',
            'details' => 'Invalid token',
        ],
        'ZERMELO_API_ERROR' => [
            'statusCode' => 500,
            'message' => 'An error occurred while fetching data from Zermelo',
            'details' => 'The Zermelo API returned an error',
        ],
        'MISSING_PARAMETERS' => [
            'statusCode' => 400,
            'message' => 'Missing parameters',
            'details' => 'Not all required parameters have been provided',
        ],
        'ENDPOINT_NOT_FOUND' => [
            'statusCode' => 404,
            'message' => 'Endpoint not found',
            'details' => 'The requested endpoint does not exist',
        ],
        'DEFAULT' => [
            'statusCode' => 500,
            'message' => 'An unknown error occurred',
            'details' => 'An unknown error occurred',
        ],
    ];

    public function setDetailsFromZermeloResponse($response) {
        if (empty($response) || !isset($response['status'])) {
            return;
        }

        $zermeloMessage = $response['message'];
        $zermeloDetails = isset($response['details']) ? $response['details'] : "";

        if(DEBUG_MODE) {
            $this->details = $zermeloMessage . " - " . $zermeloDetails;
        } else {
            $this->details = $zermeloMessage;
        }
    }

    public function __construct($errorCode = null, $details = null)
    {
        // If $errorCode is not set, set the default error code
        if (!isset($errorCode)) {
            $errorCode = 'DEFAULT';
        }

        // Set the error details
        $this->statusCode = self::$errorDefinitions[$errorCode]['statusCode'];
        $this->message = self::$errorDefinitions[$errorCode]['message'];
        $this->details = $details ?? self::$errorDefinitions[$errorCode]['details'];
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getDetails()
    {
        return $this->details;
    }
}