<?php
function upload_file($image_location){
  $client_id = "b42c7c40cb73c84";
  $filename = $image_location;
  
  $handle = fopen($filename, "r");
  $data = fread($handle, filesize($filename));
  $pvars = array('image' => base64_encode($data));
  $timeout = 30;
  
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
  curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
  $out = curl_exec($curl);
  curl_close ($curl);
  
  $pms = json_decode($out, true);
  $url = $pms['data']['link'];
  if($url != ""){
    return $url;
  }else{
    return "error";
  }
}

$files_url = array();

if(isset($_POST['submit']) && isset($_FILES['file'])){
  $imgs = $_FILES['file'];
  
  foreach($imgs['tmp_name'] as $loc){

    if(getimagesize($loc)){
      $upload = upload_file($loc);
      if($upload != "error"){
        $files_url[] = $upload;
      }
    }
  }
  echo implode("\n", $files_url);
}
?>
