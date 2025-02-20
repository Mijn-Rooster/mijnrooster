<?php

/**
 * Device Check Controller
 * 
 * With this endpoint the device can check if it is connected to the API.
 */

// Require the necessary files and classes
require_once __DIR__ . '/../Services/Auth.php';
require_once __DIR__ . '/../Services/ErrorHandler.php';

use api\Services\Auth;
use api\Services\ErrorHandler;
use api\Models\Response;

try {
    // Generate a new instance of the Auth and ZermeloAPI class
    $auth = new Auth();

    // Authenticate via bearer token
    $auth->authenticate();

    // Create response
    $response = new Response([
        'tenant' => TENANT_NAME,
        'version' => API_VERSION,
    ], 200, "Device is connected to the API");
    $response->send();
} catch (\Exception $e) {
    ErrorHandler::handle("ERROR", $e->getMessage());
}
