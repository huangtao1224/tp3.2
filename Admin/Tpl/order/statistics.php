<?php require APP_ROOT.'public/top.php';?>
<script type="text/javascript">
$('#form_menu0').ajaxForm({ success:function showResponse(responseText)  {
    layer.msg(responseText,{time:1500},function(){
        parent.location.reload();
    });
}});
</script>

<div class="content">
  <?php require APP_ROOT.'public/left.php';?>
  <?php
  date_default_timezone_set('Asia/Shanghai'); 
	$data=pg('data');
	$time=time();
	$search_type=pg('search_type');
	$search=trim(pg('search'));
  $ltyeardate=pg('ltyeardate');
  $gtyeardate=pg('gtyeardate');
  $ltmonthdate=pg('ltmonthdate');
  $gtmonthdate=pg('gtmonthdate');
 
	
	?>
  <div class="right_panel" id="right">
    <form method="post" id="form_menu" action="admin.php?m=order&a=batch_edit_save" >
      <div class="analydivwc">
        <div class="analydiv">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ordertable">
            <thead>
              <tr>
                <th>时间(年)</th>
                <th>销售额</th>
                <th>订单数量</th>
              </tr>
            </thead>
            <?php 
			for($i=0;$i<5;$i++)
			{
				$gtyeartime=strtotime(cover_time($time,'Y')-$i.'-1-1');
				$ltyeartime=strtotime((cover_time($time,'Y')-$i+1).'-1-1');
        
				$price=M('order')->where(array('date'=>array(array('gt',$gtyeartime),array('lt',$ltyeartime))))->sum('price');
				//echo M()->getlastsql().'<br><br><br>';
				$number=M('order_goods')->where(array('date'=>array(array('gt',$gtyeartime),array('lt',$ltyeartime))))->sum('number');
				if(!$price)$price=0;
				if(!$number)$number=0;
		?>
            <tbody>
              <tr class="analysistr">
                <td height="30"><a href="admin.php?m=order&a=statistics&gtyeardate=<?php echo $gtyeartime;?>&ltyeardate=<?php echo $ltyeartime;?>&admin_classify_id=21"><?php echo cover_time($time,'Y')-$i;?></a></td>
                <td>¥<?php echo $price; ?></td>
                <td><?php echo $number; ?></td>
              </tr>
            </tbody>
            <?php }?>
          </table>
        </div>
        <div class="analydiv">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ordertable">
            <thead>
              <tr>
                <th>时间(月)</th>
                <th>销售额</th>
                <th>订单数量</th>
              </tr>
            </thead>
            <?php
        if($gtyeardate==''){
        $gtyeardate=time();
        }
			for($i=1;$i<=12;$i++)
			{
				
        $gtmonthtime=strtotime(cover_time($gtyeardate,'Y').'-'.$i.'-1');
        $ltmonthtime=strtotime(cover_time($gtyeardate,'Y').'-'.($i+1));
				$price=M('order')->where(array('date'=>array(array('gt',$gtmonthtime),array('lt',$ltmonthtime))))->sum('price');
				//echo M()->getlastsql().'<br><br><br>';
				$number=M('order_goods')->where(array('date'=>array(array('gt',$gtmonthtime),array('lt',$ltmonthtime))))->sum('number');
				if(!$price)$price=0;
				if(!$number)$number=0;
		?>
            <tbody>
              <tr class="analysistr">
                <td height="30"><a href="admin.php?m=order&a=statistics&gtyeardate=<?php echo $gtyeardate;?>&ltyeardate=<?php echo $ltyeardate;?>&gtmonthdate=<?php echo $gtmonthtime;?>&ltmonthdate=<?php echo $ltmonthtime;?>&admin_classify_id=21"><?php echo cover_time($gtyeardate,'Y').'-'.$i;?></a></td>
                <td>¥<?php echo $price; ?></td>
                <td><?php echo $number; ?></td>

              </tr>
            </tbody>
            <?php }?>
          </table>
        </div>
        <div class="analydiv">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ordertable">
            <thead>
              <tr>
                <th>时间(日)</th>
                <th>销售额</th>
                <th>订单数量</th>
              </tr>
            </thead>
            <?php 
        if($gtmonthdate==''){
        $gtmonthdate=time();
        } 
			for($i=1;$i<=cover_time($gtmonthdate,'t', strtotime('-1 month'));$i++)
			{ 
				$gtdaytime=strtotime(cover_time($gtmonthdate,'Y-m').'-'.$i);
				$ltdaytime=$gtdaytime+(60*60*24);
				$price=M('order')->where(array('date'=>array(array('gt',$gtdaytime),array('lt',$ltdaytime))))->sum('price');
				//echo M()->getlastsql().'<br><br><br>';
				$number=M('order_goods')->where(array('date'=>array(array('gt',$gtdaytime),array('lt',$ltdaytime))))->sum('number');
				if(!$price)$price=0;
				if(!$number)$number=0;
		?>
            <tbody>
              <tr class="analysistr">
                <td height="30"><?php echo cover_time($gtmonthdate,'Y-m-').$i;?></td>
                <td>¥<?php echo $price; ?></td>
                <td><?php echo $number; ?></td>
              </tr>
            </tbody>
            <?php }?>
          </table>
        </div>
      </div>
    </form>
  </div>
</div>
<?php require APP_ROOT.'public/bottom.php';?>
