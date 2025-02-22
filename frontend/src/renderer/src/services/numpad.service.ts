import { get } from 'svelte/store';
import { core } from '../stores/core.store';

type NumpadHandler = (event: KeyboardEvent) => void;
const handlers: { [key: string]: NumpadHandler } = {};

/**
 * Initializes numpad controls by setting up a keydown event listener.
 * Only initializes if numpad control is enabled in core settings.
 * 
 * @returns {void}
 */
export function initNumpadControls() {
    if (!get(core).numPadControl) return;

    document.addEventListener('keydown', handleKeydown);
}

/**
 * Handles keydown events for numpad keys.
 * Prevents default behavior and executes the corresponding handler if one exists.
 * 
 * @param e - The keyboard event to handle
 * @remarks
 * Only processes events where {@link isNumpadKey} returns true.
 * Uses the {@link handlers} object to map key codes to their handlers.
 */
function handleKeydown(e: KeyboardEvent) {
    if (isNumpadKey(e.code)) {
        e.preventDefault();
        const handler = handlers[e.code];
        if (handler) handler(e);
    }
}

/**
 * Determines if a given key code represents a key on the numeric keypad.
 * 
 * @param code - The key code to check
 * @returns True if the key code represents a numeric keypad key, false otherwise
 * 
 * @example
 * ```typescript
 * isNumpadKey('Numpad1') // returns true
 * isNumpadKey('NumpadEnter') // returns true
 * isNumpadKey('KeyA') // returns false
 * ```
 */
function isNumpadKey(code: string): boolean {
    return (
        code.startsWith('Numpad') ||
        code === 'NumpadEnter' ||
        code === 'NumpadAdd' ||
        code === 'NumpadSubtract' ||
        code === 'NumpadMultiply' ||
        code === 'NumpadDivide' ||
        code === 'NumpadDecimal'
    );
}

/**
 * Registers keyboard event handlers for numeric keypad input on a login form.
 * This function sets up handlers for numbers 0-9 and special function keys for a student ID input field.
 * 
 * @param submitFn - Callback function to be executed when the NumpadEnter key is pressed
 * 
 * Key mappings:
 * - Numpad0-9: Appends the corresponding digit to the input
 * - NumpadDecimal (./del): Deletes the last entered digit
 * - NumpadEnter: Submits the form by calling the provided submitFn
 * - NumpadAdd (+): Clears the entire input field
 * 
 * Note: This function first clears any existing handlers before setting up new ones.
 * The function targets an input element with id 'leerlingnummer'.
 */
export function registerLoginHandlers(submitFn: () => void) {
    // Clear previous handlers
    clearHandlers();

    // Numbers for entering student ID
    for (let i = 0; i <= 9; i++) {
        handlers[`Numpad${i}`] = (e) => {
            const input = document.getElementById('leerlingnummer') as HTMLInputElement;
            if (input) {
                const newValue = input.value + i;
                input.value = newValue;
                // Trigger input event to update Svelte binding
                input.dispatchEvent(new Event('input'));
            }
        };
    }

    // Special function keys
    handlers['NumpadDecimal'] = () => {          // Delete last digit (./del)
        const input = document.getElementById('leerlingnummer') as HTMLInputElement;
        if (input) {
            const newValue = input.value.slice(0, -1);
            input.value = newValue;
            input.dispatchEvent(new Event('input'));
        }
    };
    handlers['NumpadEnter'] = () => submitFn(); // Submit form (enter)
    handlers['NumpadAdd'] = () => {              // Clear input (+)
        const input = document.getElementById('leerlingnummer') as HTMLInputElement;
        if (input) {
            input.value = '';
            input.dispatchEvent(new Event('input'));
        }
    };
}

/**
 * Registers numpad key handlers for schedule navigation controls.
 * Clears existing handlers before registering new ones.
 * 
 * Numpad key mappings:
 * - NumpadDivide (/) : Triggers 'previous' button click
 * - NumpadMultiply (*) : Triggers 'next' button click
 * - NumpadSubtract (-) : Triggers 'toggle-view' button click
 * - NumpadAdd (+) : Triggers 'logout' button click
 */
export function registerScheduleHandlers() {
    clearHandlers();

    // Navigation controls using operation keys
    handlers['NumpadDivide'] = () => document.querySelector<HTMLButtonElement>('[data-numpad="previous"]')?.click();        // Previous (/)
    handlers['NumpadMultiply'] = () => document.querySelector<HTMLButtonElement>('[data-numpad="next"]')?.click();          // Next (*)
    handlers['NumpadSubtract'] = () => document.querySelector<HTMLButtonElement>('[data-numpad="toggle-view"]')?.click();   // Toggle view (-)
    handlers['NumpadAdd'] = () => document.querySelector<HTMLButtonElement>('[data-numpad="logout"]')?.click();             // Logout (+)

    // Page up/down controls using arrow keys 2 and 8 of numpad
    handlers['Numpad8'] = () => {
        const scrollWindow = document.querySelector<HTMLDivElement>('[data-numpad="scroll-window"]');
        if (scrollWindow) {
            scrollWindow.scrollTop -= 50;
        }
    };
    handlers['Numpad2'] = () => {
        const scrollWindow = document.querySelector<HTMLDivElement>('[data-numpad="scroll-window"]');
        if (scrollWindow) {
            scrollWindow.scrollTop += 50;
        }
    };
}

/**
 * Clears all registered event handlers from the handlers object.
 * Removes all key-value pairs from the handlers dictionary.
 */
export function clearHandlers() {
    Object.keys(handlers).forEach(key => delete handlers[key]);
}

/**
 * Removes the keydown event listener that handles numpad controls.
 * This function cleans up the event listener previously added for numpad functionality.
 */
export function destroyNumpadControls() {
    document.removeEventListener('keydown', handleKeydown);
}