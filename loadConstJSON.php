<?php
    header("Content-type: text/plain; charset=UTF-8");
    require('db_login.php');

    if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])
       && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
    {
        if(isset($_POST['request'])){
            $data = $_POST['request'];
            $json = json_decode($data);

            $sql = "DROP TABLE IF EXISTS constTable";
            $stmt = $pdo -> query($sql);
            
            $sql = "CREATE TABLE IF NOT EXISTS constTable"
            ."("
            ."name TEXT,"
            ."difficulty char(32),"
            ."const double,"
            ."hot char(32)"
            .");";
            $stmt = $pdo -> query($sql);

            foreach($json as $row){
                //$name = '"'.$row->Name.'"';
                $name = $row->Name;
                $hot = $row->Hot == 1 ? 'true' : 'false';
                $add_data = [$name, $row->Difficulty, $row->Constant, $hot];
                $sql = "INSERT INTO constTable (name, difficulty, const, hot) VALUES(?, ?, ?, ?)";
                $stmt = $pdo -> prepare($sql);
                $stmt -> execute($add_data);
            }
        }else echo('NO');
    }
?>