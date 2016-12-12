<?php
$sql = \Lobby\DB::getDBH()->prepare("SELECT * FROM `users` WHERE `id` = ?");
$sql->execute(array($user));

if($sql->rowCount() != 0){
  $u = $sql->fetch(\PDO::FETCH_ASSOC);
  \Response::setTitle($u['display_name']);
?>
  <div class="contents">
    <h1><?php echo $u['display_name'];?></h1>
    <p>Real Name : <?php echo $u['name'];?></p>
    <p>Member of Lobby since <?php echo date("d F Y", $u['registered']);?></p>
    <h2>Apps</h2>
    <?php
    $sql = \Lobby\DB::getDBH()->prepare("SELECT `id`, `name`, `short_description`, `downloads` FROM `apps` WHERE `author` = ?");
    $sql->execute(array($user));
    
    if($sql->rowCount() != 0){
      echo "<table>";
        echo "<thead><tr><td>App</td><td>Description</td><td>Downloads</td></tr></thead>";
        echo "<tbody>";
          while($r = $sql->fetch()){
            echo "<tr>
              <td><a target='_blank' href='/apps/{$r['id']}'>{$r['name']}</a></td>
              <td>{$r['short_description']}</td>
              <td>{$r['downloads']}</td>
            </tr>";
          }
        echo "</tbody>";
      echo "</table>";
    }else{
      echo "User haven't created any apps";
    }
    ?>
  </div>
<?php
}else{
  ser();
}
