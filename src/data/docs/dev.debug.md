Debugging

# Debugging

Debugging is disabled by default. To enable it, you must set the ["debug" setting in the config file](/docs/config#DebugSetting).

Log files are situated in the **contents/extra** directory with extension as **.log**. The log files are written by the **Lobby** class.

You can add a log entry by calling :

```php
\Lobby::log($message, $filename = "lobby.log");
```

The default file is "lobby.log". Your app shouldn't call this function, instead it should call :

```php
\Lobby\App::log($message); // $this->log() in App Files
```

All app's log file is named "apps.log"
