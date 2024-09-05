
# Frontend

The frontend is the software what is running on the Mijn Rooster kiosk devices. \
It is based on Svelte and Electron.




## Start developing

Open the frontend folder

```bash
  cd frontend
```

### Installation
This is not needed when running in codespaces.

```bash
  npm install
```

### Start

```bash
  npm start
```

The application will now be opened in fullscreen. When you are using codespaces, you'll have to connect to the noVNC viewer. Go to _Ports_ > _desktop (6080)_ and open the _Forwarded Address_ in your browser.

### Build

```bash
# For windows
$ npm run build:win

# For macOS
$ npm run build:mac

# For Linux
$ npm run build:linux
```
    