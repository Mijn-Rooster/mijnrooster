/**
 * Service module for handling API communication with the server.
 * This module provides functionality for schedule retrieval, connection checking,
 * and school list retrieval.
 *
 * @module ApiService
 *
 * @remarks
 * All functions in this service handle server communication and authentication.
 * They use a token-based authentication system where the token is generated
 * from a server password and a salt.
 *
 * @example
 * ```typescript
 * // Retrieve schedule for a user
 * const schedule = await retrieveSchedule("user123", startTime, endTime);
 *
 * // Check server connection
 * const connectionStatus = await connectionCheck();
 *
 * // Get list of schools
 * const schools = await retrieveSchoolList();
 * ```
 *
 * @throws {Object} Most functions throw structured error objects with:
 * - message: User-friendly error message in Dutch
 * - details: Technical details about the error
 */
import { CheckModel } from "../models/check.model";
import type { ScheduleItemModel } from "../models/scheduleItem.model";
import { SchoolModel } from "../models/school.model";
import { core } from "../stores/core.store";
import { getHash } from "./core.service";
import { get } from "svelte/store";
import type { UserModel } from "../models/user.model";

let serverUrl: string | null = "";
let token: string | null = "";

core.subscribe(async (value) => {
  serverUrl = value.serverUrl;
});

/**
 * Ensures a token is available by generating it if it doesn't exist.
 * The token is created by hashing the server password combined with a salt.
 *
 * @returns A Promise that resolves to the authentication token string
 * @throws {Error} If the core value or server password is unavailable
 */
async function ensureToken(): Promise<string> {
  const coreValue = get(core);
  if (!token) {
    token = await getHash(coreValue.serverPassword + "D@v1dRein0utJ0nathan");
  }
  return token;
}

/**
 * Retrieves schedule items for a specific user within a given time range.
 *
 * @param user - The user identifier (can be string or number)
 * @param todayStartUnix - The start timestamp in Unix format
 * @param todayEndUnix - The end timestamp in Unix format
 * @returns Promise containing an array of ScheduleItemModel objects
 * @throws {Object} Error object with message and details if:
 *  - Server returns non-OK response
 *  - Connection to server fails
 *  - Any other unexpected errors
 */
export async function retrieveSchedule(
  user: string | number,
  todayStartUnix: number,
  todayEndUnix: number,
  signal?: AbortSignal,
): Promise<ScheduleItemModel[]> {
  try {
    const url = `${serverUrl}/v1/schedule/${user}?start=${todayStartUnix}&end=${todayEndUnix}`;
    const token = await ensureToken();

    const response = await fetch(url, {
      method: "GET",
      headers: {
        accept: "application/json",
        Authorization: "Bearer " + token,
      },
      signal,
    });

    const responsedata = await response.json();

    if (!response.ok) {
      throw {
        message: "Er is een fout opgetreden bij het ophalen van het rooster",
        details: responsedata.message + ": " + responsedata.details || null,
      };
    }

    responsedata.data.sort((a: any, b: any) => a.start - b.start);
    return responsedata.data;
  } catch (error) {
    if (error instanceof TypeError && error.message === "Failed to fetch") {
      throw {
        message: "Kon geen verbinding maken met de server",
        details:
          "Controleer of de server bereikbaar is en of het adres correct is",
      };
    }
    throw error;
  }
}

/**
 * Checks the connection to the server with optional server URL and password.
 *
 * @param serverUrl - The URL of the server to check connection with. If null, uses the stored server URL from core.
 * @param connectPasword - The password for server authentication. If null, uses the stored password from core.
 * @returns Promise<CheckModel> - Returns a promise that resolves to the check data from the server.
 * @throws {Object} With properties:
 *  - message: "Onjuist wachtwoord" if authentication fails (401)
 *  - message: "Kan geen verbinding maken met de server" if server connection fails
 *  - message: "Kon geen verbinding maken met de server" if network request fails
 *  - details: Additional error information from the server or predefined message
 */
export async function connectionCheck(
  serverUrl: string | null = null,
  connectPasword: string | null = null,
): Promise<CheckModel> {
  try {
    if (!serverUrl) {
      serverUrl = get(core).serverUrl;
    }

    if (!connectPasword) {
      connectPasword = get(core).serverPassword;
    }

    if (!serverUrl) {
      throw {
        message: "Geen server URL ingesteld",
        details: "Controleer of de server URL correct is ingesteld",
      };
    }

    const url = `${serverUrl}/v1/check`;
    const token = await getHash(connectPasword + "D@v1dRein0utJ0nathan");

    const response = await fetch(url, {
      method: "GET",
      headers: {
        accept: "application/json",
        Authorization: "Bearer " + token,
      },
    });

    const data = await response.json();

    if (response.status === 401) {
      throw {
        message: "Onjuist wachtwoord",
        details: data.message + ": " + data.details || null,
      };
    }

    if (!response.ok) {
      throw {
        message: "Kan geen verbinding maken met de server",
        details: data.message + ": " + data.details || null,
      };
    }

    return data.data;
  } catch (error) {
    if (error instanceof TypeError && error.message === "Failed to fetch") {
      throw {
        message: "Kon geen verbinding maken met de server",
        details:
          "Controleer of de server bereikbaar is en of het adres correct is",
      };
    }
    throw error;
  }
}

/**
 * Retrieves a list of schools from the server.
 * @throws {Object} If the server returns an error response with:
 *  - message: "Er is een fout opgetreden bij het ophalen van de scholen"
 *  - details: Error details from the server response
 * @throws {Object} If connection to server fails with:
 *  - message: "Kon geen verbinding maken met de server"
 *  - details: "Controleer of de server bereikbaar is en of het adres correct is"
 * @returns {Promise<SchoolModel[]>} A promise that resolves to an array of school models
 */
export async function retrieveSchoolList(): Promise<SchoolModel[]> {
  try {
    const url = `${serverUrl}/v1/schools`;
    const token = await ensureToken();

    const response = await fetch(url, {
      method: "GET",
      headers: {
        accept: "application/json",
        Authorization: "Bearer " + token,
      },
    });

    const responsedata = await response.json();

    if (!response.ok) {
      throw {
        message: "Er is een fout opgetreden bij het ophalen van de scholen",
        details: responsedata.message + ": " + responsedata.details || null,
      };
    }

    return responsedata.data;
  } catch (error) {
    if (error instanceof TypeError && error.message === "Failed to fetch") {
      throw {
        message: "Kon geen verbinding maken met de server",
        details:
          "Controleer of de server bereikbaar is en of het adres correct is",
      };
    }
    throw error;
  }
}

export async function retrieveUserInfo(
  schoolInSchoolYear: string,
  leerlingnummer: string,
): Promise<UserModel | null> {
  const url = `http://localhost:8000/v1/schools/${schoolInSchoolYear}/user/${leerlingnummer}`;
  const token = await ensureToken();

  const response = await fetch(url, {
    method: "GET",
    headers: {
      accept: "application/json",
      Authorization: "Bearer " + token,
    },
  });

  if (!response.ok) {
    console.error("User info fetch failed:", response.status);
    return null;
  }

  const data = await response.json();
  return data.data[0];
}
