<?php
    require("db_login.php");

    $sql = "DROP TABLE IF EXISTS scoreData";
    $stmt = $pdo -> query($sql);
    
    $sql = "CREATE TABLE IF NOT EXISTS scoreData"
    ."("
    ."name TEXT,"
    ."difficulty char(32),"
    ."level char(32),"
    ."techScore int(32),"
    ."rank char(32),"
    ."next int(32),"
    ."AB char(32),"
    ."FB char(32),"
    ."const double,"
    ."rate double,"
    ."hot char(32)"
    .");";
    $stmt = $pdo -> query($sql);
    // name str
    // difficulty str
    // level str
    // techScore int
    // rank str
    // next int
    // AB bool
    // FB bool
    // constant double
    // rate double
    // hot bool
?>
