# ALTS-Registration

##Information
A simple website for the marking attendance at sessions
for the Oxford University Alternative Ice Hockey Societey.
It uses php5 and sqlite3, and is inteded to be used with a
simple server like XAMPP.

Currently in alpha.

##Installation
We're using XAMPP which can be downloaded here: https://www.apachefriends.org/xampp-files/5.6.19/xampp-win32-5.6.19-0-VC11-installer.exe
Once you have it installed, run the program, then click on config on the Apache line and open "Apache (httpd.config)".
Change the file path on lines 243 & 244 to the website root folder on your computer. If you need help ask.

If you have allready have server software: Ensure that sqlite is enabled (default for php5.4 and above I think), then copy the files to the web directory
