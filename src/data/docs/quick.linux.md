Install Lobby In Linux

# Linux

There are two ways to install Lobby in Linux :

* [Lobby Standalone](#lobby-standalone)
* [Manual Install](#manual)

## Lobby Standalone

Lobby Standalone's Linux version is a portable version. It works in all Linux distributions that has **Bash**.

* Download the **Zip** file [from here](/api/lobby/download/linux).

* Extract the folder "Lobby" inside the Zip file to a location of your choice.

* Run the **Lobby** file or you can run the **Lobby.sh** file.

Lobby will be then opened in your default browser. Then, [finish installing Lobby](/docs/quick#configure-lobby).

### Change Host

By default, Lobby Server runs on **127.0.0.1:2020**. You can change it by opening **Lobby.sh** file and changing the first variable :

```bash
# "hostname:port" where Lobby Server should be running
host="127.0.0.1:9000"
```

## Manual

Install Apache & MySQL Server (Debian) :
```bash
sudo apt-get install apache2 mysql-server
```
Install Dependencies :
```bash
sudo apt-get install php5-mysql php5-curl php5-json libapache2-mod-php5 unzip
```

As you may know, the localhost site directory on Linux systems is 
```html
/var/www/html
```
So, we install Lobby in this directory.

In older systems, it was "/var/www". So, if you're installing on an older system, replace the "/var/www/html" location mentioned in the Lobby Docs with just "/var/www"

  * Make sure dependencies of Lobby is satisfied
  * [Download Lobby](/api/download/lobby/latest)
  * Create a Direcory named **lobby** in "/var/www/html" directory :
    ```bash
    sudo mkdir /var/www/html/lobby
    ```
    
  * Extract the downloaded Lobby Zip file into **/var/www/html/lobby**
    ```bash
    sudo unzip <path_to_lobby.zip> -d /var/www/html/lobby
    ```
  * Do the steps in the **Permissions** section below
  * Open a Web Browser and visit the URL [//localhost/lobby](http://localhost/lobby) to [configure Lobby](/docs/quick#configure-lobby)

### Permissions

After installing Lobby, change the permission of **Lobby Directory** (/var/www/lobby) to **Read & Write**.

An easy way is to do this is by the following commands :
```bash
sudo chown -R root:www-data /var/www/lobby
sudo chmod -R ug+rw /var/www/lobby
sudo chmod -R o+r /var/www/lobby
```
The above commands will make **root** the owner and sets the group as "www-data" ie web server. And the second & third command will make the owner & group have full permissions whereas others will only have read permission.

### Lobby on a Domain

Lobby can also be installed on a special domain in localhost. By using this, you can access Lobby easily from a domain. Examples :
```html
http://lobby.dev
http://lobby.localhost
http://lobby.com
```
Subin has written a tutorial on creating a localhost site :

1. [How To Create A localhost Web Site In Linux Using Apache Web Server](http://subinsb.com/linux-apache-localhost)
2. [Create a localhost Website in Ubuntu 11.04 & Up](http://subinsb.com/ubuntu-linux-create-localhost-website)
