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
     * The subject full/friendly names this appointment is about.
     * @var array $subjectsFriendlyNames
     */
    private array $subjectsFriendlyNames;
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
     * Create a new appointment instance.
     * @param int $id
     * @param string $appointmentInstance
     * @param string $start
     * @param string $end
     * @param array $locations
     * @param array $subjects
     * @param array $teachers
     * @param string $lessonNumberStart
     * @param string $lessonNumberEnd
     */
    public function __construct(
        int $id,
        string $appointmentInstance,
        string $start,
        string $end,
        string $lessonNumberStart,
        string $lessonNumberEnd,
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
        $this->lessonNumberStart = $lessonNumberStart;
        $this->lessonNumberEnd = $lessonNumberEnd;
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
            'subjectsFriendlyNames' => $this->subjectsToFriendlyNames($this->subjects),
            'teachers' => $this->teachers,
            'lessonNumberStart' => $this->lessonNumberStart,
            'lessonNumberEnd' => $this->lessonNumberEnd
        ];
    }

    private function subjectsToFriendlyNames(array $subjects): array
    {
        $subjectsFriendlyNames = [];
        foreach ($subjects as $subject) {
            $subjectsFriendlyNames[] = $this->subjectFriendlyName($subject);
        }
        return $subjectsFriendlyNames;
    }

    private function subjectFriendlyName(string $subject): string {
        $map = [
        // Wiskunde variants
        'wi'    => 'Wiskunde',
        'wis'   => 'Wiskunde',
        'wisa'  => 'Wiskunde A',
        'wisb'  => 'Wiskunde B',
        'wisc'  => 'Wiskunde C',
        'wisd'  => 'Wiskunde D',
        // Scheikunde
        'sk'    => 'Scheikunde',
        'schk'  => 'Scheikunde',
        // Biologie
        'bi'    => 'Biologie',
        'bio'   => 'Biologie',
        // Informatica
        'in'    => 'Informatica',
        // Natuur- en Scheikunde
        'nask'  => 'Natuur- en Scheikunde',
        // Aardrijkskunde (Roepnaam: aardrijkskunde, Afk.: ak)
        'ak'    => 'aardrijkskunde',
        // Algemene natuurwetenschappen
        'anw'   => 'algemene natuurwetenschappen',
        // Arabische taal
        'ar'    => 'Arabische taal en letterkunde',
        'ar1'   => 'Arabische taal 1',
        'ar12'  => 'Arabische taal 1,2',
        'ar-e'  => 'Arabische taal (elementair)',
        // Biologie extra
        'bi1'   => 'biologie 1',
        'bi12'  => 'biologie 1,2',
        // Culturele en kunstzinnige vorming
        'ckv1'  => 'culturele en kunstzinnige vorming 1',
        'ckv2'  => 'culturele en kunstzinnige vorming 2',
        'bv3'   => 'culturele en kunstzinnige vorming 3 (beeldende vormgeving)',
        'mu3'   => 'culturele en kunstzinnige vorming 3 (muziek)',
        'dr3'   => 'culturele en kunstzinnige vorming 3 (drama)',
        'da3'   => 'culturele en kunstzinnige vorming 3 (dans)',
        // Duitse taal
        'du'    => 'Duitse taal en letterkunde',
        'du1'   => 'Duitse taal 1',
        'du12'  => 'Duitse taal 1,2',
        // Economie en Handel
        'ee'    => 'economische wetenschappen I en recht',
        'et'    => 'economische wetenschappen II en recht',
        'ec'    => 'economie',
        'hr'    => 'handelswetenschappen en recht',
        'ec1'   => 'economie 1',
        'ec12'  => 'economie 1,2',
        // Engelse taal
        'en'    => 'Engelse taal en letterkunde',
        // Filosofie
        'fi'    => 'filosofie',
        // Franse taal
        'fa'    => 'Franse taal en letterkunde',
        'fa1'   => 'Franse taal 1',
        'fa12'  => 'Franse taal 1,2',
        // Friese taal
        'fr'    => 'Friese taal en letterkunde',
        'fr1'   => 'Friese taal 1',
        'fr12'  => 'Friese taal 1,2',
        // Geschiedenis
        'gst'   => 'geschiedenis en staatsinrichting',
        'gs'    => 'geschiedenis',
        'gs1'   => 'geschiedenis 1',
        'gm'    => 'geschiedenis en maatschappijleer',
        // Griekse taal
        'gr'    => 'Griekse taal en letterkunde',
        // Handvaardigheid
        'ha'    => 'handvaardigheid I (handenarbeid)',
        'tw'    => 'handvaardigheid II (textiele werkvormen)',
        // Italiaanse taal
        'it'    => 'Italiaanse taal',
        'it1'   => 'Italiaanse taal 1',
        'it12'  => 'Italiaanse taal 1,2',
        'it-e'  => 'Italiaanse taal (elementair)',
        // Klassieke culturele vorming
        'kcv'   => 'klassieke culturele vorming',
        // Latijn
        'la'    => 'Latijnse taal en letterkunde',
        // Letterkunde
        'lett'  => 'letterkunde',
        // Lichamelijke opvoeding
        'lo'    => 'lichamelijke opvoeding',
        'lo1'   => 'lichamelijke opvoeding 1',
        'lo2'   => 'lichamelijke opvoeding 2',
        // Maatschappijleer
        'ma'    => 'maatschappijleer',
        'ma1'   => 'maatschappijleer 1',
        // Muziek
        'mu'    => 'muziek (tot tenminste 2002)',
        // Natuurkunde
        'na'    => 'natuurkunde',
        'na1'   => 'natuurkunde 1',
        'na12'  => 'natuurkunde 1,2',
        // Nederlandse taal
        'ne'    => 'Nederlandse taal en letterkunde',
        // Russische taal
        'ru'    => 'Russische taal en letterkunde',
        'ru1'   => 'Russische taal 1',
        'ru12'  => 'Russische taal 1,2',
        'ru-e'  => 'Russische taal (elementair)',
        // Scheikunde extra
        'sk1'   => 'scheikunde 1',
        'sk12'  => 'scheikunde 1,2',
        // Spaanse taal
        'sp'    => 'Spaanse taal en letterkunde',
        'sp1'   => 'Spaanse taal 1',
        'sp12'  => 'Spaanse taal 1,2',
        'sp-e'  => 'Spaanse taal (elementair)',
        // Tekenen
        'te'    => 'tekenen',
        // Turkse taal
        'tu'    => 'Turkse taal en letterkunde',
        'tu1'   => 'Turkse taal 1',
        'tu12'  => 'Turkse taal 1,2',
        'tu-e'  => 'Turkse taal (elementair)',
        ];
    
        $subject = strtolower($subject);
        return $map[$subject] ?? ucfirst($subject);
    }

}
