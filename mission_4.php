<?php
$dsn='データベース名;charset=utf8';
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn,$user,$password);
//データベース接続

$sql="CREATE TABLE yu"
."("
."id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,"
."name char(32),"
."comment TEXT,"
."date datetime,"
."pass char(32)"
.");";
$stmt=$pdo -> query($sql);
//テーブル作成

/*
$sql='SHOW TABLES';
$result=$pdo -> query($sql);
foreach($result as $row){
echo $row[0];
echo '<br>';
}
echo "<hr>";
//テーブル一覧表示


$sql='SHOW CREATE TABLE yu';
$result=$pdo -> query($sql);
foreach($result as $row){
print_r($row);
}
echo "<hr>";
//テーブルの中身を確認
*/

header('Content-Type: text/html; charset=utf-8');
?>



<?php
$name=$_POST['名前'];
$comment=$_POST['コメント'];
$date=date("Y/m/d H:i:s");
$pass=$_POST['パスワード１'];


if(!empty($_POST['編集対象番号']) && !empty($_POST['パスワード３'])){//編集対象番号とパスワードが入力されたら
$edit=$_POST['編集対象番号'];
$sql='SELECT id,name,comment,pass FROM yu';//yuからidとnameとcommentとpassを取得
$results=$pdo -> query($sql);
foreach($results as $row){
	if($_POST['パスワード３']==$row['pass'] && $_POST['編集対象番号']==$row['id']){//編集対象番号とパスワードが一致したら
	$da0 = $row['id'];
	$da1 = $row['name'];
	$da2 = $row['comment'];
	$da3 = $row['date'];
	$da4 = $row['pass'];
	}
	else if($_POST['パスワード３']!==$row['id'] && $_POST['編集対象番号']==$row['id']){//編集対象番号〇,パスワード×のとき
	echo "一致しません";
	}
}//ループ終わり
}
//フォームに表示


if(!empty($_POST['名前']) && !empty($_POST['コメント'])){//もし名前とコメントが書かれたら

	if(!empty($_POST['番号'])){//名前コメントあり、番号ありのとき
	$nm=$_POST['番号'];
	//編集用のコード
	$sql='SELECT id,name,comment FROM yu';//yuからidとnameとcommentを取得
	$sql="update yu set name ='$name',comment ='$comment' where id ='$nm'";
	$result=$pdo -> query($sql);
/*
 echo "編集対象番号:".$nm."<br>";
 echo "名前:".$name."<br>";
 echo "コメント:".$comment."<br>";
*/
	//編集おわり
}else{
	//新規投稿用のコード
	$sql=$pdo -> prepare("INSERT INTO yu(id,name,comment,date,pass)VALUES(:id,:name,:comment,:date,:pass)");
	$sql -> bindParam(':id',$id,PDO::PARAM_INT);//数値
	$sql -> bindParam(':name',$name,PDO::PARAM_STR);//文字列
	$sql -> bindParam(':comment',$comment,PDO::PARAM_STR);//文字列
	$sql -> bindParam(':date',$date,PDO::PARAM_STR);//$dateを定義してbindParmにしてみた
	$sql -> bindParam(':pass',$pass,PDO::PARAM_STR);//文字列
	$sql -> execute();//実行
	
}}
//データ入力、HTMLとの接続？？


if(!empty($_POST['削除対象番号']) && !empty($_POST['パスワード２'])){//削除対象番号とパスワードが入力されたら
$del=$_POST['削除対象番号'];

$sql='SELECT id,pass FROM yu';//yuからidとpassを取得
$results=$pdo -> query($sql);
foreach($results as $row){

	if($row['id']==$del && $row['pass']==$_POST['パスワード２']){//番号とパスワードが一致すると消える、成功
	$sql="delete from yu where id=$del";//idはカラム（項目）名なので$なし
	$result=$pdo -> query($sql);
	}

}//ループ終わり

	if($row['pass']!==$_POST['パスワード２']){//一致しないとき表示、成功。ループ終わり後に置く！
	echo"一致しません".'<br>';
	}
}
//削除
?>

<html>
  <head>
  <meta charset="utf-8"><!--日本語にする-->
  </head>

  <body>
	<form action="mission_4.php"method="post"><!--mission_4.phpに送信-->
	<input type="text"name="名前"value="<?php echo $da1;?>"placeholder="名前"><br /><!--代入-->
	<input type="text"name="コメント"value="<?php echo $da2;?>"placeholder="コメント"><br /><!--代入-->
	<input type="text"name="パスワード１"placeholder="パスワード">
	<input type="submit"value="送信"><br/><!--送信ボタンの名前を変える--><br />
	<input type="text"name="削除対象番号"placeholder="削除対象番号"><br /><!--テキストファイルに「削除対象番号」と表示させる-->
	<input type="text"name="パスワード２"placeholder="パスワード">
	<input type="submit"value="削除"><!--送信ボタンの名前を変える--><br />
	<input type="hidden"name="番号"value="<?php echo $da0;?>"placeholder="番号"><br />
	<input type="text"name="編集対象番号"placeholder="編集対象番号"><br /><!--テキストファイルに「編集対象番号」と表示させる-->
	<input type="text"name="パスワード３"placeholder="パスワード">
	<input type="submit"value="編集"><!--送信ボタンの名前を変える-->
	</form>
  </body>
</html>


<?php
$sql='SELECT * FROM yu';
$results=$pdo -> query($sql);
foreach($results as $row){
 echo $row['id'].' ';
 echo $row['name'].' ';
 echo $row['comment'].' ';
 echo $row['date'].'<br>';
}
//データを表示


?>
