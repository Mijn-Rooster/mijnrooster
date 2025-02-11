<?php

/**
 * Schedule Controller
 * 
 * This file contains the logic for the schedule endpoint.
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

    // Get schedule appointments from Zermelo API. Default to this week.
    $startDate = isset($_GET['start']) && !empty($_GET['start']) ? $_GET['start'] : strtotime('this week monday 00:00:00');
    $endDate = isset($_GET['end']) && !empty($_GET['end']) ? $_GET['end'] : strtotime('this week sunday 23:59:59');
    $zermeloData = $zermeloApi->getScheduleAppointments($studentId, $startDate, $endDate);

    // Check if the Zermelo API returned an error
    if ($zermeloData['status'] !== 200) {
        ErrorHandler::handleZermeloError($zermeloData);
    }

    // Check if the Zermelo API returned no data
    if (count($zermeloData['data']) === 0) {
        $response = new Response([]);
        $response->send();
    }

    //print_r($zermeloData);
    
    // Create new schedule
    $schedule = new Schedule();

    // Loop through the appointments and add them to the schedule model
    foreach ($zermeloData['data'] as $appointmentData) {
        $appointment = new Appointment(
            $appointmentData['id'],
            $appointmentData['appointmentInstance'],
            $appointmentData['start'],
            $appointmentData['end'],
            $appointmentData['startTimeSlotName'],
            $appointmentData['endTimeSlotName'],
            $appointmentData['locations'],
            $appointmentData['subjects'],
            $appointmentData['teachers'],
            $appointmentData['groups'],
            $appointmentData['type'],
            $appointmentData['valid'],
            $appointmentData['cancelled'],
            $appointmentData['teacherChanged'],
            $appointmentData['groupChanged'],
            $appointmentData['locationChanged'],
            $appointmentData['timeChanged'],
            $appointmentData['changeDescription']
        );
        $schedule->addAppointment($appointment);
    }

    // Create response
    $response = new Response($schedule->getAppointments(),);
    $response->send();
} catch (\Exception $e) {
    ErrorHandler::handle("ERROR", $e->getMessage());
}
