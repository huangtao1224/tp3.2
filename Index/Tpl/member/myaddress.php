<?php require APP_ROOT.'public/member_top.php';?>
<div class="bg">
    <div class="member_main">
	<?php require APP_ROOT.'public/member_left.php'; ?>
<div class="member_right">
    <?php 
	$count=M('address')->where(array('member_id'=>$member['member_id']))->count();
	?>
    <div class="main-box">
    	<h6 class="main-t">我的地址</h6>
        <div class="address">
        	<div class="adds"><a href="index.php?m=member&a=add_address">新增收货地址</a><b>已经保存的收货地址</b></div>
        	<div class="address-list">
            	<ul class="clearfix">
                	<li class="li1" title="添加收货地址"><a href="index.php?m=member&a=add_address"></a></li>
                     <?php 
						$list=M('address')->where(array('member_id'=>$member['member_id']))->select();
						foreach($list as $k=>$v){
							$province=M('region')->where(array('region_id'=>$v['province']))->find();
							$city=M('region')->where(array('region_id'=>$v['city']))->find();
							$area=M('region')->where(array('region_id'=>$v['area']))->find();
						?>
							<li class="<?php echo $v['state']==411?'defalut':'reset';?>">
								<a href="javascript:;" class="mo" onclick="set_default_address(<?php echo $v['address_id'];?>,$(this))">默认地址</a>
								<div class="div1"><em></em><?php echo  $v['phone'] ?></div>
								<div class="div2"><em></em><?php echo $province['region_name'].' '.$city['region_name'].' '.$area['region_name'].' '.$v['address']; ?></div>
								<div class="div3">
                                <a href="javascript:;" onclick="if(confirm('确定删除吗!')){ajax_list('index.php?m=member&a=del_address&address_id=<?php echo $v['address_id'] ?>','index.php?&m=member&a=myaddress')}">删除</a>
                                <a href="index.php?m=member&a=edit_address&address_id=<?php echo $v['address_id'] ?>">修改</a>
                                
                                <em></em><?php echo  $v['consignee'] ?></div>
							</li>
					   <?php }?>   
                  
                </ul>
            </div>
        </div>
    </div>
</div>




<?php require APP_ROOT.'public/bottom.php';?>