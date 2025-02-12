import { writable } from "svelte/store";
import { getCurrentTime, getCurrentDate } from "../services/time.service";

interface TimeStore {
  time: string;
  date: string;
}

function createTimeStore() {
  const { subscribe, set } = writable<TimeStore>({
    time: getCurrentTime(),
    date: getCurrentDate(),
  });

  // Start the interval when the store is created
  const interval = setInterval(() => {
    set({
      time: getCurrentTime(),
      date: getCurrentDate(),
    });
  }, 1000);

  // Clean up on app exit
  window.addEventListener("beforeunload", () => clearInterval(interval));

  return {
    subscribe,
  };
}

export const timeStore = createTimeStore();
