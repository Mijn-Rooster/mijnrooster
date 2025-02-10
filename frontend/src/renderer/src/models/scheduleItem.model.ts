/**
 * Represents a schedule item in the timetable.
 * @interface
 * @property {number} appointmentInstanceId - Unique identifier for the appointment instance
 * @property {number} id - Unique identifier for the schedule item
 * @property {number} start - Start time of the schedule item (timestamp)
 * @property {number} end - End time of the schedule item (timestamp)
 * @property {string[]} locations - Array of location names where the schedule item takes place
 * @property {string[]} subjects - Array of subject codes
 * @property {string[]} subjectsFriendlyNames - Array of human-readable subject names
 * @property {string[]} teachers - Array of teacher identifiers
 * @property {string} lessonNumberStart - Starting lesson number in the schedule
 * @property {string} lessonNumberEnd - Ending lesson number in the schedule
 */
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
