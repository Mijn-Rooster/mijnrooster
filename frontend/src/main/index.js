import { app, shell, BrowserWindow, ipcMain, dialog } from "electron";
import { join } from "path";
import { electronApp, optimizer, is } from "@electron-toolkit/utils";
import icon from "../../resources/icon.png?asset";
import installExtension from "electron-devtools-installer";
import { createHash } from "crypto";
import { autoUpdater } from "electron-updater";
import isOnline from "@esm2cjs/is-online";

// Enable logging for debugging updates
import log from "electron-log";
log.transports.file.level = "info";
autoUpdater.logger = log;

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
    },
  });

  mainWindow.on("ready-to-show", () => {
    mainWindow.show();
  });

  mainWindow.webContents.setWindowOpenHandler((details) => {
    shell.openExternal(details.url);
    return { action: "deny" };
  });

  // HMR for renderer base on electron-vite cli.
  // Load the remote URL for development or the local html file for production.
  if (is.dev && process.env["ELECTRON_RENDERER_URL"]) {
    mainWindow.loadURL(process.env["ELECTRON_RENDERER_URL"]);
  } else {
    mainWindow.loadFile(join(__dirname, "../renderer/index.html"));
  }

  return mainWindow;
}

// Handle auto-updates
function setupAutoUpdater(win) {
  autoUpdater.autoDownload = true;
  autoUpdater.autoInstallOnAppQuit = true; // Install on quit

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
        title: "Er is een nieuwe update beschikbaar!",
        message: "Er is een nieuwe versie gedownload? Opnieuw opstarten om te installeren?",
        buttons: ["Restart", "Later"],
      })
      .then((result) => {
        if (result.response === 0) autoUpdater.quitAndInstall();
      });
  });

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

// This method will be called when Electron has finished
// initialization and is ready to create browser windows.
// Some APIs can only be used after this event occurs.
app.whenReady().then(() => {
  // Set app user model id for windows
  electronApp.setAppUserModelId("com.electron");

  // Default open or close DevTools by F12 in development
  // and ignore CommandOrControl + R in production.
  // see https://github.com/alex8088/electron-toolkit/tree/master/packages/utils
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

  app.on("activate", function () {
    // On macOS it's common to re-create a window in the app when the
    // dock icon is clicked and there are no other windows open.
    if (BrowserWindow.getAllWindows().length === 0) createWindow();
  });
});

// Quit when all windows are closed, except on macOS. There, it's common
// for applications and their menu bar to stay active until the user quits
// explicitly with Cmd + Q.
app.on("window-all-closed", () => {
  if (process.platform !== "darwin") {
    app.quit();
  }
});
