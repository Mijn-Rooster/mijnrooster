<?php

// Foutmeldingen lijst
$ERRORS = array(
    'INVALID_URL' => 'Ongeldige Zermelo URL.',
    'ZERMELO_ERROR' => 'Er is een fout opgetreden bij het ophalen van de gegevens van Zermelo.',
    'UNKNOWN_ERROR' => 'Er is een onbekende fout opgetreden.',
    'INVALID_REQUEST' => 'Ongeldige aanvraag.',
    'UNAUTHORIZED' => 'Ongeautoriseerde aanvraag.',
    'FORBIDDEN' => 'Geen toegang',
    'NOT_FOUND' => 'Resource niet gevonden.',
    'TOO_MANY_REQUESTS' => 'Te veel aanvragen.',
    'INTERNAL_SERVER_ERROR' => 'Interne serverfout.',
);

function errorResponse($error, $message = "Er ging iets fout", $details = '')
{
    global $ERRORS;

    $response = [
        'error' => $error,
        'message' => isset($ERRORS[$error]) ? $ERRORS[$error] : $message,
        'details' => $details,
    ];

    http_response_code(400);
    echo json_encode($response);
    exit;
}
