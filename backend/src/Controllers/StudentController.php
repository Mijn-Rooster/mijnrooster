<?php

/**
 * Student Controller
 * 
 * This file contains the logic for the student controller.
 * What does this controller do? It gets the student data from the Zermelo API and returns it.
 * 
 */

// Require the necessary files and classes
require_once __DIR__ . '/../Services/Auth.php';
require_once __DIR__ . '/../Services/ZermeloAPI.php';
require_once __DIR__ . '/../Services/ErrorHandler.php';

use api\Services\Auth;
use api\Services\ZermeloAPI;
use api\Services\ErrorHandler;

try {
    // Generate a new instance of the Auth and ZermeloAPI class
    $auth = new Auth();
    $zermeloApi = new ZermeloAPI();

    // Authenticate via bearer token
    $auth->authenticate();

    // Get user data from Zermelo API
    $zermeloData = $zermeloApi->getStudentData($studentId, $schoolInSchoolYear);

    // Return the data
    echo json_encode($zermeloData);
} catch (\Exception $e) {
    ErrorHandler::handle("ERROR", $e->getMessage());
}
