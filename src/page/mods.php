<?php
$docs_location = $this->dir . "/src/data/mods/";

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
    \Response::setTitle($doc_name . " | Modules");
  }
}else{
  ser();
}
$this->addStyle("docs.css");
?>
<div class="sidebar">
  <div style="position: absolute;right: 0px;top: 0px;bottom: 0px;width: 2px;box-shadow: -5px 0px 30px rgba(0,0,0,1);"></div>
  <ul>
    <li>
      <li><?php echo \Lobby::l("/mods", "<h4 style='padding-top: 0;'>Modules</h4>", 'class="head"');?></li>
      <ul>
        <li><?php echo \Lobby::l("/mods/admin", "Admin");?></li>
        <li><?php echo \Lobby::l("/mods/indi", "Indi");?></li>
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
      $slug = "section-" . str_replace(" ", "-", strtolower($title));
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
}
</style>
<?php
require_once $this->dir . "/src/inc/views/track.php";
