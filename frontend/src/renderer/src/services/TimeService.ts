
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