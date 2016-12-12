Creating Apps

# Creating An App

Creating apps for Lobby is simple as a piece of cake if you're a PHP Programmer having knowledge of OOP.

## App ID

The first task in making an app is to find out a name & ID. If you want to upload your app to the Lobby Server, it's required that your app ID should be a unique one.

An app is recoginized by this unique **ID**. Once it is set, it can't be changed. So, choose wisely.

The title of the app can be anything you wish. But the App ID should be a unique one.

Here are the conditions of the app ID :

- Must contain only alphabetic characters
- Lowercase characters
- The "**-**" character is the only allowed special character.

You can search Lobby to check if the App ID exist. Simply go to
```
http://lobby.subinsb.com/apps/myAppID
```
to see if the App Exist. Example : **[//lobby.subinsb.com/apps/ledit](/apps/ledit)**

## Structure

Apps are stored inside **lobby/contents/apps**. Your app files must be in a folder with the name of App ID.

Example : The App **lEdit** have it's contents in **ledit/contents/apps/ledit** directory.

Here are the steps to set up your app :

- Create a folder in **lobby/contents/apps**
- Create a `manifest.json` and `App.php` file in the App Folder you just created.
- Setup the [`manifest.json`](/docs/dev/app/manifest.json) and [`App.php`](/docs/dev/app/App.php) files.

Pages, images etc. of your app should be stored in a folder called `src` inside your app.

Here is how it should look like :

| File/Folder | Sub File/Folder | Description |
| ------------------------------------------------------------------- |
| [manifest.json](app/manifest.json) | | The Manifest File |
| [App.php](app/App.php) | | The Core File of App |
| src | | The folder containing resources of app |
| | ajax | Contains handlers for AJAX requests |
| | css | Contains stylesheets to be used via [`\Lobby\App::addStyle()`](/docs/dev/api/) |
| | image | Contains image files for app. The App's logo (**logo.png**) is inside this folder |
| | inc | Contains PHP libraries, class files to be used by other PHP files inside app |
| | js | Contains JavaScript files to be used via [`\Lobby\App::addScript()`] |
| | page | [Pages of app](app/pages) is in this file |

Note that the above table is not final and you have the freedom to add new directories and files in to it.

### manifest.json & App.php

See [this page](app/manifest.json) to read about the **manifest.json** file.

See [this page](app/App.php) to read about the **App.php** file.

## Submitting App

You can request for adding your app in to the Lobby Database by emailing with the **subject** "Lobby - App Review" and add an attachment of the App's source code in **.zip** Archive File Format.

The address for emailing can be found [here](/docs/contact).

Another way is to upload your App code to your **GitHub** Repository and tell us the link of repository through [Lobby's GitHub Page](https://github.com/subins2000/lobby/issues). The issue must have the label "appSubmission".

# F.A.Q

1. How is **APP_URL** different from **APP_SOURCE** ?

	Suppose you want to display an image in your app of the file name "logo.png". If you use **APP_URL**, the URL would be **lobby.dev/app/myAppID/logo.png** which would results in a 404 page, because you haven't defined it in the app's _page_ function.
	
	But, if you use **APP_SOURCE**, the URL would be **lobby.dev/contents/apps/myAppID/logo.png** which would result in the actual image file. With this, you can directly access the files of your apps.
	
2. Is it necessary for my App to have a License ?

	Yes, since the App will be directly available to everyone, it's a good idea to put in a license. There are many [licenses compatible with the GPL license](http://www.gnu.org/licenses/license-list.html#GPLCompatibleLicenses) which Lobby is using.
