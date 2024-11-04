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
require_once __DIR__ . '/../Models/ResponseModel.php';
require_once __DIR__ . '/../Models/StudentModel.php';

use api\Services\Auth;
use api\Services\ZermeloAPI;
use api\Services\ErrorHandler;
use api\Models\Response;
use api\Models\Student;

try {
    // Generate a new instance of the Auth and ZermeloAPI class
    $auth = new Auth();
    $zermeloApi = new ZermeloAPI();

    // Authenticate via bearer token
    $auth->authenticate();

    // Get student data from Zermelo API
    $zermeloData = $zermeloApi->getStudentDetails($studentId, $schoolInSchoolYear);

    // Check if the Zermelo API returned an error
    if ($zermeloData['status'] !== 200) {
        ErrorHandler::handleZermeloError($zermeloData);
    }

    // Check if the Zermelo API returned no data
    if (count($zermeloData['data']) === 0) {
        $response = new Response([]);
        $response->send();
    }

    // Create student
    $student = new Student(
        $zermeloData['data'][0]['student'],
        $zermeloData['data'][0]['firstName'],
        $zermeloData['data'][0]['prefix'],
        $zermeloData['data'][0]['lastName'],
        $zermeloData['data'][0]['mainGroupName'],
        $zermeloData['data'][0]['mainGroup'],
        $zermeloData['data'][0]['mentorGroup'],
        $zermeloData['data'][0]['departmentOfBranch']
    );

    // Create response
    $response = new Response([$student->getStudent()]);
    $response->send();
} catch (\Exception $e) {
    ErrorHandler::handle("ERROR", $e->getMessage());
}
