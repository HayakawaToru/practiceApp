<?php
// 共通変数と関数を読み込み
require "functions.php";
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　メッセージ登録処理開始　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

if(!empty($_POST)){
  debug('POST送信があります'.print_r($_POST,true));
  $board_id = $_POST['board_id'];
  $send_id = $_POST['send_id'];
  $reciever_id = $_POST['reciever_id'];
  $msg = $_POST['msg'];
  var_dump($_POST);

  try{
    // DB接続
    $dbh = dbConnect();
    $sql = 'INSERT INTO messages(board_id, sender_id, reciever_id, msg) VALUES (:board_id, :sender_id, :reciever_id, :msg)';
    $data = array(":board_id" => (int)$board_id,":sender_id" => (int)$send_id, ":reciever_id" => (int)$reciever_id, ":msg" => $msg);
    $stmt = queryPost($dbh, $sql, $data);
    var_dump($stmt);

    if($stmt){
      debug('クエリ成功しました');
      header("Location:makeMessage.php?b_id=${board_id}");
    }else{
      debug('クエリ失敗しました');
      header("Locaiton.makeMessage.php");
    }
  }catch (Exception $e){
    error_log('エラー発生:'.$e->getMessage());
    // header("Location:makeMessage.php");
  }
}


 ?>
