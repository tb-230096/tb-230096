<!DOCTYPE html>
<html lang=ja>
<head>
    <meta charset="UTF-8">
    <title>mission_5-1d</title>
</head>
<body>

<?php
 // DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

   
//編集機能
        if(isset($_POST["edit"],$_POST["edit_password"])&&$_POST["edit"]!=""&&$_POST["edit_password"]!=""){
            $sql = 'SELECT * FROM tb';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                if($row["id"]==$_POST["edit"]&&$row["password"]==$_POST["edit_password"]){
                    $edit_name=$row["name"];
                    $edit_comment=$row["comment"];
                    $edit_line=$row["id"];
                }
            }    
        }
        
    ?>
     <form action="" method="post">
        <input type="text" name="yourname" placeholder="名前" value="<?php if(isset($edit_name)){echo $edit_name;}?>"><br>
        <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($edit_comment)){echo $edit_comment;}?>"><br>
        <input type="text" name="password" placeholder="パスワード">
        <input type="hidden" name="editline" value="<?php if(isset($edit_line)){echo $edit_line;}?>">
        <input type ="submit"><br><br>
        <input type="text" name="delete" placeholder="削除する行"><br>
        <input type="text" name="delete_password" placeholder="パスワード">
        <input type="submit" value="削除"><br><br>
        <input type="text" name="edit" placeholder="編集する行"><br>
        <input type="text" name="edit_password" placeholder="パスワード">
        <input type="submit" value="編集">
    </form>
    
 <?php
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $db_password = 'パスワード';
        $pdo = new PDO($dsn, $user, $db_password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        $sql1 = "CREATE TABLE IF NOT EXISTS tb"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date TEXT,"
        . "password TEXT"
        .");";
        
        //新規投稿
        if(isset($_POST["yourname"],$_POST["comment"],$_POST["password"])&&$_POST["yourname"]!=""&&$_POST["comment"]!=""&&$_POST["password"]!=""&&$_POST["editline"]==""){
            $sql3 = $pdo -> prepare("INSERT INTO tb (name,comment,date,password) VALUES (:name,:comment,:date,:password)");
            $sql3 -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql3 -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql3 -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql3 -> bindParam(':password', $password, PDO::PARAM_STR);
            $name = $_POST["yourname"];
            $comment = $_POST["comment"];
            $date = date("Y年m月d日 H:i:s");
            $password = $_POST["password"];
            $sql3 -> execute();
        }
//編集機能
        if(isset($_POST["yourname"],$_POST["comment"],$_POST["password"])&&$_POST["yourname"]!=""&&$_POST["comment"]!=""&&$_POST["password"]!=""&&$_POST["editline"]!=""){
            $id=$_POST["editline"];
            $name=$_POST["yourname"];
            $comment=$_POST["comment"];
            $date=date("Y年m月d日 H:i:s");
            $password=$_POST["password"];
            $sql = 'UPDATE tb SET name=:name,comment=:comment,date=:date,password=:password WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        
//削除機能
   if(!empty($delete)) {  
    $sql = 'SELECT * FROM tb WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $delete, PDO::PARAM_INT); 
    $stmt->execute();
    $results = $stmt->fetchAll();
    foreach ($results as $row) {
        if ($delpass == $row['compass']){
            $sql = 'DELETE FROM tb WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
            $stmt->execute();
        }else{
            echo "パスワードが違います<br>";
        }
    }  
}


 //名前とコメントとパスワードがあるなら
 if(!empty($name) && !empty($comment)){

  //編集番号が送信されたなら編集モード
  if(!empty($editnum)) {
      $sql = 'UPDATE tb SET name=:name,comment=:comment,compass=:compass,date=:date WHERE id=:id';
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':name', $name, PDO::PARAM_STR);
      $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
      
      $stmt->bindParam(':date', $date, PDO::PARAM_STR);
      $stmt->bindParam(':id', $edit, PDO::PARAM_INT);
      $stmt->execute();

  //編集番号が送信されてないなら追記モード
  }else{    
      $sql = $pdo -> prepare("INSERT INTO tb (name, comment, compass, date) VALUES (:name, :comment, :compass, :date)");
      $sql -> bindParam(':name', $name, PDO::PARAM_STR);
      $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
      
      $sql -> bindParam(':date', $date, PDO::PARAM_STR);
    
  }
}

    //ブラウザに表示
        $sql = 'SELECT * FROM tb';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].','.$row['name'].','.$row['comment'].','.$row['date'].','.$row['password'].'<br>';
            echo "<hr>";
    }
  ?>

</body>
</html>