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
      <?php $goods=M('goods')->where(array('goods_id'=>$content_id))->find();?>
      <div class="f14464854883"> <img class="f14464854884" src="<?php echo $goods['goods_bigimg'];?>" /> </div>
      <div class="f14464854885"> <span class="f14464854888"><?php echo $goods['goods_name'];?></span> </div>
      <div class="f144648548813"> <?php echo $goods['goods_content'];?> </div>
    </div>
  </div>
</div>
<?php require APP_ROOT.'public/bottom.php';?>
