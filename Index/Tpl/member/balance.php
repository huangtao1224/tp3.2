<?php require APP_ROOT.'public/member_top.php';?>

<div class="bg">
  <div class="member_main">
    <?php require APP_ROOT.'public/member_left.php';?>
    <div class="member_right">
      <div class="title">
        <h3>我的余额</h3>
      </div>
      <div class="member_account body">
        <p>可用余额：<span>￥<?php echo $balance;?></span> <a href="index.php?m=member&a=topup">立即充值</a></p>
        <div class="data">
          <p>余额明细</p>
          <table cellpadding="1" cellspacing="1" style="overflow: scroll;">
            <tr>
              <th>时间</th>
              <th>交易类型</th>
              <th>交易金额</th>
              <th>余额</th>
              <th>备注</th>
              <th>状态</th>
            </tr>
            <?php $member_data=M('account')->where(array('member_id'=>$member_id))->order('account_id desc')->select();
							foreach($member_data as $k=>$v){
						?>
            <tr>
              <td><?php echo date('Y-m-d h:i:s',$v['date'])?></td>
              <td><i><?php echo M('input')->where(array('input_id'=>$v['amount_type']))->getField('input_name');?></i></td>
              <td><i>
                <?php
		if($v['amount_type']==426)
		{
			 echo "+".$v['amount'];
		}
		else
		{
			echo "-".$v['amount'];
		}?>
                </i></td>
              <td><i><?php echo $v['balance'];?></i></td>
              <td><?php echo $v['note']?></td>
              <td><?php $state=M('input')->where(array('input_id'=>$v['state']))->find();?>
                <span style="color:<?php echo $state['color'];?>"><?php echo $state['input_name'];?></span></td>
            </tr>
            <?php }?>
          </table>
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<script>
$(function(){
	var left=$('.member_main .member_left')
	var right=$('.member_main .member_right')
	if(left.height()>right.height()){
		right.height(left.height()-50)
	}else{
		left.height(right.height()+50)
	}
	
	
})

</script>
<?php require APP_ROOT.'public/bottom.php';?>
