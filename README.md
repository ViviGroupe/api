# Vivicrew backend api services

# Step 1
Download all file and put into htdocs

# Step 2
Go to your "httpd-vhosts.conf" file located in "C:\xampp\apache\conf\extra\"

$ Step 3
Add these code to bottom of your code
---------------------------------------------------------
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/TourGuide/public/"
    ServerName localhost
</VirtualHost>
---------------------------------------------------------
