<?php
$this->setTitle("New App");
?>
<div class="contents">
  <h1>Add App</h1>
  <?php
  $app_info = array(
    "id" => \Request::get("app_id"),
    "name" => \Request::get("app_name"),
    "git_url" => \Request::get("app_download"),
    "requires" => \Request::get("app_requires"),
    "short_description" => \Request::get("app_short_description"),
    "description" => \Request::get("app_description"),
    "category" => \Request::get("app_category"),
    "sub_category" => \Request::get("app_sub_category"),
    "version" => \Request::get("app_version"),
    "page" => \Request::get("app_page"),
    "author_id" => \Request::get("author_id")
  );
  
  if(isset($_POST['app_id']) && array_search(null, $app_info) === false && CSRF::check()){
    $apps_sql = \Lobby\DB::getDBH()->prepare("SELECT COUNT(1) FROM `apps` WHERE `id` = ?");
    $apps_sql->execute(array($app_info['id']));
    
    if($apps_sql->fetchColumn() != 0){
      ser("App Exists", "Hmmm... Looks like the App ID you submitted already exists either on App Center Or in the App Queue. " . \Lobby::l("/apps/{$app_info['id']}", "See Existing App"));
    }else{
      $app_info["logo"] = isset($_POST["app_logo"]) ? "1" : "0";
      $lobby_web = isset($_POST['app_lobby_web']) ? 1 : 0;
      
      $sql = \Lobby\DB::getDBH()->prepare("INSERT INTO `apps` (`id`, `name`, `version`, `logo`, `requires`, `git_url`, `description`, `short_description`, `category`, `sub_category`, `app_page`, `author`, `lobby_web`, `updated`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW());");
      
      $sql->execute(array($app_info['id'], $app_info['name'], $app_info['version'], $app_info['logo'], $app_info['requires'], $app_info['git_url'], $app_info['description'], $app_info['short_description'], $app_info['category'], $app_info['sub_category'], $app_info['page'], $app_info['author_id'], $lobby_web));
      
      require_once __DIR__ . "/../../inc/LobbyGit.php";
      $LG = new LobbyGit($app_info["id"], $app_info["git_url"]);
      $LG->register();
      
      sss("App Added", "App was added to the repository");
    }
  }
  ?>
  <form action="<?php echo \Lobby::u();?>" method="POST">
    <label>
      <span>App ID</span>
      <input type="text" name="app_id" />
    </label>
    <label>
      <span>Name</span>
      <input type="text" name="app_name" />
    </label>
    <label>
      <span>Git URL</span>
      <input type="text" name="app_download" placeholder="Git URL" size="70" />
    </label>
    <label>
      <span>Requires</span>
      <textarea type="text" name="app_requires" placeholder="Dependencies of app" class="materialize-textarea"></textarea>
    </label>
    <label>
      <input type="checkbox" name="app_logo" />
      <span>Logo ?</span>
    </label>
    <label>
      <span>Short Description</span>
      <input type="text" name="app_short_description" />
    </label>
    <label>
      <span>Description</span>
      <textarea type="text" name="app_description" class="materialize-textarea"></textarea>
    </label>
    <label>
      <span>Category</span>
      <select name="app_category" id="app_category">
        <?php
        foreach($this->app_categories as $value => $category){
          echo "<option value='$value'>$category</option>";
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
            echo "<span><option value='$sub_value'>$sub_category</option></span>";
          }
        echo "</select>";
      }
      ?>
    </label>
    <label>
      <span>Version</span>
      <input type="text" name="app_version" placeholder="0.1" />
    </label>
    <label>
      <span>App Page</span>
      <input type="text" name="app_page" placeholder="http:// or https://" />
    </label>
    <label>
      <span>Author ID</span>
      <input type="number" name="author_id" placeholder="Author's user ID" />
    </label>
    <label>
      <input type="checkbox" name="app_lobby_web" />
      <span>Lobby Web App ?</span>
    </label>
    <?php CSRF::getInput();?>
    <button class="btn green">Submit App</button>
  </form>
  <style>
    label{
      display: block;
      margin: 10px 0px;
    }
    label span{
      display: block;
    }
  </style>
  <script>
    $(function(){
      $(".app_sub_category:not(:first)").attr("disabled", "disabled").hide();
      $("#app_category").live("change", function(){
        v = $(this).val();
        $(".app_sub_category").attr("disabled", "disabled").hide();
        $("#app_sub_category_" + v).removeAttr("disabled").show();
      });
    });
  </script>
</div>
