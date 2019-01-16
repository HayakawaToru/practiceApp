<?php
  // 共通変数・関数ファイルを読み込み
  require "functions.php";

  debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
  debug('「　ログアウトページ　');
  debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
  debugLogstart();

  debug('ログアウトする');
  // セッションを削除（ログアウトする）
  session_destroy();
  debug('ログインページへ遷移します。');
  // ログインページへ
  header("Location:login.php");
?>
