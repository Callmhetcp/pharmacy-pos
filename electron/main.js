import { app, BrowserWindow } from "electron";
import { spawn } from "child_process";
import path from "path";
import { fileURLToPath } from "url";
import http from "http";


const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

let phpProcess = null;
let mariaDbProcess = null;

function getAppPath() {

    if (app.isPackaged) {

        return path.join(process.resourcesPath, "app");

    }

    return process.cwd();

}

function waitForLaravel(url) {

    return new Promise((resolve) => {

        const check = () => {

            http.get(url, (res) => {

                console.log("Laravel HTTP Status:", res.statusCode);

                // Accept any successful response or redirect
                if (res.statusCode >= 200 && res.statusCode < 400) {

                    resolve();

                } else {

                    setTimeout(check, 1000);

                }

            }).on("error", () => {

                setTimeout(check, 1000);

            });

        };

        check();

    });

}
async function startMariaDB() {

    const appPath = getAppPath();

    const mariadbPath = path.join(
        appPath,
        "runtime",
        "mariadb",
        "bin",
        "mariadbd.exe"
    );

    const dataDir = path.join(
        appPath,
        "runtime",
        "mariadb",
        "data"
    );

    mariaDbProcess = spawn(
        mariadbPath,
        [
            "--console",
            `--datadir=${dataDir}`
        ],
        {
            cwd: path.join(appPath, "runtime", "mariadb"),
            windowsHide: true,
            shell: false
        }
    );

    mariaDbProcess.stdout.on("data", (data) => {

        console.log("MariaDB:", data.toString());

    });

    mariaDbProcess.stderr.on("data", (data) => {

        console.log("MariaDB:", data.toString());

    });

    mariaDbProcess.on("error", (err) => {

        console.error("MariaDB Error:", err);

    });

    // Give MariaDB a few seconds to start
    await new Promise(resolve => setTimeout(resolve, 5000));
}

async function startLaravel() {

    const appPath = getAppPath();

    const phpPath = path.join(
        appPath,
        "runtime",
        "php",
        "php.exe"
    );

    phpProcess = spawn(

        phpPath,

        [
            "artisan",
            "serve",
            "--host=127.0.0.1",
            "--port=8000"
        ],

        {

            cwd: appPath,

            windowsHide: true,

            shell: false

        }

    );

    phpProcess.stdout.on("data", (data) => {

        console.log(data.toString());

    });

    phpProcess.stderr.on("data", (data) => {

        console.log(data.toString());

    });

    phpProcess.on("error", (err) => {

        console.error("PHP Error:", err);

    });

    await waitForLaravel("http://127.0.0.1:8000");

}



async function createWindow() {

    console.log("Starting MariaDB...");
await startMariaDB();

console.log("Starting Laravel...");
await startLaravel();

console.log("Laravel is ready.");

    await startMariaDB();

    await startLaravel();

    console.log("Creating Electron window...");

    const win = new BrowserWindow({

        width: 1500,
        height: 900,
        autoHideMenuBar: true,

        show: true,

        webPreferences: {
            contextIsolation: true,
            nodeIntegration: false
        }

    });

    win.webContents.openDevTools();

    win.loadURL("http://127.0.0.1:8000");

    win.webContents.on("did-finish-load", () => {

        console.log("Laravel loaded successfully.");

    });

    win.webContents.on("did-fail-load", (event, code, description) => {

        console.log("Failed to load:", code, description);

    });

}

app.whenReady().then(createWindow);

app.on("window-all-closed", () => {

    if (phpProcess) {
        phpProcess.kill();
    }

    if (mariaDbProcess) {
        mariaDbProcess.kill();
    }

    app.quit();

});