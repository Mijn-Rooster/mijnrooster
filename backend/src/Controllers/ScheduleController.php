<?php

/**
 * Schedule Controller
 * 
 * This file contains the logic for the schedule controller.
 * What does this controller do? It gets the schedule data from the Zermelo API and returns it.
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
    $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : strtotime('-1 week monday 00:00:00');
    $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : strtotime('sunday 23:59:59');
    $zermeloData = $zermeloApi->getScheduleData($studentId, $startDate, $endDate);

    // Return the data
    echo json_encode($zermeloData);
} catch (\Exception $e) {
    ErrorHandler::handle("ERROR", $e->getMessage());
}
