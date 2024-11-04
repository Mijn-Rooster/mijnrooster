<?php

namespace api\Models;

class Student
{
    private $code;
    private $firstName;
    private $prefix;
    private $lastName;
    private $mainGroupName;
    private $mainGroup;
    private $mentorGroup;
    private $departmentOfBranch;

    public function __construct($code, $firstName, $prefix, $lastName, $mainGroupName, $mainGroup, $mentorGroup, $departmentOfBranch)
    {
        $this->code = $code;
        $this->firstName = $firstName;
        $this->prefix = $prefix;
        $this->lastName = $lastName;
        $this->mainGroupName = $mainGroupName;
        $this->mainGroup = $mainGroup;
        $this->mentorGroup = $mentorGroup;
        $this->departmentOfBranch = $departmentOfBranch;
    }

    public function getStudent() {
        return [
            'code' => $this->code,
            'firstName' => $this->firstName,
            'prefix' => $this->prefix,
            'lastName' => $this->lastName,
            'mainGroupName' => $this->mainGroupName,
            'mainGroup' => $this->mainGroup,
            'mentorGroup' => $this->mentorGroup,
            'departmentOfBranch' => $this->departmentOfBranch
        ];
    }
}