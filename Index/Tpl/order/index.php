<?php require APP_ROOT.'public/member_top.php';?>

<div class="bg">
    <div class="member_main">
      <?php require APP_ROOT.'public/member_left.php'; ?>
    <div class="member_right">
      <?php 
	   $member=session('member');
	   if(!empty($member))
	   {
			$member_id=$member['member_id'];
			$user=M('member')->where(array('member_id'=>$member_id))->find();
		}
	?>
      <div class="main-box">
        <h6 class="main-t">我的订单</h6>
        <div class="orders">
          <div class="remind clearfix">
            <?php
				$state=pg('state');
				?>
            <p><em></em>提醒:</p>
            <ul>
              <?php
					$dfk_count=M('order')->where(array('state'=>381,'member_id'=>$member['member_id']))->count();//待付款
					$dfh_count=M('order')->where(array('state'=>382,'member_id'=>$member['member_id']))->count();//待发货
					$dsh_count=M('order')->where(array('state'=>383,'member_id'=>$member['member_id']))->count();//待收货
					?>
              <li><a href="index.php?m=order&a=index&state=381">待付款订单 (<?php echo $dfk_count;?>)</a></li>
              <li><a href="index.php?m=order&a=index&state=382">待发货订单(<?php echo $dfh_count;?>) </a></li>
              <li><a href="index.php?m=order&a=index&state=383">待收货订单 (<?php echo $dsh_count;?>)</a></li>              
            </ul>
          </div>

          <div class="find clearfix"> <i>查找订单</i>
            <form>
              <select>
                <option>所有订单</option>
                <option>待付款订单</option>
                <option>待确认收货订单</option>
                <option>待发货订单</option>
                <option>已取消订单</option>
              </select>
              <input type="text" value="" class="text"/>
              <input type="button" value="查询" class="button"/>
            </form>
          </div>
          <!--find-->
          
          <div class="order-list">
            <table cellpadding="0" cellspacing="0" class="table">
              <thead>
                <tr>
                  <th>订单商品</th>
                  <th>收货人</th>
                  <th>订单金额</th>
                  <th>数量</th>
                  <th>订单状态</th>
                  <th class="th5">操作</th>
                </tr>
              </thead>
              <?php 
					$perpage=5;
					$offset=($p-1)*$perpage;//偏移量
					$order=M('order')->where(array('member_id'=>$member['member_id']))->limit($offset,$perpage)->order('date desc')->select();
					$total_num=M('order')->where(array('member_id'=>$member['member_id']))->count();
					foreach($order as $k=>$v)
					{
					 ?>
              <tbody>
                <tr>
                  <td colspan="6" class="info"><input type="checkbox" checked="checked"/>
                    <?php echo date('Y-m-d H:i:s',$v['date']) ?> <span>订单号：<a href="#"><?php echo $v['order_number'] ?></a></span></td>
                </tr>
                <?php
						$order_goods=M('order_goods')->where(array('order_id'=>$v['order_id']))->select();
						foreach($order_goods as $og_k=>$og_v)
						{
						?>
                <tr>
                  <td class="td1"><a href="index.php?m=goods&a=details&content_id=<?php echo $og_v['goods_id'];?>" class="fl"><img src="<?php echo $og_v['goods_img']?>" width="100" height="80"/></a>
                    <p>
                    <a href="index.php?m=goods&a=details&goods_id=<?php echo $og_v['goods_id'];?>" class="fl"><?php echo $og_v['goods_name'] ?></a>
          <?php
		 $name=unserialize($og_v['name']);
		 $value=unserialize($og_v['value']);
		   foreach($name as $k2=>$v2)
		   {
			   echo $v2.':'.$value[$k2].'<br>';
		   }
		   ?>
                       </p></td>
                  <!--title-o-->
                  <td class="td2"><?php echo $v['consignee'] ?></td>
                  <td class="td3">¥<?php echo $og_v['price'] ?></td>
                  <td class="td2"><?php echo $og_v['number']; ?></td>
                  <?php if($og_k==0){?>
                  <td class="td4" rowspan="<?php echo count($order_goods);?>"><b>¥<?php echo $v['price'];?></b><br />
                    <br />
                    (含运费：+<?php echo $v['freight'];?>)<br />
                    <br />
                    <?php if($v['coupons_price']>0){?>
                    (优惠券抵扣：-<?php echo $v['coupons_price'];?>)<br />
                    <br />
                    <?php } ?>
                    <?php
							$input=M('input')->where(array('input_id'=>$v['state']))->field('input_name')->find();
							echo $input['input_name'];
							?></td>
                  <td class="td5" rowspan="<?php echo count($order_goods);?>">
				  <?php if($v['state']==381){?>
                    <a href="index.php?m=order&a=pay&order_id=<?php echo $v['order_id'] ?>" target="_blank" class="pay-btn">付款</a> <a  href="javascript:;" onclick="if(confirm('确定删除吗!')){ajax_list('index.php?m=order&a=del_save&order_id=<?php echo $v['order_id'];?>','')}" class="delete">删除订单</a> <a href="index.php?m=order&a=detail&order_id=<?php echo $v['order_id'] ?>">订单详情</a>
                    <?php }else if($v['state']==382){?>
                    <a href="">等待卖家发货</a> <a href="index.php?m=order&a=detail&order_id=<?php echo $v['order_id'] ?>">订单详情</a> 
                    <?php }else if($v['state']==383){?>
                     <a href="index.php?m=order&a=detail&order_id=<?php echo $v['order_id'] ?>">订单详情</a> <a href="" class="delete">查看物流</a>
                    <?php }else if($v['state']==384){?>
                     <a href="">再次购买</a>
                    <?php }?></td>
                  <!--pay-->
                  <?php }?>
                </tr>
                <?php }?>
              </tbody>
              <?php }?>
            </table>
          </div>
          <?php require APP_ROOT.'public/page.php';?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require APP_ROOT.'public/bottom.php';?>