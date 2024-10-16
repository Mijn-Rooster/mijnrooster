<?php

namespace api\Services;

class Auth {
    /**
     * The valid token. See config.php for the API_TOKEN constant.
     *
     * @var string
     */
    private $validToken = API_TOKEN;

    /**
     * Validate the token
     *
     * @param string $token
     * @return bool
     */
    public function validateToken($token) {
        if ($token === $this->validToken) {
            return true;
        } else {
            return false;
        }
    }

    /** 
     * Authenticate via bearer token
    */
    public function authenticate() {
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
