<?php

namespace api\Models;

use api\Models\Appointment;

/**
 * Schedule Model
 * A schedule is a collection of appointments.
 */

class Schedule
{
    /**
     * An array of appointments that are part of this schedule.
     * @var array $appointments
     */
    private array $appointments = [];

    /**
     * Create a new schedule instance.
     * @param array $appointments
     */
    public function __construct(array $appointments = [])
    {
        $this->appointments = $appointments;
    }

    /**
     * Get the appointments of this schedule.
     * @return array $appointments
     */
    public function getAppointments(): array
    {
        return $this->appointments;
    }

    /**
     * Add an appointment to the schedule.
     * @param Appointment $appointment
     * @return void
     */
    public function addAppointment(Appointment $appointment): void
    {
        $this->appointments[] = $appointment->getAppointment();
    }
}