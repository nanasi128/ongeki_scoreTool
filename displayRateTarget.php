<?php
    require("db_login.php");

    $sql = "SELECT * FROM scoreData WHERE hot = 'true'";
    $stmt = $pdo -> query($sql);
    $hot = $stmt -> fetchAll();
    foreach($hot as $key => $value){
        $arr[$key] = $value['rate'];
    }
    array_multisort($arr, SORT_DESC, $hot);
    $idx = 0;
    $hot_ave = 0;
    foreach($hot as $value){
        if($idx == 15) break;
        $hot_ave += $value['rate'] / 15;
        $idx++;
    }
    $sql = "SELECT * FROM scoreData WHERE hot = 'false'";
    $stmt = $pdo -> query($sql);
    $other = $stmt -> fetchAll();
    $arr = array();
    foreach($other as $key => $value){
        $arr[$key] = $value['rate'];
    }
    array_multisort($arr, SORT_DESC, $other);
    $idx = 0;
    $other_ave = 0;
    foreach($other as $value){
        if($idx == 30) break;
        $other_ave += $value['rate'] / 30;
        $idx++;
    }
    $max_rate = max($hot[0]['rate'], $other[0]['rate']);
    $max = ($max_rate * 10 + $hot_ave * 15 + $other_ave * 30) / 55;
    $hot_ave = round($hot_ave,3);
    $other_ave = round($other_ave, 3);
    $max = round($max, 3);
    $idx = 0;
?>

<html>
    <head>
        <title>レート対象</title>
    </head>
    <style>
        #wrapper{
            display: flex;
        }
        .rate_table{
            margin-left: 20px;
        }
        #summary{

        }
    </style>
    <body>
        <a href="displayScoredata_ajax.html" style="display: block; text-align: center;">スコア一覧へ</a></br>
        <div id="summary">
            <table style = "margin: 10px auto;" border = 1>
                <tr>
                    <td>Hot Rate</td>
                    <td>Other Rate</td>
                    <td>Hot + Other</td>
                    <td>理論値</td>
                </tr>
                <tr>
                    <td><?=$hot_ave?></td>
                    <td><?=$other_ave?></td>
                    <td><?=round((($hot_ave * 15 + $other_ave * 30) / 45) ,3)?></td>
                    <td><?=$max?></td>
                </tr>
            </table>
        <div id="wrapper">
            <div class="rate_table">
                <table border = 1>
                    <tr>
                        <td>#</td>
                        <td>曲名</td>
                        <td>難易度</td>
                        <td>スコア</td>
                        <td>譜面定数</td>
                        <td>レート値</td>
                    </tr>
                    <?php foreach($hot as $row){
                        if($row['rate'] == 0) continue;
                        if($idx == 15) break; ?>
                        <tr>
                            <td><?=$idx+1?></td>
                            <td><?=$row['name']?></td>
                            <td><?=$row['difficulty']?></td>
                            <td><?=$row['techScore']?></td>
                            <td><?=$row['const']?></td>
                            <td><?=round($row['rate'], 3)?></td>
                        </tr>
                    <?php $idx++; }
                    $idx = 0; ?>
                </table>
            </div>
            <div class="rate_table">
                <table border = 1>
                    <tr>
                        <td>#</td>
                        <td>曲名</td>
                        <td>難易度</td>
                        <td>スコア</td>
                        <td>譜面定数</td>
                        <td>レート値</td>
                    </tr>
                    <?php foreach($other as $row){ 
                        if($row['rate'] == 0) continue;
                        if($idx == 30) break; ?>
                        <tr>
                            <td><?=$idx+1?></td>
                            <td><?=$row['name']?></td>
                            <td><?=$row['difficulty']?></td>
                            <td><?=$row['techScore']?></td>
                            <td><?=$row['const']?></td>
                            <td><?=round($row['rate'], 3)?></td>
                        </tr>
                    <?php $idx++; } ?>
                </table>
            </div>
        </div>
    </body>
</html>
