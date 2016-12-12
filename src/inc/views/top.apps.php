<?php
if(!isset($no_header)){
?>
  <h1><a href="<?php echo L_URL;?>/apps">Lobby Store</a></h1>
<?php
}
?>
<div class="nav">
  <div class="nav-item">
    <a href="<?php echo L_URL;?>/apps" class="btn">Featured</a>
  </div>
  <div class="nav-item">
    <a href="<?php echo L_URL;?>/apps?browse=popular" class="btn red">Popular</a>
  </div>
  <div class="nav-item">
    <a href="<?php echo L_URL;?>/apps?browse=new" class="btn green">New</a>
  </div>
  <div class="nav-item" id="settings">
    <a class='dropdown-button' data-beloworigin="true" href='#' data-activates='dropdown1'><i class="material-icons">settings</i></a>
    <ul id='dropdown1' class='dropdown-content'>
      <li><a id="change_lobby_url" class="modal-trigger" href="#modal1">Change Lobby URL</a></li>
    </ul>
  </div>
  <div class="nav-item" id="search">
    <form action="<?php echo L_URL . "/apps";?>">
      <input name="q" placeholder="Search..." value="<?php echo isset($q) ? $q : "";?>" />
    </form>
  </div>
</div>
<div id="modal1" class="modal">
  <div class="modal-content">
    <h4>Change Lobby URL</h4>
    <p>Type in the URL of your Lobby installation</p>
    <p>Eg: http://127.0.0.1:2020, http://localhost/lobby</p>
    <input type='text' id='lobby_url' />
  </div>
  <div class="modal-footer">
    <a id="save" class="modal-action modal-close btn-flat">Save</a>
    <a class="modal-action modal-close btn-flat">Cancel</a>
  </div>
</div>
