<?php
    $dsn = 'mysql:dbname=mydb;host=mydatabase.crxttjzwzrqc.ap-northeast-1.rds.amazonaws.com';
    $user = 'admin';
    $password = 'koedo128';
    try{
      $pdo = new PDO($dsn, $user, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
      echo $e;
    }
?>
