<?php

/**
 * Schedule Controller
 * 
 * This file contains the logic for the schedule controller.
 * What does this controller do? It gets the schedule data from the Zermelo API and returns it.
 * 
 */

// Require the necessary files and classes
require_once __DIR__ . '/../Services/Auth.php';
require_once __DIR__ . '/../Services/ZermeloAPI.php';
require_once __DIR__ . '/../Services/ErrorHandler.php';
require_once __DIR__ . '/../Models/AppointmentModel.php';
require_once __DIR__ . '/../Models/ScheduleModel.php';
require_once __DIR__ . '/../Models/ResponseModel.php';

use api\Services\Auth;
use api\Services\ZermeloAPI;
use api\Services\ErrorHandler;
use api\Models\Appointment;
use api\Models\Schedule;
use api\Models\Response;

try {
    // Generate a new instance of the Auth and ZermeloAPI class
    $auth = new Auth();
    $zermeloApi = new ZermeloAPI();

    // Authenticate via bearer token
    $auth->authenticate();

    // Get user data from Zermelo API
    $startDate = isset($_GET['start']) ? $_GET['start'] : strtotime('-1 week monday 00:00:00');
    $endDate = isset($_GET['end']) ? $_GET['end'] : strtotime('sunday 23:59:59');
    $zermeloData = $zermeloApi->getScheduleAppointments($studentId, $startDate, $endDate);

    // Check if the Zermelo API returned an error
    if ($zermeloData['status'] !== 200) {
        ErrorHandler::handleZermeloError($zermeloData);
    }

    // Create schedule model
    $schedule = new Schedule([]);

    // Loop through the appointments and add them to the schedule model
    foreach ($zermeloData['data'] as $appointmentData) {
        $appointment = new Appointment(
            $appointmentData['id'],
            $appointmentData['appointmentInstance'],
            $appointmentData['start'],
            $appointmentData['end'],
            $appointmentData['locations'],
            $appointmentData['subjects'],
            $appointmentData['teachers']
        );
        $schedule->addAppointment($appointment);
    }

    // Create a new response
    $response = new Response($schedule->getAppointments(),);
    $response->send();

} catch (\Exception $e) {
    ErrorHandler::handle("ERROR", $e->getMessage());
}
