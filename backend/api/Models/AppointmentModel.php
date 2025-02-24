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
     * The description/remarks of the appointment.
     * @var string $description
     */
    private string $description;

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
     * The groups participating in this appointment.
     * @var array $groups
     */
    private array $groups;

    /**
     * The starting lesson number for the appointment.
     * @var string $lessonNumberStart
     */
    private string $lessonNumberStart;

    /**
     * The ending lesson number for the appointment.
     * @var string $lessonNumberEnd
     */
    private string $lessonNumberEnd;

    /**
     * The type of the appointment.
     * @var string $type
     */
    private string $type;

    /**
     * Whether the appointment is valid.
     * @var bool $valid
     */
    private bool $valid;

    /**
     * Whether the appointment is cancelled.
     * @var bool $cancelled
     */
    private bool $cancelled;

    /**
     * Whether the teacher of the appointment has changed.
     * @var bool $teacherChanged
     */
    private bool $teacherChanged;

    /**
     * Whether the group of the appointment has changed.
     * @var bool $groupChanged
     */
    private bool $groupChanged;

    /**
     * Whether the location of the appointment has changed.
     * @var bool $locationChanged
     */
    private bool $locationChanged;

    /**
     * Whether the time of the appointment has changed.
     * @var bool $timeChanged
     */
    private bool $timeChanged;

    /**
     * The description of the change.
     * @var string $changeDescription
     */
    private string $changeDescription;

    /**
     * Create a new appointment instance.
     * @param int $id
     * @param string $appointmentInstance
     * @param string $start
     * @param string $end
     * @param string $description
     * @param string $lessonNumberStart
     * @param string $lessonNumberEnd
     * @param array $locations
     * @param array $subjects
     * @param array $teachers
     * @param array $groups
     * @param string $type
     * @param bool $valid
     * @param bool $cancelled
     * @param bool $teacherChanged
     * @param bool $groupChanged
     * @param bool $locationChanged
     * @param bool $timeChanged
     * @param string $changeDescription
     */
    public function __construct(
        int $id,
        string $appointmentInstance,
        string $start,
        string $end,
        string $lessonNumberStart,
        string $lessonNumberEnd,
        string $description,
        array $locations,
        array $subjects,
        array $teachers,
        array $groups,
        string $type,
        bool $valid,
        bool $cancelled,
        bool $teacherChanged,
        bool $groupChanged,
        bool $locationChanged,
        bool $timeChanged,
        string $changeDescription
    ) {
        $this->id = $id;
        $this->appointmentInstance = $appointmentInstance;
        $this->start = $start;
        $this->end = $end;
        $this->description = $description;
        $this->locations = $locations;
        $this->subjects = $subjects;
        $this->teachers = $teachers;
        $this->groups = $groups;
        $this->lessonNumberStart = $lessonNumberStart;
        $this->lessonNumberEnd = $lessonNumberEnd;
        $this->type = $type;
        $this->valid = $valid;
        $this->cancelled = $cancelled;
        $this->teacherChanged = $teacherChanged;
        $this->groupChanged = $groupChanged;
        $this->locationChanged = $locationChanged;
        $this->timeChanged = $timeChanged;
        $this->changeDescription = $changeDescription;
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
            'description' => $this->description,
            'locations' => $this->locations,
            'subjects' => $this->subjects,
            'groups' => $this->groups,
            'subjectsFriendlyNames' => $this->subjectsToFriendlyNames($this->subjects),
            'teachers' => $this->teachers,
            'lessonNumberStart' => $this->lessonNumberStart,
            'lessonNumberEnd' => $this->lessonNumberEnd,
            'type' => $this->type,
            'valid' => $this->valid,
            'changes' => [
                'cancelled' => $this->cancelled,
                'teacherChanged' => $this->teacherChanged,
                'groupChanged' => $this->groupChanged,
                'locationChanged' => $this->locationChanged,
                'timeChanged' => $this->timeChanged,
                'changeDescription' => $this->changeDescription
            ]
        ];
    }

    /**
     * Converts an array of subjects to their friendly names.
     *
     * @param array $subjects Array of subject codes/identifiers to convert
     * @return array Array containing the friendly names of the subjects
     */
    private function subjectsToFriendlyNames(array $subjects): array
    {
        $subjectsFriendlyNames = [];
        foreach ($subjects as $subject) {
            $subjectsFriendlyNames[] = $this->subjectFriendlyName($subject);
        }
        return $subjectsFriendlyNames;
    }

    /**
     * Converts abbreviated subject codes to their full, friendly names.
     * 
     * Takes an abbreviated subject code (like 'ak', 'en', 'wi') and returns
     * its full Dutch name (like 'Aardrijkskunde', 'Engels', 'Wiskunde').
     * If the subject code is not found in the mapping, returns the original
     * subject with its first letter capitalized.
     *
     * @param string $subject The abbreviated subject code to convert
     * @return string The full, friendly name of the subject
     */
    private function subjectFriendlyName(string $subject): string {
        $map = [
            'ak' => 'Aardrijkskunde',
            'beco' => 'Bedrijfseconomie',
            'bi' => 'Biologie',
            'biol' => 'Biologie',
            'bio' => 'Biologie',
            'bsm' => 'Bewegen, Sport en Maatschappij',
            'bv' => 'Beeldende Vorming',
            'bwi' => 'Bouwen, Wonen en Interieur',
            'ckv' => 'Culturele en Kunstzinnige Vorming',
            'cm' => 'Cultuur en Maatschappij',
            'die' => 'Diëtetiek',
            'du' => 'Duits',
            'dutl' => 'Duitse taal en literatuur',
            'ec' => 'Economie',
            'eco' => 'Economie',
            'econ' => 'Economie',
            'em' => 'Economie en Maatschappij',
            'en' => 'Engels',
            'entl' => 'Engelse taal en literatuur',
            'eo' => 'Economie en Ondernemen',
            'fa' => 'Frans',
            'fatl' => 'Franse taal en literatuur',
            'fi' => 'Filosofie',
            'fil' => 'Filosofie',
            'filo' => 'Filosofie',
            'gd' => 'Godsdienst',
            'gds' => 'Godsdienst',
            'ges' => 'Geschiedenis',
            'gs' => 'Geschiedenis',
            'gr' => 'Grieks',
            'grtl' => 'Griekse taal en literatuur',
            'in' => 'Informatica',
            'kc' => 'Kunst en Cultuur',
            'kcv' => 'Kunst en Culturele Vorming',
            'la' => 'Latijn',
            'latl' => 'Latijnse taal en literatuur',
            'lo' => 'Lichamelijke Opvoeding',
            'lob' => 'Loopbaanoriëntatie',
            'ma' => 'Maatschappijleer',
            'maat' => 'Maatschappijleer',
            'maw' => 'Maatschappijwetenschappen',
            'mu' => 'Muziek',
            'na' => 'Natuurkunde',
            'nat' => 'Natuurkunde',
            'nlt' => 'Natuur, Leven en Technologie',
            'ne' => 'Nederlands',
            'netl' => 'Nederlandse taal en literatuur',
            'nask' => 'Natuur- en Scheikunde',
            'nsk' => 'Natuur- en Scheikunde',
            'rek' => 'Rekenen',
            'sk' => 'Scheikunde',
            'schk' => 'Scheikunde',
            'wi' => 'Wiskunde',
            'wisa' => 'Wiskunde A',
            'wisb' => 'Wiskunde B',
            'wisc' => 'Wiskunde C',
            'wisd' => 'Wiskunde D',
            'zw' => 'Zorg en Welzijn',

        ];
    
        $subject = strtolower($subject);
        return $map[$subject] ?? ucfirst($subject);
    }

}
