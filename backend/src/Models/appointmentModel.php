<?php

class Appointment
{
    /**
     * Class AppointmentModel
     * 
     * Represents an appointment with various attributes.
     * 
     * @property int $id Unique identifier for the appointment.
     * @property int $appointmentInstance Instance Id of the appointment.
     * @property string $type Type of the appointment (lesson/exam/activity/choice/talk/other/interlude/meeting/unknown).
     * 
     * @property string $start Start time of the appointment (in UNIX timestamp).
     * @property string $end End time of the appointment (in UNIX timestamp).
     * @property string $startTimeSlot Start time slot of the appointment.
     * @property string $endTimeSlot End time slot of the appointment.
     * 
     * @property array $groups Groups associated with the appointment.
     * @property array $locations Locations where the appointment takes place.
     * @property array $subjects Subjects related to the appointment.
     * @property array $teachers Teachers involved in the appointment.
     * 
     * @property bool $cancelled Indicates if the appointment is cancelled.
     * @property bool $modified Indicates if the appointment has been modified.
     * @property bool $moved Indicates if the appointment has been moved.
     * @property bool $hidden Indicates if the appointment is hidden.
     * @property string $changeDescription Description of the change made to the appointment.
     */

    public $id;
    public $appointmentInstance;
    public $type;

    public $start;
    public $end;
    public $startTimeSlot;
    public $endTimeSlot;

    public $groups;
    public $locations;
    public $subjects;
    public $teachers;

    public $cancelled;
    public $modified;
    public $moved;
    public $hidden;
    public $changeDescription;

    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->appointmentInstance = $data['appointmentInstance'];
        $this->type = $data['type'];

        $this->start = $data['start'];
        $this->end = $data['end'];
        $this->startTimeSlot = $data['startTimeSlot'];
        $this->endTimeSlot = $data['endTimeSlot'];

        $this->groups = $data['groups'];
        $this->locations = $data['locations'];
        $this->subjects = $data['subjects'];
        $this->teachers = $data['teachers'];

        $this->cancelled = $data['cancelled'];
        $this->modified = $data['modified'];
        $this->moved = $data['moved'];
        $this->hidden = $data['hidden'];
        $this->changeDescription = $data['changeDescription'];
    }
}
