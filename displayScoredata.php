<?php
    require("db_login.php");

    $sql = "SELECT * FROM scoreData";
    $stmt = $pdo -> query($sql);
    $result = $stmt -> fetchAll();
?>

<html>
    <head>
        <title>スコア一覧</title>
    </head>
    <style>
        #panel{
            background: gray;
            margin: 20 auto;
            width: 800px;
            height: 300px;
        }
        #scoreTable{
            margin: 0 auto;
        }
    </style>
    <body>
        <div id="panel">
        </div>
        <table id="scoreTable" border = 1>
            <tr>
                <td>曲名</td>
                <td>難易度</td>
                <td>レベル</td>
                <td>スコア</td>
                <td>ランク</td>
                <td>次のランクまで</td>
                <td>AB</td>
                <td>FB</td>
            </tr>
            <?php foreach($result as $row){ ?>
            <tr>
                <td><?=$row['name']?></td>
                <td><?=$row['difficulty']?></td>
                <td><?=$row['level']?></td>
                <td><?=$row['techScore']?></td>
                <td><?=$row['rank']?></td>
                <td><?=$row['next']?></td>
                <td><?=$row['AB']?></td>
                <td><?=$row['FB']?></td>
            </tr> <?php } ?>
    </body>
</html>