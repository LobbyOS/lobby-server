<?php
namespace Lobby\App\lobby_server;

class DB {

  public function prepare($query){
    $obj = \Lobby\DB::getDBH()->prepare($query);
    return $obj;
  }

}