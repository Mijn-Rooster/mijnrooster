import type { ScheduleItemModel } from "../models/scheduleItem.model";
import { core } from "../stores/core.store";
import { getHash } from "./core.service";
import { get } from "svelte/store";

let serverUrl: string | null = "";
let token: string | null = "";

/**
 * Represents the result of a check operation.
 * @interface CheckResult
 * @property {('ok' | 'url_error' | 'auth_error' | 'unknown_error')} status - The status of the check operation.
 * @property {string} message - A descriptive message about the check result.
 * @property {Object} [data] - Optional data associated with the check result.
 * @property {string} [data.version] - Optional version information.
 * @property {any} [data[key]] - Additional dynamic properties in the data object.
 */
type CheckResult = {
  status: 'ok' | 'url_error' | 'auth_error' | 'unknown_error';
  message: string;
  data?: {
    version?: string;
    [key: string]: any;
  };
};

/**
 * Subscribes to changes in the core store and updates the server URL and token accordingly.
 */
core.subscribe(async (value) => {
  serverUrl = value.serverUrl;
  token = await getHash(value.serverPassword + "D@v1dRein0utJ0nathan");
});

/**
 * Retrieve the schedule for a given user within the specified time range.
 *
 * @param user - The identifier of the user; can be either a string or a number.
 * @param todayStartUnix - The starting Unix timestamp for the schedule range.
 * @param todayEndUnix - The ending Unix timestamp for the schedule range.
 * @returns A promise that resolves to an array of schedule items sorted by their start time.
 *
 * This function sends a GET request to an API endpoint using the provided parameters and an authorization header.
 * If the server returns a successful response, the function parses the JSON data, sorts the schedule items by
 * the 'start' property in ascending order, and returns the sorted array.
 * In case of an unsuccessful response, it logs an error to the console and returns an empty array.
 */
export async function retrieveSchedule(
  user: string | number,
  todayStartUnix: number,
  todayEndUnix: number
): Promise<ScheduleItemModel[]> {
  const url = `${serverUrl}/v1/schedule/${user}?start=${todayStartUnix}&end=${todayEndUnix}`;

  const response = await fetch(url, {
    method: "GET",
    headers: {
      accept: "application/json",
      Authorization: "Bearer " + token,
    },
  });

  if (!response.ok) {
    console.error("Schedule fetch failed:", response.status);
    return [];
  }

  const responsedata = await response.json();
  responsedata.data.sort((a: any, b: any) => a.start - b.start);
  return responsedata.data;
}

/**
 * Checks the connection and authentication with the server
 * @param serverUrl - The URL of the server to check. If null, uses the URL from core store
 * @param connectPasword - The password to authenticate with. If null, uses the password from core store
 * @returns Promise<CheckResult> containing:
 * - status: 'ok' | 'auth_error' | 'unknown_error' | 'url_error'
 * - message: A human-readable message about the result
 * - data?: Additional data from the server response
 * @throws Never throws - errors are returned as part of CheckResult
 */
export async function check(serverUrl: string|null = null, connectPasword: string|null = null): Promise<CheckResult> {
  if (!serverUrl) {
    serverUrl = get(core).serverUrl;
  }

  if (!connectPasword) {
    connectPasword = get(core).serverPassword;
  }

  const url = `${serverUrl}/v1/check`;
  const token = await getHash(connectPasword + "D@v1dRein0utJ0nathan");

  try {
    const response = await fetch(url, {
      method: "GET",
      headers: {
        accept: "application/json",
        Authorization: "Bearer " + token,
      },
    });

    const data = await response.json();

    if (response.status === 401) {
      return {
        status: 'auth_error',
        message: data.message || 'Kon niet inloggen op de server'
      };
    }

    if (!response.ok) {
      return {
        status: 'unknown_error',
        message: data.message || 'Onbekende fout op de server',
        data: data.data
      };
    }

    return {
      status: 'ok',
      message: data.message,
      data: data.data
    };

  } catch (error) {
    return {
      status: 'url_error',
      message: 'Kon geen verbinding maken met de server'
    };
  }
}