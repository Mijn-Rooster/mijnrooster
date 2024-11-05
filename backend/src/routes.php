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
get('/v1/schedule/$studentId', 'Controllers/ScheduleController.php');

// Future endpoints

//get('/v1/school', 'Controllers/SchoolsController.php'); // To be implemented
//get('/v1/school/$schoolInSchoolYear', 'Controllers/SchoolController.php'); // To be implemented
//get('/v1/device', 'Controllers/DeviceController.php'); // To be implemented
//get('/v1/device/$deviceId', 'Controllers/DeviceController.php'); // To be implemented
//put('/v1/device/$deviceId', 'Controllers/DeviceController.php'); // To be implemented
//delete('/v1/device/$deviceId', 'Controllers/DeviceController.php'); // To be implemented
//post('/v1/device/$deviceId/alive', 'Controllers/DeviceController.php'); // To be implemented
//post('/v1/device/register', 'Controllers/DeviceController.php'); // To be implemented


// Redirect all other request to page
any('/404','404.php');