<?php
// 共通変数と関数の読み込み
require "functions.php";

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　メッセージ送信先選択　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

require "auth.php";

$siteTitle = "メッセージ送信先選択";
require "head.php";

require "header.php";

// メッセージ送信先のデータ格納変数
$recieverInfo = getRecieveUser();
?>

<!-- メインコンテンツ -->
<div id="contents" class="site-width">
<!-- Main -->
<section id="main">
<form action="makeMessage.php" method="post">
    <!-- foreachで送信先情報を展開 -->
    <ul>メッセージの送信先を選んでください
      <input type="hidden" name="sender" value="<?php echo $_GET['u_id'];?>">
      <?php
        foreach ($recieverInfo as $key => $val){
      ?>
        <li>
          <input type="radio" name="reciever" value="<?php echo $val['id'];?>">
          <?php echo $val['name'];?>
        </li>
      <?php } ?>
    </ul>
    <input type="submit" value="メッセージを作成する">
  </form>
</section>
</div>

<?php
require "footer.php";
?>
