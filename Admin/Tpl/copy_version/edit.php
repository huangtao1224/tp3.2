<?php
$classify_id = pg('classify_id');
$type_id = pg('type_id');
$content_id = pg('content_id');
$table_name = pg('table_name');
$list = M($table_name)->where(array($table_name . '_id' => $content_id))->find();
?>
<script type="text/javascript">
$('#form').ajaxForm({ success:function showResponse(responseText)  {
	dialog_div_close('ajax_dialog');
	msg_show(responseText,50000);
}});
</script>

<form action="admin.php?m=content&a=edit_save" method="post" enctype="multipart/form-data" id="form">
<input name="classify_id" type="hidden" value="<?php echo $classify_id;?>" />
<input name="type_id" type="hidden" value="<?php echo $type_id;?>" />
<input name="content_id" type="hidden" value="<?php echo $content_id;?>" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
<?php
$input = M('input')->where(array('type_id'=>$type_id,'edit_switch'=>2,'input_pid'=>array('exp','is null')))->order('date asc')->select();
foreach($input as $k=>$v){
?>
<tr class="row">
  <td><?php echo $v['input_name']?></td>
  <td>
    <?php
	switch($v['input_type_id']){
		case 1:
		?>
        <input name="data[<?php echo $v['field_name'];?>]" type="text" id="<?php echo $v['field_name'];?>" value="<?php echo $list[$v['field_name']];?>" size="<?php echo $v['edit_size'];?>" />
        <?php
		break;
		case 2:
		?>
        <textarea name="data[<?php echo $v['field_name'];?>]" cols="60" rows="<?php echo $v['edit_size'];?>" id="<?php echo $v['field_name'];?>"><?php echo $list[$v['field_name']];?></textarea>
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
		$input_p = M('input')->where(array('input_pid'=>$v['input_id']))->select();
		foreach($input_p as $k2=>$v2){
		?>
          <option value="<?php echo $v2['input_id'];?>"<?php if($list[$v['field_name']]==$v2['input_id'])echo ' selected="selected"';?>><?php echo $v2['input_name'];?></option>
        <?php } ?>
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
            <a href="javascript:;" onclick="if(confirm('确定删除吗!')){delete_img('admin.php?m=content&a=delete_img&content_id=<?php echo $content_id;?>&table_name=<?php echo $table_name;?>&type_id=<?php echo $type_id;?>&field_name=<?php echo $v['field_name'];?>',$(this))}" class="delete">删除</a>
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