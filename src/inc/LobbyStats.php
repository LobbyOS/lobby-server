<?php
class LobbyStats {

  public function __construct(){
    if(isset($_POST["lobby"])){
      self::addStat();
    }else if(isset($_POST["lobbyID"])){
      $_POST["lobby"] = array(
        "lid" => $_POST["lobbyID"],
        "version" => ""
      );
      self::addStat();
    }
  }
  
  public static function addStat(){
    if(isset($_POST["lobby"]["lid"]) && isset($_POST["lobby"]["version"])){
      $sql = \Lobby\DB::getDBH()->prepare("INSERT INTO `lobby_api_access` (`lid`, `version`, `accessed`, `frequency`) VALUES (:lid, :version, UNIX_TIMESTAMP(), '1') ON DUPLICATE KEY UPDATE `accessed` = UNIX_TIMESTAMP(), `frequency` = `frequency` + 1, `version` = :version");
      $sql->execute(array(
        "lid" => $_POST["lobby"]["lid"],
        "version" => $_POST["lobby"]["version"]
      ));
    }
  }

}
