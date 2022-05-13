<?php require APP_ROOT.'public/member_top.php';?>
<div class="header-box header-box2">
  <p class="currents">在线支付</p>
  <div class="progress-bar">
    <dl>
      <dt class="on">1</dt>
      <dd>我的购物车</dd>
    </dl>
    <dl>
      <dt class="on">2</dt>
      <dd>确认订单</dd>
    </dl>
    <dl>
      <dt class="on">3</dt>
      <dd>付款</dd>
    </dl>
    <dl>
      <dt class="on">4</dt>
      <dd>支付成功</dd>
    </dl>
  </div>
  <!--progress-bar-->
  <div class="cart-bg1 cart-bg4 "></div>
</div>
<!--header-box-->
<?php
$order_id=pg('order_id');
if($order_id=='')
{
	$data=$_GET;
	if($data['is_success']==TRUE)
	{
		$order=unserialize($data['metadata']);
	}
}
else
{
	$order=M('order')->where(array('order_id'=>$order_id))->find();
}
?>
<div class="cart-main main2">
	<div class="suc-payment">
		<span class="suc-check"></span>
		<div class="suc-info">
			<p class="info-1">恭喜您，支付成功啦！</p>
			<p class="info-2">您已成成功支付<i>￥<?php echo $order['price'];?>元 </i>          <span>订单编号：<i><?php echo $order['order_number'];?></i></span></p>
			<p class="info-3">您还可以：<a href="index.php">继续购物</a>        <a href="index.php?m=order&a=detail&order_id=<?php echo $order['order_id'];?>">查看订单详情</a></p>
		</div>
	</div>
</div>

<?php require APP_ROOT.'public/bottom.php';?>
