<?php require APP_ROOT.'public/top.php';
if(session('member')=='')
{
	goto_url('index.php?m=member&a=login');
}
?>
<div class="member_headimg">
	<div class="width">
    	<div class="img"><?php if(!empty($member['headimgurl'])){?><img src="" /><?php }?><a href="">编辑头像</a></div>
        <span class="name"><?php echo $member['username']?></span>
        <span class="photo"><span class="fa fa-mobile-phone"></span>&nbsp;&nbsp;<?php  $photo=substr($member['mobile'],0,3)."*****".substr($member['mobile'],8,3);echo $photo;?></span>
    </div>
</div>