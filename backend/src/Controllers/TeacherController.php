<?php

/**
 * Teacher Controller
 * 
 * This file contains the logic for the teacher endpoint.
 */

// Require the necessary files and classes
require_once __DIR__ . '/../Services/Auth.php';
require_once __DIR__ . '/../Services/ZermeloAPI.php';
require_once __DIR__ . '/../Services/ErrorHandler.php';
require_once __DIR__ . '/../Models/ResponseModel.php';
require_once __DIR__ . '/../Models/TeacherModel.php';

use api\Services\Auth;
use api\Services\ZermeloAPI;
use api\Services\ErrorHandler;
use api\Models\Response;
use api\Models\Teacher;

try {
    // Generate a new instance of the Auth and ZermeloAPI class
    $auth = new Auth();
    $zermeloApi = new ZermeloAPI();

    // Authenticate via bearer token
    $auth->authenticate();

    // Get teacher data from Zermelo API
    $zermeloData = $zermeloApi->getTeacherDetails($teacherId, $schoolInSchoolYear);

    // Check if the Zermelo API returned an error
    if ($zermeloData['status'] !== 200) {
        ErrorHandler::handleZermeloError($zermeloData);
    }

    // Check if the Zermelo API returned no data
    if (count($zermeloData['data']) === 0) {
        $response = new Response([]);
        $response->send();
    }

    // Create teacher
    $teacher = new Teacher(
        code: $zermeloData['data'][0]['employee'],
        firstName: $zermeloData['data'][0]['firstName'],
        prefix: $zermeloData['data'][0]['prefix'] ?? "",
        lastName: $zermeloData['data'][0]['lastName'],
    );

    // Create response
    $response = new Response([$teacher->getTeacher()]);
    $response->send();
} catch (\Exception $e) {
    ErrorHandler::handle("ERROR", $e->getMessage());
}
