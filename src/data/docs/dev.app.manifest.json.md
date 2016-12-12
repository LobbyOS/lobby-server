Manifest File

# manifest.json

The information about the app is stored as [JSON](http://en.wikipedia.org/wiki/JSON) format in this file.

## Location

This file should be created at the root of app directory.

## Items

The manifest file should have the following items :

| Key | Value
| ---- | -----
| [name](#name) | App's name
| [short_description](#short_description) | A one line short description of app. Must not be more than 50 characters
| [category](#category) | The category in which the app belong.
| [sub_category](#sub_category) | The sub category of the category.
| [version](#version) | The version of app.
| [require](#require) | Requirements of app
| [author](#author) | The Author name
| [author_page](#author_page) | The App Author's Web Page URL
| [app_page](#app_page) | The official URL of the App
| [logo](#logo) | Whether the app has a logo. Default : `false`

### name

The name of your app. Should be related to your app ID. Otherwise, it doesn't make any sense and makes user confused.

### short_description

A one line description will make it easy for the user to understand about the app. Here are some examples :

```html
lEdit - The Default Text Editor of Lobby
```
```html
diary - A Diary for your thoughts
```

The maximum characters for the short description is **100**.

### category

Lobby uses categories to quickly identify the type of app.

Only some categories are currently accepted. They are :

| Category    | Description
| -------     | -----------
| accessories | For Apps like text editors, calculator etc...
| development | Apps for programmers, developers, coders etc...
| games       | For Game Apps
| multimedia  | For Apps associated with audio, video, pictures and other media files

Your app should only be in one category

### sub_category

Subcategories helps to find an app in depth. Only some main categories have subcategories.

It is not necessary for your app to have a sub category, but it will help to quickly find apps for users.

| Category    | Sub Category| Description + Examples
| --------    | ------------| -----------
| accessories | office      | Office related apps <br/> Calculator
|             | tools       | Extra Tools <br/> Text Editors
|             | security    | Security related apps <br/> Password Manager
| development | graphics    | Apps for graphic designers <br/> Image editing softwares, etc...
|             | web         | For Web Developers <br/> HTML, CSS, JS, jQuery etc...
|             | programming | Apps related to Programming Languages <br/> Python, Java, C++ etc...
| games       | arcade      | Arcade games
|             | multiplayer | Multiplayer games
|             | puzzles     | Puzzle games
|             | sports      | Sports games
| multimedia  | music       | Apps associated with audio
|             | photos      | Photo gallery apps etc.. associated with photos/images
|             | video       | Video Apps <br/> Video Editor, Movie Maker etc...

Your app should only be in one category

### version

Every App is required to have a version number. The version value must be numeric characters with "**.**" as an optional character.

| Can Be | Can't Be
| ------ | --------
| 0.1    | 1.4889
| 0.5    | 1-5
| 1      | 2014-58.45
| 1.0    | one.0
| 5.25   | mi45

### require

You can mention your app's dependencies in this property as a JSON object. Example :

```json
"require" : {
  "lobby" : ">=0.9",
  "curl" : ">=7.0.25"
}
```

These are the supported dependencies :

* lobby
* curl
* All supported params for phpversion()

### logo

The logo should be saved as `logo.svg` or `logo.png` in `src/image` folder. If the app has such a file, use the value `true` for this item.

Default value is `false`.

## Plain

Here is the JSON data for you to fill :

```json
{
  "name" : "",
  "short_description" : "",
  "category" : "",
  "sub_category" : "",
  "version" : "0.1",
  "author" : "",
  "author_page" : "",
  "app_page" : ""
}
```

## Example

Here is a sample manifest file of the **lEdit** app :

```php
{
  "name" : "lEdit",
  "short_description" : "The Default Text Editor of Lobby",
  "category" : "accessories",
  "sub_category" : "tools",
  "version" : "0.1",
  "author" : "Lobby",
  "author_page" : "http://lobby.subinsb.com",
  "app_page" : "http://lobby.subinsb.com/apps/ledit"
}
```
And here is the `src/image/logo.png` file :

![lEdit Logo](https://lobby.subinsb.com/api/app/ledit/logo)

[See full source code of **lEdit**](https://github.com/LobbyOS/app-ledit).
