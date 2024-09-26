<?php

require 'errorHandling.php';

function checkResponse($curl, $response = null)
{
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $response = json_decode($response, true);

    if (isset($response['response']['status']) && $response['response']['status'] !== 200) {
        errorResponse('ZERMELO_ERROR', $response['response']['message'], $response['response']['details']);
    }

    if ($httpCode !== 200) {
        switch ($httpCode) {
            case 400:
                errorResponse('INVALID_REQUEST');
                break;
            case 401:
                errorResponse('UNAUTHORIZED');
                break;
            case 403:
                errorResponse('FORBIDDEN');
                break;
            case 404:
                errorResponse('NOT_FOUND');
                break;
            case 429:
                errorResponse('TOO_MANY_REQUESTS');
                break;
            case 500:
                errorResponse('INTERNAL_SERVER_ERROR');
                break;
            case 0:
                errorResponse('INVALID_URL');
            default:
                errorResponse('UNKNOWN_ERROR', 'Request failed with HTTP code ' . $httpCode);
        }
    }

    return true;
}
