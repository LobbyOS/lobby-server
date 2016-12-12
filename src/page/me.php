<?php
use \Lobby\App\lobby_server\Fr\LS;

if(!isset($_GET['logout'])){
  LS::init();
}else{
  LS::logout();
}

if(LS::$loggedIn){
  $this->addStyle("me.css");
}

if($node === "index"){
  \Response::setTitle("Me");
?>
  <div class="contents">
    <h1>Lobby Me</h1>
    <p><strong>Lobby Me</strong> is an account on Lobby that would help you to add your own apps to the App Center and do more with your Lobby installation.</p>
    <div clear="clear">
      <?php
      echo \Lobby::l("/me/login", "Log In Or Register", "class='btn green'");
      ?>
    </div>
  </div>
<?php
}else if($node === "login"){
  \Response::setTitle("Login | Me");
  $c = isset($_GET['c']) ? "&c=" . urlencode($_GET['c']) : "";
  $_SESSION['c'] = "";
?>
  <div class="contents">
    <h1>Login Or Register</h1>
    <div>
      <a class="btn" href="<?php echo L_URL . "/me/open?" . $c;?>" style="display: inline-block;height: 43px;width: 98%;margin: 0px;padding: 0px 20px 0px 52px;font-family: 'Ubuntu', sans-serif;font-size: 18px;font-weight: 400;color: #fff;line-height: 41px;background: #7BBDE7 url(<?php echo $this->srcURL;?>/src/image/open_icon.png) no-repeat 35% 5px scroll;background-size: 2em;border: none;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;text-decoration: none;cursor:pointer;margin-right:5px;">Login With Open</a>
    </div>
    <div clear="clear">
      <a class="btn" href="<?php echo L_URL . "/me/open?facebook" . $c;?>" style="display: inline-block;height: 43px;width: 98%;margin: 0px;padding: 0px 20px 0px 100px;font-family: 'Ubuntu', sans-serif;font-size: 18px;font-weight: 400;color: #fff;line-height: 41px;background: #3b579d url(<?php echo $this->srcURL;?>/src/image/fb_icon.png) no-repeat 35% 7px scroll;border: none;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;text-decoration: none;cursor:pointer;margin-right:5px;">Login With Facebook</a>
    </div>
    <div clear="clear">
      <a class="btn b-red" href="<?php echo L_URL . "/me/open?google" . $c;?>" style="display: inline-block;height: 43px;width: 98%;margin: 0px;padding: 0px 20px 0px 90px;font-family: 'Ubuntu', sans-serif;font-size: 18px;font-weight: 400;color: #fff;line-height: 41px;background:rgb(231, 38, 54) url(<?php echo $this->srcURL;?>/src/image/plus_icon.png) no-repeat 35% 7px scroll;border: none;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;text-decoration: none;cursor:pointer;">Login With Google +</a>
    </div>
  </div>
<?php
}else if($node == "open"){
  \Response::setTitle("Open Auth");
  if(isset($_GET['c'])){
    $_SESSION['c'] = urldecode($_GET['c']);
  }

  require_once $this->dir . "/src/inc/open.auth.php";
  $Opth = new OpenAuth("EAtGbLfgxiCJxhwWfsLsyxA0p8Zj4oUyOd4POaVc", "80d23edfa535caf4cc44b91e16c55c0f09e3bed927fecff96b092df0f517f410");
  $access_token = $Opth->login("http://server.lobby.sim/me/open", array(
    "email-send",
    "read-name"
  ), isset($_GET['facebook']) ? "facebook" : (isset($_GET['google']) ? "google" : "open"));
  
  if(is_array($access_token)){
    if($access_token["error"] == "obtain_failed"){
      \Response::redirect("/me/login");
    }
  }else if($access_token != false){
    $info = json_decode($Opth->get("info"), true);
    
    $status = LS::register($access_token, "", array(
      "name" => $info['name'],
      "display_name" => $info['name'],
      "registered" => time()
    ));
    
    if(isset($_SESSION['c']) && $_SESSION['c'] != ""){
      LS::$config['pages']['home_page'] = $_SESSION['c'];
    }
    LS::login($access_token, "", true);
  }
  
  exit;
}else if($node == "home"){
  \Response::setTitle("Home | Me");
?>
  <div class="contents">
    <h1>Me Home</h1>
    <p>Are you a PHP developer ? Do you want to create an app for Lobby. Head straight to <?php echo \Lobby::l("/docs", "Docs", "target='_blank'");?> and learn how to create an app from scratch easily.</p>
    <?php
    echo \Lobby::l("/me/profile", "My Profile", "class='btn green'") . "<br/>";
    
    echo "<h2>My Apps</h2>";
    echo \Lobby::l("/me/app", "Submit A New App", "class='btn blue'") . "<br/>";
    
    $sql = \Lobby\DB::getDBH()->prepare("SELECT `id`, `name` FROM `apps` WHERE `author` = ?");
    $sql->execute(array(LS::$user));
    
    echo "<ul>";
      while($r = $sql->fetch()){
        echo "<li><a href='". L_URL . "/me/app/{$r['id']}' class='btn green' style='margin: 5px 10px;'>{$r['name']}</a></li>";
      }
    echo "</ul>";
    ?>
  </div>
<?php
}else if($node == "profile"){
  \Response::setTitle("Edit Profile | Me");
?>
  <div class="contents">
    <h1>My Profile</h1>
    <?php
    $site = \Request::get("me_site");
    $site = $site != null ? $site : LS::getUser("web_page");
    $display_name = \Request::get("me_display");
    $display_name = $display_name != null ? $display_name : LS::getUser("display_name");

    if(\Request::get("me_site") != null && \Request::get("me_display") != null && CSRF::check()){
      if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $site)){
        LS::updateUser(array(
          "web_page" => $site,
          "display_name" => $display_name
        ));
        sss("Updated Profile", "Your profile was successfully updated");
      }else{
        ser("Error", "The info you provided was invalid");
      }
    }
    ?>
    <p>You can see your profile <a href="<?php echo \Lobby::u("/u/" . LS::$user);?>">here</a></p>
    <form action="<?php echo \Lobby::u();?>" method="POST">
      <label>
        <span>Display Name</span>
        <input type="text" name="me_display" value="<?php echo LS::getUser("display_name");?>" placeholder="Name to show in your apps" />
      </label>
      <label>
        <span>My Website</span>
        <input type="text" name="me_site" value="<?php echo LS::getUser("web_page");?>" placeholder="Required" />
      </label>
      <?php CSRF::getInput();?>
      <button class="btn green">Update Profile</button>
    </form>
  </div>
  <style>
    label{
      display: block;
      margin: 10px 0px;
    }
    label span{
      display: block;
    }
  </style>
