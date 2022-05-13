<?php require APP_ROOT.'public/member_top.php';?>

<div class="bg">
  <div class="member_main">
    <?php require APP_ROOT.'public/member_left.php';?>
    <div class="member_right">
      <div class="title">
        <h3>我的资料</h3>
      </div>
      <div class="mydata">
<form action="index.php?m=member&a=mydata" method="post" enctype="multipart/form-data" id="form">
<?php $member=M('member')->where(array('member_id'=>$member_id))->find();?>
        <?php  $input = M('input')->where(array('type_id'=>46,'show_switch'=>2,'input_pid'=>array('exp','is null')))->order('date asc')->select();  foreach($input as $k=>$v){?>
        <dl>
          <dt><?php echo $v['input_name']?></dt>
          <dd>
    <?php
	switch($v['input_type_id']){
		case 1:
		?>
        <input class="text" name="data[<?php echo $v['field_name'];?>]" type="text" id="<?php echo $v['field_name'];?>" value="<?php echo $member[$v['field_name']];?>" size="<?php echo $v['edit_size'];?>" />
        <?php
		break;
		case 2:
		?>
        <textarea name="data[<?php echo $v['field_name'];?>]" cols="60" rows="<?php echo $v['edit_size'];?>" id="<?php echo $v['field_name'];?>"><?php echo $member[$v['field_name']];?></textarea>
        <?php
		break;
		case 3:
			$facname='data['.$v['field_name'].']';
			echo fckeditor('data['.$v['field_name'].']',$v['edit_size'],$member[$v['field_name']]);
		break;
		case 4:
		?>
        <?php
		$input_p = M('input')->where(array('input_pid'=>$v['input_id']))->select();
		$valarr=unserialize($member[$v['field_name']]);
		foreach($input_p as $k2=>$v2){
		?>
        <label>
        <input type="checkbox" name="data[<?php echo $v['field_name'];?>][]"<?php if(in_array($v2['input_id'],$valarr))echo ' checked="checked"';?> value="<?php echo $v2['input_id']?>" />
        <?php echo $v2['input_name'];?>
        </label>
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
        <input type="radio" name="data[<?php echo $v['field_name'];?>]" value="<?php echo $v2['input_id']?>" <?php if($member[$v['field_name']]==$v2['input_id'])echo ' checked="checked"';?>/>
        <?php echo $v2['input_name'];?>
        </label>&nbsp;&nbsp;
        <?php } ?>
        <?php
		break;
		case 6:
		?>
        <select name="data[<?php echo $v['field_name'];?>]" id="<?php echo $v['field_name'];?>">
        <option value="0">请选择</option>
        <?php
		$input_p = M('input')->where(array('input_pid'=>$v['input_id']))->select();
		foreach($input_p as $k2=>$v2){
		?>
          <option value="<?php echo $v2['input_id'];?>"<?php if($member[$v['field_name']]==$v2['input_id'])echo ' selected="selected"';?>><?php echo $v2['input_name'];?></option>
        <?php } ?>
        </select>
        <?php
		break;
		case 7:
		?>
        <input name="<?php echo $v['field_name'];?>" id="<?php echo $v['field_name'];?>" type="file" />
        <?php
		if($member[$v['field_name']]!='')
		{
			echo '<a href="/'.$member[$v['field_name']].'" target="_blank"><img src="'.$member[$v['field_name']].'" width="50" height="25"/></a>';
			?>
            <a href="javascript:;" onclick="if(confirm('确定删除吗!')){delete_img('admin.php?m=content&a=delete_img&content_id=<?php echo $content_id;?>&table_name=<?php echo $table_name;?>&type_id=<?php echo $type_id;?>&field_name=<?php echo $v['field_name'];?>',$(this))}" class="delete">删除</a>
            <?php
		}
		break;
		case 8:
		?>
        <input class="text" name="data[<?php echo $v['field_name'];?>]" type="text" id="<?php echo $v['field_name'];?>" onclick="laydate({istime: true, format: 'YYYY-MM-DD'})" value="<?php echo cover_time($member[$v['field_name']],'Y-m-d')?>" size="<?php echo $v['edit_size'];?>" />
        <?php
		break;		
	}
	?>
          </dd>
        </dl>
        <?php } ?>
        <dl>
        <dt>&nbsp;</dt>
        <dd>
        <input class="submit" value="保存" type="submit">
        </dd>
        </dl>
</form>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<?php require APP_ROOT.'public/bottom.php';?>
