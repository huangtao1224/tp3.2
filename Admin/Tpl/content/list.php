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
	$type_id = pg('type_id');
	$classify_id = pg('classify_id');
	$content_id = pg('content_id');
	$table_name = M('classify_type')->where(array('type_id' => $type_id))->getField('table_name');
	?>
	<div class="right_panel" id="right">
		<a href="admin.php?m=content&a=index&type_id=3&classify_id=<?php echo $classify_id; ?>" class="button">返回列表</a>

		<a href="javascript:;" onclick="dialog_div(900,600,'admin.php?m=content&a=add&type_id=<?php echo $type_id;?>&goods_id=<?php echo $content_id ?>&classify_id=<?php echo $classify_id;?>&ajax=1&time=<?php echo time()?>')" class="button">添加内容</a>

		<form method="post" id="form_menu" action="admin.php?m=content&a=batch_edit_save" >
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr class="title">
					<input name="table_name" type="hidden" id="table_name" value="<?php echo $table_name;?>" />
					<input name="type_id" type="hidden" id="type_id" value="<?php echo $type_id;?>" />
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
				$where['r.type_id'] = $type_id;
				$list = M($table_name)->where(array('goods_id' => $content_id))->order('date desc')->limit($offset,$perpage)->select();
				$total_num = M($table_name)->where(array('goods_id' => $content_id))->order('date desc')->count();
				foreach($list as $k=>$v){
				?>
					<tr>
						<td height="35">
							<input name="content_id[]" type="checkbox" class="checkbox" value="<?php echo $v['ping_id']?>" />
							<input name="data[content_id][]" type="hidden" value="<?php echo $v['ping_id']?>" />
							<?php echo $v['ping_id']?>
						</td>
						<?php foreach($list_input as $key=>$val){ ?>
							<td>
								<?php if($val['input_type_id']==7){?>
									<a href="<?php echo $v[$val['field_name']];?>" target="_blank"><img src="<?php echo $v[$val['field_name']];?>" width="50" height="15" /></a>
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
							评论列表
						</td>
						<td><input name="data[date][]" type="text" value="<?php echo cover_time($v['date'],'Y-m-d H:i:s');?>" size="20" /></td>
						<td><a href="javascript:;" onclick="dialog_div(800,500,'admin.php?m=content&a=edit&type_id=<?php echo $type_id;?>&classify_id=<?php echo $classify_id;?>&table_name=<?php echo $table_name;?>&content_id=<?php echo $v[$table_name.'_id'];?>&ajax=1&time=<?php echo time()?>','admin.php')" class="delete">修改</a> &nbsp;&nbsp; <a href="javascript:;" onclick="if(confirm('确定删除吗!')){ajax_list('admin.php?m=content&a=del_save&content_id=<?php echo $v['ping_id'];?>&table_name=<?php echo $table_name;?>&type_id=<?php echo $type_id;?>','')}" class="delete">删除</a></td>
					</tr>
				<?php }?>
			</table>

		<!-- 	<div class="content_btn_w">
				<select name="shared_id" id="shared_id">
					<option value="">批量共享</option>
					<?php
					global $list;
					$list = M('classify')->field('classify_id,level_id,classify_pid,type_id,classify_name')->where(array('version_id'=>'1'))->select();
					function recursive_menu($classify_pid='1'){
						global $list;
						foreach($list as $k=>$v)
						{
							if($v['classify_pid']==$classify_pid)
							{
								$i++;
					?>
									<option value=" <?php echo $v['classify_id'];?>">┣<?php for($i=3;$i<=$v['level_id'];$i++)echo "━";?><?php echo $v['classify_name'];?></option>
					<?php
								recursive_menu($v['classify_id']);
							}
						}
					}
					recursive_menu($_SESSION['version_id']);
					?>
				</select>
                
				<select name="cancel_shared_id" id="cancel_shared_id">
					<option value="">批量取消共享</option>
					<?php recursive_menu($_SESSION['version_id']); ?>
				</select>
				<input name="" class="submit" type="submit" value="提交" />
			</div> -->

			
		</form>
		<?php require APP_ROOT.'public/page.php';?>
	</div>

</div>
<?php require APP_ROOT.'public/bottom.php';?>
