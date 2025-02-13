import { writable } from "svelte/store";
import { getCurrentTime, getCurrentDate } from "../services/time.service";

export const time = writable(getCurrentTime());
export const date = writable(getCurrentDate());

/**
 * Updates the time store with the current time.
 */
function updateTime() {
  time.set(getCurrentTime());
}

/**
 * Updates the date store with the current date.
 */
function updateDate() {
  date.set(getCurrentDate());
}

setInterval(updateTime, 1000); // Update time every second
setInterval(updateDate, 60000); // Update date every minute
