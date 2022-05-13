<?php
require APP_ROOT.'public/top.php';
$type_id = pg('type_id');
?>
<script type="text/javascript">
    $('#form_menu').ajaxForm({ success:function showResponse(responseText)  {
        layer.msg(responseText,{time:1500},function(){
            parent.location.reload();
        });
    }});
</script>
<div class="content">
    <?php require APP_ROOT.'public/left.php';?>
    <div id="right">
        <div class="right_data">
            <div class="button_box">
                <a href="javascript:;" data-url="admin.php?m=input&a=add&type_id=<?php echo $type_id;?>&time=<?php echo time()?>" class="button model_btn">添加表单</a>
            </div>
            <form method="post" id="form_menu" action="admin.php?m=input&a=batch_edit_save" class="white table_form">
                <input name="type_id" type="hidden" value="<?php echo $type_id;?>" />
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 40px;padding-left: 10px;">ID</th>
                            <th>名称</th>
                            <th style="width: 70px;"></th>
                            <th style="width: 75px;">默认值</th>
                            <th>表单提示</th>
                            <th>备注</th>
                            <th style="width: 80px;">颜色</th>
                            <th>表单名</th>
                            <th style="width: 60px;">必填</th>
                            <th style="width: 60px;">后台</th>
                            <th style="width: 60px;">前台</th>
                            <th style="width: 60px;">价格</th>
                            <th style="width: 60px;">大小</th>
                            <th style="width: 85px;">表单类型</th>
                            <th style="width: 85px;">数据类型</th>
                            <th style="width: 85px;">数据长度</th>
                            <th style="width: 60px;">列表</th>
                            <th style="width: 85px;">列表大小</th>
                            <th style="width: 180px;">排序</th>
                            <th style="width: 65px;">操作</th>
                        </tr>
                    </thead>
                    <?php
                    $list = M('input')->where(array('type_id'=>$type_id,'input_pid'=>array('EXP','IS NULL')))->order('date asc')->select();
                    foreach($list as $k=>$v){
                    ?>
                    <tr class="row_list2">
                        <td style="width: 40px;padding-left: 10px;">
                            <input name="data[input_id][]" type="hidden" value="<?php echo $v['input_id'];?>" />
                            <?php echo $v['input_id'];?>
                        </td>
                        <td>
                            <input name="data[input_name][]" type="text" value="<?php echo $v['input_name'];?>"/>
                            <input name="data[input_value][]" type="hidden" value="<?php echo $v['input_value'];?>" />
                        </td>
                        <td>
                            <?php
                            $input_type = M('input_type')->where(array('input_type_id'=>$v['input_type_id'],'is_parent'=>2))->select();
                            if(!empty($input_type)){
                                ?>
                                <a href="javascript:;" data-url="admin.php?m=input&a=child_add&type_id=<?php echo $type_id;?>&input_pid=<?php echo $v['input_id']?>&time=<?php echo time()?>" class="model_btn button">添加</a>
                            <?php }	?>
                        </td>
                        <td><input name="data[default_value][]" type="text" value="<?php echo $v['default_value'];?>"/></td>
                        <td><input name="data[prompt][]" type="text" value="<?php echo $v['prompt'];?>"/></td>
                        <td><input name="data[note][]" type="text" value="<?php echo $v['note'];?>"/></td>
                        <td><input name="data[color][]" type="text" value="<?php echo $v['color'];?>"/></td>
                        <td><input name="data[field_name][]" type="text" value="<?php echo $v['field_name'];?>"/></td>
                        <td>
                            <select name="data[required_switch][]" class="w40">
                                <option value="1"<?php if($v['required_switch']==1)echo 'selected="selected"';?>>否</option>
                                <option value="2"<?php if($v['required_switch']==2)echo 'selected="selected"';?>>是</option>
                            </select>
                        </td>
                        <td>
                            <select name="data[edit_switch][]" class="w40">
                                <option value="1"<?php if($v['edit_switch']==1)echo 'selected="selected"';?>>隐</option>
                                <option value="2"<?php if($v['edit_switch']==2)echo 'selected="selected"';?>>显</option>
                            </select>
                        </td>
                        <td>
                            <select name="data[show_switch][]" class="w40">
                                <option value="1"<?php if($v['show_switch']==1)echo 'selected="selected"';?>>隐</option>
                                <option value="2"<?php if($v['show_switch']==2)echo 'selected="selected"';?>>显</option>
                            </select>
                        </td>
                        <td>
                            <select name="data[price_switch][]" class="w40">
                                <option value="1"<?php if($v['price_switch']==1)echo 'selected="selected"';?>>否</option>
                                <option value="2"<?php if($v['price_switch']==2)echo 'selected="selected"';?>>是</option>
                            </select>
                        </td>
                        <td><input name="data[edit_size][]" type="text" value="<?php echo $v['edit_size'];?>"/></td>
                        <td>
                            <select name="data[input_type_id][]">
                                <?php
                                $list2 = M('input_type')->select();
                                foreach($list2 as $k2=>$v2){
                                    ?>
                                    <option value="<?php echo $v2['input_type_id'];?>"<?php if($v['input_type_id']==$v2['input_type_id'])echo 'selected="selected"';?>><?php echo $v2['input_type_name'];?></option>
                                <?php }?>
                            </select>
                        </td>
                        <td>
                            <select name="data[data_type_id][]">
                                <?php
                                $list2 = M('data_type')->select();
                                foreach($list2 as $k2=>$v2){
                                    ?>
                                    <option value="<?php echo $v2['data_type_id'];?>"<?php if($v['data_type_id']==$v2['data_type_id'])echo 'selected="selected"';?>><?php echo $v2['data_type_name'];?></option>
                                <?php }?>
                            </select>
                        </td>
                        <td><input name="data[data_length][]" type="text" value="<?php echo $v['data_length'];?>"/></td>
                        <td>
                            <select name="data[list_switch][]" >
                                <option value="1"<?php if($v['list_switch']==1)echo 'selected="selected"';?>>隐</option>
                                <option value="2"<?php if($v['list_switch']==2)echo 'selected="selected"';?>>显</option>
                            </select>
                        </td>
                        <td><input name="data[list_size][]" type="text" value="<?php echo $v['list_size'];?>"/></td>
                        <td><input name="data[date][]" type="text" value="<?php echo cover_time($v['date'],'Y-m-d H:i:s');?>"/></td>
                        <td><a href="javascript:;" onclick="confirm('确定删除吗!','admin.php?m=input&a=del_save&type_id=<?php echo $type_id;?>&input_id=<?php echo $v['input_id'];?>&time=<?php echo time();?>','admin.php?m=input&a=index&type_id=<?php echo $type_id;?>')" class="delete">删除</a></td>
                    </tr>
                    <?php
                    //if(!empty($input_type)){
                    $input = M('input')->where(array('input_pid'=>$v['input_id']))->select();
                    foreach($input as $k2=>$v2){
                    ?>
                    <tr>
                        <td></td>
                        <td>
                            <input name="data[input_id][]" type="hidden" value="<?php echo $v2['input_id'];?>" />
                            <input name="data[default_value][]" type="hidden" value="<?php echo $v2['default_value'];?>" />
                            <input name="data[prompt][]" type="hidden" value="<?php echo $v2['prompt'];?>" />
                            <input name="data[note][]" type="hidden" value="<?php echo $v2['note'];?>" />
                            <input name="data[field_name][]" type="hidden" value="<?php echo $v2['field_name'];?>" />
                            <input name="data[required_switch][]" type="hidden" value="1" />
                            <input name="data[edit_switch][]" type="hidden" value="1" />
                            <input name="data[show_switch][]" type="hidden" value="1" />
                            <input name="data[price_switch][]" type="hidden" value="1" />
                            <input name="data[input_type_id][]" type="hidden" value="1" />
                            <input name="data[data_type_id][]" type="hidden" value="1" />
                            <input name="data[list_switch][]" type="hidden" value="1" />
                            <input name="data[edit_size][]" type="hidden" value="<?php echo $v2['edit_size'];?>" />
                            <input name="data[data_length][]" type="hidden" value="<?php echo $v2['data_length'];?>" />
                            <input name="data[list_size][]" type="hidden" value="<?php echo $v2['list_size'];?>" />
                            <?php echo $v2['input_id'];?>
                        </td>
                        <td>
                            <input name="data[input_name][]" type="text" value="<?php echo $v2['input_name'];?>"/>
                        </td>
                        <td><input name="data[input_value][]" type="text" value="<?php echo $v2['input_value'];?>"/></td>
                        <td></td>
                        <td></td>
                        <td><input name="data[color][]" type="text" value="<?php echo $v2['color'];?>"/></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><input name="data[date][]" type="text" value="<?php echo cover_time($v2['date'],'Y-m-d H:i:s');?>"/></td>
                        <td><a  href="javascript:;" onclick="confirm('确定删除吗!','admin.php?m=input&a=del_save&input_id=<?php echo $v2['input_id'];?>&type_id=<?php echo $type_id;?>','admin.php?m=input&a=index&type_id=<?php echo $type_id;?>')" class="delete">删除</a></td>
                    </tr>
                    <?php } //}?>
                    <?php }?>
                    <tr>
                        <td colspan="20"><input name="" type="submit" class="submit" value="确认提交" style="width: 150px;"/></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<script>
    $('.model_btn').click(function(){
        var url = $(this).attr('data-url');
        layer.open({
            type: 2,
            title:'在线编辑',
            maxmin:true,
            resize:true,
            area:['50%','80%'],
            content: url,
        });
    })
</script>
<?php require APP_ROOT.'public/bottom.php';?>
