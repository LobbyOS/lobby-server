Lobby Developer Introduction

# Introduction

* [Apps](#apps)
* [Lobby](#lobb)
* [Standards](#standards)

## Apps

Creating Apps for Lobby is really easy. You have the full control over the app, but with some restrictions :

- DON'T BE **EVIL**

  A Lobby app can access the user's system. So, user's privacy must be respected.
- Any damage caused to user because of your app will cause termination of your app and account.

## Lobby

The Lobby tree will look something like this :
- Lobby
	- [dir]  admin
	- [dir]  contents
	- [dir]  includes
	- config-sample.php
	- index.php
	- load.php
	- lobby.json

This directory structure is like **WordPress**, but not exactly like it.

The Apps, themes and User created stuff goes in the _contents_ directory. Here is the `contents` directory tree :

- contents
	- [dir] apps
	- [dir] extra
  - [dir] modules
  - [dir] themes
	- [dir] update

`apps` folder is where Apps are stored.

`extra` contains extra stuff like logs. It is also the default location where SQLite database is stored.

`themes` is where Themes are installed

`update` folder is a temporary location for all downloaded files. The app.zip (Archive) File and zip files of new versions of Lobby is downloaded to here.

## Standards

While creating apps or making a change in Lobby core, please follow these guidelines :

- Indentation of 2 spaces, not tabs
- Use `<?php`, not `<?`
- Document most of what you code
- Make code readable
- Give priority to performace
  
  * Even usage of `===` instead of `==` can increase performace.
  * Do not overdo things
- Filenames should be lowercase unless it is a PHP class file.
  
    If it is a file that contains a class, the name of the file should be the same as that of class.
    Example : The class 'FileSystem' would be in a file 'FileSystem.php'
- Short names are used for folder names :

  | Short name | Full name | Descripton |
  | ----- | ---- | ---- |
  | css | CSS | CSS files used in pages is in here |
  | inc | includes | External files that are included. Eg: 'url.php' for parsing URLs used by other scripts |
  | lib | library | Contains standalone libraries. Eg: jQuery, Bootstrap |
  | js | JavaScript | JavaScript files used in pages is in here |
  | src | source | The source files goes in this. |
