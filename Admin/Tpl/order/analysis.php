<?php require APP_ROOT.'public/top.php';?>
<script type="text/javascript">
$('#form_menu0').ajaxForm({ success:function showResponse(responseText)  {
	alert(responseText);
	msg_show(responseText,500000);
	goto_url("");
}});
</script>

<div class="content">
  <?php require APP_ROOT.'public/left.php';?>
  <?php
	$data=pg('data');
	$search_type=pg('search_type');
	$search=trim(pg('search'));
	$lt_date=pg('lt_date');
	$gt_date=pg('gt_date');
	$data[$search_type]=$search;
	
	if($lt_date && $gt_date)
	{
		$where['date']=array(array('gt',strtotime($gt_date)),array('lt',strtotime($lt_date)));
	}
	else if($lt_date)
	{
		$where['date']=array('lt',strtotime($lt_date));
	}
	else if($gt_date)
	{
		$where['date']=array('gt',strtotime($gt_date));
	}
	foreach($data as $k=>$v)
	{
		if($v)$where[$k]=$v;
	}
	$goods=M('goods')->field('goods_id,goods_name')->select();
	foreach($goods as $k=>$v)
	{
		$goods_arr[$v['goods_id']]['number']=0;
		$goods_arr[$v['goods_id']]['price']=0;
		$order_goods=M('order_goods')->where($where)->where(array('goods_id'=>$v['goods_id']))->field('price,number')->select();
		foreach($order_goods as $key=>$val)
		{
			$goods_arr[$v['goods_id']]['number']+=$val['number'];
			$goods_arr[$v['goods_id']]['price']+=$val['price'];
		}
		$goods_arr[$v['goods_id']]['goods_name']=$v['goods_name'];
	}
	$goods_arr=array_sort($goods_arr,'number','desc');
	?>
  <div class="right_panel" id="right">
    <form action="admin.php?m=order&a=analysis&admin_classify_id=21" method="post">
      起始日期:
      <input type="text"  class="laydate-input"  id="start_time" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" value="<?php echo $gt_date;?>" size="25" name="gt_date" />
      终止日期:
      <input type="text" class="laydate-input" id="end_time" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" onkeyup="checkDate()"  value="<?php echo $lt_date;?>" size="25" name="lt_date" />
      <Br />
      <input type="submit" class="submit" value="搜索"/>
    </form>
    <form method="post" id="form_menu" action="admin.php?m=order&a=batch_edit_save" >
<div class="analydivwc">
    <div class="analydiv">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ordertable">
        <thead>
          <tr class="title">
            <th height="30" colspan="3">产品订单数量</th>
          </tr>
          <tr>
            <th>产品ID</th>
            <th>订单数量</th>
            <th>销售额</th>
          </tr>
        </thead>
        <?php 
			foreach($goods_arr as $k=>$v)
			{
		 ?>
        <tbody>
          <tr class="analysistr">
            <td height="30"><?php echo $v['goods_name'].'id:'.$k;?></td>
            <td><?php echo $v['number']; ?></td>
            <td>¥<?php echo $v['price'] ?></td>
          </tr>
        </tbody>
        <?php }?>
      </table>
      </div>
    <div class="analydiv">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ordertable">
       <tr class="title">
            <th height="30" colspan="3">跟踪ID统计质量</th>
          </tr>
      <tr>
        <th height="30">&nbsp;编号</th>
        <th>GZID</th>
        <th>订单个数</th>
      </tr>
      <?php 
$list=M('order')->where($where)->field('gzid')->select();
foreach($list as $k=>$v)
{
	$gzid[$v['gzid']]++;
}
foreach($gzid as $k=>$v)
{
	$i++;
?>
      <tr class="analysistr">
        <td height="30">&nbsp;<?php echo $i;?></td>
        <td><?php echo $k;?></td>
        <td><?php echo $v;?></td>
      </tr>
      <?php }?>
    </table>
</div>
<div class="analydiv">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ordertable">
       <tr class="title">
            <th height="30" colspan="3">最近来源关键字</th>
          </tr>
      <tr>
        <th height="30">&nbsp;订单ID</th>
        <th>关键字</th>
        <th>时间</th>
      </tr>
      <tr class="analysistr">
        <td height="30">&nbsp;</td>
        <td></td>
        <td></td>
      </tr>
    </table>
</div>
</div>



      
    </form>
    <?php require APP_ROOT.'public/page.php';?>
  </div>
</div>
<?php require APP_ROOT.'public/bottom.php';?>
