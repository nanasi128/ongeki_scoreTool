<?php
header("Content-type: text/plain; charset=UTF-8");

if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])
   && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{
  require("db_login.php");

  if (isset($_POST['request'])){
    $data = $_POST['request'];
    $json = json_decode($data);
    if($json->type == "add"){
      $add_data = [$json->song_title, $json->difficulty, $json->const, $json->hot];
      $sql = "INSERT INTO constTable (name, difficulty, const, hot) VALUES(?, ?, ?, ?)";
      $stmt = $pdo -> prepare($sql);
      $stmt -> execute($add_data);
      echo "OK";
    }else{
      $id = $json->song_title;
      $new_const = $json->const;
      $sql = "UPDATE constTable SET const = $new_const WHERE id = $id";
      $stmt = $pdo -> query($sql);
    }
  }else{
      echo 'The parameter of "request" is not found.';
  }
}
?>