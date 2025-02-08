/**
 * Represents a school entity with its associated metadata.
 * @interface SchoolModel
 * @property {number} schoolInSchoolYearId - Unique identifier for the school in a specific school year
 * @property {number} schoolId - Unique identifier for the school
 * @property {number} schoolYear - The academic year
 * @property {string} projectName - Name of the project associated with the school
 * @property {string} schoolName - Name of the school
 */
export interface SchoolModel {
    schoolInSchoolYearId: number;
    schoolId: number;
    schoolYear: number;
    projectName: string;
    schoolName: string;
}