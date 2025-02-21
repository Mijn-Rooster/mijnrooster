/**
 * Represents a user in the system with their personal information.
 * @interface UserModel
 * @property {string} code - The unique identifier code for the user
 * @property {string} firstName - The user's first name
 * @property {string} prefix - The user's prefix (like van der, de, etc.)
 * @property {string} lastName - The user's last name
 */
export interface UserModel {
  code: string;
  firstName: string;
  prefix: string;
  lastName: string;
}