<?php
}else if($node === "app"){
  $app_edit = false;
  $app_info = array();
  
  if(isset($path[3])){
    $sql = \Lobby\DB::getDBH()->prepare("SELECT * FROM `apps` WHERE `author` = ? AND `id` = ?");
    $sql->execute(array(LS::$user, $path[3]));
    
    if($sql->rowCount() == "0"){
      ser();
    }else{
      $app_edit = true;
      
      $result = $sql->fetch(\PDO::FETCH_ASSOC);
      $app_info = $result;
      $AppID = $app_info['id'];
    }
  }
?>
  <div class="contents">
    <?php
    if($app_edit && isset($_GET['update']) && !isset($_GET['app-updated'])){
      require_once $this->dir . "/src/inc/LobbyGit.php";
      
      $lg = new \LobbyGit($AppID, $app_info['git_url']);
      $lg->update();
      
      Response::redirect("/me/app/$AppID?app-updated");
    }
    
    if(isset($_POST['app_name'])){
      $app_info_required = array(
        "id" => $app_edit ? $path[3] : \Request::get("app_id"),
        "name" => \Request::get("app_name"),
        "git_url" => \Request::get("app_src"),
        "description" => \Request::get("app_description"),
        "category" => \Request::get("app_category"),
        "sub_category" => \Request::get("app_sub_category"),
        "app_page" => \Request::get("app_page")
      );
      $app_info = array_merge($app_info, $app_info_required);
      $app_info["lobby_web"] = isset($_POST["app_lobby_web"]) ? "1" : "0";
      $app_info["logo"] = isset($_POST["app_logo"]) ? "1" : "0";
    }

    if(isset($_POST['app_name']) && CSRF::check() && array_search(null, $app_info_required) === false){
      $apps_sql = \Lobby\DB::getDBH()->prepare("SELECT COUNT(1) FROM `apps` WHERE `id` = ?");
      $apps_sql->execute(array($app_info['id']));
      
      $queue_sql = \Lobby\DB::getDBH()->prepare("SELECT COUNT(1) FROM `apps_queue` WHERE `id` = ?");
      $queue_sql->execute(array($app_info['id']));
      
      if($app_edit != true && ($queue_sql->fetchColumn() != 0 || $apps_sql->fetchColumn() != 0)){
        ser("App Exists", "Hmmm... Looks like the App ID you submitted already exists either on App Center Or in the App Queue. " . \Lobby::l("/apps/{$app_info['id']}", "See Existing App"));
      }else if($app_edit != true && preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $app_info['git_url']) == 0){
        ser("Invalid URL", "The app's source code URL you provided was invalid.");
      }else{
        if($app_edit != true){
          $sql = \Lobby\DB::getDBH()->prepare("INSERT INTO `apps_queue` (`id`, `name`, `src`, `description`, `category`, `sub_category`, `app_page`, `author`, `updated`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW());");
          $sql->execute(array($app_info['id'], $app_info['name'], $app_info['git_url'], $app_info['description'], $app_info['category'], $app_info['sub_category'], $app_info['app_page'], LS::$user));
        
          $admin_access_token = LS::getUser("username", 1);
          
          require_once $this->dir . "/src/inc/open.auth.php";
          $Opth = new OpenAuth("EAtGbLfgxiCJxhwWfsLsyxA0p8Zj4oUyOd4POaVc", "80d23edfa535caf4cc44b91e16c55c0f09e3bed927fecff96b092df0f517f410");
        
          $Opth->action("email", array(
            "subject" => "Lobby App Review",
            "body" => "Dude, a person requested to review her/his app ({$app_info['id']}). Please go and check it. http://lobby.subinsb.com"
          ), $admin_access_token);
        
          sss("App Submitted", "Your app was added to the review queue. You will be notified by email about your app's review status.");
        }else{        
          $sql = \Lobby\DB::getDBH()->prepare("UPDATE `apps` SET `name` = ?, `logo` = ?, `description` = ?, `category` = ?, `sub_category` = ?, `app_page` = ?, `lobby_web` = ?, `updated` = NOW() WHERE `id` = ? AND `author` = ?");
          
          $sql->execute(array($app_info['name'], $app_info['logo'], $app_info['description'], $app_info['category'], $app_info['sub_category'], $app_info['app_page'], $app_info['lobby_web'], $app_info['id'], LS::$user));
          
          sss("Updated", "Your app was successfully updated.");
        }
      }
    }
    if($app_edit){
      \Response::setTitle("Edit App " . $app_info['name'] . " | Me");
    }else{
      \Response::setTitle("New App | Me");
    }
    ?>
    <h1><?php echo $app_edit == true ? $app_info['name'] : "New App";?></h1>
    <p>Thank you for taking interest in submitting apps to Lobby. Please enter the details of your app :</p>
    <?php
    echo $this->l("/docs/dev/app/publish", "Help | How to submit apps", "class='btn blue'");
    if($app_edit === true){
    ?>
      <h2>Update</h2>
      <?php
      if(isset($_GET['app-updated'])){
        echo sss("Updated App", "The app was updated from the Git source.");
      }
      ?>
      <p>If the app's Git repo (branch master) was updated, Lobby will update it automatically within an hour.<cl/>If that didn't happen or you want to update immediately, please click the following button to update the app :</p>
      <center><a class="btn green" href="?update">Update</a></center>
    <?php
    }
    ?>
    <h2>Edit</h2>
    <form action="/me/app<?php echo $app_edit ? "/{$app_info['id']}" : "";?>" method="POST">
      <label>
        <span>App ID</span>
        <input type="text" name="app_id" value="<?php echo $app_edit == true ? $app_info['id'] : "";?>" <?php if($app_edit == true){echo "disabled";}?>/>
      </label>
      <label>
        <span>Name</span>
        <input type="text" name="app_name" value="<?php echo $app_edit == true ? $app_info['name'] : "";?>" />
      </label>
      <?php
      if($app_edit != true){
      ?>
        <label>
          <span>Git URL</span>
          <p>You should <a href="https://help.github.com/articles/create-a-repo/">create a Git repo</a> of your app and host the code there. Then paste the URL to the .git file here :</p>
          <input type="text" name="app_src" placeholder="The URL to the git file of your app" size="70" />
        </label>
      <?php
      }else{
      ?>
        <label>
          <span>Git URL</span>
          <input type="text" name="app_src" placeholder="The URL to git repo of your app" value="<?php echo $app_info['git_url'];?>" size="70" />
        </label>
      <?php
      }
      /**
       * Now updated with Git
      if($app_edit === true){
      ?>
        <label>
          <span>Version</span>
          <input type="text" name="app_version" placeholder="0.1" value="<?php echo $app_edit == true ? $app_info['version'] : "";?>" />
        </label>
        <label>
          <span>Requires</span>
          <textarea type="text" style="height: 10rem;" name="app_requires" placeholder="JSON Data of dependencies required by app"><?php echo $app_edit == true ? $app_info['requires'] : "";?></textarea>
        </label>
      <?php
      }else{
      ?>
        <input type="hidden" name="app_version" value="0.1" />
        <input type="hidden" name="app_requires" value="{}" />
      <?php
      }
      */
      
      ?>
      <label>
        <span>Logo</span>
        <input type="checkbox" name="app_logo" <?php if($app_edit === true && $app_info['logo'] === "1"){echo "checked='checked'";}?> />
        <span>Does the app have a logo ?</span>
      </label>
      <?php
      /**
      <label>
        <span>Short Description</span>
        <input type="text" name="app_short_description" value="<?php echo $app_edit == true ? $app_info['short_description'] : "";?>" />
      </label>
      */
      ?>
      <label>
        <span>Description</span>
        <textarea type="text" style="height: 10rem;" name="app_description" rows="10" cols="70"><?php echo $app_edit == true ? $app_info['description'] : "";?></textarea>
      </label>
      <?php
      /**
       * Screenshots are now obtained from manifest file
      if($app_edit === true){
      ?>
        <label>
          <span>Screenshots</span>
          <div clear>
            <input type="file" name="files[]" multiple id="screenshot_upload" />
          </div>
          <textarea clear type="text" style="height: 10rem;" name="app_screenshots" rows="4" cols="50" placeholder="Choose multiple files above and the URLs will be seen here"><?php echo $app_edit == true ? $app_info['screenshots'] : "";?></textarea>
        </label>
        <script>
          $(function(){
            $('#screenshot_upload').live("change", function(){
              if($(this)[0].files.length != 0){
                fm = new FormData();
                $.each($(this)[0].files, function(i, elem){
                  fm.append("file[]", elem);
                });
                fm.append("submit", true);
                
                $(this).parent().append("<div>Uploading...</div>");
                $.ajax({
                  url : "<?php echo $this->srcURL;?>/src/ajax/upload_img.php",
                  type : 'POST',
                  data : fm,
                  processData: false,  // tell jQuery not to process the data
                  contentType: false,  // tell jQuery not to set contentType
                  success : function(data) {
                    beforeVal = $("textarea[name=app_screenshots]").val();
                    
                    $("textarea[name=app_screenshots]").val(beforeVal + data + "\n");
                    $('#screenshot_upload').parent().find("div").remove();
                  }
                });
              }
            });
          });
        </script>
      <?php
      }
      */
      ?>
      <label>
        <span>Category</span>
        <select name="app_category" id="app_category">
          <?php
          foreach($this->app_categories as $value => $category){
            if($app_edit == true && $app_info['category'] == $value){
              echo "<option value='$value' selected='selected'>$category</option>";
            }else{
              echo "<option value='$value'>$category</option>";
            }
          }
          ?>
        </select>
      </label>
      <label>
        <span>Sub Category</span>
        <?php
        foreach($this->app_sub_categories as $value => $category){
          echo "<select name='app_sub_category' id='app_sub_category_$value' class='app_sub_category'>";
            foreach($category as $sub_value => $sub_category){
              if($app_edit == true && $app_info['sub_category'] == $sub_value){
                echo "<span><option value='$sub_value' selected='selected'>$sub_category</option></span>";
              }else{
                echo "<span><option value='$sub_value'>$sub_category</option></span>";
              }
            }
          echo "</select>";
        }
        ?>
      </label>
      <label>
        <span>Lobby Web App ?</span>
        <p>Whether this app is able to run on <b><a href="//lobby.subinsb.com/web-readme" target="_blank">Lobby Demo</a></b></p>
        <label>
          <input type="checkbox" name="app_lobby_web" <?php if($app_edit === true && $app_info['lobby_web'] === "1"){echo "checked='checked'";}?> />
          <span></span>
        </label>
      </label>
      <label>
        <span>App Page</span>
        <p>Official webpage of your app</p>
        <input type="text" name="app_page" placeholder="http:// or https://" value="<?php echo $app_edit == true ? $app_info['app_page'] : "";?>" />
      </label>
      <?php CSRF::getInput();?>
      <button class="btn green"><?php echo $app_edit == true ? "Update App" : "Submit App";?></button>
    </form>
  </div>
  <style>
    label{
      display: block;
      margin: 10px 0px;
    }
    label span{
      display: block;
      font-size: 18px;
      font-weight: 500;
      margin: 30px 0px 10px;
    }
  </style>
  <script>
    $(function(){
      $(".app_sub_category").not($(".app_sub_category [selected]").parent()).attr("disabled", "disabled").hide();
      $("#app_category").live("change", function(){
        v = $(this).val();
        $(".app_sub_category").attr("disabled", "disabled").hide();
        $("#app_sub_category_" + v).removeAttr("disabled").show();
      });
      <?php
      /**
       * Make accessories' sub category show first
       */
      if(!$app_edit){
        echo "$('#app_sub_category_accessories').removeAttr('disabled').show();";
      }
      ?>
    });
  </script>
<?php
}
require_once $this->dir . "/src/inc/views/track.php";
