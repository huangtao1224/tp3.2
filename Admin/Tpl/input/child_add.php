<?php require APP_ROOT.'public/head.php';?>
<script type="text/javascript">
    $('#form').ajaxForm({ success:function showResponse(responseText)  {
        layer.msg(responseText,{time:1500},function(){
            parent.location.reload();
        });
    }});
</script>
<div class="ajaxcontent">
    <?php
    $type_id=pg('type_id');
    $input_pid=pg('input_pid');
    ?>
    <form action="admin.php?m=input&a=child_add_save" method="post" id="form">
        <input name="data[input_pid]" type="hidden" value="<?php echo $input_pid;?>" />
        <input name="data[type_id]" type="hidden" value="<?php echo $type_id;?>" />
        <input name="data[date]" type="hidden" value="<?php echo time();?>" />
        <div class="tabconent">
            <div class="flex align-items tabconent_row">
                <div class="tabconent_row_title flex align-items">选项名称：</div>
                <div class="tabconent_row_input">
                    <input name="data[input_name]" id="menu_name" type="text">
                </div>
            </div>
            <div class="flex align-items tabconent_row">
                <div class="tabconent_row_title flex align-items">选项值：</div>
                <div class="tabconent_row_input">
                    <input name="data[input_value]" id="input_value" type="text">
                </div>
            </div>
        </div>
        <input class="submit" type="submit" value="确认提交">
    </form>
</div>
<?php require APP_ROOT.'public/foot.php';?>
