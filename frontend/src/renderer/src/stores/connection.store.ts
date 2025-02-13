import { writable } from "svelte/store";
import { isConnectedToInternet, isConnectedToServer } from "../services/core.service";

export const internetStatus = writable(false);
export const serverStatus = writable(false);

async function updateConnectionStatus() {
  internetStatus.set(await isConnectedToInternet());
  serverStatus.set(await isConnectedToServer());
}

// Update connection status every 10 seconds
setInterval(updateConnectionStatus, 10000);
updateConnectionStatus(); // Initial call to set the status immediately