<?php

namespace api\Models;

/**
 * Teacher Model
 */

class Teacher
{
    /**
     * The codes/abbreviations of the teacher (e.g. "GIJS")
     * @var string|int
     */
    private string|int $code;
    /**
     * The first name of the teacher (e.g. "Jan")
     * @var string $firstName
     */
    private string $firstName;
    /**
     * The prefix of the teacher's last name (e.g. "van der")
     * @var string $prefix
     */
    private string $prefix;
    /**
     * The last name of the teacher (e.g. "Dool")
     * @var string $lastName
     */
    private string $lastName;

    /**
     * Create a new teacher instance.
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
     * return the teacher as an array
     * @return array
     */
    public function getTeacher(): array {
        return [
            'code' => $this->code,
            'firstName' => $this->firstName,
            'prefix' => $this->prefix,
            'lastName' => $this->lastName
        ];
    }
}