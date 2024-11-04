<?php

namespace api\Models;

class Teacher
{
    private $code;
    private $firstName;
    private $prefix;
    private $lastName;

    public function __construct($code, $firstName, $prefix, $lastName)
    {
        $this->code = $code;
        $this->firstName = $firstName;
        $this->prefix = $prefix;
        $this->lastName = $lastName;
    }

    public function getTeacher() {
        return [
            'code' => $this->code,
            'firstName' => $this->firstName,
            'prefix' => $this->prefix,
            'lastName' => $this->lastName
        ];
    }
}