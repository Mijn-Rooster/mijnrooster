<?php

namespace api\Models;

/**
 * Error Model
 * An error is a response that indicates that something went wrong.
 * It contains a status code, a message and details.
 * These values are defined by the error code that is passed to the constructor.
 */
class Error {
    /**
     * The status code of the error (e.g. 403)
     * @var int $statusCode
     */
    private int $statusCode;
    /**
     * The message of the error (e.g. "Access denied")
     * @var string $message
     */
    private string $message;
    /**
     * The details of the error (e.g. "You are not authorized to access this resource")
     * @var string $details
     */
    private string $details;

    /**
     * An array of error definitions that can be used to generate an error.
     * This is the place to define new error codes and their corresponding status codes and messages.
     * @var array $errorDefinitions
     */
    private static $errorDefinitions = [
        'AUTH_DENIED' => [
            'statusCode' => 401,
            'message' => 'Toegang geweigerd',
            'details' => 'Je hebt geen toegang tot deze resource',
        ],
        'AUTH_MISSING_TOKEN' => [
            'statusCode' => 401,
            'message' => 'Toegang geweigerd',
            'details' => 'Er is een token nodig om deze actie uit te voeren',
        ],
        'AUTH_INVALID_TOKEN' => [
            'statusCode' => 401,
            'message' => 'Toegang geweigerd',
            'details' => 'Ongeldig token',
        ],
        'ZERMELO_API_ERROR' => [
            'statusCode' => 500,
            'message' => 'Er is een fout opgetreden bij het ophalen van data van Zermelo',
            'details' => 'De Zermelo API gaf een fout terug',
        ],
        'ZERMELO_USER_NOT_FOUND' => [
            'statusCode' => 404,
            'message' => 'Gebruiker niet gevonden',
            'details' => 'De gebruiker kon niet worden gevonden in de Zermelo API',
        ],
        'SCHEDULE_INVALID_DATE' => [
            'statusCode' => 400,
            'message' => 'Ongeldige datum',
            'details' => 'De startdatum ligt na de einddatum of het verschil tussen start- en einddatum is te groot',
        ],
        'MISSING_PARAMETERS' => [
            'statusCode' => 400,
            'message' => 'Ontbrekende parameters',
            'details' => 'Niet alle vereiste parameters zijn meegegeven',
        ],
        'PARAMETER_INVALID' => [
            'statusCode' => 400,
            'message' => 'Ongeldige parameter',
            'details' => 'De parameter is ongeldig',
        ],
        'ENDPOINT_NOT_FOUND' => [
            'statusCode' => 404,
            'message' => 'Endpoint niet gevonden',
            'details' => 'Het opgevraagde endpoint bestaat niet',
        ],
        'SCHOOL_NOT_FOUND' => [
            'statusCode' => 404,
            'message' => 'School niet gevonden',
            'details' => 'Er zijn geen scholen gevonden in de Zermelo API',
        ],
        'NO_DATA' => [
            'statusCode' => 500,
            'message' => 'Er is een fout opgetreden in de communicatie met het netwerk',
            'details' => 'Controleer de internetverbinding van uw server',
        ],
        'CONFIG_MISSING' => [
            'statusCode' => 500,
            'message' => 'Configuration file niet gevonden',
            'details' => 'Kopieer config.php.example naar config.php en configureer de instellingen',
        ],
        'CONFIG_NOT_WRITABLE' => [
            'statusCode' => 500,
            'message' => 'Configuratie vereist aanpassingen',
            'details' => 'De configuratie moet worden bijgewerkt maar het bestand is niet schrijfbaar',
        ],
        'DEFAULT' => [
            'statusCode' => 500,
            'message' => 'Er is een onbekende fout opgetreden',
            'details' => 'Er is een onbekende fout opgetreden',
        ],
    ];

    /**
     * Create a new error instance.
     * @param string $errorCode, the error code that defines the errorcode and message, defaults to 'DEFAULT'
     * @param string $details (optional), the details of the error
     */
    public function __construct($errorCode = 'DEFAULT', $details = null) {
        // if the error code is not defined, set it to the default error code
        if (!isset(self::$errorDefinitions[$errorCode])) {
            $errorCode = 'DEFAULT';
        }

        // Set the error details
        $this->statusCode = self::$errorDefinitions[$errorCode]['statusCode'];
        $this->message = self::$errorDefinitions[$errorCode]['message'];
        $this->details = $details ?? self::$errorDefinitions[$errorCode]['details'];
    }

    /**
     * Set the error details from a Zermelo API response.
     * @param array $response the response from the Zermelo API that contains the error details
     */
    public function setDetailsFromZermeloResponse($response): void {
        if (empty($response) || !isset($response['status'])) {
            return;
        }

        $zermeloMessage = $response['message'];
        $zermeloDetails = isset($response['details']) ? $response['details'] : "";

        $this->details = $zermeloMessage;
        if (DEBUG_MODE && !empty($zermeloDetails)) {
            $this->details .= ". Details: " . $zermeloDetails;
        }
    }

    /**
     * Set the status code of the error.
     * @param int $statusCode
     * @return void
     */
    public function setStatusCode($statusCode): void {
        $this->statusCode = $statusCode;
    }

    /**
     * Return the status code of the error.
     * @return int $statusCode
     */
    public function getStatusCode(): int {
        return $this->statusCode;
    }

    /**
     * Return the message of the error.
     * @return string $message
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * Return the details of the error.
     * @return string $details
     */
    public function getDetails(): string {
        return $this->details;
    }
}
