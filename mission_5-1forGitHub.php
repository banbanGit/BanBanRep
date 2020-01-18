<?php
  $dsn ='データベース名;charset=utf8';
  $db_user = 'ユーザー名';
  $db_pass = 'パスワード';

  $pdo = new PDO($dsn,$db_user,$db_pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


  //テーブル作成
  $sql = 'CREATE TABLE IF NOT EXISTS table5_1 (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    number INT(255),
    name TEXT,
    comment TEXT,
    registry_determmine DATETIME
  )engine=innodb default charset=utf8';

  $res = $pdo->query($sql);

  $value_name = "名前";
  $value_comment = "コメント";
  $value_mode = "";

/*  TO DO LIST
    ★数字かどうかチェック
*/

if( isset( $_POST['name'] )&& isset( $_POST['comment'] ) ){ 
  if(($_POST['name'] !== "") && ($_POST['comment'] !== "")){//空じゃないかチェック
    if($_POST['password'] == "pass"){//パスワード
      /*echo"送信受付";*/
      $date = date('Y/m/d H:i:s');
      if( $_POST['mode'] == ""){
      //新規insert
      echo"新規受付";
      $name = htmlspecialchars($_POST['name']);
      $comment = htmlspecialchars($_POST['comment']);
      $ins = $pdo -> prepare("INSERT INTO table5_1 (name,comment,registry_determmine) VALUES(:name,:comment,:registry_determmine)");
      $ins->bindParam(':name', $name, PDO::PARAM_STR);
      $ins -> bindParam(':comment', $comment, PDO::PARAM_STR);
      $ins -> bindParam(':registry_determmine',$date,PDO::PARAM_STR);
      $ins->execute();
      }else{
     //EDT
       /*echo"編集開始";
       echo $_POST['mode'];*/
       $name_edt = htmlspecialchars($_POST['name']);
       $comment_edt = htmlspecialchars($_POST['comment']);
       $upd = $pdo -> prepare('UPDATE table5_1 SET name = :name, comment = :comment ,registry_determmine = :registry_determmine WHERE id = :id ');
       $upd -> execute( array(':name' => $name_edt, ':comment' => $comment_edt, ':registry_determmine' => $date,':id' =>$_POST['mode']));
      }

    }else{
     echo"パスワードが違います。";
    }

  }else{
    echo"必要な項目が入力されていません。";//空だった時
  }

}else if(isset($_POST['del_num'])){//消去DELETE
  if($_POST['del_num'] !== ""){//空チェック

    if($_POST['password'] == "pass"){

    $id = htmlspecialchars($_POST['del_num']);
    $del = $pdo->prepare('DELETE FROM table5_1 WHERE id = :id');

    $del->execute(array(':id' => $id));

    }else{
      echo"パスワードが違います。";
    }

  }else{
    echo"削除したい番号を入力してください。";//空の時
  }


}else if( isset($_POST['edt_num'])){//編集UPDATE
  if($_POST['edt_num'] !== ""){//空チェック

  if($_POST['password'] == "pass"){
    $edt_get = $pdo -> prepare('SELECT * FROM table5_1 WHERE id = :id');
    $edt_get -> execute(array(':id' => $_POST['edt_num']));
    $edt_res = $edt_get -> fetchAll();//これでいいの？
    foreach($edt_res as $er){
      $value_name = $er['name'];//★
      $value_comment = $er['comment'];//★
    }
    $value_mode = $_POST['edt_num'];

  }else{
    echo"パスワードが違います。";
  }

  }else{//空の時
    echo"編集したい番号を入力してください。";
  }

}


?>






<html>
  <STRONG>好きな言葉を書き込もう！</STRONG>

  <!--送信フォーム-->
  <form action = "mission_5-1.php" method = "post">
    <input type = "text" name = "name" value = "<?php echo $value_name ;?>" >
    <input type = "text" name = "comment" value = "<?php echo $value_comment;?>">
    <input type = "text" name = "password" value = "パスワード">
  <!-- モード切替用テキストボックス-->
  <input type = "hidden" name = "mode"  value = "<?php echo $value_mode;?>">
    <input type = "submit" name = "sousin">

  </form>
  
  <!--削除フォーム-->
  <form actsion = "mission_5-1.php" method = "post">
    <input type = "text" name = "del_num" value = "削除番号">
    <input type = "text" name = "password" value = "パスワード">
    <input type = "submit" value = "削除" name = "sakuzyo">

  </form>

  <!--編集フォーム-->
  <form actsion = "mission_5-1.php" method = "post">
    <input type = "text" name = "edt_num" value = "編集番号">
    <input type = "text" name = "password" value = "パスワード">
    <input type = "submit" value = "編集" name = "hensyu">
  </form>


</html>

<?php
//select
  $hyouzi = $pdo -> query('SELECT * FROM table5_1');
  $result = $hyouzi -> fetchAll();
    foreach($result as $value){
    echo $value['id']."  ≪名前≫".$value['name']."  ≪コメント≫".$value['comment'].$value['registry_determmine'];
    echo "<br>";
  }
    echo "<hr>";

?>