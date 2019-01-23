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

if(!empty($_GET['b_id'])){
  $board_id = $_GET['b_id'];
  $msgTableData = getMsgsAndBoard($board_id);
}

if(!empty($_POST['reciever'])){
  $send_id = $_POST['sender'];
  $reciever_id = $_POST['reciever'];

  debug('POST情報：'.print_r($_POST,true));
  // 例外処理
  try{
    debug('ボード情報の検索');
    $dbh = dbConnect();
    $sql = 'SELECT id FROM boards WHERE sender_id = :send_id AND reciever_id = :reciever_id';
    $data = array(":send_id" => $send_id, ":reciever_id" => $reciever_id);
    $stmt = queryPost($dbh, $sql, $data);
    $board_id = $stmt->fetch();

    if($board_id == false){
      debug('ボード情報データを交換して開始');
      $data = array(":send_id" => $reciever_id, ":reciever_id" => $send_id);
      $stmt = queryPost($dbh, $sql, $data);
      $board_id = $stmt->fetch();
    }

    if($board_id != false){
      debug('登録済みの掲示板です');
      $board_id = (int)$board_id['id'];
      $msgTableData = getMsgsAndBoard($board_id);
    }else{
      debug('新規登録掲示板です');
      $sql = 'INSERT INTO boards(sender_id, reciever_id) VALUES (:sender_id, :reciever_id)';
      $data = array(":sender_id" => $send_id,":reciever_id" => $reciever_id);
      $stmt = queryPost($dbh, $sql, $data);
      $board_id = $dbh->lastInsertID();
      // var_dump($board_id);

      $msgTableData = getMsgsAndBoard($board_id);
    }
  }catch(Exception $e){
    error_log('エラー発生:'.$e->getMessage());
  }
}
?>

<div class="area-board">
  <?php
    foreach($msgTableData as $key => $val){
  ?>
    <div>
      <span><?php echo $val['msg']?></span>
    </div>
  <?php
    }
  ?>
  <form action="msgPost.php" method="post">
    <input type="hidden" name="board_id" value="<?php echo $board_id;?>">
    <input type="hidden" name="send_id" value="<?php echo $send_id;?>">
    <input type="hidden" name="reciever_id" value="<?php echo $reciever_id;?>">
    <input type="text" name="msg">
    <input type="submit" value="送信">
  </form>
</div>
