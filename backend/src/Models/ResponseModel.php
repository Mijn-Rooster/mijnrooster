<?php

namespace api\Models;

/**
 * Response Model
 * A response is a message that is sent back to the client.
 * It contains a status code, a message, details, a row count and data.
 */

class Response
{
    /**
     * The status code of the response (e.g. 200)
     * @var int $statusCode
     */
    private int $statusCode;
    /**
     * The message of the response
     * @var string $message
     */
    private string $message;
    /**
     * The details of the response
     * @var string $details
     */
    private string $details;
    /**
     * The number of rows in the response data
     * @var int $rowCount
     */
    private int $rowCount;
    /**
     * The data of the response
     * @var array $data
     */
    private array $data;

    /**
     * Create a new response instance.
     * @param array $data
     * @param int $statusCode
     * @param string $message
     * @param string $details
     * @param int $rowCount
     */
    public function __construct($data = [], $statusCode = 200, $message = "", $details = "", $rowCount = 0)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->details = $details;
        $this->rowCount = $rowCount;
        $this->data = $data;
    }

    /**
     * Send the response to the client as a JSON object.
     * @return void
     */
    public function send(): never
    {
        // Get rowcount from data
        $this->rowCount = count($this->data);

        // Create response array
        $response = [
            'statusCode' => $this->statusCode,
            'message' => $this->message,
            'details' => $this->details,
            'rowCount' => $this->rowCount,
            'data' => $this->data,
        ];

        // Set the response code and send the response
        http_response_code($this->statusCode);
        echo json_encode($response);
        exit;
    }	
}