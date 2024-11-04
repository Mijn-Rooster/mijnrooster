<?php

namespace api\Models;

/**
 * Error Model
 * An error is a response that indicates that something went wrong.
 * It contains a status code, a message and details.
 * These values are defined by the error code that is passed to the constructor.
 */
class Error
{
    /**
     * The status code of the error (e.g. 403)
     * @var int $statusCode
     */
    private int $statusCode;
    /**
     * The message of the error (e.g. "Access denied")
     * @var string $message
     */
    private string $message;
    /**
     * The details of the error (e.g. "You are not authorized to access this resource")
     * @var string $details
     */
    private string $details;

    /**
     * An array of error definitions that can be used to generate an error.
     * This is the place to define new error codes and their corresponding status codes and messages.
     * @var array $errorDefinitions
     */
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

    /**
     * Create a new error instance.
     * @param string $errorCode, the error code that defines the errorcode and message, defaults to 'DEFAULT'
     * @param string $details (optional), the details of the error
     */
    public function __construct($errorCode = 'DEFAULT', $details = null)
    {
        // if the error code is not defined, set it to the default error code
        if (!isset(self::$errorDefinitions[$errorCode])) {
            $errorCode = 'DEFAULT';
        }

        // Set the error details
        $this->statusCode = self::$errorDefinitions[$errorCode]['statusCode'];
        $this->message = self::$errorDefinitions[$errorCode]['message'];
        $this->details = $details ?? self::$errorDefinitions[$errorCode]['details'];
    }

    /**
     * Set the error details from a Zermelo API response.
     * @param array $response the response from the Zermelo API that contains the error details
     */
    public function setDetailsFromZermeloResponse($response): void {
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

    /**
     * Set the status code of the error.
     * @param int $statusCode
     * @return void
     */
    public function setStatusCode($statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Return the status code of the error.
     * @return int $statusCode
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Return the message of the error.
     * @return string $message
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Return the details of the error.
     * @return string $details
     */
    public function getDetails(): string
    {
        return $this->details;
    }
}