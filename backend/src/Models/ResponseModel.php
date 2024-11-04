<?php

namespace api\Models;

class Response
{
    private $statusCode;
    private $message;
    private $details;
    private $rowCount;
    private $data;

    public function __construct($data = [], $statusCode = 200, $message = "", $details = "", $rowCount = 0)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->details = $details;
        $this->rowCount = $rowCount;
        $this->data = $data;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setDetails($details)
    {
        $this->details = $details;
    }

    public function send()
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