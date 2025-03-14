<?php

namespace api\Services;

class LoggingRequest {
    public static function logRequest() {
        if (!file_exists(__DIR__ . '/../logs')) {
            mkdir(__DIR__ . '/../logs', 0777, true);
        }
        $logFile = __DIR__ . '/../logs/request.log';
        $headers = getallheaders();
        $log = date('Y-m-d H:i:s') . "\n";
        $log .= "Method: " . $_SERVER['REQUEST_METHOD'] . "\n";
        $log .= "URI: " . $_SERVER['REQUEST_URI'] . "\n";
        $log .= "Headers: " . json_encode($headers, JSON_PRETTY_PRINT) . "\n";
        $log .= "------------------------\n";
        
        file_put_contents($logFile, $log, FILE_APPEND);
    }
}