<?php require APP_ROOT.'public/top.php';?>

<div class="g00002">
  <div class="g00003">
    <?php require APP_ROOT.'public/left.php';?>
  </div>
  <div class="g00004">
      <?php require APP_ROOT.'public/right.php';?>    
    <div class="c1448872173">
      <div class="f14488721901">
        <form action="index.php" method="get" enctype="multipart/form-data" onsubmit="return return_message()" id="form" class="f14488721902">
          <input name="m" type="hidden" value="query" />
          <input name="a" type="hidden" value="details" />
          <input name="classify_id" type="hidden" id="classify_id" class="f14488721904" value="<?php echo $classify_id;?>" />
          <div class="f14488721906">
            <?php  $input = M('input')->where(array('type_id'=>$type_id,'show_switch'=>2,'input_pid'=>array('exp','is null')))->order('date asc')->select();  foreach($input as $k=>$v){?>
            <dl class="f14488721907">
              <dt class="f14488721908">
			  <span>*</span><?php echo $v['input_name']?></dt>
              <dd class="f14488721909">
                <?php  	switch($v['input_type_id']){  		case 1:  		?>
                <input class="f144887219010" name="data[<?php echo $v['field_name'];?>]" type="text" id="<?php echo $v['field_name'];?>" size="<?php echo $v['edit_size'];?>" />
                <?php  		break;  		case 2:  		?>
                <textarea class="f144887219011" name="data[<?php echo $v['field_name'];?>]" cols="60" rows="<?php echo $v['edit_size'];?>" id="<?php echo $v['field_name'];?>"></textarea>
                <?php  		break;  		case 3:  			echo fckeditor('data['.$v['field_name'].']',$v['edit_size'],'');  		break;  	
		case 4:
		?>
                <?php
		$input_p = M('input')->where(array('input_pid'=>$v['input_id']))->select();
		foreach($input_p as $k2=>$v2){
		?>
                <label>
                  <input type="checkbox" name="data[<?php echo $v['field_name'];?>][]" value="<?php echo $v2['input_value']?>" />
                  <?php echo $v2['input_name'];?> </label>
                &nbsp;&nbsp;
                <?php } ?>
                <?php
		break;
		case 5:
		?>
                <?php
		$input_p = M('input')->where(array('input_pid'=>$v['input_id']))->select();
		foreach($input_p as $k2=>$v2){
		?>
                <label>
                  <input type="radio" name="data[<?php echo $v['field_name'];?>]" value="<?php echo $v2['input_value']?>" />
                  <?php echo $v2['input_name'];?> </label>
                &nbsp;&nbsp;
                <?php } ?>
                <?php
		break;
				
						case 6:  		?>
                <select class="f144887219015" name="data[<?php echo $v['field_name'];?>]" id="<?php echo $v['field_name'];?>">
                  <option value="0">请选择</option>
                  <?php  		$input_p = M('input')->where(array('input_pid'=>$v['input_id']))->select();  		foreach($input_p as $k2=>$v2){  		?>
                  <option value="<?php echo $v2['input_value'];?>"><?php echo $v2['input_name'];?></option>
                  <?php } ?>
                </select>
                <?php  		break;  		case 7:  		?>
                <input class="f144887219016" name="<?php echo $v['field_name'];?>" id="<?php echo $v['field_name'];?>" type="file" />
                <?php  		break;  		case 8:  		?>
                <input name="data[<?php echo $v['field_name'];?>]" class="f144887219017" type="text" id="<?php echo $v['field_name'];?>" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" size="<?php echo $v['edit_size'];?>" />
                <?php  		break;  	}  	?>
              </dd>
            </dl>
            <?php }?>
            <dl class="f14436873648">
              <input class="f14436873649" name="" type="submit" value="提交">
            </dl>
          </div>
        </form>
      </div>
      <script type="text/javascript">
function return_message()
{
	<?php  $input = M('input')->where(array('type_id'=>$type_id,'show_switch'=>2,'required_switch'=>2,'input_pid'=>array('exp','is null')))->order('date asc')->select();  foreach($input as $k=>$v){?>
	if($("#<?php echo $v['field_name'];?>").val()=='')
	{
		alert("<?php echo $v['prompt'];?>");
		return false;
	}
	<?php }?>
}
</script> 
    </div>
  </div>
</div>
<?php require APP_ROOT.'public/bottom.php';?>
