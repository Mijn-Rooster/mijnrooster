
import { writable } from 'svelte/store';


/**
 * A writable store that holds the current login information.
 */
export const isLoggedIn = writable(false);

/**
 * Logs the user in by setting the `isLoggedIn` state to `true`.
 */
export function logIn() {
    isLoggedIn.set(true);
}

/**
 * Logs out the current user by setting the `isLoggedIn` state to `false`.
 */
export function logOut() {
    isLoggedIn.set(false);
}
