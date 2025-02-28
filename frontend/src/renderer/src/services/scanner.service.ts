import { get } from "svelte/store";
import { core } from '../stores/core.store';

let scanBuffer = '';
let lastKeyTime = 0;
const SCAN_TIMEOUT = 15; // Time in ms between keystrokes to be considered part of the same scan

/**
 * Handles keyboard input from a barcode scanner device.
 * Processes the scanned input by accumulating characters into a buffer and
 * submits the data when Enter is pressed.
 * 
 * @param event - The keyboard event triggered by scanner input
 * @remarks
 * - Clears the scan buffer if timeout between keystrokes is exceeded
 * - Only accepts alphanumeric characters
 * - On Enter key, populates the value into an input element with id 'leerlingnummer'
 * - Triggers input and submit events after populating the value
 * 
 * @throws - No explicit throws, but may fail silently if input element is not found
 */
function handleScannerInput(event: KeyboardEvent): void {
    const input = document.getElementById('leerlingnummer') as HTMLInputElement;
    if (!input || input.disabled) return;

    const currentTime = new Date().getTime();
    
    // Clear buffer if timeout exceeded
    if (currentTime - lastKeyTime > SCAN_TIMEOUT && scanBuffer.length > 0) {
        scanBuffer = '';
    }
    
    lastKeyTime = currentTime;
    
    // Add character to buffer if alphanumeric
    if (/^[a-zA-Z0-9]$/.test(event.key)) {
        scanBuffer += event.key;
    }
    
    // Process complete scan on Enter
    if (event.key === 'Enter' && scanBuffer.length > 0) {
        if (input) {
            input.value = scanBuffer;
            input.dispatchEvent(new Event('input', { bubbles: true }));

            // Trigger submit event
            const submitEvent = new Event('submit', { bubbles: true });
            input.form?.dispatchEvent(submitEvent);
        }
        
        scanBuffer = '';
    }
}

/**
 * Initializes the barcode scanner in keyboard mode.
 * This function sets up a keyboard event listener to handle barcode scanner input.
 * If the barcode scanner is not available in the core state, the function returns early.
 * Any existing scanner mode is cleaned up before initializing a new one.
 * 
 * @remarks
 * This function assumes that the barcode scanner emulates keyboard input.
 * The actual handling of scanner input is done by the `handleScannerInput` function.
 * 
 * @returns {void}
 * 
 * @example
 * ```typescript
 * initScannerKeyboardMode();
 * ```
 */
export function initScannerKeyboardMode() {
    console.log("init")
    if (!get(core).barcodeScanner) return;
    // Cleanup any existing scanner mode
    cleanupScannerKeyboardMode();
    // Add the event listener with the separated handler function
    document.addEventListener('keypress', handleScannerInput);
}

/**
 * Cleans up the scanner keyboard mode by removing the keypress event listener
 * and clearing the scan buffer.
 * 
 * This function removes the specific keyboard event handler that was used for
 * processing scanner input and resets the scan buffer to an empty string.
 */
export function cleanupScannerKeyboardMode() {
    console.log("remove")
    // Update to remove the specific handler function
    document.removeEventListener('keypress', handleScannerInput);
    scanBuffer = '';
}


