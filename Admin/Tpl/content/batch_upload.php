<?php require APP_ROOT.'public/top.php'; ?>
<script type="text/javascript">
$('#form_menu').ajaxForm({ success:function showResponse(responseText)  { 
	msg_show(responseText,500000);
	goto_url("");
}});
</script>
<div class="content">
	<?php require APP_ROOT.'public/left.php';?>
	<div class="right_panel" id="right">
	<?php
	$classify_id = pg('classify_id');
	if($classify_id!='')
{?>
	<script type="text/javascript">
	var swfu;
	window.onload = function() {
		var settings = {
			flash_url : "<?php echo APP_ROOT.'js/swfupload.swf';?>",
			upload_url: "admin.php?m=content&a=content_batch_upload_save",
			post_params: {"PHPSESSID" : "<?php echo session_id(); ?>","classify_id":"<?php echo $classify_id;?>"},
			file_size_limit : "200 MB",
			file_types : "*.*",
			file_types_description : "All Files",
			file_upload_limit : 0,  //配置上传个数
			file_queue_limit : 0,
			custom_settings : {
				progressTarget : "fsUploadProgress",
				cancelButtonId : "btnCancel"
			},
			debug: false,
			// Button settings
			button_image_url: "",
			button_width: "100",
			button_height: "30",
			button_placeholder_id: "spanButtonPlaceHolder",
			button_text: '<span class="theFont">批量上传</span>',
			button_text_style: ".theFont { font-size: 16; }",
			button_text_left_padding: 12,
			button_text_top_padding: 3,
			
			file_queued_handler : fileQueued,
			file_queue_error_handler : fileQueueError,
			file_dialog_complete_handler : fileDialogComplete,
			upload_start_handler : uploadStart,
			upload_progress_handler : uploadProgress,
			upload_error_handler : uploadError,
			upload_success_handler : uploadSuccess,
			upload_complete_handler : uploadComplete,
			queue_complete_handler : queueComplete	
		};

		swfu = new SWFUpload(settings);
	 };
	 /*
	 //上传成功后返回值
	 function uploadSuccess(file, serverData){
		 EditorXHTML=GetEditorXHTML('editor1');
		 SetEditorXHTML('editor1',EditorXHTML+'<img src="'+serverData+'" />');
	}
	*/
</script>
	<?php
}
?>
	<div id="content" align="center">
		<div class="version"></div>
        
        <select name="classify_relating" id="classify_relating"
			onChange="if(this.options[this.selectedIndex].value   !=''){window.location=this.options[this.selectedIndex].value}">
			<option value="index.php?f=content&w=batch_upload">请选择分类</option>
<?php
$list=M('classify')->where(array('type_id'=>3))->select();
foreach($list as $k=>$v)
{
?>
			<option <?php if($v['classify_id']==$classify_id){?> selected="selected" <?php }?> value="admin.php?m=content&a=batch_upload&classify_id=<?php echo $v['classify_id'];?>">
				┣
				<?php for($i=0;$i<$v['level_id'];$i++) {
					echo '━';
				};?>
				<?php echo $v['classify_name'];?>
			</option>
<?php }?>
		</select>
			<div class="fieldset flash" id="fsUploadProgress" align="left"></div>
			<div id="divStatus">0 个文件已上传</div>
			<div>
				<span id="spanButtonPlaceHolder"></span> <input id="btnCancel" type="button" value="取消所有上传" onClick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
			</div>
	</div>
</div>
</div>
<?php require APP_ROOT.'public/bottom.php'; ?>