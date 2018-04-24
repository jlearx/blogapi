# blogapi
## Blog API

By: Jacob Lear

Contact: jacob@paradisent.com

Github: https://github.com/jlearx/

## Description 
This API provides basic blog functionality. Specifically, it provides the
ability to fetch all blog posts from the database and the ability to insert a new blog
post to the database.

## Usage
The PHP SQLite3 module (**php-sqlite3**) must be installed and enabled for the database to work.
This can be done on Ubuntu/Debian with: 

`sudo apt-get install php-sqlite3 php7.0-sqlite3 sqlite3`

*Note: You may need to restart the web server or php-fpm (if applicable) after installing this.*

If using Apache, the API requires the use of a .htaccess file, or alternatively you can insert the lines 
directly into your virtual host file. Either way, you must have **mod_rewrite** installed and enabled
in Apache.

For Apache 2.x, this can be done by: 
`sudo a2enmod rewrite`

*Note: You will need to restart apache2 after enabling this.*

If using .htaccess, be sure AllowOverride is set in the virtual host file.

See the Apache 2.x documentation for more info: 
https://httpd.apache.org/docs/2.4/mod/core.html#allowoverride

#### .htaccess file contents
    <IfModule mod_rewrite.c>
      RewriteEngine On
      RewriteCond %{REQUEST_FILENAME} !-f
      RewriteCond %{REQUEST_FILENAME} !-d
      RewriteRule blogapi/(.*)$ blogapi/api.php?request=$1 [QSA,NC,L]
    </IfModule>

The .htaccess file should be located in the same directory containing the blogapi directory.
That is, it should NOT be inside the blogapi directory.
In my setup, the .htaccess file, blogapi directory, and example.php (renamed to index.php)
were inside the website root directory.

An example of how to use the API is included as example.php in the blogapi directory.
You should copy this file to the parent directory (e.g. website root directory) if you would
like to use it. You can rename it to index.php if you like.

The CallAPI() function in example.php requires the PHP Curl module (**php-curl**) to be installed 
and enabled in PHP.

In Ubuntu/Debian this can be done with: 
`sudo apt-get install php-curl`

*Note: You may need to restart the web server or php-fpm (if applicable) after installing this.*

## Credits
CallAPI() code from:  https://stackoverflow.com/questions/9802788/call-a-rest-api-in-php

RESTful API code based on: http://coreymaynard.com/blog/creating-a-restful-api-with-php/
