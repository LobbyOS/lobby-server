<?php
namespace Lobby\App;

use Hooks;
use Lobby\App\lobby_server\Fr\LS;
use Lobby\DB;
use Lobby\UI\Panel;

class lobby_server extends \Lobby\App {

  public $lobby_version = "0.9.4";
  public $lobby_released = "2016-11-04";
  public $lobby_release_notes = '<p>Lobby 0.9.4 comes with bug fixes. <a class="btn" href="https://github.com/LobbyOS/lobby/blob/dev/CHANGELOG.md#094" target="_blank">See Changelog</a></p>';
  public $lobby_annoucement = "http://subinsb.com/lobby/version-0-9";

  public $app_categories = array(
    "accessories" => "Accessories",
    "development" => "Development",
    "games" => "Games",
    "multimedia" => "Multimedia",
  );
  public $app_sub_categories = array(
    "accessories" => array(
      "office" => "Office",
      "tools" => "Tools"
    ),
    "development" => array(
      "graphics" => "Graphics",
      "web" => "Web",
      "programming" => "Programming"
    ),
    "games" => array(
      "action" => "Action",
      "adventure" => "Adventure",
      "role-play" => "Role Play",
      "multiplayer" => "Multiplayer",
      "puzzles" => "Puzzles",
      "sports" => "Sports",
      "simulation" => "Simulation",
      "strategy" => "Strategy"
    ),
    "multimedia" => array(
      "music" => "Music",
      "photos" => "Photos",
      "video" => "Video"
    )
  );

  /**
   * @var \PDO Database handler
   */
  public $dbh;

  public function init(){
    $this->dbh = DB::getDBH();
  }

  public function page($p){
    $path = explode("/", $p);

    /**
     * Clean up Assets
     */
    \Assets::removeCSS(array(
      "theme.hine-/src/dashboard/css/scrollbar.css",
      "theme.hine-/src/dashboard/css/jquery.contextmenu.css",
      "theme.hine-/src/dashboard/css/dashboard.css",
      "theme.hine-/src/main/lib/jquery-ui/jquery-ui.css",
    ));

    \Assets::js("jqueryui", "");

    \Assets::removeJS(array(
      "theme.hine-/src/dashboard/js/scrollbar.js",
      "theme.hine-/src/dashboard/js/jquery.contextmenu.js",
      "theme.hine-/src/dashboard/js/dashboard.js",
      "app"
    ));

    /**
     * Mobile
     */
    Hooks::addAction("head.end", function(){
      echo '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" async="async" defer="defer">';
      echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
      if(\Lobby::getHostname() != "server.lo"."bby.sim"){
        echo '<script>if (window.location.protocol != "https:") window.location.href = "https:" + window.location.href.substring(window.location.protocol.length);</script>';
      }
    });

    require_once $this->dir . "/src/inc/logsys.php";

    if($path[1] === "me" && LS::$loggedIn){
      Panel::addLeftItem("me-dash", array(
        "text" => "Dashboard",
        "href" => "/me/home"
      ));
      Panel::addLeftItem("me-apps", array(
        "text" => "Profile",
        "href" => "/me/profile"
      ));
      Panel::addLeftItem("me-submit", array(
        "text" => "Submit A New App",
        "href" => "/me/app",
        "class" => "yellow"
      ));
    }else{
      Hooks::addAction("panel.top.begin", function(){
        echo '<a href="#" data-activates="panel-left" class="button-collapse"><i class="mdi-navigation-menu"></i></a>';
      });

      Hooks::addAction("panel.top.end", function(){
        echo '<ul class="side-nav" id="panel-left">
          <li><a href="/">Lobby</a></li>
          <li><a href="/apps">Apps</a></li>
          <li><a href="/download">Download</a></li>
          <li><a href="/web-readme">Demo</a></li>
          <li><a href="/docs">Documentation</a></li>
        </ul>';
        unset($leftPanelTopItems);
      });
    }

    /**
     * Add notifications
     */
    Panel::addNotifyItem("lobby-0-6-released", array(
      "contents" => "Lobby ". $this->lobby_version ." Released",
      "href" => $this->lobby_annoucement,
      "icon" => "update"
    ));
    Panel::addNotifyItem("z-new-game", array(
      "contents" => "New Site Compressor!",
      "href" => "https://lobby.subinsb.com/apps/site-compressor",
      "icon" => "new"
    ));

    if($path[1] === "docs"){
      $doc = isset($path[2]) ? str_replace("/", ".", substr_replace($p, "", 0, 6)) : "index";

      $this->menu_items();

      \Assets::removeJS("theme.hine-/src/main/js/init.js");

      return $this->inc("/src/page/docs.php", array(
        "doc" => $doc
      ));
    }elseif($path[1] === "mods"){
      $mod = isset($path[2]) ? str_replace("/", ".", substr_replace($p, "", 0, 6)) : "index";

      $this->menu_items();

      \Assets::removeJS("theme.hine-/src/main/js/init.js");

      return $this->inc("/src/page/mods.php", array(
        "doc" => $mod
      ));
    }else if($path[1] == "api"){
      $node = isset($path[2]) ? $path[2] : "index";

      return $this->inc("/src/page/api.php", array(
        "node" => $node,
        "path" => $path
      ));
    }else if($path[1] == "me"){
      $this->menu_items();
      $node = isset($path[2]) ? $path[2] : "index";

      return $this->inc("/src/page/me.php", array(
        "node" => $node,
        "path" => $path
      ));
    }else if($path[1] == "apps"){
      $this->menu_items();
      $node = isset($path[2]) ? $path[2] : "index";

      return $this->inc("/src/page/apps.php", array(
        "node" => $node
      ));
    }else if($path[1] == "u"){
      $this->menu_items();
      $node = isset($path[2]) ? $path[2] : "index";

      return $this->inc("/src/page/u.php", array(
        "user" => $node
      ));
    }else{
      $this->menu_items();
      return "auto";
    }
  }

