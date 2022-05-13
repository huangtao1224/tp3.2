<?php if (!defined('THINK_PATH')) exit(); require APP_ROOT.'public/top.php';?>
<div class="content">
    <?php require APP_ROOT.'public/left.php';?>
    <div id="right">
        <div class="right_data">
            <div class="button_box flex align-items">
                <a href="javascript:;" data-url="admin.php?m=type&a=add&time=<?php echo time()?>" class="button model_btn">添加表单</a>
            </div>
            <div style="background: #fff;padding: 10px;">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 20px;display: none;">
                                <input name="" class="checkbox" type="checkbox" value="" id="checkbox_all"/>
                                <label class="checkbox" for="checkbox_all"></label>
                            </th>
                            <th class="class_id">ID</th>
                            <th>表单类型名称</th>
                            <th>表名</th>
                            <th style="width: 140px;">页面名</th>
                            <th style="width: 90px;">显示</th>
                            <th style="width: 140px;">表单设置</th>
                            <th style="width: 140px;">内容添加</th>
                            <th style="width: 160px;">操作</th>
                        </tr>
                    </thead>
                    <?php
 $list=M('classify_type')->select(); foreach($list as $k=>$v){?>
                    <tr class="row_list">
                        <td style="display: none;">
                            <input name="type_id[]" class="checkbox" type="checkbox" value="<?php echo $v['type_id'];?>" id="checkbox_<?php echo $v['type_id'];?>"/>
                            <label class="checkbox" for="checkbox_<?php echo $v['type_id'];?>"></label>
                        </td>
                        <td><?php echo $v['type_id'];?></td>
                        <td><input name="type_name[]" type="text" value="<?php echo $v['type_name'];?>" /></td>
                        <td><input name="table_name[]" type="text" value="<?php echo $v['table_name'];?>"/></td>
                        <td><input name="page_name[]" type="text" value="<?php echo $v['page_name'];?>"/></td>
                        <td>
                            <label class="el-switch">
                                <input type="checkbox" name="switch" <?php if($v['show_id']==2){ echo 'checked';}?>>
                                <span class="el-switch-style" data-id="<?php echo $v['type_id'];?>"></span>
                            </label>
                        </td>
                        <td><a class="con_list" href="admin.php?m=input&a=index&type_id=<?php echo $v['type_id'];?>">表单设置</a></td>
                        <td><a href="admin.php?m=cms&a=index&type_id=<?php echo $v['type_id'];?>" class="warm">内容列表</a></td>
                        <td>
                            <?php if($v['glids'] || $v['biaoshi']){?>
                                <a href="javascript:;" data-url="admin.php?m=type&a=edit&type_id=<?php echo $v['type_id'];?>" class="edit model_btn">修改</a>
                            <?php }?>
                            <a href="javascript:;" onclick="confirm('确定删除吗!','admin.php?m=type&a=del_save&type_id=<?php echo $v['type_id'];?>','admin.php?m=type&a=index&admin_classify_id=4&time=<?php echo time();?>')" class="delete">删除</a>
                        </td>
                    </tr>
                    <?php }?>
                </table>
            </div>
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
    $('.el-switch-style').click(function () {
        var type_id = $(this).attr('data-id');
        if($(this).siblings("input[type='checkbox']").attr('checked')){
            var show_id = 1;
        }else {
            var show_id = 2;
        };
        $.ajax({
            url:'?m=type&a=show_save',
            type:'post',
            data:{
                type_id:type_id,
                show_id:show_id,
            },
            success:function (msg) {
                layer.msg(msg);
            }
        })
    })
</script>
<?php require APP_ROOT.'public/bottom.php';?>