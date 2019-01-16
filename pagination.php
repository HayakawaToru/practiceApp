<div class="pagination">
  <ul class="pagination-list">
    <?php
      $pageColNum = 5;
      $totalPageNum = $dbPostData['total_page'];
      // 現在のページが、総ページ数と同じ、かつ総ページ数が表示項目数以上なら左にリンクを４つだす
      if($currentPageNum == $totalPageNum && $totalPageNum >= $pageColNum){
        $minPageNum = $currentPageNum -4;
        $maxPageNum = $currentPageNum;
      }else if($currentPageNum == ($totalPageNum-1) && $totalPageNum >= $pageColNum){
        $minPageNum = $currentPageNum -3;
        $maxPageNum = $currentPageNum +1;
      }else if($currentPageNum == 2 && $totalPageNum >= $pageColNum){
        $minPageNum = $currentPageNum -1;
        $maxPageNum = $currentPageNum +3;
      }else if($currentPageNum == 1 && $totalPageNum >= $pageColNum){
        $minPageNum = $currentPageNum;
        $maxPageNum = 5;
      }else if($totalPageNum < $pageColNum){
        $minPageNum = 1;
        $maxPageNum = $totalPageNum;
      }else{
        $minPageNum = $currentPageNum -2;
        $maxPageNum = $currentPageNum +2;
      }
    ?>

<?php if($currentPageNum != 1): ?>
  <li class="list-item"><a href="?p=1">&lt;</a></li>
<?php endif; ?>
<?php
  for($i = $minPageNum; $i <= $maxPageNum; $i++):
?>
<li class="list-item <?php if($currentPageNum == $i) echo 'active'; ?>"><a href="?p=<?php echo $i;?>"><?php echo $i;?></a></li>
<?php
  endfor;
?>
<?php if($currentPageNum != $maxPageNum):?>
  <li class="list-item"><a href="?p=<?php echo $maxPageNum;?>">&gt;</a></li>
<?php endif; ?>
</ul>
</div>
