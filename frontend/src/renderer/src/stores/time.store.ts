import { writable } from "svelte/store";
import { getCurrentTime, getCurrentDate } from "../services/time.service";

export const time = writable(getCurrentTime());
export const date = writable(getCurrentDate());

function updateTime() {
  time.set(getCurrentTime());
}

function updateDate() {
  date.set(getCurrentDate());
}

setInterval(updateTime, 1000); // Update time every second
setInterval(updateDate, 60000); // Update date every minute
