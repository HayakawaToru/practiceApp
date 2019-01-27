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


// メッセージ送信先のデータ格納変数
$recieverInfo = getRecieveUser();

?>
<body class="page-1colum">
<?php require "header.php";?>
<!-- メインコンテンツ -->
<div id="contents" class="site-width">
<!-- Main -->
<section id="main">
<form action="makeMessage.php" method="post" class="form msg-form">
    <!-- foreachで送信先情報を展開 -->

    <ul>
      <input type="hidden" name="sender" value="<?php echo $_GET['u_id'];?>">
      <?php
        foreach ($recieverInfo as $key => $val){
          if($val['id']!=$_GET['u_id']){
      ?>
        <li class="list-dm-user">
          <label><input type="radio" name="reciever" value="<?php echo $val['id'];?>"></label>
          <div class="to-user-img">
            <img src='<?php echo validImagePath($val['profile_path']);?>'>
          </div>
          <div class="to-send-name">
            <?php echo $val['name'];?>
          </div>
        </li>
      <?php
        }
      }?>
    </ul>
    <input type="submit" value="メッセージを作成する">
  </form>
</section>
</div>

<?php
require "footer.php";
?>
