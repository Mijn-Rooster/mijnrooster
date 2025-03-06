/**
 * Service module for handling time-related functionality.
 *
 * @module TimeService
 *
 * @remarks
 * This module provides functionality for retrieving the current time and date.
 *
 * @example
 * ```typescript
 * // Get the current time
 * const time = getCurrentTime();
 *
 * // Get the current date
 * const date = getCurrentDate();
 *
 * console.log(`The current time is ${time} on ${date}.`);
 * ```
 *
 * @throws {Error} If the time or date retrieval fails
 *
 * */

/**
 * Returns the current time as a string in the format HH:MM.
 *
 * @returns {string} The current time formatted as HH:MM.
 */
export function getCurrentTime(): string {
  const now = new Date();
  const hours = String(now.getHours()).padStart(2, "0");
  const minutes = String(now.getMinutes()).padStart(2, "0");
  return `${hours}:${minutes}`;
}

/**
 * Returns the current date formatted as "day month year".
 *
 * The day is padded to two digits and the month is formatted in Dutch.
 *
 * @returns {string} The current date in the format "dd month yyyy".
 */
export function getCurrentDate(): string {
  const now = new Date();
  const year = String(now.getFullYear());
  const monthName = new Intl.DateTimeFormat("nl", { month: "long" }).format(
    now,
  );
  const day = String(now.getDate()).padStart(2, "0");
  return `${day} ${monthName} ${year}`;
}

/**
 * Converts a UNIX timestamp to a time string in the format "HH:MM"
 * @param {number} UNIX_timestamp - The UNIX timestamp to convert (in seconds)
 * @returns {string} The formatted time string
 * @example
 * // returns "14:30"
 * timeConverter(1622548200)
 */
export function timeConverter(UNIX_timestamp: number) {
  const a = new Date(UNIX_timestamp * 1000);
  const hour = String(a.getHours()).padStart(2, "0");
  const min = String(a.getMinutes()).padStart(2, "0");
  return `${hour}:${min}`;
}
