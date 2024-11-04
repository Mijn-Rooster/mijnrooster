<?php

namespace api\Models;

class Response
{
    private $statusCode;
    private $message;
    private $details;
    private $rowCount;
    private $data;

    public function __construct($statusCode, $message, $details = null, $rowCount = 0, $data = [])
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->details = $details;
        $this->rowCount = $rowCount;
        $this->data = $data;
    }

    public function send()
    {
        $response = [
            'statusCode' => $this->statusCode,
            'message' => $this->message,
            'details' => $this->details,
            'rowCount' => $this->rowCount,
            'data' => $this->data,
        ];

        http_response_code($this->statusCode);
        echo json_encode($response);
        exit;
    }	
}