<?php

namespace api\Models;
class Appointment
{
    public int $id;
    public string $appointmentInstance;
    public string $start;
    public string $end;
    public array $locations;
    public array $subjects;
    public array $teachers;

    public function __construct(
        int $id,
        string $appointmentInstance,
        string $start,
        string $end,
        array $locations = [],
        array $subjects = [],
        array $teachers = []
    ) {
        $this->id = $id;
        $this->appointmentInstance = $appointmentInstance;
        $this->start = $start;
        $this->end = $end;
        $this->locations = $locations;
        $this->subjects = $subjects;
        $this->teachers = $teachers;
    }

}
