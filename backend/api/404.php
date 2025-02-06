<?php

require_once __DIR__ . '/Services/ErrorHandler.php';
use api\Services\ErrorHandler;

$endpoint = $_SERVER['REQUEST_URI'];
ErrorHandler::handle("ENDPOINT_NOT_FOUND", "Endpoint " . $endpoint . " not found");
