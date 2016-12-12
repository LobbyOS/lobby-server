<?php
namespace Lobby\App\lobby_server;

require __DIR__ . "/class.logsys.php";

use Lobby\App\lobby_server\Fr\LS;

class DB {
  public function prepare($query){
    $obj = \Lobby\DB::getDBH()->prepare($query);
    return $obj;
  }
}

LS::$config = array(
  "db" => array(),
  "features" => array(
    "auto_init" => false,
    "start_session" => false,
    "email_login" => false,
    "block_brute_force" => false
  ),
  "keys" => array(
    "cookie" => getenv("LOBBY_LOGSYS_cookie"),
    "salt" => getenv("LOBBY_LOGSYS_salt")
  ),
  "pages" => array(
    "no_login" => array(
      "/me", "/me/open"
    ),
    "login_page" => "/me/login",
    "home_page" => "/me/home"
  ),
  "cookies" => array(
    "expire" => "+28 days"
  )
);
LS::construct();
