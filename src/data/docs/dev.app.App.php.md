App.php File

# App.php

A file named **App.php** should be in the root directory of your app along with **manifest.json**.

When the user requests for anything in your app, Lobby interacts with this file to obtain what the user wants.

## Class Naming

This file contains a **class** with the name of your app's ID. It is **required** that the class name should be the same as the App ID.

In case there's a `-` (minus) character in your App ID, then when naming the class, replace it to "_" (underscore). Because including `-` denotes subtraction and would cause a fatal PHP error.

## Inside The Class

The class should be the child class of **\Lobby\App** and under the namespace **Lobby\App**. Here is a basic App.php file :

```php
<?php
namespace Lobby\App;

class myAppID extends \Lobby\App {
  public function page($page){
    return "auto";
  }
}
```

These are the conditions of a valid App class :

* The [same name as App ID](#class-naming)
* A child class of `\Lobby\App`
* Should be under the namespace `Lobby\App`

## Handling Requests

When the user requests for a page of your app such as "//lobby.dev/app/myAppID/page", Lobby will call the **myAppID::page()** function with parameter value ($page) as the page's path name.

Examples of parameter value :

| URL | Parameter Value |
| --------------------- |
| //lobby.dev/app/myAppID/create | "/create" |
| //lobby.dev/app/myAppID/weight/kilo | "/weight/kilo" |
| //lobby.dev/app/myAppID/url?c=subinsb.com | "/url" |

The return value of **myAppID::page()** should be **HTML** code. If you want to return a **PHP** file as output, use **myAppID::inc($path)** as the return value. What this function does is execute the PHP file and return the processed output of the PHP file. Just like a normal PHP page.

If the **myAppID::page()** function returns "auto", then Lobby will look for the page file in "src/page" folder of your app's directory.

Examples of lookup in src/page : 

| URL | Path Location in App Directory |
| --------------------- |
| //lobby.dev/app/myAppID/create | "/src/page/create" |
| //lobby.dev/app/myAppID/weight/kilo | "/src/page/weight/kilo" |
| //lobby.dev/app/myAppID/url?c=subinsb.com | "/src/page/url" |

Here is a small example on how to respond with the requests got :

```php
namespace Lobby\App;

class myAppID extends \Lobby\App {
	public function page($page){
		if( $page == "/" ){
			return "<h2>Requested the App's Index Page</h2>";
		}elseif ( $page == "/subinsiby" ){
			return $this->pageSubin();
		}
	}
	
	public function pageSubin(){
		return "<p>This is Subin's Page.</p>";
	}
}
```

Here is an example of including an other file as output :

```php
public function pageSubin(){
	return $this->inc("/src/inc/subin_page.php");
}
```

## Constants

For easability, there are some **constants** for your app which can be used in **App.php** file :

| Constant | Value | Example
| -------- | ----- | -------
| $this->dir | The absolute path to the App Directory.	| /var/www/lobby/contents/apps/ledit
| APP_URL | The App's URL. | http://localhost/lobby/app/ledit
| APP_SRC	| The App's Source URL. | http://localhost/lobby/contents/apps/ledit
