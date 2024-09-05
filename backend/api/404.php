<?php
$endpoint = $_SERVER['REQUEST_URI'];
http_response_code(404);
echo json_encode(['error' => 'Endpoint ' . $endpoint . ' not found']);

