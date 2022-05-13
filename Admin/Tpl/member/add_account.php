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
        $type_id=51;
        $member_id=pg('member_id');
    ?>
    <form action="admin.php?m=member&a=add_account_save" method="post" enctype="multipart/form-data" id="form">
        <input name="data[date]" type="hidden" id="date" value="<?php echo time();?>" />
        <input name="data[version_id]" type="hidden" id="version_id" value="1" />
        <input name="data[type_id]" type="hidden" id="type_id" value="<?php echo $type_id; ?>" />
        <input name="data[member_id]" type="hidden" id="member_id" value="<?php echo $member_id; ?>" />
        <div class="flex align-items tabconent_row">
            <div class="tabconent_row_title flex align-items">用户名</div>
            <div class="tabconent_row_input"><?php echo M('member')->where(array('member_id'=>$member_id))->getfield('username');?></div>
        </div>
        <?php
        $list = M('input')->where(array('type_id'=>$type_id,'edit_switch'=>2,'input_pid'=>array('exp','is null')))->order('date asc')->select();
        foreach($list as $k=>$v){
        ?>
        <div class="flex align-items tabconent_row">
            <div class="tabconent_row_title flex align-items">
                <?php echo $v['input_name']?>
            </div>
            <div class="tabconent_row_input">
                <?php
                switch($v['input_type_id']){
                    case 1:
                        ?>
                        <input name="data[<?php echo $v['field_name'];?>]" type="text" id="<?php echo $v['field_name'];?>" size="<?php echo $v['edit_size'];?>" value="<?php if($v['field_name']=='member_id')echo $member_id;?>" />
                        <?php
                        break;
                    case 2:
                        ?>
                        <textarea name="data[<?php echo $v['field_name'];?>]" cols="60" rows="<?php echo $v['edit_size'];?>" id="<?php echo $v['field_name'];?>"></textarea>
                        <?php
                        break;
                    case 3:
                        echo fckeditor('data['.$v['field_name'].']',$v['edit_size']);
                        break;
                    case 4:
                        ?>
                        <?php
                        $input_p = M('input')->where(array('input_pid'=>$v['input_id']))->select();
                        foreach($input_p as $k2=>$v2){
                            ?>
                            <label>
                                <input type="checkbox" name="data[<?php echo $v['field_name'];?>][]" value="<?php echo $v2['input_value']?>" />
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
                                <input type="radio" name="data[<?php echo $v['field_name'];?>]" value="<?php echo $v2['input_value']?>" />
                                <?php echo $v2['input_name'];?>
                            </label>
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
                                <option value="<?php echo $v2['input_value'];?>"><?php echo $v2['input_name'];?></option>
                            <?php } ?>
                        </select>
                        <?php
                        break;
                    case 7:
                        ?>
                        <span class="file-box">
                            <span>请选择文件</span>
                            <input name="<?php echo $v['field_name'];?>" id="<?php echo $v['field_name'];?>" type="file" class="link_up_size" style="cursor: pointer"/>
                        </span>
                        <?php
                        break;
                    case 8:
                        ?>
                        <div class="laydate-box">
                            <input name="data[<?php echo $v['field_name'];?>]" class="laydate-input" type="text" id="<?php echo $v['field_name'];?>" size="<?php echo $v['edit_size'];?>" />
                            <i class="bi bi-calendar3" aria-hidden="true"></i>
                        </div>
                        <?php
                        break;
                }
                ?>
            </div>
        </div>
        <?php }?>
        <input type="submit" class="submit" value="确认无误，提交">
    </form>
</div>
<script>
    $('.link_up_size').change(function(){
        var n=$(this).attr('id');
        var input = document.getElementById(n);
        var len = input.files.length;
        for (var i = 0; i < len; i++) {
            var temp = input.files[i].name;
            $('#'+n).siblings('span').text(temp);
            console.log(temp);
        }
    })
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        laydate.render({
            elem: '.laydate-input',
            type: 'datetime',
        });
    });
</script>
<?php require APP_ROOT.'public/bottom.php';?>
