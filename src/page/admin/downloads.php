<?php
$this->setTitle("Download");
?>
<div class="contents">
  <h1>Lobby Admin</h1>
  <?php
  $sql = \Lobby\DB::getDBH()->query("SELECT `value` FROM `lobby` WHERE `key_name` = 'downloads'");
  echo "<pre style='word-wrap: break-word;white-space: pre-wrap;'><code>";
  var_dump($sql->fetchColumn());
  echo "</code></pre>";
  ?>
  <h2>Usage</h2>
  <p>
  4lqmSC3SaCrolEcgTlSyjyhDzyBelHUfTbsCDmkEOCT06 - Me<cl/>
  CQEfQvwumHWpwRZ3t7p8dc7Zjp4X91lCHPIoa4ImBuBYc - Demo
  </p>
  <table>
    <thead>
      <colgroup>
         <col span="1" style="width: 15%;">
         <col span="1" style="width: 15%;">
         <col span="2" style="width: 55%;">
         <col span="1" style="width: 15%;">
      </colgroup>
      <tr>
        <th>Lobby Version</th>
        <th>Frequency</th>
        <th>Last Accessed</th>
        <th>Public ID</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = \Lobby\DB::getDBH()->query("SELECT * FROM `lobby_api_access` ORDER BY `accessed` DESC");
      while($r = $sql->fetch(\PDO::FETCH_ASSOC)){
        echo "<tr>";
          echo "<td>{$r['version']}</td>";
          echo "<td>{$r['frequency']}</td>";
          echo "<td>". Lobby\Time::date(date("Y-m-d H:i:s", $r['accessed']), "Y-m-d H:i:s") ."</td>";
          echo "<td><div style='width: 300px;'>{$r['lid']}</div></td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>
</div>
