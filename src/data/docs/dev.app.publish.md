Publish

# Publish App

## Git Repository

You should create a **git** repository (repos) for storing your app's source files. `Free git repos` are provided by these sites :

* [GitHub](https://github.com)
* [Bitbucket](https://bitbucket.org/)
* [GitLab](https://gitlab.com/)

There are more free as well as paid services where you can host your app's source.

Once you set up a repo, [push your app's source code into it](https://guides.github.com/introduction/getting-your-project-on-github/).

## Branches

It's recommended you create another branch for developing. `dev-master` branch is recommended for pushing your code in development. This command will create the branch :
```
git checkout -b dev-master
```

* When your app is in a stable state, you should make it a version. Do this by changing the `version` parameter in your `manifest.json` file.
* Merge your stable code from `dev-master` into `master` branch
  ```bash
  git checkout master
  git merge -no-ff dev-master
  ```
* Push your changes
  ```bash
  git push
  ```

## Tags

It is **highly recommended** you use tags for each versions. Say you are in the version 0.1 and is going to release 0.2. After you push the 0.2 code into `master`, you should tag the current state by the name `v0.2` and push it :

```html
git tag v0.2
git push origin v0.2
```

In this way, you will have different tags for different versions :

* v0.3
* v0.2
* v0.1.1
* v0.1

**NOTE** : If both `master` branch and tags are present, then tags are given preference above the branch. The tag with the greatest number is taken as the latest version.

If there are no tags in the repo, the `master` branch will be used as latest version.

## Submit

Once you have set up the repo and have a working code, you may submit it to **Lobby** by :
* [Create an account on Lobby](/me)
* Go to [Submit App page](/me/app), fill in the details of your app and submit

Once you submit, the moderators of Lobby are notified and will look into your app. You will be further contacted by Lobby moderator(s) with instructions on how to proceed.

## Updating

When your app on Lobby needs to be updated, just push the changes into your git repo. If it's a new version, don't forget to make the tag and push it too.

Lobby will update your app within an `hour`. If you want to update it immediately, you should go to [your app's admin page](/me/home) and click the `Update` button.


## How Apps Are Managed

App source code is with the authors in their repositories. When their app is approved, the source code is referenced by the `git url` in Lobby.

Lobby periodically checks for source code updates in the git repositories of apps every **hour**. When a new tag is found or when a new commit was found on the `master` branch, Lobby will get the full source code an make it into a `zip` file. This `zip` file is uploaded to a remote server and it's link is updated in the apps table inside Lobby Server.

When users open Lobby, they are notified of the app updates and when they download, the `zip` file stored in the remote server is downloaded.
