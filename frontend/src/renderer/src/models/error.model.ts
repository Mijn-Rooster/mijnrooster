/**
 * Represents an error model with a message and optional details.
 * @interface ErrorModel
 * @property {string} message - The main error message
 * @property {string|null} details - Additional error details, if available
 */
export interface ErrorModel {
  message: string;
  details?: string | null;
}
