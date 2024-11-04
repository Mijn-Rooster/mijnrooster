<?php

namespace api\Models;

use api\Models\Appointment;

class Schedule
{
    private array $appointments = [];

    public function __construct(array $appointments = [])
    {
        $this->appointments = $appointments;
    }

    public function getAppointments()
    {
        // Return the appointments in json format
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): void
    {
        $this->appointments[] = $appointment->getAppointment();
    }

    public function countAppointments(): int
    {
        return count($this->appointments);
    }
}