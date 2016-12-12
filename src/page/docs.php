<?php
$docs_location = $this->dir . "/src/data/docs/";
$docs = array_diff(scandir($docs_location), array('..', '.'));

$doc = isset($doc) ? $doc . ".md" : "index.md";
if(isset($doc) && array_search($doc, $docs) !== false){
  $doc_path = "$docs_location/$doc";
  
  $f = fopen($doc_path, 'r');
  $doc_name = fgets($f);
  $content = fread($f, filesize($doc_path));
  fclose($f);
  
  if(substr($doc, 0, 4) === "dev."){
    $doc = substr_replace($doc, '', 0, 4);
    \Response::setTitle($doc_name . " | Developer Documentation");
  }else{
    \Response::setTitle($doc_name . " | Documentation");
  }
}else{
  ser();
}
$this->addStyle("docs.css");
?>
<div class="sidebar">
  <div style="position: absolute;right: 0px;top: 0px;bottom: 0px;width: 2px;box-shadow: -5px 0px 30px rgba(0,0,0,1);"></div>
  <ul>
    <li class="doc-side-head">
      <?php echo \Lobby::l("/docs", "Preface", 'class="head"');?>
      <ul>
        <li><?php echo "<a href='". L_URL ."/docs/about'>About</a>";?></li>
        <li><?php echo "<a href='". L_URL ."/docs/contact'>Contact</a>";?></li>
        <li><?php echo "<a href='". L_URL ."/docs/quick'>Quick Install</a>";?></li>
        <li><?php echo "<a href='". L_URL ."/docs/config'>Configuration</a>";?></li>
        <li><?php echo "<a href='". L_URL ."/docs/install-app'>Install Apps</a>";?></li>
        <li><?php echo "<a href='". L_URL ."/docs/update'>Updates</a>";?></li>
      </ul>
    </li>
    <li class="doc-side-head">
      <?php echo \Lobby::l("/docs/dev", "Developer", 'class="head"');?>
      <ul>
        <li><?php echo "<a href='". L_URL ."/docs/dev/introduction'>Introduction</a>";?></li>
        <li><?php echo "<a href='". L_URL ."/docs/dev/create-app'>Creating Apps</a>";?></li>
        <li><?php echo "<a href='". L_URL ."/docs/dev/debug'>Debugging</a>";?></li>
      </ul>
    </li>
    <li class="doc-side-head">
      <?php echo \Lobby::l("/docs/dev/app", "App", 'class="head"');?>
      <ul>
        <li><?php echo "<a href='". L_URL ."/docs/dev/app/manifest.json'>Manifest File</a>";?></li>
        <li><?php echo "<a href='". L_URL ."/docs/dev/app/App.php'>App.php</a>";?></li>
        <li><?php echo "<a href='". L_URL ."/docs/dev/app/publish'>Publish</a>";?></li>
      </ul>
    </li>
    <li class="doc-side-head">
      <?php echo \Lobby::l("/docs/dev/core", "Core", 'class="head"');?>
      <ul>
        <li><?php echo "<a href='". L_URL ."/docs/dev/core/hooks'>Hooks</a>";?></li>
      </ul>
    </li>
  </ul>
</div>
<div class="contents">
  <?php
  require_once $this->dir . "/src/inc/Parsedown.php";
  $Parsedown = new ParsedownExtra();
  $html = $Parsedown->text($content);
  
  // This function adds nice anchor with id attribute to our h2 tags for reference
  // @link: http://www.w3.org/TR/html4/struct/links.html#h-12.2.3
  function anchor_content_headings($content) {
    // now run the pattern and callback function on content
    // and process it through a function that replaces the title with an id 
    $content = preg_replace_callback("/\<h([1|2|3|4])\>(.*?)\<\/h([1|2|3|4])\>/", function ($matches) {
      $hTag = $matches[1];
      $title = $matches[2];
      $slug = str_replace(" ", "-", strtolower($title));
      return '<a href="#'. $slug .'"><h'. $hTag .' id="' . $slug . '">' . $title . '</h'. $hTag .'></a>';
    }, $content);
    return $content;
  }
  echo anchor_content_headings($html);
  ?>
</div>
<script type="text/javascript" src="//cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js?skin=sunburst"></script>
<script>
  $(document).ready(function(){
    $("pre").each(function(){
      $(this).addClass("prettyprint linenums");
    });
  });
</script>
<style>
li.L0, li.L1, li.L2, li.L3, li.L5, li.L6, li.L7, li.L8{
  list-style-type: decimal !important;
}div.el {margin-left: 2em}
</style>
<?php
require_once $this->dir . "/src/inc/views/track.php";
