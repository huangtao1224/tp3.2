<?php require APP_ROOT.'public/member_top.php';?>

<div class="bg">
    <div class="member_main">
	<?php require APP_ROOT.'public/member_left.php'; ?>

    <div class="member_right">
    <div class="main-box">
    	<h6 class="main-t">添加收货地址</h6>
        <div class="addBox">
        	<div class="add-news">
            	<form action="index.php?m=member&a=add_address_save" method="post">
                	<div class="text1">
                    <label><span>*</span>收货人：</label><input type="text" placeholder="收货人姓名" name="data[consignee]"/></div>
                    <input type="hidden" name="data[member_id]" value="<?php echo $member['member_id'];  ?>"/>
                    <div class="detials">
                   		<label><span>*</span>地址：</label>
                       
                    	<select name="data[province]" id="province" onChange="menu_ca($(this),'province')">
                         <?php $adr1=M('region')->where('region_pid=1')->select();
						   
						    foreach($adr1 as $k=>$va){;
						  ?>
                        <option value="<?php echo $va['region_id'] ?>"><?php echo$va['region_name'];?></option>
                        <?php }?>
                        
                        </select>
                       
                        
                        <select name="data[city]" id="city" onChange="menu_ca($(this),'city')" >
                         <?php $adr2=M('region')->where('region_pid='.$adr1[0]['region_id'])->select();
						    foreach($adr2 as $kk=>$vaa){;
						  ?>
                        <option value="<?php echo $vaa['region_id'] ?>"><?php echo  $vaa['region_name']?></option>
                        <?php }?>
                        </select>
							

                        <select name="data[area]" id="area" onChange="menu_ca($(this),'area')">
                        <?php $adr3=M('region')->where('region_pid='.$adr2[0]['region_id'])->select();
						 
						    foreach($adr3 as $k3=>$va3){;
						  ?>
                        <option value="<?php echo $va3['region_id'] ?>"><?php echo  $va3['region_name']?></option>
                        <?php } ?>
                        </select>
                        
                    	<input type="text" placeholder="详细地址" class="text2" name="data[address]"/>
                    </div>
                    <div class="text1"><label><span>*</span>联系电话：</label><input name="data[phone]" type="text" placeholder="手机号/固定电话"/></div>
                    <div class="btn1"><input type="submit" value="保存收货地址"/></div>   
                </form>
            </div>        	
        </div>
    </div>
</div>
</div>
</div>
<?php require APP_ROOT.'public/bottom.php' ?>

