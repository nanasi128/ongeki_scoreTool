<?php
    require("db_login.php");

    $sql = "SELECT * FROM scoreData";
    $stmt = $pdo -> query($sql);
    $result = $stmt -> fetchAll();

    $json = json_encode($result, JSON_UNESCAPED_UNICODE);
    header('Content-type: application/json');
    echo $json;
?>