<?php
\Response::setTitle("Download");
?>
<div class="contents">
  <h1>Download</h1>
	<p>Lobby can be installed in many ways for different platforms.</p>
  <p>If you already have a Web Server Installed, <a href="#direct" onclick="$('.contents .tab:last a').click()">do this</a>. Or you can use the <b>Standalone</b> packages.</p>
  <ul class="tabs">
    <li class="tab"><a href="#windows">Windows</a></li>
    <li class="tab"><a href="#linux">Linux</a></li>
    <li class="tab"><a href="#android">Android</a></li>
    <li class="tab"><a href="#mac">macOS</a></li>
    <li class="tab"><a href="#direct">Server</a></li>
  </ul>
  <div id="windows">
    <h2>Windows</h2>
    <p>Download the Zip file and extract the "Lobby" folder inside it to a location of your choice.</p>

      <a class="btn btn-download blue" href="<?php L_URL;?>/api/lobby/download/windows">
        Download 32-bit
        <span class='btn-caption'>10MB - Windows 7, 8, 10</span>
      </a>
      <a class="btn btn-download red" href="<?php L_URL;?>/api/lobby/download/windows64">
        Download 64-bit
        <span class='btn-caption'>11MB - Windows 7, 8, 10</span>
      </a>

    <p>You may want to <b>disable your antivirus software temporarily</b> as some antivirus softwares detect Lobby falsely as a virus.</p>
    <p>Run the "Lobby.exe" or "Lobby64.exe" file in the extracted folder to open Lobby</p>
    <p><a href="/docs/quick/windows" class="btn">How To Install ?</a></p>
  </div>
  <div id="linux">
    <h2>Linux</h2>
    <p>Download the <b>Lobby-Linux.zip</b> file and extract the folder inside the Zip file to anywhere you like.</p>

    <a class="btn btn-download red" href="<?php L_URL;?>/api/lobby/download/linux">
      Download Lobby-Linux.zip
      <span class="btn-caption">Ubuntu, Linux Mint, openSUSE, Fedora, CentOS, ArchLinux, ElementaryOS etc.</span>
    </a>

    <p>To run, just open the Lobby file inside the folder you extracted.</p>
    <p><a href="/docs/quick/linux" class="btn">Further Information</a></p>
  </div>
  <div id="android">
    <h2>Android</h2>
    <p>Download the Lobby app from Play Store and install the dependencies to run Lobby.</p>
    <a class="btn red btn-download" href="https://play.google.com/store/apps/details?id=com.lobby.lobby">
      Install From Play Store
      <span class="btn-caption">4.5MB, additional 6MB to download dependencies</span>
    </a>
  </div>
  <div id="mac">
    <h2>Mac</h2>
    <p>A Lobby Standalone version for Mac hasn't been devloped <b>yet</b>. Meanwhile you can run Lobby in macOS on a web server.</p>
    <p><a href="/docs/quick/mac" class="btn orange">Install Lobby In macOS</a></p>
  </div>
  <div id="direct">
    <h2>Server</h2>
    <p>If you have a PHP web server installed, then download this .zip file.</p>
    <ul class="collection">
      <li class="collection-item">Create a folder named "lobby" in your server's document root</li>
      <li class="collection-item">Open the Zip file and extract the contents of it to this newly created folder ("lobby")</li>
      <li class="collection-item">Access the folder through your web browser.</li>
      <li class="collection-item"><?php echo \Lobby::l("/docs/quick#configuration", "Install Lobby");?></li>
    </ul>
    <p>
      If you already have localhost set up and just want to download Lobby, click the button below :
      <a class='btn btn-download green' href="/api/lobby/download/latest">Download Lobby<span class='btn-caption'>Zip, 2.9 MB</span></a>
    </p>
  </div>
</div>
<style>
.btn-download{
  display: table;
  margin: 10px auto;
  font-size: 1.4rem;
  padding: 10px 30px;
}
.btn-caption{
  display:block;
  margin-top: -5px;
  font-size: 0.7rem;
}
</style>
<?php
require_once $this->dir . "/src/inc/views/track.php";
