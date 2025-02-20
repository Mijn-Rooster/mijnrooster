import { writable } from "svelte/store";
import { isConnectedToInternet, isConnectedToServer } from "../services/core.service";

/**
 * A writable store that holds the current internet connection status.
 */
export const internetStatus = writable(true);
export const serverStatus = writable(true);

internetStatus.subscribe(value => {
  console.log("Internet status changed to", value);
});
serverStatus.subscribe(value => {
  console.log("Server status changed to", value);
});

/**
 * Updates the internet connection status.
 */
async function updateInternetStatus() {
  internetStatus.set(await isConnectedToInternet());
}

/**
 * Updates the server connection status.
 */
async function updateServerStatus() {
  serverStatus.set(await isConnectedToServer());
}

// Update internet status every 10 seconds
setInterval(updateInternetStatus, 10000);
// Update server status every 30 seconds
setInterval(updateServerStatus, 30000);

// Initial calls to set the status immediately
updateInternetStatus();
updateServerStatus();