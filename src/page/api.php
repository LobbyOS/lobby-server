<?php
require_once $this->dir . "/src/inc/LobbyStats.php";

$Stats = new LobbyStats();

$client = array(
  "version" => isset($_POST["lobby"]) ? $_POST["lobby"]["version"] : 0
);

/**
 * List of available downloads
 */
$lobby_downloads = array(
  "linux" => "BSMk6No0RZ8qZhq",
  "windows" => "iRXEoTSvKYvdEmZ",
  "windows64" => "auRSvesJ44I8mPi",

  // "script" => "lobby-install.sh",
  // "deb" => "lobby.deb",
  // "msi" => "https://raw.githubusercontent.com/LobbyOS/windows-installer/master/LobbyInstaller/Lobby.msi",

  "0.1" => "0.1.zip",
  "0.1.1" => "0.1.1.zip",
  "0.2" => "0.2.zip",
  "0.2.1" => "0.2.1.zip",
  "0.3" => "0.3.zip",
  "0.4" => "0.4.zip",
  "0.4.1" => "0.5.zip", // Legacy. I screwed up
  "0.5" => "0.5.zip",
  "0.5.1" => "0.5.1.zip",
  "0.6" => "qVHNgLfOxeVoLMd",
  "0.7" => "fJY9iGB5ffCX6HB",
  "0.8" => "H8rRxEM2aX9KmkU",
  "0.9" => "qqmExNJadhByFjy",
  "0.9.1" => "r6HJa4RjNs1HMVx",
  "0.9.2" => "h7HLaq8UFHiHos4",
  "0.9.3" => "wK6sjJCEsnmOsVA",
  "0.9.4" => "J6JPxQBv3N12Jyd"
);

function getDownloadURL($id, $lobby_downloads){
  if(strlen($lobby_downloads[$id]) === 15){
    /**
     * ownCloud URL
     */
    return "https://sky-phpgeek.rhcloud.com/index.php/s/{$lobby_downloads[$id]}/download";
  }else{
    return "https://googledrive.com/host/0B2VjYaTkCpiQM0JXUkVneFZtbUk/{$lobby_downloads[$id]}";
  }
}

