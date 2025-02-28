import { writable, get } from "svelte/store";

/**
 * Represents the core state configuration for the application.
 *
 * @property serverUrl - The URL of the server to which the application connects, or null if not configured.
 * @property serverConnected - A flag indicating whether the application is currently connected to the server.
 * @property serverPassword - The server password used for authentication, or null if not provided.
 * @property schoolInYearId - The identifier for the current school year, or null if not specified.
 * @property adminPassword - The administration password for elevated operations.
 * @property weekView - A flag indicating whether the application should display the week view.
 * @property numPadControl - A flag indicating whether the application should use the numpad for input.
 * @property logoutTimeOut - The time in minutes before the application automatically logs out from the schedule view.
 */
interface CoreStore {
  serverUrl: string | null;
  serverConnected: boolean;
  serverPassword: string | null;
  schoolInYearId: number | null;
  schoolId: number | null;
  adminPassword: string;
  weekView: boolean;
  numPadControl: boolean;
  autoLogout: boolean;
  logoutTimeOut: number;
  barcodeScanner: boolean;
}

const DEFAULT: CoreStore = {
  serverUrl: null,
  serverConnected: false,
  serverPassword: null,
  schoolInYearId: null,
  schoolId: null,
  adminPassword: "",
  weekView: false,
  numPadControl: false,
  autoLogout: false,
  logoutTimeOut: 20,
  barcodeScanner: false,
};

const storedCore = localStorage.getItem("core");

/**
 * Represents the core configuration state for the application.
 *
 * This writable store is initialized with the stored core values (if available) using JSON parsing.
 * This store is used to manage the core settings across the application's lifecycle.
 */
export const core = writable<CoreStore>(
  storedCore
    ? {
        ...DEFAULT, // First spread defaults
        ...JSON.parse(storedCore), // Then overlay stored values
      }
    : {
        ...DEFAULT,
      },
);

core.subscribe((value) => {
  localStorage.setItem("core", JSON.stringify(value));
});

/**
 * Determines the setup completion status based on the core store configuration.
 *
 * The function evaluates the configuration from the core store and returns a status code:
 * - 0: Both the server URL and server password are not set.
 * - 1: The school in-year ID is not set.
 * - 2: All required configurations are set.
 *
 * @returns {number} A numeric code representing the setup status.
 */
export function isSetupComplete(): number {
  const coreStore: CoreStore = get(core);
  if (coreStore.serverUrl == null && coreStore.serverPassword == null) {
    return 0;
  } else if (coreStore.schoolId == null && coreStore.schoolInYearId == null) {
    return 1;
  } else {
    return 2;
  }
}

/**
 * Resets the core store to its default state by removing the "core" item from localStorage
 * and setting the core store to its default values.
 * @returns void
 */
export function resetCoreStore(): void {
  localStorage.removeItem("core");
  core.set({
    ...DEFAULT,
  });
}
