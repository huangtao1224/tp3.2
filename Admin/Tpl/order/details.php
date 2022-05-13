<?php require APP_ROOT.'public/head.php';?>
<style>
    body{overflow: auto}
    body::-webkit-scrollbar {width : 8px;height: 8px;background: transparent;}
    body::-webkit-scrollbar-thumb {border-radius: 8px;box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);background:#666;}
    body::-webkit-scrollbar-track {background:transparent;}
</style>
<div class="ajaxcontent">
    <?php
    $order_id=pg('order_id');
    $order = M('order')->where(array('order_id'=>$order_id))->find();
    ?>
    <div class="order-box2">
        <div class="order-info-box">
            <div class="orders-title">订单号：<?php echo $order['order_number'] ?><i></i><a href="#" class="pays-btn"></a></div>
            <div class="order-info">
                <p class="order-number">
                    下单时间：<?php echo cover_time($order['date'],'Y-m-d H:i:s') ?><br>
                    付款时间：<?php echo cover_time($order['pay_date'],'Y-m-d H:i:s') ?><br>
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
                        手机号码：<?php echo $order['phone']; ?><br>
                        IP：<?php echo $order['ip']; ?><br>
                        所在地区：<?php echo $order['ip_address']; ?><br>
                        来源地址：<?php echo $order['source_url']; ?>
                    </dd>
                </dl>
                <dl class="user-info">
                    <dt>支付及配送方式</dt>
                    <?php $pay_type=M('input')->where(array('input_pid'=>487,'input_value'=>$order['pay_type']))->getField('input_name')?>
                    <dd>
                        支付方式：<?php echo $pay_type?><br>
                        费用：￥<?php  echo $order['price'];?><br>
                    </dd>
                </dl>
                <dl class="user-info">
                    <dt>留言</dt>
                    <dd>
                        <?php  echo $order['message'];?><br>
                    </dd>
                </dl>
                <dl class="user-info">
                    <dt>状态:<?php $input=M('input')->where(array('input_pid'=>380,'input_value'=>$order['state']))->field('input_name,color')->find();?>
                    <span style="color:<?php echo $input['color'];?>; font-weight:600;"><?php echo $input['input_name'];?></span></dt>
                </dl>
		    </div>
	    </div>
	    <div class="order-info-box cbb">
		    <div class="orders-title">商品列表</div>
		    <div class="order-info2">
			    <table class="order-table2" width="100%" cellpadding="0" cellspacing="0">
				    <thead>
					    <tr>
                            <th class="td1">商品图片</th>
                            <th class="td2">商品名称</th>
                            <th class="td2">产品属性</th>
                            <th class="td3">商品价格</th>
                            <th class="td4">商品数量</th>
                            <th class="td5">库存状态</th>
                        </tr>
				    </thead>
				    <tbody>
                        <?php
                         $order_goods=M('order_goods')->where(array('order_id'=>$order['order_id']))->select();
                        foreach($order_goods as $og_k=>$og_v)
                        {
                        ?>
					    <tr>
						    <td class="td1"><a target="_blank" href="index.php?m=goods&a=details&ontent_id=<?php echo $og_v['goods_id'] ?>"><img src="<?php echo $og_v['goods_img']?>" width="80" height="60" alt="" title=""/></a></td>
						    <td class="td2"><a target="_blank" href="index.php?m=goods&a=details&content_id=<?php echo $og_v['goods_id'] ?>"><?php echo $og_v['goods_name'] ?></a></td>
                            <?php
                            $name=unserialize($og_v['name']);
                            $value=unserialize($og_v['value']);
                            if(!empty($name)){
                            ?>
					        <td class="td3 red lineheight">
						        <?php foreach($name as $k2=>$v2){?>
                                <p><span style="margin-right:15px"><?php echo $v2;?>：</span><span><?php echo $value[$k2];?></span></p>
							    <?php }?>
                            </td>
                            <?php }else{?>
                            <td class="td3 red">无属性商品</td>
                            <?php }?>
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
		    <div class="counts">应付总额：<i>￥<?php echo  $order['price'];?></i></div><!--counts-->
	    </div><!--order-info3-->
    </div>
</div>
<?php require APP_ROOT.'public/foot.php';?>
