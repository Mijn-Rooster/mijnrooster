<?php

require_once __DIR__.'/router.php';
require_once __DIR__.'/Config/config.php';

if(DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Set headers for all requests
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

any('/', 'index.php');

get('/v1/school/$schoolInSchoolYear/user/$userId', 'Controllers/UserDetailsController.php');
get('/v1/school', 'Controllers/SchoolController.php');
get('/v1/school/$schoolInSchoolYearId', 'Controllers/SchoolController.php');
get('/v1/schedule/$studentId', 'Controllers/ScheduleController.php');

// Redirect all other request to page
any('/404','404.php');