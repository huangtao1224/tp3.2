<div class="Vx9yKNITbV">
  <div class="DYEWQbXJsD">
    <?php $classify=M('classify')->where(array('classify_id'=>$recursive_classify_id))->find(); echo $classify['classify_name'];?>
  </div>
  <ul class="P3dRtu5cqF">
    <li class="Mq9Rle5IN2">当前位置：</li>
    <li class="slFWr3Xw0b"><a class="xsfEM6Zg6i" href="index.php">首页</a> </li>
    <?php 
if($classify_id!='')
{
	function recursive_crumbs($classify_id)
	{
		$classify=M('classify')->where(array('classify_id'=>$classify_id))->find();
		if($classify['level_id']>2)
		{
			recursive_crumbs($classify['classify_pid']);
			?>
    <li class="slFWr3Xw0b"> <span class="mw912jo74H">-</span> <a class="BMM5w72wMJ" href="<?php echo classify_url($classify['type_id'],$classify['classify_id']);?>"><?php echo $classify['classify_name'];?></a></li>
    <?php
		}
	}
	recursive_crumbs($classify_id);
}?>
  </ul>
</div>
