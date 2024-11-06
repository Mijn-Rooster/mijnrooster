<?php

/**
 * Zermelo user Controller
 * 
 * This file contains the logic for the userdetails endpoint.
 */

// Require the necessary files and classes
require_once __DIR__ . '/../Services/Auth.php';
require_once __DIR__ . '/../Services/ZermeloAPI.php';
require_once __DIR__ . '/../Services/ErrorHandler.php';
require_once __DIR__ . '/../Models/ResponseModel.php';
require_once __DIR__ . '/../Models/ZermeloUserModel.php';

use api\Services\Auth;
use api\Services\ZermeloAPI;
use api\Services\ErrorHandler;
use api\Models\Response;
use api\Models\ZermeloUser;

try {
    // Generate a new instance of the Auth and ZermeloAPI class
    $auth = new Auth();

    // Authenticate via bearer token
    $auth->authenticate();

    // Check if user type is given
    if (!isset($_GET['type'])) {
        // First search for the user in the student list
        $user = getStudentDetails($userId, $schoolInSchoolYear);

        // If there is no user found in the student list, search for the user in the teacher list
        if ($user === null) {
            $user = getTeacherDetails($userId, $schoolInSchoolYear);
        }
    } else if ($_GET['type'] === "student") {
        $user = getStudentDetails($userId, $schoolInSchoolYear);
    } else if ($_GET['type'] === "teacher") {
        $user = getTeacherDetails($userId, $schoolInSchoolYear);
    } else {
        ErrorHandler::handle("PARAMETER_INVALID","The parameter 'type' is invalid. Please use 'student' or 'teacher'");
    }

    // Check if the user is found
    if ($user === null) {
        ErrorHandler::handle("ZERMELO_USER_NOT_FOUND");
    }

    // Create response
    $response = new Response([$user->getUserDetails()]);
    $response->send();
} catch (\Exception $e) {
    ErrorHandler::handle("ERROR", $e->getMessage());
}

/**
 * Get the details of a student
 * @param mixed $userId
 * @param int $schoolInSchoolYear
 * @return ZermeloUser|null Returns the user object if the user is found, otherwise null
 */
function getStudentDetails($userId, $schoolInSchoolYear): ?ZermeloUser {
    $zermeloApi = new ZermeloAPI();
    $studentData = $zermeloApi->getStudentDetails($userId, $schoolInSchoolYear);

    // Check if the Zermelo API returned an error
    if ($studentData['status'] !== 200) {
        ErrorHandler::handleZermeloError($studentData);
    }

    // Check if the Zermelo API returned no data
    if (count($studentData['data']) === 0) {
        return null;
    }

    // Create user object for teacher
    $user = new ZermeloUser(
        $studentData['data'][0]['student'],
        $studentData['data'][0]['firstName'],
        $studentData['data'][0]['prefix'] ?? "",
        $studentData['data'][0]['lastName'],
    );

    return $user;
}

/**
 * Get the details of a teacher
 * @param mixed $userId
 * @param int $schoolInSchoolYear
 * @return ZermeloUser|null Returns the user object if the user is found, otherwise null
 */
function getTeacherDetails($userId, $schoolInSchoolYear): ?ZermeloUser {
    $zermeloApi = new ZermeloAPI();
    $teacherData = $zermeloApi->getTeacherDetails($userId, $schoolInSchoolYear);

    // Check if the Zermelo API returned an error
    if ($teacherData['status'] !== 200) {
        ErrorHandler::handleZermeloError($teacherData);
    }

    // Check if the Zermelo API returned no data
    if (count($teacherData['data']) === 0) {
        return null;
    }

    // Create user object for teacher
    $user = new ZermeloUser(
        $teacherData['data'][0]['employee'],
        $teacherData['data'][0]['firstName'],
        $teacherData['data'][0]['prefix'] ?? "",
        $teacherData['data'][0]['lastName'],
    );

    return $user;
}
