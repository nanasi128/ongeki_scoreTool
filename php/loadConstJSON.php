<?php
    header("Content-type: text/plain; charset=UTF-8");
    require('db_login.php');

    if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])
       && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
    {
        if(isset($_POST['request'])){
            $data = $_POST['request'];
            $json = json_decode($data);

            foreach($json as $row){
                $title = $row->title;
                $diff = $row->difficulty;
                $level = $row->level;
                $const = $row->const;
                $hot = $row->hot;
                $search = $row->search;
                $add_data = [$title, $diff, $level, $const, $hot, $search];
                $sql = "INSERT INTO constTable (title, difficulty, level, const, hot, search) VALUES(?, ?, ?, ?, ?, ?)";
                $stmt = $pdo -> prepare($sql);
                $stmt -> execute($add_data);
            }
        }else echo('NO');
    }
?>
