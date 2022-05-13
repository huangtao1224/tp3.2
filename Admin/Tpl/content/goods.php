
	
	
<?php require APP_ROOT.'public/top.php';?>
<script type="text/javascript">
$('#form_menu').ajaxForm({ success:function showResponse(responseText)  {
	msg_show(responseText,500000);
	goto_url("");
}});
</script>
<div class="content">
	<?php require APP_ROOT.'public/left.php';?>
	<?php
	global $list,$type_id,$main_classify_id;
	$type_id = pg('type_id');
	$content_id = pg('content_id');
	$export_content_id=pg('export_content_id');
	$classify_id = pg('classify_id');
	$table_name = M('classify_type')->where(array('type_id' => $type_id))->getField('table_name');
	$classify = M('classify')->where(array('classify_id' => $classify_id))->find();
	?>
	<div class="right_panel" id="right">
		<a href="admin.php?m=content&a=index&type_id=3&classify_id=<?php echo $classify_id; ?>" class="button">返回列表</a>
		<a href="javascript:;" onclick="dialog_div(900,600,'admin.php?m=content&a=add&type_id=<?php echo $type_id;?>&classify_id=<?php echo $classify_id;?>&gid=<?php echo $content_id; ?>&ajax=1&time=<?php echo time()?>')" class="button">添加内容</a>
      
		<form method="post"<?php if($export_content_id==''){?> id="form_menu"<?php }?> class="form_content" action="admin.php?m=content&a=batch_edit_save" onSubmit="return return_content()">
			<table class="tables" width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr class="title">
					<input name="table_name" type="hidden" id="table_name" value="<?php echo $table_name;?>" />
					<input name="type_id" type="hidden" id="type_id" value="<?php echo $type_id;?>" />
					<input name="classify_id" type="hidden" id="classify_id" value="<?php echo $classify_id;?>" />
					<td height="30"><input name="" type="checkbox" class="checkbox" onclick="SelectAll('content_id[]')" value="" />全选</td>
					<?php
					$where = array();
					$where['type_id'] = $type_id;
					$where['list_switch'] = 2;
					$list_input=M('input')->where($where)->order('date asc')->select();
					foreach($list_input as $k=>$v){
					 ?>
						<td><?php echo $v['input_name']?></td>
					<?php }?>
					<td>归属栏目</td>
					<td>排序时间</td>
					<td>操作</td>
				</tr>
				<?php
				$perpage=20;
				$offset=($p-1)*$perpage;//偏移量
				$where = array();
				$where['r.classify_id'] = $classify_id;
				$where['c.gid'] = $content_id;

				$where['r.type_id'] = $type_id;
				$list = M()->table(C('DB_PREFIX') . $table_name . ' c left join ' . C('DB_PREFIX') . 'relevance r on r.content_id = c.' . $table_name . '_id')->where($where)->order('c.date desc')->limit($offset,$perpage)->select();
				$total_num = M()->table(C('DB_PREFIX') . $table_name . ' c left join ' . C('DB_PREFIX') . 'relevance r on r.content_id = c.' . $table_name . '_id')->where($where)->count();
				// echo M()->getlastsql();
				foreach($list as $k=>$v){
				?>
					<tr>
						<td height="35">
							<input name="content_id[]" type="checkbox" class="checkbox" value="<?php echo $v['content_id']?>" />
							<input name="data[content_id][]" type="hidden" value="<?php echo $v['content_id']?>" />
							<?php echo $v['content_id']?>
						</td>
						<?php foreach($list_input as $key=>$val){ ?>
							<td>
								<?php if($val['input_type_id']==7){?>
									<a href="<?php echo $v[$val['field_name']];?>" target="_blank"><img src="<?php echo $v[$val['field_name']];?>" width="50" height="15" /></a>
									<?php }else if($val['input_type_id']==8){?>

									<?php echo date('Y-m-d H:i:s',$v[$val['field_name']]);?>

								<?php }else if($val['input_type_id']==6){?>
									<select name="data[<?php echo $val['field_name'];?>][]">
										<option value="0">请选择</option>
										<?php
										$input = M('input')->where(array('input_pid' => $val['input_id']))->order('date asc')->select();
										foreach($input as $k2=>$v2){
										?>
											<option value="<?php echo $v2['input_value'];?>"<?php if($v[$val['field_name']]==$v2['input_value']){?> selected="selected"<?php }?>><?php echo $v2['input_name'];?></option>
										<?php } ?>
									</select>
								<?php }else{?>
									<input type="text" name="data[<?php echo $val['field_name'];?>][]" style="width:<?php echo $val['list_size'];?>px;" value="<?php echo $v[$val['field_name']];?>" />
								<?php }?>
							</td>
						<?php }?>
						<td>
							<?php
							$relevance_list = M('relevance')->where(array('content_id' => $v['content_id'], 'type_id' => $type_id))->order('main_id desc')->select();
							foreach($relevance_list as $key=>$val){
								$classify_list = M('classify')->where(array('classify_id' => $val['classify_id']))->find();
								if($val['main_id'] == 1){
									$main_classify_id=$classify_list['classify_id'];
									echo '<font style="font-weight:bold;">'.$classify_list['classify_name'].'</font>';
								}else{
									echo "|".$classify_list['classify_name'];
								}
							}
							if($type_id==44){
							?>
                            <a class="con_list" href="admin.php?m=content&a=apply&type_id=45&classify_id=<?php echo $classify_id;?>&content_id=<?php echo $v[$table_name.'_id'];?>">应聘列表(<?php echo M('apply')->where(array('recruit_id' => $v['content_id']))->count();?>)</a>
                            <?php }?>
                            <?php if($type_id==3){
							?>
                            <a class="con_list" href="admin.php?m=content&a=goods&type_id=60&classify_id=<?php echo $classify_id;?>&content_id=<?php echo $v[$table_name.'_id'];?>">商品列表(<?php echo M('product')->where(array('gid' => $v['content_id']))->count();?>)</a>
                            <?php }?>
						</td>
						<td><input name="data[date][]" type="text" value="<?php echo cover_time($v['date'],'Y-m-d H:i:s');?>" size="20" /></td>
						<td><a href="javascript:;" onclick="dialog_div(900,600,'admin.php?m=content&a=edit&type_id=<?php echo $type_id;?>&classify_id=<?php echo $classify_id;?>&table_name=<?php echo $table_name;?>&content_id=<?php echo $v[$table_name.'_id'];?>&ajax=1&time=<?php echo time()?>')" class="delete">修改</a> &nbsp;&nbsp; <a href="javascript:;" onclick="if(confirm('确定删除吗!')){ajax_list('admin.php?m=content&a=del_save&content_id=<?php echo $v['content_id'];?>&table_name=<?php echo $table_name;?>&type_id=<?php echo $type_id;?>','')}" class="delete">删除</a></td>
					</tr>
				<?php }?>
			</table>
