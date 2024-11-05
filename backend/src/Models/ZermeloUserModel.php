<?php

namespace api\Models;

/**
 * Zermelo user Model
 */

class ZermeloUser
{
    /**
     * The codes/abbreviations of the user (e.g. GIJS or 545959)
     * @var string|int
     */
    private string|int $code;
    /**
     * The first name of the user (e.g. "Jan")
     * @var string $firstName
     */
    private string $firstName;
    /**
     * The prefix of the user's last name (e.g. "van der")
     * @var string $prefix
     */
    private string $prefix;
    /**
     * The last name of the user (e.g. "Dool")
     * @var string $lastName
     */
    private string $lastName;

    /**
     * Create a new user instance.
     * @param string|int $code
     * @param string $firstName
     * @param string $prefix
     * @param string $lastName
     */
    public function __construct($code, $firstName, $prefix, $lastName)
    {
        $this->code = $code;
        $this->firstName = $firstName;
        $this->prefix = $prefix;
        $this->lastName = $lastName;
    }

    /**
     * return the user details as an array
     * @return array
     */
    public function getUserDetails(): array {
        return [
            'code' => $this->code,
            'firstName' => $this->firstName,
            'prefix' => $this->prefix,
            'lastName' => $this->lastName
        ];
    }
}