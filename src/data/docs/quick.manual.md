Manual Install

# Manual Install

You can install Lobby without using the automatic installer. This is done by :

* Extracting Lobby files
* Creating **config.php** file
* Creating tables in Database

## Extract Lobby

* Download the latest version from [here](/download#direct).
* Create a folder named "lobby" in your [server's document root](http://www.karelia.com/support/sandvox/help/z/Document_Root.html) folder.
* Open the Zip file and extract the contents of it to this newly created folder named "lobby"
* Make the permissions of the folder writable.

  On \*NIX, this would be :
  ```bash
  chmod 0775 lobby -R
  ```

## Create `config.php`

Copy the `config-sample.php` to the same directory and rename the copied file as `config.php`. Edit the file and fill the array values.

You can see detailed information about [`config.php` here](/docs/config).

## Create Tables

The SQL code for creating tables is different for **MySQL** and **SQLite**.

The default table name prefix used is `l_` in both SQL codes. You may change it, but you should change it in the `config.php` file too.

### MySQL

Connect to your database and execute the following **SQL** code :

```sql
CREATE TABLE IF NOT EXISTS `l_options` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(64) NOT NULL,
    `value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `l_data` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `app` varchar(50) NOT NULL,
    `name` varchar(150) NOT NULL,
    `value` longblob NOT NULL,
    `created` datetime NOT NULL,
    `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
```

### SQLite

Using a SQLite client, connect to the database file and execute this **SQL** code :

```sql
CREATE TABLE IF NOT EXISTS `l_options` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `name` varchar(64) NOT NULL,
    `value` text NOT NULL
);
```

```sql
CREATE TABLE IF NOT EXISTS `l_data` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `app` varchar(50) NOT NULL,
    `name` varchar(150) NOT NULL,
    `value` blob NOT NULL,
    `created` datetime NOT NULL,
    `updated` datetime NOT NULL
);
```
The SQL code for the two tables are separated because, some SQL clients doesn't allow multiple queries to be executed at a single time.
