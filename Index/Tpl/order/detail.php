<?php require APP_ROOT.'public/top.php';?>
<?php 
$order_id=pg('order_id');
$order = M('order')->where(array('member_id'=>$member['member_id'],'order_id'=>$order_id))->find();
?>
<div class="order-box2">
	<div class="order-info-box">
		<div class="orders-title">订单号：<?php echo $order['order_number'] ?>&nbsp;&nbsp;&nbsp;&nbsp;状态：<i>
        <?php
		$input=M('input')->where(array('input_id'=>$order['state']))->field('input_name')->find();
		echo $input['input_name'];
		?>
        </i>
        <?php if($order['state']==381){?>
        <a href="index.php?m=order&a=pay&order_id=<?php echo $order_id;?>" class="pays-btn"></a>
        <?php }?>
        </div>
		<div class="order-info">
			<p class="order-number">
				下单时间：<?php echo date('Y-m-d H:i:s',$order['date']) ?><br>
				付款时间：：<?php echo date('Y-m-d H:i:s',$order['pay_date']);?><br>
				
				
			</p><!--order-number-->
		</div><!--order-info-->
	</div><!--order-info-box-->
	
	<div class="order-info-box">
		<div class="orders-title">订单信息</div>
		<div class="order-info">
			<dl class="user-info">
				<dt>收货人信息</dt>
				<dd>
					收 货 人：<?php echo $order['consignee']; ?><br>
					地    址：<?php echo $order['address']; ?><br>
					手机号码：<?php echo $order['phone']; ?>
				</dd>
			</dl>
			
			<dl class="user-info">
				<dt>支付及配送方式</dt>
				<dd>
					支付方式：在线支付<br>
					费用：￥<?php  echo $order['price'];?><br>
				</dd>
			</dl>

		</div><!--order-info-->
	</div><!--order-info-box-->
	
	<!--order-info-box-->
		
	<div class="order-info-box cbb">
		<div class="orders-title">商品列表</div>
		<div class="order-info2">
			<table class="order-table2" cellpadding="0" cellspacing="0">
				<thead>
					<tr><th class="td1">商品图片</th><th class="td2">商品名称</th><th class="td3">商品价格</th><th class="td4">商品数量</th><th class="td5">库存状态</th></tr>
				</thead>
				
				<tbody>
					<?php 
                     $order_goods=M('order_goods')->where(array('order_id'=>$order['order_id']))->select();
					foreach($order_goods as $og_k=>$og_v)
					{
					?>
					<tr>
						<td class="td1"><a href="index.php?m=goods&a=details&goods_id=<?php echo $og_v['goods_id'] ?>"><img src="<?php echo $og_v['goods_img']?>" width="80" height="60" alt="" title=""/></a></td>
						<td class="td2"><a href="index.php?m=goods&a=details&goods_id=<?php echo $og_v['goods_id'] ?>"><?php echo $og_v['goods_name'] ?></a></td>
						<td class="td3 red">￥<?php echo $og_v['price'] ?></td>
						<td class="td4"><?php echo $og_v['number']?></td>
						<td class="td5 cbr">有货</td>
					</tr>
				<?php }?>
				</tbody>
			</table>
		</div><!--order-info2-->
	</div><!--order-info-box-->
	
	<div class="order-info3 clearfix">
		<div class="counts">
			<p class="counts1">总商品金额：￥<?php  echo $order['price'];?><br></p> 
			<p class="counts2">应付总额：<i>￥<?php echo  $order['price'];?></i></p>
		</div><!--counts-->
	</div><!--order-info3-->

</div><!--order-box2-->




<?php require APP_ROOT.'public/bottom.php';?>
<script src="<?php echo APP_ROOT;?>js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo APP_ROOT;?>js/jquery.SuperSlide.2.1.1.js"></script>

<!--商品分类js-->
<script type="text/javascript">
	$(function(){
		var category = $('#category .category-info');
		category.mouseenter(function(){
			$(this).addClass('cur').find('.sub-item').show();
			});
			
		category.mouseleave(function(){
			$(this).removeClass('cur');
			$('.sub-item').hide();
			});	
 		});

</script>

<!--案例轮播js-->
<script type="text/javascript">jQuery(".slideBoxs").slide({mainCell:".bd ul",autoPage:true,effect:"leftLoop",scroll:3,vis:3,easing:"easeOutCirc",trigger:"click",pnLoop:false});</script>

<!--顶部广告展开/关闭js-->
<script type="text/javascript">
	$(function(){
		$('.close-t').toggle(function(){
			$('.top-ad').hide();
			},function(){
				$('.top-ad').show();
				});
		});
</script>

<!--下拉列表js-->
<script type="text/javascript">
$(function(){
	var dropDown = $('.drop-down');
	dropDown.mouseenter(function(){
		$(this).addClass('dhover').find('.drop-box').show()
		}).mouseleave(function(){
			dropDown.removeClass('dhover');
			$('.drop-box').hide();
			});
	});
</script>

<!--返回顶部-->
<script type="text/javascript" src="<?php echo APP_ROOT;?>js/gotop.js"></script>
<script type="text/javascript">
$(function (){
	$(window).toTop({
		showHeight : 500,//设置滚动高度时显示
		speed : 300 //返回顶部的速度以毫秒为单位
	});
});
</script>

<!--显示/隐藏菜单分类-->
<script type="text/javascript">
$(function(){
	var category=$('.category');
	var categoryHead=$('.category-t');
	category.hide();
	
	categoryHead.mouseenter(function(){
		category.show();
		});
		
	category.mouseleave(function(){
			$(this).hide();
			});
	});
</script>
</body>
</html>
