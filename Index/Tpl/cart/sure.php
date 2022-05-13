<?php require APP_ROOT.'public/top.php';?>
<div class="header-box header-box2">
  <p class="currents">购物车</p>
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
      <dt>3</dt>
      <dd>付款</dd>
    </dl>
    <dl>
      <dt>4</dt>
      <dd>支付成功</dd>
    </dl>
  </div>
  <!--progress-bar-->
  <div class="cart-bg1 cart-bg2 "></div>
</div>
<!--header-box-->

<input id="freight_switch" type="hidden" value="2" />    
<div class="cart-main main2">
  <label class="xdz" id="xdz01" onclick="select_address($(this))">
    <input name="address_type" type="radio" value="1" checked="checked" />
    选择收货地址</label>
  <p class="xdz">选择收货地址</p>
  <div class="address-list address-list2">
    <ul class="clearfix">
      <?php 
			$list=M('address')->where(array('member_id'=>$member['member_id']))->select();
			foreach($list as $k=>$v){
				$province=M('region')->where(array('region_id'=>$v['province']))->find();
				$city=M('region')->where(array('region_id'=>$v['city']))->find();
				$area=M('region')->where(array('region_id'=>$v['area']))->find();
				if($v['state']==411)$address_id=$v['address_id'];
			?>
      <li class="<?php echo $v['state']==411?'defalut':'reset';if(($k+1)%4==0)echo ' cmr';?>" onclick="use_address(<?php echo $v['address_id'];?>,$(this))"> <a href="#" class="mo">默认地址</a>
        <div class="div1"><em></em><?php echo  $v['consignee'] ?></div>
        <div class="div2"><em></em><?php echo $province['region_name'].' '.$city['region_name'].' '.$area['region_name'].' '.$v['address']; ?></div>
        <div class="div3"><a href="index.php?m=member&a=update_adr&address_id=<?php echo $v['address_id'] ?>">修改</a><em></em>
          <?php $v['phone'] ?>
        </div>
      </li>
      <?php }?>
    </ul>
  </div>
  <!--address-list-->
 <label class="xdz" id="xdz02" onclick="select_address($(this))">
    <input name="address_type" type="radio" value="2"/>
    使用其它收货地址</label>
  <div class="add-news hide">
    <form method="post" id="form_menu" action="index.php?f=order&w=add_address_save" >
      <div class="text1">
        <label><span>*</span>收货人：</label>
        <input type="text" name="data[consignee]" id="consignee" placeholder="收货人姓名"/>
      </div>
      <div class="detials">
        <label><span>*</span>地址：</label>
        <select name="province" id="province" onChange="menu_ca($(this),'province')">
          <?php $adr1=M('region')->where('region_pid=1')->select();
						    foreach($adr1 as $k=>$va){;
						  ?>
          <option value="<?php echo $va['region_id'] ?>"><?php echo$va['region_name'];?></option>
          <?php }?>
        </select>
        <select name="city" id="city" onChange="menu_ca($(this),'city')" >
          <?php $adr2=M('region')->where('region_pid='.$adr1[0]['region_id'])->select();
						    foreach($adr2 as $kk=>$vaa){;
						  ?>
          <option value="<?php echo $vaa['region_id'] ?>"><?php echo  $vaa['region_name']?></option>
          <?php }?>
        </select>
        <select name="area" id="area" onChange="menu_ca($(this),'area')">
          <?php $adr3=M('region')->where('region_pid='.$adr2[0]['region_id'])->select();
						 
						    foreach($adr3 as $k3=>$va3){;
						  ?>
          <option value="<?php echo $va3['region_id'] ?>"><?php echo  $va3['region_name']?></option>
          <?php } ?>
        </select>
        <input type="text" placeholder="详细地址" class="text2" name="data[address]" id="address"/>
      </div>
      <div class="text1">
        <label><span>*</span>联系电话：</label>
        <input type="text" name="data[phone]" id="phone" placeholder="手机号/固定电话"/>
      </div>
      <div class="btn1">
        <input type="button" id="add_address_btn" onclick="add_address_save()" value="保存收货地址"/>
        <font id="address_prompt" class="red"></font></div>
    </form>
  </div>
  <p class="gldz"><a href="index.php?m=member&a=myaddress" target="_blank">管理收货地址</a></p>
  <div class="back-cart"><a href="index.php?m=cart&a=index">返回购物车修改</a></div>
  <form action="index.php?m=order&a=card_save" method="post" enctype="application/x-www-form-urlencoded" onSubmit="return return_mycart1()">
    <p class="xdz">确认订单信息</p>
    <div class="cart-thead clearfix">
      <div class="t-checkbox">&nbsp;&nbsp;</div>
      <div class="t-goods t-goods1">商品信息</div>
      <div class="t-price">单价(元)</div>
      <div class="t-quantity">数量</div>
      <div class="t-sum">小计(元)</div>
      <div class="t-action t-action2">配送方式</div>
    </div>
    <!--cart-thead-->
    <input name="address_id" id="address_id" type="hidden" value="<?php echo $address_id;?>" />
    <?php 
		$cart_id=pg('cart_id');
		$cart=M('cart')->where(array('member_id'=>$member['member_id'],'cart_id'=>array('in',$cart_id)))->order('date desc')->select();
		$sum=0;
		foreach($cart as $k=>$v){
			$goods=M('goods')->where(array('goods_id'=>$v['goods_id']))->find();
			$goods_price=$v['price']*$v['number'];
			$sum+=$goods_price;
	?>
    <div class="column clearfix">
      <input name="cart_id[]" type="hidden" value="<?php echo $v['cart_id'];?>" />
      <div class="t-checkbox"><span></span><a href="index.php?m=goods&a=details&goods_id=<?php echo $v['goods_id'];?>" class="t-pro"><img src="<?php echo $goods['goods_img'] ?>" width="82" height="82"/></a></div>
      <div class="t-goods t-goods1 "><a href="index.php?m=goods&a=details&goods_id=<?php echo $v['goods_id'];?>"><?php echo $goods['goods_name'];?></a>
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
      <div class="t-price"><?php echo $v['price']; ?></div>
      <div class="t-quantity">
        <p class="t-q"><?php echo $v['number'];?></p>
      </div>
      <div class="t-sum"><?php echo $goods_price; ?></div>
      <div class="t-action t-action2"> </div>
    </div>
    <!--column-->
    <?php }?>
    <p class="s-sum">
    <?php $coupons_member = M('coupons_member')->where(array('state'=>277,'member_id'=>$member['member_id']))->select();
	if(!empty($coupons_member)){
	?>
	<?php foreach($coupons_member as $k_cm=>$v_cm){?>
	<input id="coupons_price<?php echo $v_cm['coupons_member_id'];?>" type="hidden" value="<?php echo $v_cm['price'];?>" />
	<?php }?>
    <label>
    <input name="coupons" id="coupons" type="checkbox" value="1" onclick="use_coupons()" />使用优惠券
    </label>
    <select name="coupons_member_id" id="coupons_member_id" onchange="use_coupons()">
	<?php foreach($coupons_member as $k_cm=>$v_cm){?>
    <option value="<?php echo $v_cm['coupons_member_id'];?>"><?php echo $v_cm['price'];?> 元 </option>
    <?php } ?>
    </select>
    <?php }?>
        <input name="total_amount" id="total_amount" type="hidden" value="<?php echo $sum;?>" />
      <input type="radio" checked="checked"/>
      普通配送
      <select class="courier" name="freight" id="freight_select">
        <option>包邮 0 元 </option>
      </select>
      店铺合计(不含运费): <?php echo $sum; ?> </p>
    <p class="s-count">实付款：<i>¥<span class="total_amount_html"><?php echo $sum; ?></span></i></p>
    <div class="s-btn">
      <input name="sum" type="hidden" value="<?php echo $sum;?>" />
      <input name="" class="submit" type="submit" value="提交订单" />
      </div>
  </form>
</div>
<!--cart-main-->

<?php require APP_ROOT.'public/bottom.php';?>
<script src="<?php echo APP_ROOT;?>js/jquery-1.7.2.min.js" type="text/javascript"></script> 
<script type="text/javascript" src="<?php echo APP_ROOT;?>js/jquery.SuperSlide.2.1.1.js"></script> 

<!--商品分类js--> 
<script type="text/javascript">
	$(function(){
		freight_total();
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
</body></html>