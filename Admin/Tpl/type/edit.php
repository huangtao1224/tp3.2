<?php require APP_ROOT.'public/head.php';
$ctype=M('classify_type')->where('type_id='.pg('type_id'))->find();
?>

<div class="ajaxcontent"> 
<script type="text/javascript">
  $('#form').ajaxForm({ success:function showResponse(responseText)  {
      layer.msg(responseText,{time:1500},function(){
          parent.location.reload();
      });
  }});
</script>
  <div class="menu_add">
    <form action="admin.php?m=type&a=edit_save" method="post" id="form">
      <ul>
        <input type="hidden" name="type_id" value="<?php echo $ctype['type_id'];?>" />
        <li><span>类型名称：</span>
          <input value="<?php echo $ctype['type_name'];?>" readonly type="text">
        </li>
        <li><span>表名：</span>
          <input value="<?php echo $ctype['table_name'];?>" readonly type="text">
        </li>
        <li><span>页面名：</span>
          <input readonly type="text" value="index">
        </li>
        <li><span>关联表单：</span>
        <?php
          $list=M('classify_type')->where('show_id=2')->select();
          foreach ($list as $k => $v) {
            if(!$v['table_name']){continue;}
        ?>
          <span style="margin:0 5px 5px 0;white-space: nowrap;min-width: 100px;width:auto;">
              <input type="checkbox" name="data[glids][]" value="<?php echo $v['type_id']?>" id="gl<?php echo $k;?>" <?php if(in_array($v['type_id'],explode(',',$ctype['glids']))){echo 'checked';}?> />
              <label for="gl<?php echo $k;?>" class="checkbox"><?php echo $v['type_name'].'（'.$v['table_name'].'）';?></label></span>
        <?php }?>
            <div style="clear: both;"></div>
        </li>
        <li style="color:red;">*创建关联表会自动创建关联id，同时之前的操作也会自动完成（即创建关联表只需在此处选择即可）</li>
        <input class="submit" style="margin-left:100px;" type="submit" value="确认无误，提交">
      </ul>
    </form>
  </div>
</div>
<?php require APP_ROOT.'public/foot.php';?>
