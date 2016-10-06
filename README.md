# ALTS-Registration

##Information
A simple website for the marking attendance at sessions
for the Oxford University Alternative Ice Hockey Societey.
It uses php5 and sqlite3, and is inteded to be used with a
simple server like XAMPP.

Currently in alpha.

##Installation
We're using XAMPP which can be downloaded [here](https://www.apachefriends.org/xampp-files/5.6.19/xampp-win32-5.6.19-0-VC11-installer.exe).
Once you have it installed, run the program, then click on config on the Apache line and open "Apache (httpd.config)".
Change the file path on lines 243 & 244 to the website root folder on your computer. If you need help ask.

If you have allready have server software: Ensure that sqlite is enabled (default for php5.4 and above I think), then copy the files to the web directory

You can use the software from multiple computers at once. The steps are:

1. Open the server on computer one.
2. Connect computer one to a network.
3. On computer one press `WIN+R` then type `cmd` then press enter.
4. Type `ipconfig` and press enter.
5. Find the network you are using from the list shown. Read the IP of your computer. It should be of the form `192.168.X.X`.
6. On any other computers you want to use(This will also work for tablets). Connect them to the same network as laptop one. Open a browser and put the IP of computer one in the URL bar.
