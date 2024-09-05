<?php

require_once __DIR__.'/router.php';
require_once __DIR__.'/config/config.php';

// Set headers for all requests
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // TODO: CHANGE IN FUTURE TO ONLY ALLOW KNOWN ORIGINS
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

get('/api/v1/user', 'controllers/userController.php');
get('/api/v1/schedule', 'controllers/scheduleController.php');

// Redirect all other request to page
any('/404','index.php');