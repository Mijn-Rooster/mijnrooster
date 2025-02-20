import { contextBridge, ipcRenderer } from "electron";
import { electronAPI } from "@electron-toolkit/preload";

// Custom APIs for renderer
const api = {
  generateHash: async (data) => {
    return ipcRenderer.invoke("generate-hash", data);
  },
  isOnline: async () => {
    return ipcRenderer.invoke('check-connection');
  },
  onOpenSettings: (callback) => {
    ipcRenderer.on("open-settings", callback);
  },
  appInfo: () => {
    return {
      appVersion: process.env.npm_package_version, // or use ipcRenderer.invoke if needed
      electronVersion: process.versions.electron,
      nodeVersion: process.versions.node,
      chromeVersion: process.versions.chrome,
      v8Version: process.versions.v8,
    };
  },
};

// Use `contextBridge` APIs to expose Electron APIs to
// renderer only if context isolation is enabled, otherwise
// just add to the DOM global.
if (process.contextIsolated) {
  try {
    contextBridge.exposeInMainWorld("electron", electronAPI);
    contextBridge.exposeInMainWorld("api", api);
  } catch (error) {
    console.error(error);
  }
} else {
  window.electron = electronAPI;
  window.api = api;
}
