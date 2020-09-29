<?php
    require("db_login.php");

    $sql = "SELECT * FROM scoreData";
    $stmt = $pdo -> query($sql);
    $result = $stmt -> fetchAll();
    $ret = [];
    foreach($result as $row){
      $arr = array(
        'title' => $row['title'],
        'difficulty' => $row['difficulty'],
        'level' => $row['level'],
        'techScore' => $row['techScore'],
        'rank' => $row['rank'],
        'next' => $row['next'],
        'AB' => $row['AB'],
        'FB' => $row['FB'],
        'const' => $row['const'],
        'rate' => $row['rate'],
        'hot' => $row['hot']
      );
      $ret[] = $arr;
    }

    $json = json_encode($ret, JSON_UNESCAPED_UNICODE);
    header('Content-type: application/json');
    echo $json;
?>
