<?php
    require("db_login.php");

    $sql = "DROP TABLE IF EXISTS constTable_backup";
    $stmt = $pdo -> query($sql);
    $sql = "CREATE TABLE IF NOT EXISTS constTable_backup"
            ."("
            ."id INT,"
            ."name TEXT,"
            ."difficulty char(32),"
            ."const double,"
            ."hot char(32),"
            ."PRIMARY KEY (id)"
            .");";
    $stmt = $pdo -> query($sql);

    $sql = "SELECT * FROM constTable";
    $stmt = $pdo -> query($sql);
    $result = $stmt -> fetchAll();
    foreach($result as $row){
        $arr = [];
        $i = 0;
        foreach($row as $elem){
            if($i % 2 == 0) $arr[] = $elem;
            $i++;
        }
        $sql = "INSERT INTO constTable_backup (id, name, difficulty, const, hot) VALUES(?, ?, ?, ?, ?)";
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute($arr);
    }
?>