<?php

namespace api\Services;

class Auth {
    private $validToken = "YOUR_STATIC_TOKEN"; // Vervang met jouw eigen token

    public function validateToken($token) {
        if ($token === $this->validToken) {
            return true;
        } else {
            throw new \Exception("Invalid token");
        }
    }
}
