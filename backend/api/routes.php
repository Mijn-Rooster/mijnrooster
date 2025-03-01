<?php

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With, Authorization');
    Header('Access-Control-Allow-Credentials: true');
    header('HTTP/1.1 200 OK');
    exit();
}

// Set headers for all other requests
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With, Authorization');

require_once __DIR__.'/Services/ConfigCheck.php';

use api\Services\ConfigCheck;

// Verify configuration before proceeding
if (!ConfigCheck::verifyConfig()) {
    exit; // Error message is handled in ConfigHelper
}

require_once __DIR__.'/router.php';
require_once __DIR__.'/Config/config.php';
require_once __DIR__.'/Config/version.php';
require_once __DIR__.'/Services/Logging.php';

use api\Services\LoggingRequest;

if(defined('DEBUG_MODE') && DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Log the request
    LoggingRequest::logRequest();
}

any('/', 'index.php');

get('/v1/schools/$schoolInSchoolYear/user/$userId', 'Controllers/UserDetailsController.php');
get('/v1/schools', 'Controllers/SchoolController.php');
get('/v1/schools/$schoolInSchoolYearId', 'Controllers/SchoolController.php');
get('/v1/schedule/$studentId', 'Controllers/ScheduleController.php');

get('/v1/check', 'Controllers/DeviceCheckController.php');

// Redirect all other request to page
any('/404','404.php');