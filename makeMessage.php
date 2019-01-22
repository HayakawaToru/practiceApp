<?php
// 共通変数と関数の読み込み
require "functions.php";

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　メッセージ画面処理　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

$siteTitle = "メッセージ作成";
require "head.php";

require "header.php";

if(!empty($_POST['reciever'])){
  $send_id = $_POST['sender'];
  $reciever_id = $_POST['reciever'];

  debug('POST情報：'.print_r($_POST,true));
  // 例外処理
  try{
    $dbh = dbConnect();
    $sql = 'SELECT id FROM boards WHERE sender_id = :send_id AND reciever_id = :reciever_id';
    $data = array(":send_id" => $send_id, ":reciever_id" => $reciever_id);
    $stmt = queryPost($dbh, $sql, $data);
    debug('stmtinfo'.print_r($stmt, true));
    $board_id = $stmt->fetch();

    if($board_id != false){
      debug('クエリ成功しました。登録済みの掲示板です');
      $board_id = (int)$board_id['id'];
      var_dump($board_id);

      $msgTableData = getMsgsAndBoard($board_id);
      var_dump($msgTableData);

    }else{
      debug('クエリ失敗しました。新規登録掲示板です');
      $sql = 'INSERT INTO boards(sender_id, reciever_id) VALUES (:sender_id, :reciever_id)';
      $data = array(":sender_id" => $send_id,":reciever_id" => $reciever_id);
      $stmt = queryPost($dbh, $sql, $data);
      $msgTableData = getMsgsAndBoard($dbh->lastInsertID());
      var_dump($msgTableData);

    }

  }catch(Exception $e){
    error_log('エラー発生:' . $e->getMessage());
  }
}
?>

<div class="area-board">
</div>