  public function menu_items(){
    $this->addStyle("style.css");
    Panel::addTopItem("lobbyDownload", array(
      "position" => "left",
      "text" => "<span class='btn red' style='margin:0;padding: 0 10px;'>Download</span>",
      "href" => "/download"
    ));
    Panel::addTopItem("lobbyWeb", array(
      "position" => "left",
      "text" => "<span class='btn purple' style='margin:0;padding: 0 10px;'>Demo</span>",
      "href" => "/web-readme"
    ));
    Panel::addTopItem("lobbyApps", array(
      "position" => "left",
      "text" => "<span class='btn green' style='margin:0;padding: 0 10px;'>Apps</span>",
      "href" => "/apps"
    ));
    Panel::addTopItem("lobbyDocs", array(
      "position" => "left",
      "text" => "<span class='btn' style='margin:0;padding: 0 10px;'>Docs</span>",
      "href" => "/docs",
      "subItems" => array(
        "mods" => array(
          "text" => "Modules",
          "href" => "/mods"
        ),
        "install_apps" => array(
          "text" => "Install Apps",
          "href" => "/docs/install-app"
        ),
        "dev_docs" => array(
          "text" => "Developer",
          "href" => "/docs/dev"
        )
      )
    ));

    $meSubItems = array();
    if(LS::$loggedIn){
      $meSubItems["Profile"] = array(
        "text" => "My Profile",
        "href" => $this->getProfileURL(LS::$user)
      );
      $meSubItems["EditProfile"] = array(
        "text" => "Edit Profile",
        "href" => "/me/profile"
      );
      $meSubItems["SubmitApp"] = array(
        "text" => "Submit App",
        "href" => "/me/app"
      );
      $meSubItems["LogOut"] = array(
        "text" => "Log Out",
        "href" => "/me/login?logout"
      );
    }else{
      $meSubItems["LogIn"] = array(
        "text" => "Log In",
        "href" => "/me/login"
      );
    }

    Panel::addTopItem("lobbyUser", array(
      "position" => "right",
      "text" => LS::$loggedIn ? LS::getUser("display_name") : "Me",
      "href" => "/me",
      "subItems" => $meSubItems
    ));
  }

  public function download($file_name, $file_path = ""){
    if(file_exists($file_path)){
      header("Content-Disposition: attachment; filename=\"$file_name\"");
      header("Pragma: public");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

      /**
       * Resumable download
       */
      header("Accept-Ranges: bytes");

      $filesize = filesize($file_path);

      $offset = 0;
      $length = $filesize;

      if ( isset($_SERVER['HTTP_RANGE']) ) {
	      // if the HTTP_RANGE header is set we're dealing with partial content

	      $partialContent = true;

	      // find the requested range
	      // this might be too simplistic, apparently the client can request
	      // multiple ranges, which can become pretty complex, so ignore it for now
	      preg_match('/bytes=(\d+)-(\d+)?/', $_SERVER['HTTP_RANGE'], $matches);

	      $offset = intval($matches[1]);
	      $length = intval($matches[2]) - $offset;
      }else{
        $partialContent = false;
      }

      set_time_limit(0);
      $file = fopen($file_path, 'r');

      // seek to the requested offset, this is 0 if it's not a partial content request
      fseek($file, $offset);

      $data = fread($file, $length);
      fclose($file);

      if ( $partialContent ) {
	      // output the right headers for partial content

	      header('HTTP/1.1 206 Partial Content');
	      header('Content-Range: bytes ' . $offset . '-' . ($offset + $length) . '/' . $filesize);
      }
      header('Content-Length: ' . $filesize);
      header('Content-Type: ' . filetype($file_path));

      print $data;
    }else{
      echo ser("<h2>File Doesn't Exist</h2>", "The file you requested to download isn't available on the server.");
    }
  }

  /**
   * Get URL to profile
   * @param  int    $userID   User's ID
   * @param  bool   $relative Whether relative path should be returned
   * @return string           URL to profile
   */
  public function getProfileURL($userID, $relative = false){
    $sth = $this->dbh->prepare("SELECT `lobby_username` FROM `users` WHERE `id` = ?");
    $sth->execute(array($userID));

    $lobbyUsername = $sth->fetchColumn();

    if(!empty($lobbyUsername))
      return $relative ? "/u/$lobbyUsername" : $this->u("/u/$lobbyUsername");
    else
      return $relative ? "/u/$userID" : $this->u("/u/$userID");
  }

}
