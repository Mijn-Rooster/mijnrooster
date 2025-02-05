export interface ScheduleItemModel {
    appointmentInstanceId: number;
    id: number;
    start: number;
    end: number;
    locations: string[];
    subjects: string[];
    subjectsFriendlyNames: string[];
    teachers: string[];
    lessonNumberStart: string;
    lessonNumberEnd: string;
}