<?php
use \Lobby\App\lobby_server\Fr\LS;

$this->addStyle("apps.css");
$this->addScript("apps.js");

function get_timeago( $ptime ){
  $estimate_time = time() - $ptime;

  if( $estimate_time < 1 ){
    return 'less than 1 second ago';
  }

  $condition = array( 
    12 * 30 * 24 * 60 * 60  =>  'year',
    30 * 24 * 60 * 60       =>  'month',
    24 * 60 * 60            =>  'day',
    60 * 60                 =>  'hour',
    60                      =>  'minute',
    1                       =>  'second'
  );

  foreach( $condition as $secs => $str ){
    $d = $estimate_time / $secs;
      
    if( $d >= 1 ){
      $r = round( $d );
      return 'about ' . $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
    }
  }
}

if($node === "index"){  
  \Response::setTitle("Store");
  
  $query = "SELECT * FROM `apps` WHERE 1 ";
  $params = array();
   
  /**
   * Search
   */
  if(isset($_GET['q'])){
    $query .= "AND (`name` LIKE :q OR `description` LIKE :q) ";
    $params[":q"] = "%{$_GET['q']}%";
    $q = htmlspecialchars(urldecode($_GET['q']));
  }
  
  /**
   * Category
   */
  if(isset($_GET['c'])){
    $c = htmlspecialchars($_GET['c']);
    $query .= "AND `category` = :c ";
    $params[":c"] = $c;
    
    \Response::setTitle(ucfirst($c) . " Store");
  }
  
  /**
   * Sub Category
   */
  if(isset($_GET['sc'])){
    $sc = htmlspecialchars($_GET['sc']);
    $query .= "AND `sub_category` = :sc ";
    $params[":sc"] = $sc;
    
    \Response::setTitle(ucfirst($sc) . " Store");
  }
  
  /**
   * Browse
   */
  if(isset($_GET['browse'])){
    if($_GET['browse'] === "new"){
      $query .= "ORDER BY `updated` DESC";
    }else if($_GET['browse'] === "popular"){
      $query .= "ORDER BY `downloads` DESC";
    }
  }
  
  $sql = \Lobby\DB::getDBH()->prepare($query);
  $sql->execute($params);
  $apps = $sql->fetchAll();
  
  require_once $this->dir . "/src/inc/Fr.star.php";
  $star = new \Fr\Star(array());
?>
  <div class="contents">
    <?php
    require_once $this->dir . "/src/inc/views/top.apps.php";
    ?>
    <div class="apps">
      <?php
      if(count($apps) == 0){
        ser("No App Found", "No app was found with the critera you gave");
      }else{
        foreach($apps as $app){
          $app['logo'] = $app['logo'] === "0" ? $this->srcURL . "/src/image/blank.png" : L_URL . "/api/app/{$app['id']}/logo";
        ?>
          <div class="app">
            <div class="app-inner">
              <div class="lpane">
                <a href="<?php echo L_URL . "/apps/" . $app['id'];?>">
                  <img src="<?php echo $app['logo'];?>" />
                </a>
              </div>
              <div class="rpane">
                <a href="<?php echo L_URL . "/apps/" . $app['id'];?>" class="name"><?php echo $app['name'];?></a>
                <p class="description"><?php echo $app['short_description'];?></p>
                <p>By: <a href="<?php echo L_URL . "/u/" . $app['author'];?>"><?php echo LS::getUser("name", $app['author']);?></a></p>
              </div>
            </div>
            <div class="bpane">
              <div class="lside">
                <?php
                $star->id = "app-" . $app['id'];
                echo $star->getRating();
                echo "<div class='downloads'>" . $app['downloads'] . " downloads</div>";
                ?>
              </div>
              <div class="rside">
                <div>Updated <?php echo get_timeago(strtotime($app['updated']));?></div>
                <div>Version : <?php echo $app['version'];?></div>
              </div>
            </div>
          </div>
        <?php
        }
      }
      ?>
    </div>
  </div>
<?php
}else{
  $sql = \Lobby\DB::getDBH()->prepare("SELECT * FROM `apps` WHERE `id` = ?");
  $sql->execute(array($node));
  
  if($sql->rowCount() == "0"){
    ser();
  }else{
    $this->addStyle("app.css");
    $appInfo = $sql->fetch(\PDO::FETCH_ASSOC);
    
    \Response::setTitle($appInfo['name'] . " | Store");
    
    require_once $this->dir . "/src/inc/Parsedown.php";
    $Parsedown = new Parsedown();
?>
    <div class="contents">
      <?php
      $no_header = 1;
      require_once $this->dir . "/src/inc/views/top.apps.php";
      ?>
      <h1>
        <a href=""><?php echo $appInfo['name'];?></a>
        <a data-path="/admin/lobby-store.php?app=<?php echo $node;?>" class="open-via-lobby" title="Open in Lobby"><i class="material-icons">open_in_new</i></a>
      </h1>
      <p><?php echo $appInfo['short_description'];?></p>
      <ol style="list-style: none;padding: 0 0 5px 0;">
        <li style="display: inline-block;">
          <a href="/apps?c=<?php echo $appInfo['category'];?>" class='btn red'><?php echo $this->app_categories[$appInfo['category']];?></a> >
        </li>
        <li style="display: inline-block;">
          <a href="/apps?sc=<?php echo $appInfo['sub_category'];?>" class='btn green'><?php echo $this->app_sub_categories[$appInfo['category']][$appInfo['sub_category']];?></a>
        </li>
      </ol>
      <div id="app-tabs">
        <ul class="tabs">
          <li class="tab"><a href="#description">Description</a></li>
          <li class="tab"><a href="#screenshots">Screenshots</a></li>
          <li class="tab"><a href="#download">Download</a></li>
          <li class="tab"><a href="#about">About</a></li>
        </ul>
        <script>
          lobby.load(function(){
            $(".workspace #app-tabs .tabs").tabs();
          });
        </script>
        <div id="description">
          <p><?php echo $Parsedown->text($appInfo['description']);?></p>
        </div>
        <div id="screenshots">
          <?php
          $screenshots = array_filter(explode("\n", $appInfo['screenshots']));
          if(empty($screenshots)){
            ser("No Screenshots", "This app has no screenshots");
          }else{
            echo '<ul class="rslides">';
              foreach($screenshots as $screenshot){
                if($screenshot != ""){
                  echo "<li><a href='$screenshot' target='_blank'><img src='$screenshot' /></a></li>";
                }
              }
            echo "</ul>";
          ?>
            <script src="<?php echo $this->srcURL;?>/src/js/responsiveslides.min.js"></script>
            <script>
              $(function() {
                $(".workspace #screenshots .rslides").responsiveSlides({
                  auto: false,
                  pager: true
                });
              });
            </script>
          <?php
          }
          ?>
        </div>
        <div id="download">
          <div class="chip"><span>Requirements :</span></div>
          <ul class="collection" style="margin-left: 20px;">
            <?php
            foreach(json_decode($appInfo['requires'], true) as $k => $v){
              echo "<li class='collection-item'>$k $v</li>";
            }
            ?>
          </ul>
          <div style="margin: 20px;text-align: center;">
            <a data-path="/admin/lobby-store.php?app=<?php echo $appInfo["id"];?>" class="open-via-lobby btn orange btn-large" title="Open in Lobby" style="display: inline-block;">
              <i class="material-icons">open_in_new</i>
            </a>
            <a style='display: inline-block;color: white;position:relative;line-height: 40px;' class='btn btn-large green' onclick="node = document.createElement('iframe');node.src = this.href;node.style.cssText = 'display:none;position: absolute;left:-1000px;';node.addEventListener('load', function(){$(this).remove();clog('c');}, true);document.body.appendChild(node);return false;" href="<?php echo L_URL;?>/api/app/<?php echo $appInfo['id'];?>/download">Download Zip<span style='position: absolute;font-weight: bold;bottom: 7px;left: 0;line-height: 14px;right: 0;font-size: 0.8rem;'><?php echo \Lobby\FS::normalizeSize($appInfo["download_size"]);?></span></a>
            <a style="display: inline-block;" class="btn">
              <strong><?php echo $appInfo['downloads'];?> Downloads</strong>
            </a>
            <a href="http://server.lobby.sim/docs/install-app" class="btn" target="_blank">Installation Help</a>
          </div>
          <?php
          require_once $this->dir . "/src/inc/Fr.star.php";
          $this->addScript("Fr.star.js");
          
          $star = new \Fr\Star(array(), "app-{$appInfo['id']}");
          echo "<div class='ratings'>";
            echo $star->getRating("ableToRate");
            echo "<div id='rating'></div>";
          echo "</div>";
          ?>
          <script>
            window.addEventListener("load", function(){
              function fr_star(){
                $(".contents .ratings #rating").text($(".Fr-star").data("title"));
                $(".Fr-star").Fr_star(function(rating){
                  lobby.ar("rate", {'id' : '<?php echo "app-{$appInfo['id']}";?>', 'rating': rating}, function(r){
                    if(r == "error"){
                      alert("You have to log in to rate apps");
                    }else{
                      $(".contents .ratings").html(r);
                      fr_star();
                    }
                  }, "lobby-server");
                });
              }
              fr_star();
            });
          </script>
        </div>
        <div id="about">
          <div class="chip">Version : <?php echo $appInfo['version'];?></div><cl/>
          <div class="chip" title="UTC Time Zone - <?php echo $appInfo['updated'];?>">Updated : <?php echo get_timeago(strtotime($appInfo['updated']));?></div><cl/>
          <div class="chip">Author : <a href='/u/<?php echo $appInfo['author'];?>'><?php echo LS::getUser("name", $appInfo['author']);?></a></div><cl/>
          <div class="chip">Web Page : <?php echo "<a href='{$appInfo['app_page']}' target='_blank'>". htmlspecialchars($appInfo['app_page']) ."</a>";?></div>
        </div>
      </div>  
    </div>
<?php
  }
}
require_once $this->dir . "/src/inc/views/track.php";
