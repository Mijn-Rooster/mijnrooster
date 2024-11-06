<?php

namespace api\Models;

/**
 * Appointment Model
 * An appointment is a single instance of a lesson.
 */

class Appointment
{
    /**
     * The internal id of this version of the appointment
     * @var int $id
     */
    private int $id;
    /**
     * id of the instance this appointment belongs to. All appointment versions referring to the same instance will have the same value for appointmentInstance. If an instance repeats every week every occurrence will have a different appointmentInstance.
     * @var int $appointmentInstance
     */
    private int $appointmentInstance;
    /**
     * UTC Unix time of the start of this appointment. This is the first second this appointment is taking place.
     * @var string $start
     */
    private string $start;
    /**
     * UTC Unix time of the end of this appointment. This is the last second this appointment is taking place.
     * @var string $end
     */
    private string $end;
    /**
     * The names of the locations (classrooms) where this appointment will take place.
     * @var array $locations
     */
    private array $locations;
    /**
     * The (human readable) subject names or abbreviations this appointment is about.
     * @var array $subjects
     */
    private array $subjects;
    /**
     * The codes/abbreviations of the teachers participating in this appointment.
     * @var array $teachers
     */
    private array $teachers;

    /**
     * Create a new appointment instance.
     * @param int $id
     * @param string $appointmentInstance
     * @param string $start
     * @param string $end
     * @param array $locations
     * @param array $subjects
     * @param array $teachers
     */
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

    /**
     * Get the appointment data.
     * @return array
     */
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
