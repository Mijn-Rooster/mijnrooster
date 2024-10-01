<?php

require_once __DIR__ . '/../services/auth.php';
require_once __DIR__ . '/../services/zermeloAPI.php';
require_once __DIR__ . '/../services/errorHandler.php';

use api\Services\Auth;
use api\Services\ZermeloAPI;
use api\Services\ErrorHandler;

try {
    $auth = new Auth();
    $zermeloApi = new ZermeloAPI();

    // Token validatie
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        //throw new \Exception("Authorization header ontbreekt");
        $headers['Authorization'] = 'Bearer YOUR_STATIC_TOKEN';
    }

    $token = str_replace('Bearer ', '', $headers['Authorization']);
    $auth->validateToken($token);

    // Rooster data ophalen
    $studentId = 138563;
    $schoolInSchoolYear = 1001702;
    $roosterData = $zermeloApi->getUserData($studentId, $schoolInSchoolYear);

    // Succesvolle response
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $roosterData
    ]);
} catch (\Exception $e) {
    ErrorHandler::handle(400, $e->getMessage());
}
