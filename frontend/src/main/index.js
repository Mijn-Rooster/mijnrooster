import { app, shell, BrowserWindow, ipcMain, dialog, Menu } from "electron";
import { join } from "path";
import { electronApp, optimizer, is } from "@electron-toolkit/utils";
import icon from "../../resources/icon.png?asset";
import installExtension from "electron-devtools-installer";
import { createHash } from "crypto";
import { autoUpdater } from "electron-updater";
import isOnline from "@esm2cjs/is-online";
import AutoLaunch from "auto-launch";
import log from "electron-log";

// Enable logging
log.transports.file.level = "info";
autoUpdater.logger = log;

/**
 * Creates and configures the main application window.
 *
 * @function createWindow
 * @returns {BrowserWindow} The configured Electron browser window instance
 *
 * @description
 * Creates a fullscreen browser window with the following features:
 * - Hidden until ready to show
 * - Auto-hidden menu bar
 * - Linux-specific icon handling
 * - Preloaded scripts with sandbox disabled and web security enabled
 * - External links handled by the system's default browser
 * - Development mode uses ELECTRON_RENDERER_URL
 * - Production mode loads local HTML file
 */
function createWindow() {
  // Create the browser window.
  const mainWindow = new BrowserWindow({
    fullscreen: true,
    show: false,
    autoHideMenuBar: true,
    ...(process.platform === "linux" ? { icon } : {}),
    webPreferences: {
      preload: join(__dirname, "../preload/index.js"),
      sandbox: false,
      webSecurity: true, // Set to false to test with server on leerlingsites.nl
    },
  });

  mainWindow.on("ready-to-show", () => {
    mainWindow.show();
  });

  mainWindow.webContents.setWindowOpenHandler((details) => {
    shell.openExternal(details.url);
    return { action: "deny" };
  });

  mainWindow.webContents.session.on(
    "select-hid-device",
    (event, details, callback) => {
      // Add events to handle devices being added or removed before the callback on
      // `select-hid-device` is called.
      mainWindow.webContents.session.on("hid-device-added", (event, device) => {
        console.log("hid-device-added FIRED WITH", device);
        // Optionally update details.deviceList
      });

      mainWindow.webContents.session.on(
        "hid-device-removed",
        (event, device) => {
          console.log("hid-device-removed FIRED WITH", device);
          // Optionally update details.deviceList
        },
      );

      event.preventDefault();
      if (details.deviceList && details.deviceList.length > 0) {
        callback(details.deviceList[0].deviceId);
      }
    },
  );

  // HMR for renderer based on electron-vite cli.
  // Load the remote URL for development or the local html file for production.
  if (is.dev && process.env["ELECTRON_RENDERER_URL"]) {
    mainWindow.loadURL(process.env["ELECTRON_RENDERER_URL"]);
  } else {
    mainWindow.loadFile(join(__dirname, "../renderer/index.html"));
  }

  return mainWindow;
}

/**
 * Sets up the auto-updater for the application with both silent and manual update checks.
 *
 * @param {BrowserWindow} win - The main application window instance
 * @returns {Function} A function that can be called to manually check for updates
 *
 * @listens {autoUpdater#checking-for-update} Logs when checking for updates begins
 * @listens {autoUpdater#update-available} Handles when updates are available
 * @listens {autoUpdater#update-not-available} Handles when no updates are found
 * @listens {autoUpdater#error} Handles any errors during the update process
 * @listens {autoUpdater#update-downloaded} Handles when an update is downloaded
 *
 * @description
 * This function configures the electron autoUpdater with the following features:
 * - Automatic download of updates
 * - Automatic installation on app quit
 * - Silent background checks
 * - Manual check capability with user notifications
 * - Error handling and logging
 */
function setupAutoUpdater(win) {
  autoUpdater.autoDownload = true;
  autoUpdater.autoInstallOnAppQuit = true;

  let isManualCheck = false;

  autoUpdater.on("checking-for-update", () => {
    log.info("Checking for updates...");
  });

  autoUpdater.on("update-available", (info) => {
    log.info(`Update available: ${info.version}`);
    win.webContents.send("update-available", info.version);

    if (isManualCheck) {
      dialog
        .showMessageBox({
          type: "info",
          title: "Updates beschikbaar",
          message: "Er is een nieuwe versie van Mijn Rooster beschikbaar!",
          detail:
            "Wil je de update nu installeren? De app zal opnieuw opstarten.",
          buttons: ["Nu installeren", "Later"],
        })
        .then((result) => {
          if (result.response === 0) {
            autoUpdater.downloadUpdate().then(() => {
              autoUpdater.quitAndInstall();
            });
          }
        });
    }
  });

  autoUpdater.on("update-not-available", () => {
    log.info("No updates available.");
    if (isManualCheck) {
      dialog.showMessageBox({
        type: "info",
        title: "Updates",
        message: "Controleren op updates",
        detail: "Je hebt de laatste versie van Mijn Rooster.",
        buttons: ["OK"],
      });
    }
  });

  autoUpdater.on("error", (err) => {
    log.error("Error in auto-updater:", err);
    if (isManualCheck) {
      dialog.showErrorBox("Update Error", `Failed to update: ${err.message}`);
    }
  });

  autoUpdater.on("update-downloaded", () => {
    log.info("Update downloaded.");
    if (isManualCheck) {
      dialog
        .showMessageBox(win, {
          type: "info",
          title: "Updates beschikbaar",
          message: "Er is een nieuwe versie van Mijn Rooster gedownload!",
          detail:
            "Wil je de update nu installeren? De app zal opnieuw opstarten.",
          buttons: ["Nu installeren", "Later"],
        })
        .then((result) => {
          if (result.response === 0) {
            autoUpdater.quitAndInstall();
          }
        });
    }
  });

  // Initial silent check
  autoUpdater.checkForUpdates();

  // Return function to perform manual check
  return () => {
    isManualCheck = true;
    autoUpdater.checkForUpdates().finally(() => {
      isManualCheck = false;
    });
  };
}

