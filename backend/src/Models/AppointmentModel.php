<?php

namespace api\Models;
class Appointment
{
    private int $id;
    private string $appointmentInstance;
    private string $start;
    private string $end;
    private array $locations;
    private array $subjects;
    private array $teachers;

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

    public function getAppointment(): array
    {
        return [
            'id' => $this->id,
            'appointmentInstance' => $this->appointmentInstance,
            'start' => $this->start,
            'end' => $this->end,
            'locations' => $this->locations,
            'subjects' => $this->subjects,
            'teachers' => $this->teachers
        ];
    }

}
