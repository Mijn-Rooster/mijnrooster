<?php

namespace api\Services;

class ErrorHandler {
    /**
     * This function handles the error and sends a JSON response with the error message.
     *
     * @param string $errorCode The error code.
     * @param string $message The error message. If not set, the default message for the error code will be used.
     * @param string $details The error details. Optional.
     * @param int $responseCode The HTTP response code. Default is 500.
     * @return A JSON response with the error message.
     */
    public static function handle($errorCode, $details = "") {

        $errorMessage = self::getErrorMessage($errorCode);

        http_response_code($errorMessage['responseCode']);
        echo json_encode([
            'errorCode' => $errorCode,
            'errorMessage' => $errorMessage['message'],
            'errorDetails' => $details
        ]);
        exit;
    }


    /**
     * This function returns an array with error messages for different error codes.
     *
     * @param string $errorCode The error code.
     * @return array An array with the error message.
     */
    private static function getErrorMessage($errorCode) {
        switch ($errorCode) {
            case 'AUTH_DENIED':
                return [
                    'responseCode' => 401,
                    'message' => "Verboden toegang. Je hebt geen toegang tot dit endpoint",
                ];
            case 'AUTH_MISSING_TOKEN':
                return [
                    'responseCode' => 401,
                    'message' => "Verboden toegang. Een token is vereist om deze actie uit te voeren",
                ];
            case 'AUTH_INVALID_TOKEN':
                return [
                    'responseCode' => 401,
                    'message' => "Verboden toegang. Ongeldig token",
                ];
            case 'ZERMELO_API_ERROR':
                return [
                    'responseCode' => 500,
                    'message' => "Er is een fout opgetreden bij het ophalen van data van Zermelo",
                ];
            case 'MISSING_PARAMETERS':
                return [
                    'responseCode' => 400,
                    'message' => "Ontbrekende parameters. Niet alle benodigde parameters zijn opgegeven",
                ];
            case 'ENDPOINT_NOT_FOUND':
                return [
                    'responseCode' => 404,
                    'message' => "Endpoint niet gevonden",
                ];
            default:
                return [
                    'responseCode' => 500,
                    'message' => "Er is een onbekende fout opgetreden",
                ];
        }
    }

    /**
     * This function returns the error details from the Zermelo API response.
     *
     * @param string $response The response from the Zermelo API.
     * @return string The error details.
     */
    public static function getZermeloErrorDetails($response) {
        $response = json_decode($response, true);
        if (empty($response) || !isset($response['response'])) {
            return "";
        }

        echo print_r($response);

        $status = $response['response']['status'];
        $message = $response['response']['message'];
        $details = isset($response['response']['details']) ? $response['response']['details'] : "";

        return "ZERMELO [" . $status . ": " . $message . " " . $details . "]";
    }


}
