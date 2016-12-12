Install Lobby In Windows

# Windows

There are two ways to install Lobby in Windows :

* [By installing Lobby Standalone](#lobby-standalone)
* [Manual Install](#manual)

## Lobby Standalone

* Download the **Zip** file [from here](/api/lobby/download/windows).

* Extract the folder "Lobby" inside the Zip file to a location of your choice.

That's it. Now you should [run Lobby](#running-lobby).

### Running Lobby

Run the "Lobby.exe" file in the "Lobby" folder. If running `Lobby.exe` didn't do anything, run the `lobby.bat` file.

PHP that is inside Lobby will sometimes make the following error upon starting :
```
msvcr110.dll is missing
```
or
```
VCRUNTIME140.dll is missing
```

This is because **Visual C++** is not installed in your system. Download the `vc_redist.x86.exe` installer file from [Microsoft's website](https://www.microsoft.com/en-in/download/details.aspx?id=48145) and install it. It's only about **13MB**.

**IMPORTANT** - Even if your system is 64 bit, please download and install the **x86** version of Visual C++. This is because Lobby Windows Standalone is compiled in x86 architecture.

If you still didn't get Lobby working after installing Visual C++, read [this](http://stackoverflow.com/questions/30811668/php7-missing-vcruntime140-dll) and [this](http://stackoverflow.com/questions/34215395/wamp-wont-turn-green-vcruntime140-dll-error).

<!--
When Lobby.exe is ran, an icon with [Lobby favicon](/favicon.ico) can be see in the tray area (Notification area) :

![Lobby Windows Tray Icon](/contents/apps/lobby-server/src/image/screenshots/windows/tray.png)

-->

Now, run **Lobby.exe** again. The Lobby Server (PHP) will now start running.

By default, the server will be running on [`127.0.0.1:2020`](http://127.0.0.1:2020), so Lobby can be accessed by going to that [URL](http://127.0.0.1:2020) :

![Lobby Running On Windows](/contents/apps/lobby-server/src/image/screenshots/windows/running.png)

<!--
If you want to stop the PHP server and exit Lobby, right click on the tray icon and choose "Exit" :

![Lobby Tray App](/contents/apps/lobby-server/src/image/screenshots/windows/tray-open.png)
-->

You can stop the Lobby Server by running "Stop Lobby Server.exe" file in the "Lobby" folder.

When Lobby is opened in browser, an installation dialog will be seen. To further complete installation, [see this](/docs/quick#configure-lobby).

#### Change Host

You can change the host where Lobby server should listen. The default is **127.0.0.1:2020**. To change it, open **lobby.ini** file inside the "Lobby" folder and change the `host` property under `LobbyServer` :

```ini
[LobbyServer]
host = "127.0.0.1:9000"
```

## Manual Install 

The following localhost servers are available for Windows :

* [XAMPP](http://sourceforge.net/projects/xampp/)
* [WAMP](http://sourceforge.net/projects/wampserver/)
* [MAMP](http://sourceforge.net/projects/mamp/)
* [AMMPS](http://sourceforge.net/projects/ampps/)

If you don't have a Web Server, install any of the above (or something else).

Once the server and the [requirements](/docs/quick) are installed, [Download Lobby](/api/download/lobby/latest).

Open the "www" (Web root) folder of the server and create a folder called "lobby" in it.

Extract the contents of the downloaded Lobby Zip file into the "lobby" folder created just before.

Open a Web Browser and visit the URL [//localhost/lobby](http://localhost/lobby) to [install Lobby](/docs/quick#configure-lobby).
