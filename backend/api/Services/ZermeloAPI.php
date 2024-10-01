<?php

namespace api\Services;

class ZermeloAPI {

    public function getRoosterData($user, $start, $end, $type = 'lesson,exam,oralExam,activity,talk,mixed,meeting,interlude', $fields = 'id,appointmentInstance,start,end,startTimeSlotName,endTimeSlotName,locations,teachers,subjects') {
        // Check if all required parameters are set
        if (!isset($user, $start, $end)) {
            throw new \Exception("Missing required parameters");
        }

        // Create query parameters
        $params = http_build_query([
            'valid' => true,
            'cancelled' => false,
            'user' => $user,
            'start' => $start,
            'end' => $end,
            'type' => $type,
            'fields' => $fields
        ]);
        
        $ch = curl_init(ZERMELO_PORTAL_URL . '/api/v3/appointments?' . $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . ZERMELO_API_TOKEN
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            return json_decode($response, true);
        } else {
            throw new \Exception("Zermelo API error, status code: " . $httpCode);
        }
    }

    public function getUserData($studentId, $schoolInSchoolYear, $fields = 'student,firstName,prefix,lastName,mainGroupName,mainGroup,mentorGroup,departmentOfBranch') {
        // Check if all required parameters are set
        if (!isset($studentId, $schoolInSchoolYear)) {
            throw new \Exception("Missing required parameters");
        }

        // Create query parameters
        $params = http_build_query([
            'schoolInSchoolYear' => $schoolInSchoolYear,
            'fields' => $fields,
            'student' => $studentId
        ]);

        $ch = curl_init(ZERMELO_PORTAL_URL . '/api/v3/studentsindepartments?' . $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . ZERMELO_API_TOKEN
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            return json_decode($response, true);
        } else {
            throw new \Exception("Zermelo API error, status code: " . $httpCode);
        }
    }
}
