<?php
class OpenAuth {
  public $access_token = false;
  
	private $key, $secret, $scope, $service;
  private static $server = "http://open.sim";
  private static $api_server = "http://open.sim";
  
  private $cur_action = "";
  private $values = array(
    "info" => 1,
    "email" => 1
  );
	
	public function __construct($api_key, $api_secret){
		$this->key = $api_key;
		$this->secret = $api_secret;
    $this->init();
	}
  
  public function init(){
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
    if(!isset($_SESSION['token'])){
      $_SESSION['token'] = self::randStr(20);
    }
    
    if(isset($_GET['opth_redirect']) && isset($_GET['token'])){
      $this->cur_action = "redirect";
    }elseif($this->cur_action == "login"){
      return "login";
    }else{
      return null;
    }
  }
  
  public function login($redirect, $permissions = array(), $service = "open"){
    if($this->cur_action == "redirect"){
      return $this->redirect_phase();
    }else{
      self::post("/opth/api/token", array(
        "api_key" => $this->key,
        "api_secret" => $this->secret,
        "token" => $_SESSION['token']
      ));
    
      $this->cur_action = "login";
      $url = self::$server . "/opth/login";
      $params = array(
        "api_key" => $this->key,
        "service" => $service,
        "redirect" => $redirect,
        "scope" => implode(",", $permissions),
        "token" => $_SESSION['token']
      );
      $url = $url . "?" . http_build_query($params);
      self::redirect($url);
      return false;
    }
  }
  
  private function redirect_phase(){
    if(isset($_GET['opth_error'])){
      return array("error" => $_GET['opth_error']);
    }elseif(isset($_GET['token']) && $_GET['token'] == $_SESSION['token']){
      $token = $_GET['token'];
      
      $access_token = self::post("/opth/api/access_token", array(
        "api_key" => $this->key,
        "api_secret" => $this->secret,
        "token" => $_SESSION['token']
      ));
      if($access_token != "false"){
        $this->access_token = $access_token;
        return $this->access_token;
      }else{
        return array("error" => "obtain_failed");
      }
    }else{
      return array("error" => "invalid_request");
    }
  }
  
  public function get($what, $access_token = false){
    $access_token = $access_token != false ?: $this->access_token;
    
    /**
     * isset() is used to reduce CPU usage
     */
    if($access_token != false && isset($this->values[$what])){
      $url = "/opth/api/users/{$access_token}/$what";
      
      return self::post($url, array(
        "api_key" => $this->key,
        "api_secret" => $this->secret
      ));
    }
  }
  
  public function action($what, $data = array(), $access_token = false){
    $access_token = $access_token ?: $this->access_token;

    /**
     * isset() is used to reduce memory usage
     */
    if($access_token != false && isset($this->values[$what])){
      $url = "/opth/api/users/{$access_token}/action-{$what}";
      
      return self::post($url, array_merge($data, array(
        "api_key" => $this->key,
        "api_secret" => $this->secret
      )));
    }
  }
  
  private function redirect($url){
    header("Location: $url");
    exit;
  }
  
  private static function post($url, $params){
    $ch = curl_init();
    $url = self::$api_server . $url;
    
    $fields_string = "";
    if(count($params) != 0){
      foreach($params as $key => $value){
        $fields_string .= "{$key}={$value}&";
      }
      /* Remove Last & char */
      rtrim($fields_string, '&');
    }
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    curl_setopt($ch, CURLOPT_POST, count($params));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    
    $output = curl_exec($ch);

    if(curl_errno($ch)){
      die('cURL Error: ' . curl_error($ch));
    }
    return $output;
  }
  
  /**
   * Generate a random string
   */
  public static function randStr($length){
    $str = "";
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $size = 62; // strlen($chars)
    for($i=0;$i < $length;$i++){
      $str .= $chars[rand(0, $size-1)];
    }
    return $str;
  }
  
}
?>
