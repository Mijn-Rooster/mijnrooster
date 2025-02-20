<?php

/**
 * Schools controller
 * 
 * This file contains the controller for the schools endpoint
 */

// Require the necessary files and classes
require_once __DIR__ . '/../Services/Auth.php';
require_once __DIR__ . '/../Services/ZermeloAPI.php';
require_once __DIR__ . '/../Services/ErrorHandler.php';
require_once __DIR__ . '/../Models/ResponseModel.php';
require_once __DIR__ . '/../Models/SchoolModel.php';

use api\Services\Auth;
use api\Services\ZermeloAPI;
use api\Services\ErrorHandler;
use api\Models\Response;
use api\Models\School;

try {
    // Generate a new instance of the Auth and ZermeloAPI class
    $auth = new Auth();

    // Authenticate via bearer token
    $auth->authenticate();

    // Define the school year automatically based on the current date
    $currentMonth = date('n');
    $currentYear = date('Y');

    $currentSchoolYear = ($currentMonth < 8) ? $currentYear - 1 : $currentYear;

    // Get schools from Zermelo API
    $zermeloApi = new ZermeloAPI();
    if (isset($schoolInSchoolYearId)) {
        $zermeloData = $zermeloApi->getSchoolInSchoolYear($schoolInSchoolYearId);
    } else {
        $zermeloData = $zermeloApi->getSchoolsAssignedToToken();
    }

    // Check if there is any response from the Zermelo API
    if ($zermeloData == []) {
        ErrorHandler::handle("NO_DATA");
    }

    // Check if the Zermelo API returned an error
    if ($zermeloData['status'] !== 200 && $zermeloData['status'] !== 404) {
        ErrorHandler::handleZermeloError($zermeloData);
    }

    // Check if the Zermelo API returned no data
    if (count($zermeloData['data']) === 0) {
        ErrorHandler::handle("SCHOOL_NOT_FOUND");
    }

    if (isset($zermeloData['data'][0]['employeeSchoolInSchoolYears'])) {
        $schoolsAssignedToToken = $zermeloData['data'][0]['employeeSchoolInSchoolYears'];
        $zermeloData = [];
        foreach ($schoolsAssignedToToken as $schoolAssignedToToken) {
            $request = $zermeloApi->getSchoolInSchoolYear($schoolAssignedToToken);
            if ($request['status'] === 200) {
                $zermeloData['data'][] = $request['data'][0];
            }
        }
    }

    // Create new school
    $schools = [];

    // Loop through the schools and add them to the school model
    foreach ($zermeloData['data'] as $schoolData) {
        $school = new School(
            $schoolData['id'],
            $schoolData['school'],
            $schoolData['year'],
            $schoolData['projectName'],
            $schoolData['schoolName']
        );

        array_push($schools, $school->getSchoolDetails());
    }

    // Create response
    $response = new Response($schools);
    $response->send();
} catch (\Exception $e) {
    ErrorHandler::handle("ERROR", $e->getMessage());
}
