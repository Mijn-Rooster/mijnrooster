/**
 * Service module for handling core functionality.
 *
 * @module CoreService
 *
 * @remarks
 * This module provides core functionality for the application, including cryptographic hashing.
 *
 * @example
 * ```typescript
 * // Generate a hash for a data string
 * const hash = await getHash("data");
 * ```
 *
 * @throws {Error} If the hashing operation fails
 *
 */

import { connectionCheck } from "./api.service";

/**
 * Computes a cryptographic hash for the supplied data string.
 *
 * This function asynchronously generates a hash by delegating the operation to the window API.
 *
 * @param data - The input data to hash.
 * @returns A promise that resolves to a string representing the generated hash.
 */
export async function getHash(data: string): Promise<string> {
  return window.api.generateHash(data);
}

export async function isConnectedToInternet(): Promise<boolean> {
  return window.api.isOnline();
}

export async function isConnectedToServer(): Promise<boolean> {
  try {
    await connectionCheck();
    return true;
  } catch (error) {
    return false;
  }
}
