<?php require APP_ROOT.'public/member_top.php';?>
<div class="bg">
    <div class="member_main">
<?php require APP_ROOT.'public/member_left.php';?>
        <div class="member_right">
        	<div class="title">
            	<h3>账户信息</h3>
            </div>
        	<div class="body">
            	<p>用户账号：<?php echo $member['username'] ?>&nbsp;&nbsp;&nbsp;<a href="index.php?m=member&a=mydata">资料修改</a></p>
                <p>我的余额：<span>￥<?php echo $balance;?></span>&nbsp;&nbsp;&nbsp;<a href="index.php?m=member&a=topup">在线充值</a></p>
                <p>
                <?php $login=M('memberlog')->where(array('member_id'=>$member_id))->order('date desc')->find(); ?>
                最近登录时间：<?php $memberlog=M('memberlog')->where(array('member_id'=>$member_id,'date'=>array('lt',$login['date'])))->order('date desc')->find(); ?>
                <?php
				if(empty($memberlog))
				{
					$memberlog=$login;
				}
				 echo cover_time($memberlog['date'],'Y-m-d H:i:s');?>
                </p>
            </div>
        </div>
    </div>
</div>
<?php require APP_ROOT.'public/bottom.php';?>
