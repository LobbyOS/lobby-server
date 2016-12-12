Updates

# Updates

Learn how to update Lobby and apps in it.

Lobby automatically checks for updates when any of the admin pages (admin/) are visited. This is done only once on a session even if getting response from server was failed.

You can manually check for updates by going to the Updates Page (Lobby -> Settings -> Updates) (lobby.dev/admin/update.php)

## Notification

If an update is available, an icon will appear on the top panel saying "Updates are available". When you click it, you will see the Updates page.

The Update page will show the updates available. You also have the option to once again check with the server for updates.

## Backup

Updating is a sensitive process. In case an update fails or the latest version has a bug, your data may be lost. So we recommend you do a backup before doing an update.

Go to Updates page. There you will see two buttons :

### Backup Database

The database in which Lobby is installed will be entirely extracted into an `.sql` file. This is only available for **MySQL** and for it, your **Linux/Unix** system must have the `mysqldump` package. This won't work on Windows.

For an **SQLite** database, it's easier. Just copy the `.sqlite` file at the path you chose during Lobby installation. The default location is :

```
contents/extra/lobby_db.sqlite
```

### Backup Lobby

You may also want to backup the `contents` folder and `config.php`. `contents` folder contains all your installed apps and data. The `config.php` file has your configuration.

## Updating Lobby

Lobby can be updated easily by a [one click update](#automatic-update) or you can do it [manually](#manual-update).

### Automatic Update

If a latest version of Lobby is released, the Update page will show the latest version, it's release date and release notes.

You must read the release notes before updating, because it will have any important messages to know before updating.

Here is the procedure to update Lobby :

* Do a [backup](#backup)

* Go to `Lobby Admin` -> `Updates` page.

* Click on `Check for Updates` button

* Read the release notes before updating

* Click on `Update` button

* If everything is Ok, a green button called `Start Update` will be seen. Click on it to start the update

If the update is interrupted (during download), you can start again by doing the same steps above.

### Manual Update

* Do a [backup](#backup)

* Get the [latest Lobby server version](http://server.lobby.sim/download#direct)

* Unpack the zip file that you downloaded to a temporary location

* Open the **Lobby folder** where the `config.php` file exists. If you're using Lobby Standalone, the folder will be called `lobby`

* Delete the old `includes` and `admin` folders in your **Lobby folder**

* Copy the new `includes` and `admin` directories to this **Lobby folder**, in place of the previously deleted directories

* Upload the individual files from the new `contents` folder to your existing `contents` folder in **Lobby folder**, overwriting existing files

  Do NOT delete your existing `contents` folder. Do NOT delete any files or folders in your existing `content` directory (except for the one being overwritten by new files).

* Upload all new loose files from the root directory of the new version in temporary location to your existing **Lobby folder**