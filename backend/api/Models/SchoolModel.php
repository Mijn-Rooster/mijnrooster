<?php

namespace api\Models;

/**
 * School Model
 */

class School {
    /**
     * @var int $schoolInSchoolYearId The identifier for the school in the school year.
     */
    private int $schoolInSchoolYearId;
    /**
     * @var int $schoolId The unique identifier for the school.
     */
    private int $schoolId;
    /**
     * @var int $schoolYear The current school year. (e.g 2021)
     */
    private int $schoolYear;
    /**
     * @var string $projectName The name of the project. (e.g. "Voorbeeldschool 2025-2026")
     */
    private string $projectName;
    /**
     * @var string $schoolName The name of the school. (e.g. "Voorbeeldschool")
     */
    private string $schoolName;

    public function __construct($schoolInSchoolYearId, $schoolId, $schoolYear, $projectName, $schoolName) {
        $this->schoolInSchoolYearId = $schoolInSchoolYearId;
        $this->schoolId = $schoolId;
        $this->schoolYear = $schoolYear;
        $this->projectName = $projectName;
        $this->schoolName = $schoolName;
    }

    public function getSchoolDetails() {
        return [
            'schoolInSchoolYearId' => $this->schoolInSchoolYearId,
            'schoolId' => $this->schoolId,
            'schoolYear' => $this->schoolYear,
            'projectName' => $this->projectName,
            'schoolName' => $this->schoolName
        ];
    }
}
