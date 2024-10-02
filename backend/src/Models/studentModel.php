<?php

class Student
{
    /**
     * Class StudentModel
     * 
     * Represents a student with various attributes.
     * 
     * @property int $student Unique identifier for the student.
     * @property string $firstName First name of the student.
     * @property string $prefix Prefix of the student's last name.
     * @property string $lastName Last name of the student.
     * @property string $mainGroupName Name of the students class.
     */

    public $student;
    public $firstName;
    public $prefix;
    public $lastName;
    public $mainGroupName;

    public function __construct($data)
    {
        $this->student = $data['student'];
        $this->firstName = $data['firstName'];
        $this->prefix = $data['prefix'];
        $this->lastName = $data['lastName'];
        $this->mainGroupName = $data['mainGroupName'];
    }
}