if($node === "dot.gif"){
  header("Content-type: image/gif");
  include $this->dir . "/src/image/blank_dot.gif";
}else if($node === "lobby" && isset($path[3])){
  $what = $path[3];
  $version = isset($path[4]) ? $path[4] : "";

  if($what === "download"){
    $version = $version == "latest" ? $this->lobby_version : $version;

    if(isset($lobby_downloads[$version])){
      /**
       * Stats
       */
      $sql = \Lobby\DB::getDBH()->query("SELECT `value` FROM `lobby` WHERE `key_name` = 'downloads'");
      $lobby_data = json_decode($sql->fetchColumn(), true);

      $statVersion = $version;
      if($version === "windows" || $version === "linux"){
        $statVersion = $version . "-{$this->lobby_version}";
      }

      if(!is_array($lobby_data)){
        $lobby_data = array(
          $statVersion => 0
        );
      }
      $lobby_data[$statVersion] = isset($lobby_data[$statVersion]) ? $lobby_data[$statVersion] + 1 : 1;

      $sql = \Lobby\DB::getDBH()->prepare("UPDATE `lobby` SET `value` = ? WHERE `key_name` = 'downloads'");
      $sql->execute(array(json_encode($lobby_data)));

      /**
       * Download
       */
      header("Location: " . getDownloadURL($version, $lobby_downloads));
    }
  }else if($what === "updates"){
    /**
     * Don't mess with the response
     */
    $response = array(
      "version" => $this->lobby_version,
      "released" => $this->lobby_released,
      "release_notes" => $this->lobby_release_notes,

      /**
       * Note that Lobby client will prepend "lobby_server_msg_" to item IDs
       */
      "notify" => array(
        "items" => array(
          "site-compressor" => array(
            "contents" => "Compress your sites with the new Site Compressor!",
            "href" => "/admin/lobby-store.php?app=site-compressor"
          )
        ),
        /**
         * Only values, no keys
         */
        "remove_items" => array("amoebam", "site-compressor")
      )
    );

    if(isset($_POST['apps'])){
      $apps = $_POST['apps'];
      if(preg_match("/\,/", $apps)){
        $apps = explode(",", $apps);
      }else{
        /**
         * Only a single app is present
         */
        $apps = array($apps);
      }

      foreach($apps as $app){
        $sql = \Lobby\DB::getDBH()->prepare("SELECT `version`, `requires` FROM `apps` WHERE `id` = ?");
        $sql->execute(array($app));

        if($sql->rowCount() != 0){
          if($client["version"] >= "0.9.3"){
            $r = $sql->fetchAll(PDO::FETCH_ASSOC);
            $response["apps"][$app] = array(
              "require" => json_decode($r[0]["requires"], true),
              "version" => $r[0]["version"]
            );
          }else{
            $response["apps"][$app] = $sql->fetchColumn(0);
          }
        }
      }
    }
    echo json_encode($response);
  }else if($what === "installation-id"){
    function randStr($length){
      $str = "";
      $chars='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
      $size=strlen($chars);
      for($i = 0; $i < $length; $i++){
        $str.=$chars[rand(0, $size-1)];
      }
      return $str;
    }
    $lobbyID  = randStr(10) . randStr(15) . randStr(20); // Lobby Global ID
    $lobbySID = hash("sha512", randStr(15) . randStr(30)); // Lobby Secure ID
    ?>
    <html>
      <head></head>
      <body>
        <pre><code><?php
          echo "lobbyID - $lobbyID<br/>";
          echo "lobbySID - $lobbySID";
          ?></code></pre>
      </body>
    </html>
<?php
  }
}else if($node === "app" && isset($path[3]) && isset($path[4])){

  $appID = $path[3];
  $what = $path[4];
  if($what === "logo"){
    $sql = \Lobby\DB::getDBH()->prepare("SELECT `logo`, `git_url`, `cloud_id` FROM `apps` WHERE `id` = ?");
    $sql->execute(array($appID));

    if($sql->rowCount() === 0){
      echo "error : app doesn't exist";
    }else{
      require_once __DIR__ . "/../inc/LobbyGit.php";
      $r = $sql->fetch(\PDO::FETCH_ASSOC);

      $lg = new LobbyGit($appID, $r["git_url"], $r["cloud_id"]);
      $lg->logo($r['logo']);
    }
  }else if($what === "download"){
    $sql = \Lobby\DB::getDBH()->prepare("SELECT `git_url`, `cloud_id` FROM `apps` WHERE `id` = ?");
    $sql->execute(array($appID));

    if($sql->rowCount() === 0){
      echo "error : app doesn't exist";
    }else{
      require_once __DIR__ . "/../inc/LobbyGit.php";
      $r = $sql->fetch(\PDO::FETCH_ASSOC);

      $sql = \Lobby\DB::getDBH()->prepare("UPDATE `apps` SET `downloads` = `downloads` + 1 WHERE `id` = ?");
      $sql->execute(array($appID));

      $lg = new LobbyGit($appID, $r["git_url"], $r["cloud_id"]);
      $this->download("lobby-app-$appID.zip", $lg->download());
    }
  }

}else if($node === "ping"){
  echo "pong";
}else if($node === "apps"){
  $get = Request::get("get");
  $p = Request::get("p");
  $q = Request::get("q");
  $lobby_web = Request::get("lobby_web") != null;

  if($p === null){
    $start = 0;
    $stop = 6;
  }else{
    $start = ($p - 1) * 6;
    $stop = (($p - 1) * 6) + 6;
  }

  $append = array();
  if($get === "newApps"){
    if($lobby_web){
      $query = "SELECT * FROM `apps` WHERE `lobby_web` = '1' ORDER BY `updated` DESC";
      $total_query = "SELECT COUNT(*) FROM `apps` WHERE `lobby_web` = '1'";
    }else{
      $query = "SELECT * FROM `apps` ORDER BY `updated` DESC";
      $total_query = "SELECT COUNT(*) FROM `apps`";
    }
  }else if($get === "popular"){
    if($lobby_web){
      $query = "SELECT * FROM `apps` WHERE `lobby_web` = '1' ORDER BY `downloads` DESC";
      $total_query = "SELECT COUNT(*) FROM `apps` WHERE `lobby_web` = '1'";
    }else{
      $query = "SELECT * FROM `apps` ORDER BY `downloads` DESC";
      $total_query = "SELECT COUNT(*) FROM `apps`";
    }
  }else if($get === "app"){
    if($lobby_web){
      $query = "SELECT * FROM `apps` WHERE `id` = :id AND `lobby_web` = '1'";
    }else{
      $query = "SELECT * FROM `apps` WHERE `id` = :id";
    }
    $append[":id"] = Request::get("id");
  }else if($q !== null){
    $q = "%{$q}%";

    if($lobby_web){
      $query = "SELECT * FROM `apps` WHERE `lobby_web` = '1' AND (`name` LIKE :q OR `description` LIKE :q) ORDER BY `updated` DESC";
      $total_query = "SELECT COUNT(*) FROM `apps` WHERE `lobby_web` = '1' AND (`name` LIKE :q OR `description` LIKE :q)";
    }else{
      $query = "SELECT * FROM `apps` WHERE `name` LIKE :q OR `description` LIKE :q ORDER BY `updated` DESC";
      $total_query = "SELECT COUNT(*) FROM `apps` WHERE `name` LIKE :q OR `description` LIKE :q";
    }
    $append[":q"] = $q;
    $total_query_params = 1;
  }else{
    if($lobby_web){
      $query = "SELECT * FROM `apps` WHERE `lobby_web` = '1' ORDER BY `downloads` DESC";
      $total_query = "SELECT * FROM `apps` WHERE `lobby_web` = '1' ORDER BY `downloads` DESC";
    }else{
      $query = "SELECT * FROM `apps` ORDER BY `downloads` DESC";
      $total_query = "SELECT * FROM `apps` ORDER BY `downloads` DESC";
    }
  }

  $query .= " LIMIT :start, :stop";

  $sql = \Lobby\DB::getDBH()->prepare($query);
  foreach($append as $name => $value){
    $sql->bindParam($name, $value);
  }

  $sql->bindParam(":start", $start, \PDO::PARAM_INT);
  $sql->bindParam(":stop", $stop, \PDO::PARAM_INT);
  $sql->execute();

  if($sql->rowCount() == 0){
    echo "false";
  }else{
    $results = $sql->fetchAll(\PDO::FETCH_ASSOC);

    if(isset($total_query)){
      $total_apps = \Lobby\DB::getDBH()->prepare($total_query);
      if(isset($total_query_params)){
        foreach($append as $name => $value){
          $total_apps->bindParam($name, $value);
        }
      }
      $total_apps->execute();
      $total_apps = $total_apps->fetchColumn();
    }else{
      $total_apps = 0;
    }

    $response = array(
      "apps" => array(),
      "apps_count" => $total_apps
    );

    require_once $this->dir . "/src/inc/Parsedown.php";
    require_once $this->dir . "/src/inc/DB.php";
    require_once $this->dir . "/src/inc/Fr.star.php";

    $Parsedown = new Parsedown();
    $GLOBALS['star'] = new \Fr\Star(array());

    function getAuthorName($id = 1){
      $sql = \Lobby\DB::getDBH()->prepare("SELECT `name` FROM `users` WHERE `id` = ?");
      $sql->execute(array($id));
      return $sql->fetchColumn();
    }

    function getRating($id){
      $GLOBALS['star']->id = "app-$id";
      return $GLOBALS['star']->getRating("", "rate_value");
    }

    $i = 0;
    foreach($results as $r){
      $response['apps'][$i] = $r;
      $response['apps'][$i]['author'] = getAuthorName($r['author']);
      $response['apps'][$i]['author_page'] = \Lobby::u("/u/{$r['author']}");
      $response['apps'][$i]['description'] = $Parsedown->text(htmlspecialchars($r['description']));
      $response['apps'][$i]['image'] = L_URL . "/api/app/{$r['id']}/logo";
      $response['apps'][$i]['permalink'] = L_URL . "/apps/{$r['id']}";
      $response['apps'][$i]['rating'] = getRating($r['id']) . "/5";

      $response['apps'][$i]['requires'] = json_decode($r['requires'], true);

      /**
       * If `lobby` param is not present then,
       * client is using Lobby < 0.6
       */
      if(!isset($_POST["lobby"])){
        $response['apps'][$i]['requires']["lobby"] = array(">=", "0.6");
      }

      /**
       * Recommended : Singular word
       * For versions >=0.7
       */
      $response['apps'][$i]['require'] = $response['apps'][$i]['requires'];

      $response['apps'][$i]['updated'] = strtotime($r['updated']);
      $i++;
    }

    if(isset($append[":id"])){
      $response = $response['apps'][0];
    }

    echo json_encode($response, JSON_FORCE_OBJECT);
  }
}
exit;
?>
