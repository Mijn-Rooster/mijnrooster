/**
 * Represents a schedule item in the timetable.
 * @interface
 * @property {number} appointmentInstanceId - The ID of the appointment instance.
 * @property {number} id - The ID of the schedule item.
 * @property {number} start - The start time of the schedule item.
 * @property {number} end - The end time of the schedule item.
 * @property {string} description - The description of the schedule item.
 * @property {string[]} locations - The locations where the schedule item takes place.
 * @property {string[]} subjects - The subjects of the schedule item.
 * @property {string[]} subjectsFriendlyNames - The friendly names of the subjects.
 * @property {string[]} teachers - The teachers of the schedule item.
 * @property {string[]} groups - The groups of the schedule item.
 * @property {string} lessonNumberStart - The start lesson number of the schedule item.
 * @property {string} lessonNumberEnd - The end lesson number of the schedule item.
 * @property {string} type - The type of the schedule item.
 * @property {boolean} valid - Whether the schedule item is valid.
 * @property {object} changes - The changes made to the schedule item.
 * @property {boolean} changes.cancelled - Whether the schedule item is cancelled.
 * @property {boolean} changes.teacherChanged - Whether the teacher of the schedule item has changed.
 * @property {boolean} changes.locationChanged - Whether the location of the schedule item has changed.
 * @property {boolean} changes.timeChanged - Whether the time of the schedule item has changed.
 * @property {boolean} changes.groupChanged - Whether the group of the schedule item has changed.
 * @property {string} changes.changeDescription - The description of the changes made to the schedule item.
 */
export interface ScheduleItemModel {
  appointmentInstanceId: number;
  id: number;
  start: number;
  end: number;
  description: string;
  locations: string[];
  subjects: string[];
  subjectsFriendlyNames: string[];
  teachers: string[];
  groups: string[];
  lessonNumberStart: string;
  lessonNumberEnd: string;
  type: string;
  valid: boolean;
  changes: {
    cancelled: boolean;
    teacherChanged: boolean;
    locationChanged: boolean;
    timeChanged: boolean;
    groupChanged: boolean;
    changeDescription: string;
  };
}
