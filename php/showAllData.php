<?php
    require("db_login.php");
    if(isset($_POST["check"])){
        $table_name = $_POST["table"];
        $sql ="SHOW CREATE TABLE ".$table_name;
        $result = $pdo -> query($sql);
        $boo = 0;
        foreach($result as $row){
            foreach($row as $elem){
                $boo++;
                if($boo % 2 != 0) continue;
                echo $elem.",";
            }
        }
        echo "<hr>";
        $sql ="SELECT * FROM ".$table_name;
        $stmt = $pdo -> query($sql);
        $result = $stmt -> fetchAll();
        foreach($result as $row){
            foreach($row as $elem){
                $boo++;
                if($boo % 2 != 0) continue; // そのままやるとなぜかダブるので片方表示しないように
                echo $elem.",";
            }
            echo "<hr>";
        }
        echo "<hr>";
    }

    if(isset($_POST["delete_table"])){
        $table = $_POST["table"];
        $sql = "DROP TABLE $table";
        $stmt = $pdo -> query($sql);
        echo "Delete Successful!</br>";
    }
    $sql ="SHOW TABLES";
    $result = $pdo -> query($sql);

    if(isset($_POST["delete_field"])){
        $table = $_POST["table"];
        $column = $_POST["column"];
        $value = $_POST["value"];

        $sql = "DELETE FROM $table WHERE $column = $value";
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute();
        echo "$table の $column = $value 要素を削除しました。<br>";
    }
?>

<html>
    <body>
        <form action = "" method = "POST">
            <select name = "table">
            <?php foreach($result as $row){ ?>
            <option value = <?=$row[0]?>><?=$row[0]?></option>
            <?php } ?>
            </select></br>
            <input type = "submit" name = "check" value = "表示"><br>
            削除はこちら↓<br>
            <input type = "submit" name = "delete_table" value = "テーブル削除"><br>
            フィールドを削除する場合はこちら↓<br>
            カラム
            <input type = "text" name = "column">
            値
            <input type = "text" name = "value">
            <input type = "submit" name = "delete_field" value = "フィールド削除">
        </form>
    </body>
</html>
