<?php
    require("db_login.php");

    $sql = "UPDATE constTable SET hot = 'false'";
    $stmt = $pdo -> query($sql);
?>