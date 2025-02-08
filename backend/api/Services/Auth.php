<?php

namespace api\Services;

/**
 * Auth Service
 * The Auth service is responsible for authenticating the user via a bearer token.
 * To be implemented further in the future.
 */
class Auth {

    /**
     * Validate the token
     *
     * @param string $token
     * @return bool
     */
    private function validateToken($token) {
        if ($token === hash('sha256', CONNECT_CODE . 'D@v1dRein0utJ0nathan')) {
            return true;
        } else {
            return false;
        }
    }

    /** 
     * Authenticate via bearer token
    */
    public function authenticate(): void {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            ErrorHandler::handle("AUTH_MISSING_TOKEN");
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        if (!$this->validateToken($token)) {
            ErrorHandler::handle("AUTH_INVALID_TOKEN");
        }
    }
}
