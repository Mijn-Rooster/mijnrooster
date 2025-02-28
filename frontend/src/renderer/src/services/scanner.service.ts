import { get } from 'svelte/store';
import { core } from '../stores/core.store';

let currentScanner: HIDDevice | null = null;
let currentScanValue = '';

let scanBuffer = '';
let lastKeyTime = 0;
const SCAN_TIMEOUT = 50; // Time in ms between keystrokes to be considered part of the same scan

export function initScannerKeyboardMode() {
    console.log('Initializing scanner keyboard mode for specific device');
    
    document.addEventListener('keypress', (event: KeyboardEvent) => {
        // Check if the input is from our specific scanner
        if ((event.target as any)?.ownerDocument?.defaultView?.event?.path?.[0]?.vendorId !== get(core).barcodeScanner?.vendorId ||
            (event.target as any)?.ownerDocument?.defaultView?.event?.path?.[0]?.productId !== get(core).barcodeScanner?.productId) {
            return;
        }

        console.log('Scanner keypress:', event.key);
        console.log((event.target as any)?.ownerDocument?.defaultView?.event?.path?.[0]?.vendorId)


        const currentTime = new Date().getTime();
        
        if (currentTime - lastKeyTime > SCAN_TIMEOUT && scanBuffer.length > 0) {
            console.log('Scan timeout, clearing buffer:', scanBuffer);
            scanBuffer = '';
        }
        
        lastKeyTime = currentTime;
        
        if (/^\d$/.test(event.key)) {
            scanBuffer += event.key;
        }
        
        if (event.key === 'Enter' && scanBuffer.length > 0) {
            console.log('Processing scan:', scanBuffer);
            
            const input = document.getElementById('leerlingnummer') as HTMLInputElement;
            if (input) {
                input.value = scanBuffer;
                input.dispatchEvent(new Event('input', { bubbles: true }));
                
                const form = input.closest('form');
                if (form) {
                    form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
                }
            }
            
            scanBuffer = '';
        }
    });
}

export function cleanupScannerKeyboardMode() {
    document.removeEventListener('keypress', () => {});
    scanBuffer = '';
}

export async function getAvailableDevices(): Promise<HIDDevice[]> { 
    try {
        // Request access to HID devices
        const devices = await navigator.hid.getDevices();
        
        // Filter for keyboard devices
        // Keyboards typically use usage page 0x01 (Generic Desktop) and usage 0x06 (Keyboard)
        const keyboards = devices.filter(device => 
            device.collections.some(collection => 
                collection.usagePage === 0x01 && collection.usage === 0x06
            )
        );
        
        return keyboards;
    } catch (error) {
        console.error('Error accessing HID devices:', error);
        throw error;
    }
}

export async function getDeviceById(deviceId: number): Promise<HIDDevice | null> {
    try {
        // Request access to HID devices
        const devices = await getAvailableDevices();
        
        // Find the device with the specified ID
        const device = devices.find(device => device.productId === deviceId);
        
        console.log('Device:', device);
        
        return device || null;
    } catch (error) {
        console.error('Error accessing HID devices:', error);
        throw error;
    }
}

function handleScannerInput(event: HIDInputReportEvent) {
    console.log('Scanner input received:', {
        device: event.device.productName,
        deviceId: `${event.device.vendorId}:${event.device.productId}`,
        reportId: event.reportId,
        time: new Date().toISOString()
    });

    const dataView = event.data;
    const dataArray = [];
    for (let i = 0; i < dataView.byteLength; i++) {
        dataArray.push(dataView.getUint8(i));
    }
    
    const keyCode = dataArray[2]; // First key code
    if (keyCode === 0) return; // No key pressed
    
    // Updated key code mapping
    if (keyCode >= 30 && keyCode <= 38) { // 1-9
        currentScanValue += String.fromCharCode(keyCode - 30 + 49);
    } else if (keyCode === 39) { // 0
        currentScanValue += '0';
    } else if (keyCode === 40 || keyCode === 88) { // Enter (40) or NumpadEnter (88)
        const input = document.getElementById('leerlingnummer') as HTMLInputElement;
        if (input) {
            // Set the value and trigger input event
            input.value = currentScanValue;
            input.dispatchEvent(new Event('input', { bubbles: true }));
            
            // Find and submit the form
            const form = input.closest('form');
            if (form) {
                // Dispatch both input and submit events
                form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
            }
        }
        // Reset scan value after processing
        currentScanValue = '';
    }
    
    console.log('Scanner input:', {
        dataArray,
        keyCode,
        currentValue: currentScanValue,
        modifier: dataArray[0]
    });
}

// Connect saved scanner from core store
export async function connectSavedScanner(): Promise<boolean> {
    const coreState = get(core);
    
    if (!coreState.barcodeScanner) {
        return false;
    }
    
    try {
        // Get all available devices
        const devices = await navigator.hid.getDevices();
        
        // Find our saved scanner
        const savedScanner = devices.find(device => 
            device.productId === coreState.barcodeScanner?.productId && 
            device.vendorId === coreState.barcodeScanner?.vendorId
        );
        
        if (!savedScanner) {
            return false;
        }
        
        // Connect to the scanner
        await connectToDevice(savedScanner);
        return true;
    } catch (error) {
        console.error('Error connecting to saved scanner:', error);
        return false;
    }
}

// Connect to device with event handling
export async function connectToDevice(device: HIDDevice): Promise<void> {
    try {
        // Disconnect existing scanner if connected
        if (currentScanner && currentScanner.opened) {
            currentScanner.removeEventListener('inputreport', handleScannerInput);
            await currentScanner.close();
        }
        
        // Open a connection to the device
        if (!device.opened) {
            await device.open();
        }
        
        // Set up event listener for input reports
        device.addEventListener('inputreport', handleScannerInput);
        console.log('Event listener set up for scanner:', device);
        
        // Store current scanner reference
        currentScanner = device;
        
        console.log('Connected to device:', device);
    } catch (error) {
        console.error('Error connecting to device:', error);
        throw error;
    }
}

// Request HID devices with broader filter to catch barcode scanners
export async function requestHIDDevices(): Promise<HIDDevice[]> {
    try {
        // Use a broader filter to catch various HID devices
        const devices = await navigator.hid.requestDevice({
            filters: [
                { usagePage: 0x01 } // Generic Desktop Controls
            ]
        });
        return devices;
    } catch (error) {
        console.error('Error requesting HID devices:', error);
        throw error;
    }
}


