<?php

namespace api\Services;

class ErrorHandler {
    public static function handle($code, $message, $details = null) {
        http_response_code($code);
        echo json_encode([
            'errorCode' => $code,
            'errorMessage' => $message,
            'errorDetails' => $details
        ]);
        exit;
    }
}
