<?php

require_once __DIR__.'/router.php';
require_once __DIR__.'/config/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set headers for all requests
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // TODO: CHANGE IN FUTURE TO ONLY ALLOW KNOWN ORIGINS
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

any('/', 'index.php');

get('/api/v1/school', 'Controllers/SchoolController.php'); // To be implemented
get('/api/v1/school/$schoolInSchoolYear', 'Controllers/SchoolController.php'); // To be implemented
get('/api/v1/school/$schoolInSchoolYear/student/$studentId', 'Controllers/StudentController.php');
get('/api/v1/school/$schoolInSchoolYear/teacher/$teacherId', 'Controllers/TeacherController.php'); // To be implemented
get('/api/v1/schedule/$studentId', 'Controllers/ScheduleController.php');

// Redirect all other request to page
any('/404','404.php');