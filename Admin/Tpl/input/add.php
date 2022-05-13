<?php require APP_ROOT.'public/head.php';?>
<script type="text/javascript">
    $('#form').ajaxForm({ success:function showResponse(responseText)  {
        layer.msg(responseText,{time:1500},function(){
            parent.location.reload();
        });
    }});
</script>
<div class="ajaxcontent">
    <?php $type_id=pg('type_id');?>
    <form action="admin.php?m=input&a=add_save" onsubmit="return return_inputadd()" method="post" id="form">
        <input name="data[type_id]" type="hidden" value="<?php echo $type_id;?>" />
        <input name="data[date]" type="hidden" value="<?php echo time();?>" />
        <div class="tabconent">
            <div class="flex align-items tabconent_row">
                <div class="tabconent_row_title flex align-items">表单名：</div>
                <div class="tabconent_row_input">
                    <input name="data[input_name]" type="text" id="input_name">
                </div>
            </div>
            <div class="flex align-items tabconent_row">
                <div class="tabconent_row_title flex align-items">字段名：</div>
                <div class="tabconent_row_input">
                    <input name="data[field_name]" type="text" id="field_name">
                </div>
            </div>
            <div class="flex align-items tabconent_row">
                <div class="tabconent_row_title flex align-items">表单类型：</div>
                <div class="tabconent_row_input">
                    <select name="data[input_type_id]">
                        <?php
                        $list = M('input_type')->select();
                        foreach($list as $k=>$v){
                            ?>
                            <option value="<?php echo $v['input_type_id'];?>"><?php echo $v['input_type_name'];?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="flex align-items tabconent_row">
                <div class="tabconent_row_title flex align-items">数据类型：</div>
                <div class="tabconent_row_input">
                    <select name="data[data_type_id]">
                        <?php
                        $list = M('data_type')->select();
                        foreach($list as $k=>$v){

                            ?>
                            <option value="<?php echo $v['data_type_id'];?>"><?php echo $v['data_type_name'];?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="flex align-items tabconent_row">
                <div class="tabconent_row_title flex align-items">数据类型：</div>
                <div class="tabconent_row_input">
                    <input name="data[data_length]" type="text" value="0"/>
                </div>
            </div>
        </div>
        <input class="submit" type="submit" value="确认提交">
    </form>
</div>
<?php require APP_ROOT.'public/bottom.php';?>
