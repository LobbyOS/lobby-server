<?php
$this->addStyle("main.css");
$this->addScript("responsiveslides.min.js");
\Response::setTitle("Run Desktop Web Apps");
?>
<section id="intro">
  <div class="container">
    <div class="row">
      <a href='//lobby.subinsb.com'><img src='<?php echo $this->srcURL . "/src/image/logo.png";?>' /></a>
      <div class="section-intro">Run <a class="underlined" href="/docs/about#desktop-web-apps-?">desktop web apps</a><div class="byline">On Windows, Linux, Android & Servers !</div></div>
    </div>
  </div>
</section>
<section id="runs-in" class="even-section">
  <div class='browser-window'>
    <div class='top-bar'>
      <div class='circles'>
         <div class="circle red"></div>
         <div class="circle yellow"></div>
         <div class="circle green"></div>
         <div class="title">Google Chrome</div>
      </div>
    </div>
    <div class='page-url'>
      <a class="action-btn previous" href="#"></a>
      <a class="action-btn next" href="#"></a>
      <div id='page-url'>http://localhost/lobby</div>
    </div>
    <div class='page-content'>
      <ul class="rslides">
        <li>
          <img src="<?php echo $this->srcURL;?>/src/image/screenshots/browser/dashboard.png" alt="">
        </li>
        <li>
          <img src="<?php echo $this->srcURL;?>/src/image/screenshots/browser/app-millionaire.png" alt="">
        </li>
        <li>
          <img src="<?php echo $this->srcURL;?>/src/image/screenshots/browser/lobby-store.png" alt="">
        </li>
      </ul>
      <script>
        lobby.load(function(){
          $(".rslides").responsiveSlides({
            pager:true,
            speed: 100,
            before: function(i){
              switch(i){
                case 0:
                  $("#page-url").text("http://localhost/lobby");
                  break;
                case 1:
                  $("#page-url").text("http://localhost/lobby/app/millionaire");
                  break;
                case 2:
                  $("#page-url").text("http://localhost/lobby/admin/lobby-store.php");
              }
            }
          });
          $(".action-btn.previous").click(function(){
            $(".rslides_tabs .rslides_here").prevAll(":first").find("a").trigger("click");
          });
          $(".action-btn.next").click(function(){
            $(".rslides_tabs .rslides_here").nextAll(":first").find("a").trigger("click");
          });
        });
      </script>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="section-intro">Lobby runs in <a target="_blank" href="https://en.wikipedia.org/wiki/Localhost" class="underlined">localhost</a>
        <div class="byline">and can be used with a browser</div>
      </div>
    </div>
  </div>
</section>
<div id="navigate">
  <a class="btn red page-scroll" href="#features">Features</a>
  <a class="btn blue page-scroll" href="#lobby-store">Lobby Store</a>
  <a href="/docs/about" class="btn blue" target="_blank">About Lobby</a>
  <a class="btn red page-scroll" href="#download">Download</a>
</div>
<section id="features" class="odd-section">
  <div class="container">
    <div class="row" style="text-align: left;">
      <h2>Features</h2>
      <ul class="collection">
        <li class="collection-item">Get latest apps from <a href="#lobby-store">Lobby Store</a></li>
        <li class="collection-item">Use the same app on every platform</li>
        <li class="collection-item">One click update Lobby & Apps when a new version comes out</li>
        <li class="collection-item">Lightweight - Lobby was created from scratch with performance in mind</li>
        <li class="collection-item">Open Source (Apache License)</li>
      </ul>
    </div>
  </div>
</section>
<section id="lobby-store" class="even-section">
  <div class="container">
    <div class="row" style="text-align: left;">
      <h2 style='font-size: 4em;'><a href="/apps" style="color: white;">Lobby Store</a></h2>
      <p>Lobby Store is a repository of apps for you to install. Find great apps and <a href="<?php echo $this->u("/docs/dev/app/publish");?>">publish your apps</a> for others to use.</p>
      <img src="<?php echo $this->srcURL;?>/src/image/screenshots/lobby-store.png" />
    </div>
  </div>
</section>
<section id="download">
  <div class="contents" style="padding: 20px 0;">
    <h1 style='font-size: 4em;margin-top:0;'><a href="/download">Download</a></h1>
    <div clear>
      <a class='platform linux' href='/api/lobby/download/linux' title='Download for all Linux distros'></a>
      <a class='platform android' href='https://play.google.com/store/apps/details?id=com.lobby.lobby' title='Download from Play Store'></a>
      <a class='platform windows' href='/api/lobby/download/windows' title='Download for Windows 32 bit'></a>
      <a class='platform windows64' href='/api/lobby/download/windows64' title='Download for Windows 64 bit'></a>
      <a class='platform zip' href='/api/lobby/download/latest' title='Download for installing directly in server'></a>
    </div>
    <div clear style='margin-top: 20px;' >
      <a class='platform' id='github' href='https://github.com/LobbyOS/lobby' title='GitHub'></a>
      <a class='platform' id='facebook' href='https://www.facebook.com/groups/LobbyOS' title='Support & Help'></a>
      <a class='btn' href='/download' clear>More Info</a>
    </div>
    <p style='margin-top: 50px;'>&copy; Copyleft <a href="https://github.com/orgs/LobbyOS/people" target="_blank">Lobby Team</a> 2014 - <?php echo date("Y");?></p>
  </div>
</section>
<script>
$(function(){
  $('a.page-scroll').live('click', function(event) {
    var $anchor = $(this);
    $('.workspace').scrollTo($($anchor.attr('href')));
    event.preventDefault();
  });
});
</script>
<?php
require_once $this->dir . "/src/inc/views/track.php";
