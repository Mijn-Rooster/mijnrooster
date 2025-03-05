import { get } from 'svelte/store';
import { core } from '../stores/core.store';
import { navigate } from '../stores/router.store';

// Store timer references
let logoutTimer: NodeJS.Timeout | null = null;
let warningTimer: NodeJS.Timeout | null = null;

// Callback functions
let onWarningCallback: (() => void) | null = null;
let onLogoutCallback: (() => void) | null = null;
let onResetCallback: (() => void) | null = null;

// User activity event types
const activityEvents = ['mousemove', 'mousedown', 'keydown', 'touchstart', 'scroll'];

/**
 * Initializes auto-logout functionality with specified callback functions.
 * Only initializes if auto-logout is enabled in core settings.
 * 
 * @param onWarning - Callback function triggered when warning timeout is reached
 * @param onLogout - Callback function triggered when logout timeout is reached
 * @param onReset - Callback function triggered when timers are reset
 * @returns {void}
 */
export function initAutoLogout(
    onWarning?: () => void, 
    onLogout?: () => void,
    onReset?: () => void
) {
    if (!get(core).autoLogout) return;
    
    console.log('Auto-logout enabled');
    
    // Set callbacks
    onWarningCallback = onWarning || null;
    onLogoutCallback = onLogout || null;
    onResetCallback = onReset || null;
    
    // Add event listeners
    activityEvents.forEach(event => {
        document.addEventListener(event, handleUserActivity);
    });
    
    // Start initial timers
    resetLogoutTimer();
}

/**
 * Handles user activity by resetting the auto-logout timer.
 * 
 * @param e - The user activity event
 */
function handleUserActivity(e: Event) {
    resetLogoutTimer();
}

/**
 * Resets the auto-logout timers and warning state.
 *
 * This function manages two timers:
 * - Warning timer: Triggers the warning callback after WARNING_DELAY milliseconds of inactivity
 * - Logout timer: Triggers the logout callback after LOGOUT_DELAY milliseconds of inactivity
 *
 * Both timers are cleared before being reset to prevent multiple concurrent timers.
 */
export function resetLogoutTimer() {
    if (!get(core).autoLogout) return;
    
    const LOGOUT_DELAY = get(core).logoutTimeOut * 1000;
    const WARNING_DELAY = LOGOUT_DELAY - 5000; // 5 seconds warning before logout
    
    // Clear existing timers
    clearTimers();
    
    // Set warning timer
    warningTimer = setTimeout(() => {
        console.log("Auto-logout warning triggered");
        if (onWarningCallback) onWarningCallback();
    }, WARNING_DELAY);
    
    // Set logout timer
    logoutTimer = setTimeout(() => {
        console.log("Auto-logout triggered");
        if (onLogoutCallback) onLogoutCallback();
        navigate('/'); // Default behavior is to navigate to home
    }, LOGOUT_DELAY);
    
    // Call reset callback if defined
    if (onResetCallback) onResetCallback();
}

/**
 * Clears all auto-logout timers.
 */
function clearTimers() {
    if (logoutTimer) {
        clearTimeout(logoutTimer);
        logoutTimer = null;
    }
    
    if (warningTimer) {
        clearTimeout(warningTimer);
        warningTimer = null;
    }
}

/**
 * Destroys auto-logout functionality by removing event listeners and clearing timers.
 * This function should be called when the component using auto-logout is destroyed.
 */
export function destroyAutoLogout() {
    // Remove event listeners
    activityEvents.forEach(event => {
        document.removeEventListener(event, handleUserActivity);
    });
    
    // Clear timers
    clearTimers();
    
    // Reset callbacks
    onWarningCallback = null;
    onLogoutCallback = null;
    onResetCallback = null;
    
    console.log('Auto-logout disabled');
}