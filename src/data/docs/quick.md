Install Lobby

# Quick Install

How to download & install Lobby on various systems :

* [Windows](/docs/quick/windows)
* [Linux](/docs/quick/linux)
* [Mac](/docs/quick/mac)
* [Manual Server Install](/docs/quick/manual)

## Requirements

1. Linux or Windows or Mac etc.
2. Localhost that has :
  1. Apache Web Server with :
    * Rewrite Module (mod_rewrite)
  2. MySQL 5.0 or later versions
  3. PHP version 5.6 or later with :
    * **PDO** extension
    * **cURL** extension (recommended)
    * **JSON** extension
    * **Zip** Extension
    * Output Buffering Enabled

## Installation

* Download the "lobby.zip" file from [here](/download#direct).
* Create a folder named "lobby" in your [server's document root](http://www.karelia.com/support/sandvox/help/z/Document_Root.html) folder.
* Open the Zip file and extract the contents of it to this newly created folder named "lobby"
* Open your web browser and access the "lobby" folder through web server

  Example: If your server's URL is "http://localhost", then access:
  ```html
  http://localhost/lobby
  ```
* Proceed through the installation process. In this stage, you should [configure Lobby](#configuration).

## Configuration

You will see an installation page when you first visit **Lobby** in your web browser.

Follow the instructions in the installation page to successfully configure & complete install of **Lobby** in your system.

The first step is to verify that all dependencies of Lobby is met. If everything is satisfied, a "Proceed To Installation" button will be available at the far bottom of the page. If Lobby is running on Apache Server, dependency of **mod_rewrite** module will be shown on that page.
  
### Database
  
In the second step, you have to choose what Database to use. The quickest and easiest way to finish this step is to use SQLite.

If you are installing Lobby in a **Public Server, use MySQL** (**NEVER** use SQLite).

#### MySQL

Type in the database connection information and the database name. The database will be created if it doesn't exist.

Prefix is used to make the table names. If "l\_" is used as prefix, then tables created in the database will start from "l_" :
```sql
`l_options`, `l_data`, `l_users`
```

If tables with the same "prefix" exists, then an error is shown and "config.php" file won't be created.

#### SQLite

You only need to type in the location where the ".sqlite" DB file should be created. If the file already exists, no errors will be shown and instead the existing SQLite DB will be used.
  
After Setup is finished, a message will be shown to Proceed.

Congratulations, you have successfuly installed Lobby !

Now, you can see a **config.php** file in the Lobby directory. The information you have given through the installation has been written to this file. You should keep it secure and **should only make modifications VERY carefully**.

## Common Problems

### 404 Not Found Error

Sometimes, when you visit **[//lobby.dev](//lobby.dev)**, a 404 error is shown. This is because the rules in **.htaccess** is not active. To make it active, you must edit the configuration file.

For this run the following command to edit the config file :

```
sudo nano /etc/apache2/apache2.conf
```
Search for **Directory /var/www** and under the found string replace :

```
AllowOverride None
```
with

```
AllowOverride All
```

Also, make sure the Apache **rewrite** module is active. To make it active, do : 
```
sudo a2enmod rewrite && sudo service apache2 restart
```

### 500 Internal Server Error

A server error had occured. This problem occurs due to many reasons. It occured maybe due to missing components in your system. Make sure all the dependencies of Lobby is met in your system.

For Example, if **cURL** PHP extension is not installed, this error occurs.
