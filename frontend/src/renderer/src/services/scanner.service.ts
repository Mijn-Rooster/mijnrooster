import { get } from 'svelte/store';
import { core } from '../stores/core.store';

let currentScanner: HIDDevice | null = null;
let currentScanValue = '';

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

// Input event handler voor barcode scanner
function handleScannerInput(event: HIDInputReportEvent) {
    const dataView = event.data;
    // Convert DataView to array for processing
    const dataArray = [];
    for (let i = 0; i < dataView.byteLength; i++) {
        dataArray.push(dataView.getUint8(i));
    }
    
    // Hidit keyboard protocol verwerking
    // Keyboard codes: https://www.usb.org/sites/default/files/documents/hut1_12v2.pdf (page 53)
    // Format: eerste byte is modifier, tweede is gereserveerd, daarna tot 6 key codes
    
    const keyCode = dataArray[2]; // Eerste toets
    if (keyCode === 0) return; // Geen toets ingedrukt
    
    // Verwerk de toets
    if (keyCode >= 30 && keyCode <= 39) { // 1-9
        currentScanValue += String.fromCharCode(keyCode - 30 + 49);
    } else if (keyCode === 39) { // 0
        currentScanValue += '0';
    } else if (keyCode === 40) { // Enter
        // Vind en update het leerlingnummer veld
        const input = document.getElementById('leerlingnummer') as HTMLInputElement;
        if (input) {
            input.value = currentScanValue;
            input.dispatchEvent(new Event('input'));
            // Simuleer een form submit
            setTimeout(() => {
                const form = input.closest('form');
                if (form) {
                    form.dispatchEvent(new Event('submit'));
                }
            }, 100);
        }
        // Reset scan waarde
        currentScanValue = '';
    }
    
    console.log('Scanner input:', dataArray, 'Current value:', currentScanValue);
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


