<?php
  require('db_login.php');

  $sql = "DROP TABLE IF EXISTS constTable";
  $stmt = $pdo -> query($sql);

  $sql = "CREATE TABLE IF NOT EXISTS constTable"
  ."("
  ."id INT NOT NULL AUTO_INCREMENT,"
  ."title TEXT,"
  ."difficulty char(32),"
  ."level char(32),"
  ."const double,"
  ."hot char(32),"
  ."search TEXT,"
  ."PRIMARY KEY(id)"
  .");";
  $stmt = $pdo -> query($sql);

  echo 'OK';
?>
