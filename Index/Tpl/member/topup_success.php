<?php require APP_ROOT.'public/member_top.php';?>
<div class="header-box header-box2">
  <p class="currents">在线充值</p>
</div>
<?php
$data=$_GET;
if($data['is_success']==TRUE){
?>
<div class="cart-main main2">
	<div class="suc-payment">
		<span class="suc-check"></span>
		<div class="suc-info">
			<p class="info-1">恭喜您，充值成功啦！</p>
			<p class="info-2">您已成成功充值<i>￥<?php echo $data['amount'];?>元 </i></p>
			<p class="info-3">您还可以：<a href="index.php?m=member&a=balance">查看资金明细</a></p>
		</div>
	</div>
</div>
<?php }?>

<?php require APP_ROOT.'public/bottom.php';?>
