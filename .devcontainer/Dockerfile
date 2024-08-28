# OS
FROM mcr.microsoft.com/vscode/devcontainers/javascript-node:20-bullseye

# Set Up
RUN apt-get update && apt-get install -y

# Install Angular CLI, Electron, PrimeFlex, PrimeNG en NGXS
RUN npm install -g @angular/cli \
    && npm install -g --save electron \
    && npm install -g --save primeflex primeng @ngxs/store @ngxs/devtools-plugin @ngxs/router-plugin @ngxs/storage-plugin @ngxs/cli
