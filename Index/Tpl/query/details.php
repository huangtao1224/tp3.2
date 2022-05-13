<?php require APP_ROOT.'public/top.php';?>
<div class="g00002">
  <div class="g00003" id="left">
    <?php require APP_ROOT.'public/left.php';?>
  </div>
  <div class="g00004" id="right">
    <div class="f14464848855">
      <div class="f14464848856">
        <?php require APP_ROOT.'public/right.php';?>
      </div>
    </div>
    <div class="f14464854882">
		<?php
		$data=pg('data');
		$query=M('query')->where($data)->find();
		if(empty($query)){
		?>
	   没有查询到结果！
	   <?php }else{?>
       <?php echo $query['query_content'];?>
       <?php }?>
    </div>
  </div>
</div>
<?php require APP_ROOT.'public/bottom.php';?>
