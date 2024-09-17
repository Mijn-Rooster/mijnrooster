import { writable } from 'svelte/store';

/**
 * A writable store that holds the current route information.
 */
export const route = writable({ path: '/', params: {} });

/**
 * Navigates to a specified path with optional parameters.
 *
 * @param path - The path to navigate to.
 * @param params - Optional parameters to include in the navigation. Defaults to an empty object.
 */
export function navigate(path, params = {}) {
    route.set({ path, params });
}