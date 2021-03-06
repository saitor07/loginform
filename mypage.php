<?php
mb_internal_encoding("utf8");
session_start();

if(empty($_SESSION['id'])){

//try catch文。DBに接続できなければエラーメッセージを表示
try{
    $pdo=new PDO("mysql:dbname=lesson;host=localhost;","root","root");
    }catch(PDOException $e){
    die("<p>申し訳ございません。現在サーバーが混み合っており一時的にアクセスが出来ません。<br>しばらくしてから再度ログインをしてください。</p>
    <a href='http://localhost/login_mypage/login.php'>ログイン画面へ</a>");
    }

//prepared statementでSQL文の型を作る(DBとpostデータを照合させる。select文とwhere句を使用)
$stmt=$pdo->prepare("select*from login_mypage where mail=? && password=?");

//bindValueメソッドでパラメータをセット
$stmt->bindValue(1,$_POST["mail"]);
$stmt->bindValue(2,$_POST["password"]);

//executeでクエリを実行
$stmt->execute();

//データベースを切断
$pdo=NULL;

//fetch, while文でデータ取得し、sessionに代入
while($row=$stmt->fetch()){
    $_SESSION['id']=$row['id'];
    $_SESSION['name']=$row['name'];
    $_SESSION['mail']=$row['mail'];
    $_SESSION['password']=$row['password'];
    $_SESSION['picture']=$row['picture'];
    $_SESSION['comments']=$row['comments'];
}

//データ取得ができずに（empty判定）sessionがなければ、リダイレクト（エラー画面へ）
if(empty($_SESSION['id'])){
    header("Location:login_error.php");
}
}

setcookie('mail',$_SESSION['mail'],time()+60*60*24*7,'/');
setcookie('password',$_SESSION['password'],time()+60*60*24*7,'/');

?>



<!DOCTYPE HTML>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>マイページ登録</title>
    <link rel="stylesheet" type="text/css" href="mypage.css">
</head>

<body>
    <header>
        <img src="4eachblog_logo.jpg">
        <div class="logout"><a href="log_out.php">ログアウト</a></div>
    </header>
    
<main>
    <form action="mypage_hensyu.php" method="post" class="form_center">
    <div class="registered">
        <h2>会員情報</h2>
        
        <div class="hello"><?php echo "こんにちは！".$_SESSION['name']."さん";?></div>
        <div class="profile_pic">
            <img src="<?php echo $_SESSION['picture'];?>">
        </div>
        <div class="info">
            氏名：<?php echo $_SESSION['name'];?><br>
            メール：<?php echo $_SESSION['mail'];?><br>
            パスワード：<?php echo $_SESSION['password'];?><br>
        </div>
        <div class="comments"><?php echo $_SESSION['comments'];?></div>
        <div class="edit_button">
            <input type="submit" class="button" size="35" value="編集する">
        </div>
    </div>
</main>
  

</body>
    
<footer>
    © 2018 InterNous.inc. All rights reserved
</footer>

</html>