import { app, shell, BrowserWindow, ipcMain, dialog, Menu } from "electron";
import { join } from "path";
import { electronApp, optimizer, is } from "@electron-toolkit/utils";
import icon from "../../resources/icon.png?asset";
import installExtension from "electron-devtools-installer";
import { createHash } from "crypto";
import { autoUpdater } from "electron-updater";
import isOnline from "@esm2cjs/is-online";
import AutoLaunch from "auto-launch";

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
 * Sets up the auto-updater functionality for the application
 * @param {Electron.BrowserWindow} win - The main browser window instance
 * @description
 * Configures the electron auto-updater with the following functionality:
 * - Enables automatic download of updates
 * - Enables automatic installation on app quit
 * - Logs update checks and status
 * - Notifies the renderer process when updates are available
 * - Shows error dialogs when update fails
 * - Prompts user to install downloaded updates
 * - Performs initial update check on setup
 * @throws {Error} When auto-updater encounters an error during update process
 */
function setupAutoUpdater(win) {
  autoUpdater.autoDownload = true;
  autoUpdater.autoInstallOnAppQuit = true;

  autoUpdater.on("checking-for-update", () => {
    log.info("Checking for updates...");
  });

  autoUpdater.on("update-available", (info) => {
    log.info(`Update available: ${info.version}`);
    win.webContents.send("update-available", info.version);
  });

  autoUpdater.on("update-not-available", () => {
    log.info("No updates available.");
  });

  autoUpdater.on("error", (err) => {
    log.error("Error in auto-updater:", err);
    dialog.showErrorBox("Update Error", `Failed to update: ${err.message}`);
  });

  autoUpdater.on("update-downloaded", () => {
    log.info("Update downloaded. Prompting user...");

    dialog
      .showMessageBox(win, {
        type: "info",
        title: "Updates beschikbaar",
        message: "Er is een nieuwe versie van Mijn Rooster beschikbaar!",
        detail:
          "De update is al gedownload en klaar om te installeren. De app zal automatisch herstarten.",
        buttons: ["Nu installeren", "Later"],
        icon: icon,
      })
      .then((result) => {
        if (result.response === 0) {
          autoUpdater.quitAndInstall();
        }
      });
  });

  // Perform an initial check for updates
  autoUpdater.checkForUpdatesAndNotify();
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
    console.error("Failed to check connection:", error);
    return false;
  }
});

// Get app version (IPC handler)
ipcMain.handle("get-app-version", () => {
  return app.getVersion();
});

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
  setupAutoUpdater(mainWindow);

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
            autoUpdater.checkForUpdates().then(() => {
              dialog.showMessageBox({
                type: "info",
                title: "Updates",
                message: "Controleren op updates",
                detail: "Je hebt de laatste versie van Mijn Rooster.",
                buttons: ["OK"],
              });
            });
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
  return await autoLauncher.isEnabled();
});

ipcMain.handle("set-auto-launch", async (_, enabled) => {
  try {
    if (enabled) {
      await autoLauncher.enable();
    } else {
      await autoLauncher.disable();
    }
    return true;
  } catch (error) {
    console.error("Failed to set auto-launch:", error);
    return false;
  }
});
