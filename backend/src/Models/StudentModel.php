<?php

namespace api\Models;

/**
 * Student Model
 */
class Student
{
    /**
     * The code of the student (e.g. "545959")
     * @var int $code
     */
    private int $code;
    /**
     * The first name of the student (e.g. "Jan")
     * @var string $firstName
     */
    private string $firstName;
    /**
     * The prefix of the student's last name (e.g. "van der")
     * @var string $prefix
     */
    private string $prefix;
    /**
     * The last name of the student (e.g. "Dool")
     * @var string $lastName
     */
    private string $lastName;
    /**
     * The name of the main group the student is in (e.g. "g6v2")
     * @var string $mainGroupName
     */
    private string $mainGroupName;
    /**
     * The id of the main group the student is in (e.g. 12345)
     * @var int $mainGroup
     */
    private int $mainGroup;
    /**
     * The id of the mentor group the student is in (e.g. 12346)
     * @var int $mentorGroup
     */
    private int $mentorGroup;
    /**
     * The id of the department/branch (e.g. 6v) the student is in (e.g. 12347)
     * @var int $departmentOfBranch
     */
    private int $departmentOfBranch;

    /**
     * Create a new student instance.
     * @param int $code
     * @param string $firstName
     * @param string $prefix
     * @param string $lastName
     * @param string $mainGroupName
     * @param int $mainGroup
     * @param int $mentorGroup
     * @param int $departmentOfBranch
     */
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

    /**
     * Return the student data.
     * @return array
     */
    public function getStudent(): array {
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