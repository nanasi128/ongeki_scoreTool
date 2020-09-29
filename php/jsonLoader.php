<?php
  header("Content-type: text/plain; charset=UTF-8");
  require('db_login.php');
  if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])
     && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
    if(isset($_POST['request'])){
      require('createTable.php');
      $data = json_decode($_POST['request']);
      // title, difficulty, level, techScore, rank, next, AB, FB, search
      foreach($data as $val){
        $song_data = [];
        $cnt = 0;
        foreach($val as $elem){
          if($cnt != 8) $song_data[] = $elem;
          $cnt++;
        }
        $search = stringify($val->search);
        $difficulty = stringify($val->difficulty);
        $sql = "SELECT * FROM constTable WHERE search = $search AND difficulty = $difficulty";
        // echo $sql;
        $stmt = $pdo -> query($sql);
        $result = $stmt -> fetchAll();
        if(!empty($result)){
            $song_data[] = $result[0]["const"];
            if($result[0]["const"] == 0){
              $level = $song_data[2];
              if(substr($level, -1) == '+'){
                $const = substr($level, mb_strlen($level)-1) + 0.7;
              }else {
                $const = $level;
              }
              $song_data[] = calRate($song_data[3], $const);
            }else $song_data[] = calRate($song_data[3],$result[0]["const"]);
            $song_data[] = $result[0]["hot"];
        }else{
            $song_data[] = 0;
            $song_data[] = 0;
            $song_data[] = 0;
        }
        $song_data[] = $search;
        $sql = "INSERT INTO scoreData (title, difficulty, level, techScore, rank,
            next, AB, FB, const, rate, hot, search) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute($song_data);
      }
    }
  }
  function stringify($s){
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
