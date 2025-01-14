<?php

namespace api\Services;

// Require the necessary services
use api\Services\ErrorHandler;

/**
 * Zermelo API Service
 * This class is responsible for handling all requests to the Zermelo API.
 */

class ZermeloAPI {

    /**
     * Get schedule data from Zermelo API
     * @param string $user Id of the user to get the schedule for (e.g. 545959 or "GIJS")
     * @param int $start Start date of the schedule in UNIX timestamp
     * @param int $end End date of the schedule in UNIX timestamp
     * @param string $type Type of appointments to get. Check Zermelo API documentation for possible values
     * @param string $fields Fields to get from the appointments. Check Zermelo API documentation for possible values
     * @return array Lessons from schedule
     */
    public function getScheduleAppointments($user, $start, $end, $type = 'lesson,exam,oralExam,activity,talk,mixed,meeting,interlude', $fields = 'id,appointmentInstance,start,end,startTimeSlotName,endTimeSlotName,locations,teachers,subjects'): array {
        // Check if all required parameters are set
        if (!isset($user, $start, $end)) {
            ErrorHandler::handle("MISSING_PARAMETERS");
        }

        // Filter out invalid characters
        $start = (int)$start;
        $end = (int)$end;

        // Check if the start and end date are valid
        // If the start date is in the future, it is invalid
        // The maximum difference between the start and end date is 62 days (5356800 seconds)
        if ($start > $end || (($end - $start) > 5356800)) {
            ErrorHandler::handle("SCHEDULE_INVALID_DATE");
        }

        // Create query parameters
        $params = http_build_query([
            'valid' => "true",
            'cancelled' => "false",
            'user' => $user,
            'start' => $start,
            'end' => $end,
            'type' => $type,
            'fields' => $fields
        ]);

        $ch = curl_init(ZERMELO_PORTAL_URL . '/api/v3/appointments?' . $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . ZERMELO_API_TOKEN
        ]);

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response, true)['response'];
    }

    /**
     * Get user data from Zermelo API
     * @param string $studentId Id of the student to get the data for (e.g.545959)
     * @param string $schoolInSchoolYear Id of the school in the school year
     * @param string $fields Fields to get from the user data. Check Zermelo API documentation for possible values
     * @return array User data
     */
    public function getStudentDetails($studentId, $schoolInSchoolYear, $fields = 'student,firstName,prefix,lastName,mainGroupName,mainGroup,mentorGroup,departmentOfBranch') {
        // Filter out invalid characters
        $schoolInSchoolYear = (int)$schoolInSchoolYear;

        // Create query parameters
        $params = http_build_query([
            'schoolInSchoolYear' => $schoolInSchoolYear,
            'fields' => $fields,
            'student' => $studentId
        ]);

        $ch = curl_init(ZERMELO_PORTAL_URL . '/api/v3/studentsindepartments?' . $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . ZERMELO_API_TOKEN
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true)['response'];
    }

    /**
     * Get teacher data from Zermelo API
     * @param string $teacherId Id of the teacher to get the data for (e.g. "GIJS")
     * @param int $schoolInSchoolYear Id of the school in the school year
     * @param string $fields Fields to get from the teacher data. Check Zermelo API documentation for possible values
     * @return array Teacher data
     */
    public function getTeacherDetails($teacherId, $schoolInSchoolYear, $fields = 'employee,firstName,prefix,lastName'): array {
        // Filter out invalid characters
        $schoolInSchoolYear = (int)$schoolInSchoolYear;

        // Create query parameters
        $params = http_build_query([
            'schoolInSchoolYear' => $schoolInSchoolYear,
            'fields' => $fields,
            'employee' => $teacherId
        ]);

        $ch = curl_init(ZERMELO_PORTAL_URL . '/api/v3/contracts?' . $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . ZERMELO_API_TOKEN
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true)['response'];
    }

    /**
     * Retrieves the list of schools for a given school year.
     *
     * @param int $schoolyear The school year to retrieve schools for. Must be a 4-digit year.
     * @return array The response containing the list of schools.
     */
    public function getSchoolsInSchoolYear($schoolyear) {
        // Filter out invalid characters
        $schoolyear = (int)$schoolyear;

        // Check if all required parameters are set
        if (strlen($schoolyear) !== 4) {
            ErrorHandler::handle("SCHOOLYEAR_INVALID");
        }

        // Create query parameters
        $params = http_build_query([
            'schoolYear' => $schoolyear
        ]);

        $ch = curl_init(ZERMELO_PORTAL_URL . '/api/v3/schoolsinschoolyears?' . $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . ZERMELO_API_TOKEN
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true)['response'];
    }

    /**
     * Retrieves information about a school in a specific school year from the Zermelo API.
     *
     * @param int $schoolInSchoolYearId The ID of the school in the school year.
     * @param string $schoolYear The school year in the format YYYY.
     * 
     * @return array The response from the Zermelo API as an associative array.
     */
    public function getSchoolInSchoolYear($schoolInSchoolYearId, $schoolYear) {
        // Filter out invalid characters
        $schoolInSchoolYearId = (int)$schoolInSchoolYearId;

        // Check if all required parameters are set
        if (strlen($schoolYear) !== 4) {
            ErrorHandler::handle("SCHOOLYEAR_INVALID");
        }

        // Create query parameters
        $params = http_build_query([
            'schoolYear' => $schoolYear
        ]);

        $ch = curl_init(ZERMELO_PORTAL_URL . '/api/v3/schoolsinschoolyears/'. $schoolInSchoolYearId . '?' . $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . ZERMELO_API_TOKEN
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true)['response'];
    }
}