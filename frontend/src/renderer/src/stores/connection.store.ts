import { writable } from "svelte/store";
import { isConnectedToInternet, isConnectedToServer } from "../services/core.service";

/**
 * A writable store that holds the current internet connection status.
 */
export const internetStatus = writable(true);
export const serverStatus = writable(true);

/**
 * Updates the connection status stores with the current connection status.
 */
async function updateConnectionStatus() {
  internetStatus.set(await isConnectedToInternet());
  serverStatus.set(await isConnectedToServer());
}

// Update connection status every 10 seconds
setInterval(updateConnectionStatus, 10000);
updateConnectionStatus(); // Initial call to set the status immediately