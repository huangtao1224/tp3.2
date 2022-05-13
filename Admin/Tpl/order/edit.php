<?php
$type_id = pg('type_id');
$content_id = pg('content_id');
$table_name = pg('table_name');
$list = M($table_name)->where(array($table_name . '_id' => $content_id))->find();
?>
<script type="text/javascript">
$('#form').ajaxForm({ success:function showResponse(responseText)  {
    layer.msg(responseText,{time:1500},function(){
        parent.location.reload();
    });
}});
</script>

<form action="admin.php?m=order&a=edit_save" method="post" enctype="multipart/form-data" id="form">
<input name="type_id" type="hidden" value="<?php echo $type_id;?>" />
<input name="content_id" type="hidden" value="<?php echo $content_id;?>" />
<input name="content_id" type="hidden" value="<?php echo $content_id;?>" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
<?php
$input = M('input')->where(array('type_id'=>$type_id,'list_switch'=> '2'))->order('date asc')->select();
foreach($input as $k=>$v){
?>

<tr class="row">
  <td><?php echo $v['input_name']?></td>
  <td>
    <?php
	switch($v['input_type_id']){
		case 1:
		?>
        <input name="data[<?php echo $v['field_name'];?>]" type="text" id="<?php echo $v['field_name'];?>"
         value="<?php if($v['input_id']==404){echo date('Y-m-d H:i:s',$list[$v['field_name']]);}else{echo $list[$v['field_name']]; }?>" 
          <?php if($v['input_id']==209 || $v['input_id']==404 || $v['input_id']==411)echo 'disabled="true"';?> size="<?php echo $v['edit_size'];?>" />
        <?php
		break;
		case 2:
		?>
        <textarea name="data[<?php echo $v['field_name'];?>]" cols="40" rows="<?php echo $v['edit_size'];?>" id="<?php echo $v['field_name'];?>"><?php echo $list[$v['field_name']];?></textarea>
        <?php if($v['input_id']==309 ){echo '用户的留言'; }else{echo '管理员备注';}?>
        <?php
		break;
		case 3:
			echo fckeditor('data['.$v['field_name'].']',$v['edit_size'],$list[$v['field_name']]);
		break;
		case 6:
		?>
        <select name="data[<?php echo $v['field_name'];?>]" id="<?php echo $v['field_name'];?>">
        <option value="0">请选择</option>
		
        <?php
        if($v['field_name']=='shipping_id'){
        $input_ps = M('shipping')->select();
		foreach($input_ps as $k2=>$v2){
		?>
          <option value="<?php echo $v2['shipping_id'];?>"<?php if($list['shipping_id']==$v2['shipping_id'])echo ' selected="selected"';?>><?php echo $v2['shipping_name'];?></option>
        <?php } 
		
		}else{
			$input_p = M('input')->where(array('input_pid'=>$v['input_id']))->select();
		foreach($input_p as $k2=>$v2){
		?>
          <option value="<?php echo $v2['input_value'];?>"<?php if($list[$v['field_name']]==$v2['input_value'])echo ' selected="selected"';?>><?php echo $v2['input_name'];?></option>
        <?php } 
		}
        ?>

        </select>
        <?php
		break;
		case 7:
		?>
        <input name="<?php echo $v['field_name'];?>" id="<?php echo $v['field_name'];?>" type="file" />
        <?php
		if($list[$v['field_name']]!='')
		{
			echo '<a href="/'.$list[$v['field_name']].'" target="_blank"><img src="'.$list[$v['field_name']].'" width="50" height="25"/></a>';
			?>
            <a href="javascript:;" onclick="confirm_img('确定删除吗!','admin.php?m=content&a=delete_img&content_id=<?php echo $content_id;?>&table_name=<?php echo $table_name;?>&type_id=<?php echo $type_id;?>&field_name=<?php echo $v['field_name'];?>',$(this))" class="delete">删除</a>
            <?php
		}
		break;
		case 8:
		?>
        <div class="laydate-box">
            <input name="data[<?php echo $v['field_name'];?>]" type="text" class="laydate-input" id="<?php echo $v['field_name'];?>" value="<?php echo cover_time($list[$v['field_name']],'Y-m-d H:i:s')?>" size="<?php echo $v['edit_size'];?>" />
            <i class="bi bi-calendar3" aria-hidden="true"></i>
        </div>
        <?php
		break;
	}
	?>

     <?php echo $v['note'];?>
</td>
  </tr>
<?php }?>
</table>
<input type="submit" class="submit" value="确认无误，提交">
</form>
<script>
    laydate.render({
        elem: '.laydate-input',
        type: 'datetime',
    });
</script>
<?php require APP_ROOT.'public/foot.php';?>
