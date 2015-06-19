# ALTS-Registration

##Information
A simple website for the marking attendance at sessions 
for the Oxford University Alternative Ice Hockey Societey.
It uses php5 and sqlite3, and is inteded to be used with a
simple server like mongoose.

Currently in alpha.

##Installation
If you do not have server software allready, I am aiming for this to work with Cesanta Software's Mongoose:
1.  Visit http://cesanta.com/mongoose.shtml and download Windows Mongoose + PHP Package
2.  Extract the files to a foler
3.  Open the folder named along the lines of php-5.X.X
4.  Find the file names php.ini-development
5.  Rename it to php.ini
6.  Open the file and uncomment (remove the leading ;): line 721 - extension_dir = ext, line 885 - extension=php_pdo_sqlite.dll, line 896 - extension=php_sqlite3.dll
7.  Save the file, ensuring the name is php.ini
8.  Go back to the main folder
9.  Copy the contents of the Repositry into the web_root folder
10. Go back to the main folder and double click the mongoose application.

If you have allready have server software: Ensure that sqlite is enabled (default for php5.4 and above I think), then copy the files to the web directory