<?php
$list = M('classify')->field('classify_id,level_id,classify_pid,type_id,classify_name')->where(array('version_id'=>session('version_id')))->order('date asc')->select();
function recursive_menu($classify_pid='1'){
	global $list,$type_id,$main_classify_id;
	foreach($list as $k=>$v)
	{
		if($v['classify_pid']==$classify_pid)
		{
			if($v['type_id']==$type_id){
?>
				<option value=" <?php echo $v['classify_id'];?>"<?php if($main_classify_id==$v['classify_id']){?> disabled="disabled"<?php }?>>┣<?php for($i=3;$i<=$v['level_id'];$i++)echo "━";?><?php echo $v['classify_id'].':'.$v['classify_name'];?></option>
<?php
			}
			recursive_menu($v['classify_id']);
		}
	}
}
?>
			<div class="content_btn_w">
            <?php if($function_switch['batch_shared']==2){?>
            <select class="shared_select" name="shared_id" id="shared_id">
					<option value="">批量共享</option>
					<?php recursive_menu(session('version_classify_id'));?>
				</select>
                
				<select class="shared_select" name="cancel_shared_id" id="cancel_shared_id">
					<option value="">批量取消共享</option>
					<?php recursive_menu(session('version_classify_id')); ?>
				</select>
                <?php }?>
                
                <?php if($function_switch['batch_move']==2){?>
                <select class="shared_select" name="move_id" id="move_id">
					<option value="">批量移动</option>
					<?php recursive_menu(session('version_classify_id')); ?>
				</select>
                <?php }?>
                
                <?php if($function_switch['batch_copy']==2){?>
                <select class="shared_select" name="copy_id" id="copy_id">
					<option value="">批量复制</option>
					<?php
					$list = M('classify')->field('classify_id,level_id,classify_pid,type_id,classify_name')->where(array('version_id'=>session('version_id')))->order('date asc')->select();
					function recursive_menu2($classify_pid='1'){
						global $list,$type_id,$main_classify_id;
						foreach($list as $k=>$v)
						{
							if($v['classify_pid']==$classify_pid)
							{
								if($v['type_id']==$type_id){
					?>
									<option value=" <?php echo $v['classify_id'];?>">┣<?php for($i=3;$i<=$v['level_id'];$i++)echo "━";?><?php echo $v['classify_id'].':'.$v['classify_name'];?></option>
					<?php
								}
								recursive_menu2($v['classify_id']);
							}
						}
					}
					recursive_menu2(session('version_classify_id'));
					?>
				</select>
                <?php }?>
                
                <?php if($function_switch['batch_delete']==2){?>
                <select class="shared_select" name="batch_delete_id" id="batch_delete_id">
					<option value="">批量删除</option>
                    <option value="1">确定删除</option>
				</select>
                <?php }?>
                
                <?php if($function_switch['xls_content']==2){?>
                <select class="shared_select" name="export_content_id" id="export_content_id" onChange="export_content()">
					<option value="">导出内容</option>
                    <option value="1"<?php if($export_content_id==1)echo ' selected="selected"';?>>确定导出</option>
				</select>
                <?php if($export_content_id){?>
                <div class="export_field">
<?php
$input = M('input')->where(array('type_id'=>$type_id,'edit_switch'=>2,'input_type_id'=>array('neq',3),'input_pid'=>array('exp','is null')))->order('date asc')->select();
foreach($input as $k=>$v){
?>
                <label>
                <input name="export[<?php echo $v['field_name'];?>]" class="export_check" type="checkbox" value="<?php echo $v['input_name'];?>" /><?php echo $v['input_name'];?>
                </label>
<?php }?>
                </div>
                <select class="shared_select" name="export_content_check" id="export_content_check">
					<option value="">导出选重内容</option>
                    <option value="1">导出所有内容(耗资大请慎用)</option>
				</select>
<?php }?>
                
<?php }?>
				<input name="" class="submit" type="submit" value="提交" />
			</div>
		</form>
		<?php require APP_ROOT.'public/page.php';?>
	</div>

</div>
<?php require APP_ROOT.'public/bottom.php';?>
