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