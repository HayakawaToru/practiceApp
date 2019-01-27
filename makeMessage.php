<?php
// 共通変数と関数の読み込み
require "functions.php";
require "auth.php";
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　メッセージ画面処理　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
debug('SESSION情報：'.print_r($_SESSION, true));
$siteTitle = "メッセージ作成";
require "head.php";

if(!empty($_GET['b_id'])){
  $board_id = $_GET['b_id'];
  $msgTableData = getMsgsAndBoard($board_id);
}

if(!empty($_POST)){
  $send_id = $_POST['sender'];
  $reciever_id = $_POST['reciever'];
}else{
  $send_id = $_SESSION['send_id'];
  $reciever_id = $_SESSION['reciever_id'];
}
if(isset($send_id) && isset($reciever_id)){

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


<body class="page-1colum">
  <?php
  require "header.php";

  // DMヘッダーの送信先情報の取得
  $recieverInfo = getUser($reciever_id);
  ?>

  <div id="contens" class="site-width">
    <div class="content-wrap">
    <div class="area-board">
      <div class="board-header">
        <div class="header-wrap">
          <div class="board-user-img">
            <img src='<?php echo validImagePath($recieverInfo['profile_path']);?>'>
          </div>
          <div class="board-user-name">
            <span><?php echo $recieverInfo['name'];?></span>
          </div>
        </div>
      </div>
    <!-- ログインユーザーのメッセージを右側に配置するためにif文でclassを付与 -->
    <div class="msg-area-wrap">
    <?php
      foreach($msgTableData as $key => $val){
    ?>

    <div class="each-msg
    <?php if($val['sender_id'] != $_SESSION['user_id']) {
      echo 'msg-left';
    }else{
      echo 'msg-right';
    }?>">

      <?php if($val['sender_id'] != $_SESSION['user_id']){
        $userInfo = getUser($val['sender_id']);
      ?>
      <div class="left-user-img">
        <img src='<?php echo validImagePath($userInfo['profile_path']);?>'>
      </div>
      <div class="msg-wrap left-msg-wrap">
        <span><?php echo $val['msg'];?></span>
      </div>
    <?php }else if($val['sender_id'] == $_SESSION['user_id']){
      $userInfo = getUser($val['sender_id']);
      ?>
      <div class="msg-wrap right-msg-wrap">
        <span><?php echo $val['msg'];?></span>
      </div>
      <div class="right-user-img">
        <img src='<?php echo validImagePath($userInfo['profile_path']);?>'>
      </div>
      <?php } ?>
    </div>
  <?php
    }
  ?>
</div>
</div>
<form action="msgPost.php" method="post">
  <input type="hidden" name="board_id" value="<?php echo $board_id;?>">
  <input type="hidden" name="send_id" value="<?php echo $send_id;?>">
  <input type="hidden" name="reciever_id" value="<?php echo $reciever_id;?>">
  <input type="text" name="msg">
  <input type="submit" value="送信">
</form>
</div>
</div>
<?php require "footer.php";?>
