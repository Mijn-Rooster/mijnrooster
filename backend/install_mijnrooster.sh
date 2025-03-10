#!/bin/bash

# Update package list and install Apache2, PHP, and PHP-curl
sudo apt-get update
sudo apt-get install -y apache2 php php-curl unzip

# Enable Apache mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2

# Navigate to the web server root directory
cd /var/www/html

# Download the mijnrooster/backend/api folder from GitHub
wget https://github.com/Mijn-Rooster/mijnrooster/archive/refs/heads/main.zip

# Unzip the downloaded folder
unzip main.zip
rm main.zip

# Move the api folder to the Apache server directory
mv mijnrooster-main/backend/api/* .

# Set proper permissions
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html

# Ensure PHP scripts can create directories
sudo find /var/www/html -type d -exec chmod 775 {} \;

# Restart Apache to apply changes
sudo systemctl restart apache2

echo "Installation complete. The mijnrooster project is now set up on your Apache server."
