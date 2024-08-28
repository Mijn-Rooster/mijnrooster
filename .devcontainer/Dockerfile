# Gebruik een Node.js 20-basisafbeelding
FROM mcr.microsoft.com/vscode/devcontainers/javascript-node:20-bullseye

# Installeer Angular CLI, Electron, PrimeFlex, PrimeNG en NGXS
RUN npm install -g @angular/cli \
    && npm install -g electron \
    && npm install --save @angular-builders/custom-webpack \
    && npm install --save primeflex primeng @NGXS/store @NGXS/devtools-plugin @NGXS/router-plugin @NGXS/storage-plugin

# Installeer noVNC en andere vereisten
RUN apt-get update && apt-get install -y \
    supervisor \
    novnc \
    websockify \
    xfce4 \
    xfce4-goodies \
    xorg \
    dbus-x11 \
    x11vnc \
    --no-install-recommends && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Installeer VNC server
RUN mkdir -p /var/run/dbus && \
    echo "dbus-launch xfce4-session" > /root/.xsession && \
    x11vnc -storepasswd 1234 ~/.vnc/passwd

# Configuratiebestand voor supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose the VNC port
EXPOSE 5900
