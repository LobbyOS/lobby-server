<?php
require_once __DIR__ . "/vendor/autoload.php";

use Sabre\DAV\Client;
use \CloudConvert\Api;

class LobbyGit {

  private $cloud_url = "https://sky-phpgeek.rhcloud.com/index.php";

  private $git_url = null,
          $id = 0,
          $git_dir = null,
          $cloud_id = null,
          $info = array(
            "git_url" => null,
            "last_commit" => null,
            "updated" => 0
          );
  
  public function __construct($id, $git_url, $cloud_id = null){
    $this->id = $id;
    $this->git_url = $git_url;
    $this->git_dir = \Lobby\FS::loc("/contents/apps/lobby-server/src/data/git-cache/$id");
    $this->cloud_id = $cloud_id;
  }
  
  public function register(){
    $sql = \Lobby\DB::getDBH()->prepare("SELECT * FROM `git_cache` WHERE `git_url` = ?");
    $sql->execute(array($this->git_url));
    
    if($sql->rowCount() === 0){
      $sql = \Lobby\DB::getDBH()->prepare("INSERT INTO `git_cache` (`git_url`, `last_commit`, `updated`) VALUES(?, '', '0')");
      $sql->execute(array($this->git_url));
    }else{
      $this->info = $sql->fetch(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Only update if last update was 1 minute ago
     */
    if($this->info["updated"] < strtotime("-1 minute")){
      if($this->getRepo()){
        $sql = \Lobby\DB::getDBH()->prepare("UPDATE `git_cache` SET `updated` = UNIX_TIMESTAMP() WHERE `git_url` = ?");
        $sql->execute(array($this->git_url));
        
        $this->info["updated"] = time();
        return true;
      }else{
        return false;
      }
    }else{
      return false;
    }
  }
  
  public function update(){
    $sql = \Lobby\DB::getDBH()->prepare("UPDATE `git_cache` SET `updated` = '0', `last_commit` = '' WHERE `git_url` = ?");
    $sql->execute(array($this->git_url));
    
    return $this->register();
  }
  
  public function getRepo(){
    if(file_exists($this->git_dir))
      $this->recursiveRemoveDirectory($this->git_dir);
    
    $repo = Gitonomy\Git\Admin::cloneTo($this->git_dir, $this->git_url, false);
    $tags = $repo->getReferences()->getTags();
    
    /**
     * If no tags, use master
     */
    if(empty($tags)){
      $commitHash = $repo->getReferences()->getBranch('master')->getCommitHash();
    }else{
      rsort($tags);
      $commitHash = $tags[0]->getCommitHash();
    }
    
    if($commitHash === $this->info["last_commit"]){
      // No need of update
      return true;
    }
    
    $sql = \Lobby\DB::getDBH()->prepare("UPDATE `git_cache` SET `last_commit` = ? WHERE `git_url` = ?");
    $sql->execute(array($commitHash, $this->git_url));
    
    $this->recursiveRemoveDirectory($this->git_dir . "/.git");
    
    /**
     * Get screenshots
     */
    $manifest = json_decode(file_get_contents($this->git_dir . "/manifest.json"), true);
    
    // Manifest file is invalid
    if(!is_array($manifest))
      return false;
    
    if(isset($manifest["version"]))
      $latestVersion = $manifest["version"];
    else
      return false;
    
    if(isset($manifest->screenshots)){
      $screenshots = array();
      foreach($manifest["screenshots"] as $img){
        $url = parse_url($img);
        if($url["host"] === "i.imgur.com"){
          $path = $url["path"];
          
          // $path has slash at beginning
          $headers = @get_headers("http://i.imgur.com$path", 1);
          
          if($headers[0] === "HTTP/1.1 200 OK"){
            $screenshots[] = "//i.imgur.com$path";
          }
        }
      }
      
      if(empty($screenshots))
        unset($screenshots);
    }
    
    if(exec("cd {$this->git_dir};zip -r '{$this->git_dir}/app.zip' ./ -1 -q;") !== false){
      $logo = true;
      
      if(file_exists($this->git_dir . "/src/image/logo.svg"))
        $this->convertLogoToPNG();
      else if(file_exists($this->git_dir . "/src/image/logo.png"))
        copy($this->git_dir . "/src/image/logo.png", $this->git_dir . "/logo.png");
      else
        $logo = false;
     
      $webdavPass = getenv("SKY_WEBDAV_PASS");
      $settings = array(
        'baseUri' => "https://sky-phpgeek.rhcloud.com/remote.php/webdav/Apps/{$this->id}/",
        'userName' => 'lobby-apps',
        'password' => $webdavPass
      );
      $client = new Client($settings);
      
      /**
       * Create folder
       */
      $client->request('MKCOL');
      
      /**
       * Upload files
       */
      $client->request('PUT', "{$this->id}.zip", file_get_contents($this->git_dir . "/app.zip"));
      if($logo)
        $client->request('PUT', "logo.png", file_get_contents($this->git_dir . "/logo.png"));
      
      $request = \Requests::post("https://sky-phpgeek.rhcloud.com/ocs/v1.php/apps/files_sharing/api/v1/shares?format=json", array(
        "Content-Type" => "application/x-www-form-urlencoded"
      ), array(
        "path" => "Apps/{$this->id}",
        "shareType" => "3"
      ), array(
        "auth" => array("lobby-apps", $webdavPass)
      ));
      $response = json_decode($request->body);
      $this->cloud_id = $response->ocs->data->token;
      
      $extraColumnData = array(
        "short_description" => $manifest["short_description"]
      );
      
      if(isset($latestVersion))
        $extraColumnData["version"] = $latestVersion;
      
      if(isset($screenshots))
        $extraColumnData["screenshots"] = implode("\n", $screenshots);
        
      if(isset($manifest["require"]) && is_array($manifest["require"]))
        $extraColumnData["requires"] = json_encode($manifest["require"]);
      
      /**
       * Update Cloud ID and download file size
       */
      $appInfoUpdate = array(
        ":cloudID" => $this->cloud_id,
        ":downloadSize" => filesize($this->git_dir . "/app.zip"),
        ":appID" => $this->id
      );
      
      $extraColumns = "";
      if(!empty($extraColumnData)){
        foreach($extraColumnData as $k => $v){
          $extraColumns .= ", $k = :$k";
          $appInfoUpdate[":$k"] = $v;
        }
      }
      
      $sql = \Lobby\DB::getDBH()->prepare("UPDATE `apps` SET `cloud_id` = :cloudID, `download_size` = :downloadSize, `updated` = NOW() $extraColumns WHERE `id` = :appID");
      $sql->execute($appInfoUpdate);
      
      $this->recursiveRemoveDirectory($this->git_dir);
      return true;
    }
    return false;
  }
  
  public function download(){
    header("Location: {$this->cloud_url}/s/{$this->cloud_id}/download?path=%2F&files={$this->id}.zip");
  }
  
  public function logo($logo = 1){
    if($this->cloud_id == null || $logo == 0){
      header("Location: ". L_URL ."/contents/apps/lobby-server/src/image/blank.png");
    }else{
      header("Location: {$this->cloud_url}/s/{$this->cloud_id}/download?path=%2F&files=logo.png");
    }
  }
  
  /**
   * Convert SVG to PNG
   */
  public function convertLogoToPNG(){
    $svg = $this->git_dir . "/src/image/logo.svg";
    $output = $this->git_dir . "/logo.png";
    exec("java -Djava.security.policy=='". __DIR__ ."/svg-converter/java.policy' -jar '" . __DIR__ . "/svg-converter/batik-rasterizer.jar' -d '$output' -m image/png '$svg'");
  }
  
  /**
   * Recursive Directory Remover
   */
  public function recursiveRemoveDirectory($dir, $exclude = array(), $remove_parent = true) {
    $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach($files as $file) {
      $file_name = $file->getFilename();
      $path = $file->getRealPath();
      if ($file_name === '.' || $file_name === '..' || in_array($path, $exclude)) {
          continue;
      }
      if ($file->isDir()){
        rmdir($path);
      } else {
        unlink($path);
      }
    }
    if($remove_parent){
      rmdir($dir);
    }
  }
  
}
