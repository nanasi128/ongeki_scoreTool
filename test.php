<?php
    require("db_login.php");
    if(isset($_POST["upload"])){
        switch ($_FILES['upfile']['error']) {
            case UPLOAD_ERR_OK: // OK
                break;
            case UPLOAD_ERR_NO_FILE:   // 未選択
                throw new RuntimeException('ファイルが選択されていません', 400);
            case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
                throw new RuntimeException('ファイルサイズが大きすぎます', 400);
            default:
                throw new RuntimeException('その他のエラーが発生しました', 500);
        }
        
        $data = file_get_contents($_FILES["upfile"]["tmp_name"]);
        require("createTable.php"); // initialize table
        $boo = false;
        $cnt = 0;
        $row = explode("\r\n", $data);
        foreach($row as $value){
            if(!$boo){
                $boo = true;
                continue;
            }
            $song_data = myexplode(",",$value); // expected name,difficulty,level,techScore,rank,next,AB,FB
            $song_data[0] = stringfy($song_data[0]);
            $song_data[1] = stringfy($song_data[1]);
            $sql = "SELECT * FROM constTable WHERE name = $song_data[0] AND difficulty = $song_data[1]";
            $stmt = $pdo -> query($sql);
            $result = $stmt -> fetchAll();
            var_dump($result);
            echo "<hr>";
            if(!empty($result)){
                $song_data[] = calRate($song_data[3],$result[0]["const"]);
                $song_data[] = $result[0]["const"];
                $song_data[] = $result[0]["hot"];
            }else{
                $song_data[] = 0;
                $song_data[] = 0;
                $song_data[] = 0;
            }
            var_dump($song_data);
            $sql = "INSERT INTO scoreData (name, difficulty, level, techScore, rank,
                next, AB, FB, const, rate, hot) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo -> prepare($sql);
            $stmt -> execute($song_data);
        }
        header('Location: displayRateTarget.php');
        exit();
    }
    function stringfy($s){
        return '"'.$s.'"';
    }
    function myexplode($s, $arr){
        $mode = false; //true -> song_name
        $elem = "";
        $ret_arr = array();
        for($i = 0; $i < mb_strlen($arr); $i++){
            $ch = mb_substr($arr, $i, 1);
            if($ch == '"' && !$mode){
                $mode = true;
                continue;
            }
            if($ch == '"' && $mode){ 
                $mode = false;
                continue;
            }
            if(($ch == ',' || $ch == "\r" || $ch == "\n" || $ch == "\r\n") && !$mode){
                $ret_arr[] = $elem;
                $elem = "";
                if($ch == "\r" || $ch == "\n" || $ch == "\r\n") break;
                continue;
            }
            $elem = $elem.$ch;
        }
        if($elem != "") $ret_arr[] = $elem;
        return($ret_arr);
    }
    function calRate($score_, $constant_){
        $score = sprintf("%d", $score_);
        $constant = sprintf("%.2f", $constant_);
        if($score >= 1007500){
            return $constant + 2;
        }else if($score >= 1000000){
            return ($score - 1000000) * 0.01 / 150 + 1.5 + $constant;
        }else if($score >= 990000){
            return ($score - 990000) * 0.01 / 200 + 1.0 + $constant;
        }else if($score >= 970000){
            return ($score - 970000) * 0.01 / 200 + $constant;
        }
        return 0;
    }
?>

<html>
    <body>
        <form action = "" enctype="multipart/form-data" method = "post">
        <input type = "file" name = "upfile" /></br>
        <input type = "submit" name = "upload" value = "アップロード"/>
    </form>
    </body>
</html>