// Generate SHA256 hash (IPC handler)
ipcMain.handle("generate-hash", (event, data) => {
  const hash = createHash("sha256").update(data).digest("hex");
  return hash;
});
// Connection check (IPC handler)
ipcMain.handle("check-connection", async () => {
  try {
    return await isOnline.default();
  } catch (error) {
    log.error("Failed to check connection:", error);
    return false;
  }
});

// Get app version (IPC handler)
ipcMain.handle("get-app-version", () => {
  const version = app.getVersion();
  log.info(`App version requested: ${version}`);
  return version;
});

app.commandLine.appendSwitch("disable-hid-blocklist");

// This method will be called when Electron has finished initialization.
app.whenReady().then(() => {
  // Set app user model id for windows
  electronApp.setAppUserModelId("nl.mijnrooster.client");

  // Default open or close DevTools by F12 in development
  // and ignore CommandOrControl + R in production.
  app.on("browser-window-created", (_, window) => {
    optimizer.watchWindowShortcuts(window);
  });

  // Install Svelte DevTools in development mode
  if (is.dev) {
    installExtension("kfidecgcdjjfpeckbblhmfkhmlgecoff")
      .then((name) => console.log(`Added Extension: ${name}`))
      .catch((err) => console.log("An error occurred:", err));
  }

  const mainWindow = createWindow();
  const checkForUpdates = setupAutoUpdater(mainWindow);

  // Create an application menu that includes update checks, zoom controls, and developer tools.
  const menuTemplate = [
    {
      label: "Mijn Rooster",
      submenu: [
        {
          label: "Over Mijn Rooster",
          click: () => {
            dialog.showMessageBox({
              type: "info",
              title: "Over Mijn Rooster",
              message: "Mijn Rooster",
              detail:
                "Mijn Rooster is een roosterapplicatie om op een eenvoudige manier je rooster te bekijken op een kiosk-apparaat op school.\n\nDeze applicatie is gemaakt door David Jongeneel, Reinout Muis en Jonathan van der Pligt en ontwikkeld als PWS project.\n\n(c) 2025 Mijn Rooster. Alle rechten voorbehouden.",
              buttons: ["OK"],
              icon: icon,
            });
          },
        },
        {
          label: "GitHub",
          click: () => {
            shell.openExternal("https://github.com/Mijn-Rooster/mijnrooster");
          },
        },
        { type: "separator" },
        {
          label: "Instellingen",
          accelerator: "CmdOrCtrl+,",
          click: () => {
            mainWindow.webContents.send("open-settings");
          },
        },
        {
          label: "Controleren op updates",
          accelerator: "CmdOrCtrl+U",
          click: () => {
            log.info("Manual update check initiated.");
            checkForUpdates();
          },
        },
        { type: "separator" },
        { role: "quit" },
      ],
    },
    {
      label: "Weergave",
      submenu: [
        { role: "reload" },
        { role: "forceReload" },
        {
          role: "toggleDevTools",
          label: "Toggle Developer Tools",
        },
        { type: "separator" },
        { role: "resetZoom", label: "Reset Zoom" },
        { role: "zoomIn", label: "Zoom In" },
        { role: "zoomOut", label: "Zoom Out" },
        { type: "separator" },
        { role: "togglefullscreen", label: "Toggle Fullscreen" },
      ],
    },
  ];

  const menu = Menu.buildFromTemplate(menuTemplate);
  Menu.setApplicationMenu(menu);

  app.on("activate", function () {
    // On macOS it's common to re-create a window when the dock icon is clicked.
    if (BrowserWindow.getAllWindows().length === 0) createWindow();
  });
});

// Quit when all windows are closed, except on macOS.
app.on("window-all-closed", () => {
  if (process.platform !== "darwin") {
    app.quit();
  }
});

// Auto-launch configuration with platform-specific paths
const autoLauncher = new AutoLaunch({
  name: "Mijn Rooster",
  path:
    process.platform === "linux"
      ? process.execPath // Use process.execPath for Linux
      : app.getPath("exe"), // Use app.getPath for Windows/macOS
});

ipcMain.handle("get-auto-launch-status", async () => {
  const status = await autoLauncher.isEnabled();
  log.info(`Auto-launch status checked: ${status}`);
  return status;
});

ipcMain.handle("set-auto-launch", async (_, enabled) => {
  try {
    if (enabled) {
      await autoLauncher.enable();
      log.info("Auto-launch enabled");
    } else {
      await autoLauncher.disable();
      log.info("Auto-launch disabled");
    }
    return true;
  } catch (error) {
    log.error("Failed to set auto-launch:", error);
    return false;
  }
});
