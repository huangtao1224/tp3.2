<?php require APP_ROOT.'public/top.php';?>
<div class="header-box header-box2"> 
  <p class="currents">购物车</p>
  <div class="progress-bar">
    <dl>
      <dt class="on">1</dt>
      <dd>我的购物车</dd>
    </dl>
    <dl>
      <dt>2</dt>
      <dd>确认订单</dd>
    </dl>
    <dl>
      <dt>3</dt>
      <dd>付款</dd>
    </dl>
    <dl>
      <dt>4</dt>
      <dd>支付成功</dd>
    </dl>
  </div>
  <!--progress-bar-->
  <div class="cart-bg1"></div>
</div>
<!--header-box-->
<div class="cart-main cart_pad">
  <p class="cart-all">全部商品</p>
  <div class="cart-thead clearfix">
    <div class="t-checkbox">
      <input type="checkbox" onclick="SelectAll('cart_id[]','all_content2')" name="all_content1" checked="checked" value="1"/>
      <label>全选</label>
    </div>
    <div class="t-goods">商品信息</div>
    <div class="t-price">单价(元)</div>
    <div class="t-quantity">数量</div>
    <div class="t-sum">小计(元)</div>
    <div class="t-action">操作</div>
  </div>
  <!--cart-thead-->
  <form action="index.php?m=cart&a=sure" method="post" enctype="application/x-www-form-urlencoded" onsubmit="return return_mycart()">
  <?php
		$cart=M('cart')->where(array('member_id'=>$member['member_id']))->order('date desc')->select();
		foreach($cart as $k=>$v)
		{
			$goods=M('goods')->where(array('goods_id'=>$v['goods_id']))->find();
			$goods_price=$v['price']*$v['number'];//商品总价
	?>
  <div class="column clearfix bgcolor">
    <div class="t-checkbox">
      <input type="checkbox" name="cart_id[]" onclick="cart_total_amount()" value="<?php echo $v['cart_id'];?>" checked="checked"/>
      <a href="<?php echo  content_url(3,$v['goods_id']);?>" class="t-pro"><img src="<?php echo $goods['goods_img'];?>" width="82" height="82"/></a></div>
    <div class="t-goods"><a href="<?php echo  content_url(3,$v['goods_id']);?>"><?php echo $goods['goods_name'] ?></a>
      <p>
          <?php
			$name=unserialize($v['name']);
			$value=unserialize($v['value']);
		   foreach($name as $k2=>$v2)
		   {
			   echo $v2.':'.$value[$k2].'<br>';
		   }
		   ?>
	</p>
    </div>
    <div class="t-price"><?php echo $v['price'] ?></div>
    <div class="t-quantity">
      <p class="t-q"><span onclick="cart_control_number('reduction','number<?php echo $v['cart_id'];?>',<?php echo $v['goods_id'];?>,<?php echo $v['cart_id'];?>)">-</span>
        <input name="number[]" type="text" id="number<?php echo $v['cart_id'];?>" onKeyUp="cart_control_number('onKeyUp','number<?php echo $v['cart_id'];?>',<?php echo $v['goods_id'];?>,<?php echo $v['cart_id'];?>)" value="<?php echo $v['number'];?>" readonly="readonly" />
        <span onclick="cart_control_number('add','number<?php echo $v['cart_id'];?>',<?php echo $v['goods_id'];?>,<?php echo $v['cart_id'];?>)">+</span></p>
    </div>
    <input name="product_amount[]" id="goods_price_<?php echo $v['cart_id'];?>" type="hidden" value="<?php echo $goods_price;?>" />
    <div class="t-sum" id="sum_<?php echo $v['cart_id'];?>"><?php echo $v['price']*$v['number'];?></div>
    <div class="t-action"><a href="javascript:;" style="display:none;" onclick="if(confirm('确定移入收藏夹吗!')){moved_favorites(<?php echo $v['cart_id'];?>,$(this))}">移入收藏夹</a><a href="javascript:;" onclick="if(confirm('确定从购物车中删除吗!')){del_cart(<?php echo $v['cart_id'];?>,$(this))}">删除</a></div>
  </div>
  <!--column-->
    <?php }
  if(empty($list)){
  ?>
  <div class="cart_empty">
    <em></em> 您的购物车还没有东西！
    </div>
    <?php }?>
  <div class="options-box clearfix">
    <input type="checkbox" onclick="SelectAll('cart_id[]','all_content1')" name="all_content2" checked="checked" value="1"/>
    <label>全选</label>
    <div class="o-action"><a href="javascript:;" onclick="if(confirm('确定批量从购物车中删除吗!')){batch_del_cart()}">删除</a></div>
    <div class="o-count"><input name="" class="submit" type="submit" value="结算" />
      <p class="p2">合计（不含运费）：<b id="cart_total_amount">¥</b></p>
      <p class="p1">已选商品<b class="number_b"></b>件</p>
    </div>
  </div>
  </form>
  <!--options-box--> 
</div>
<!--cart-main-->

<?php require APP_ROOT.'public/bottom.php';?>
<script type="text/javascript">
$(function(){
	cart_total_amount();
});
function SelectAll(name,all_name){ 
var checkboxs=document.getElementsByName(name); for (var i=0;i<checkboxs.length;i++) {  var e=checkboxs[i];  e.checked=!e.checked; }
var checkboxs=document.getElementsByName(all_name); for (var i=0;i<checkboxs.length;i++) {  var e=checkboxs[i];  e.checked=!e.checked; }
cart_total_amount();
}
</script> 
