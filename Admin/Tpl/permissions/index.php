<?php require APP_ROOT.'public/top.php';?>
<div class="content">
    <?php require APP_ROOT.'public/left.php';?>
    <?php
    $add =  M('permission')->where('admin_classify_id='.pg('admin_classify_id').' and user_id='.$user['user_id'].' and permission_name=2')->find();
    $edit =  M('permission')->where('admin_classify_id='.pg('admin_classify_id').' and user_id='.$user['user_id'].' and permission_name=3')->find();
    $del =  M('permission')->where('admin_classify_id='.pg('admin_classify_id').' and user_id='.$user['user_id'].' and permission_name=4')->find();
    ?>
    <div id="right">
        <div class="right_data">
            <?php if($add||$user['permissions']!=3){?>
            <div class="button_box flex">
                <a href="javascript:;" data-url="admin.php?m=permissions&a=add&ajax=1&time=<?php echo time()?>" class="button model_btn">添加内容</a>
            </div>
            <?php }?>
            <div style="background: #fff;padding: 10px;">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="20%">用户名</th>
                            <th width="20%">登录名</th>
                            <th width="20%">身份</th>
                            <th width="10%">状态</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <?php
                    $u = M('user')->where('user_id>1')->select();

                    foreach($u as $k=>$v)
                    {
                    ?>
                    <tr class="row_list">
                        <td><?php echo $v['name']?></td>
                        <td><?php echo $v['username']?></td>
                        <td><?php echo M('role')->where('role_id='.$v['permissions'])->getField('role_name');?></td>
                        <td>
                            <label class="el-switch">
                                <input type="checkbox" name="switch" <?php if($v['state']==1){ echo 'checked';}?>>
                                <span class="el-switch-style" data-id="<?php echo $v['user_id'];?>"></span>
                            </label>
                        </td>
                        <td>
                            <?php if($edit||$user['permissions']!=3){?>
                            <a href="javascript:;" data-url="admin.php?m=permissions&a=edit&user_id=<?php echo $v['user_id'];?>" class="edit model_btn">信息修改</a>
                            <?php if($v['permissions']==3){?>
                            <a href="javascript:;" data-url="admin.php?m=permissions&a=permissions_edit&user_id=<?php echo $v['user_id'];?>" class="edit model_btn">权限修改</a>
                            <?php } }?>
                            <?php if($del||$user['permissions']!=3){?>
                            <a href="javascript:;" onclick="confirm('确定删除吗!','admin.php?m=permissions&a=del_save&user_id=<?php echo $v['user_id'];?>','')" class="delete">删除</a>
                            <?php }?>
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
            area:['50%','50%'],
            content: url,
        });
    })
    $('.el-switch-style').click(function () {
        var user_id = $(this).attr('data-id');
        if($(this).siblings("input[type='checkbox']").attr('checked')){
            var state = 0;
        }else {
            var state = 1;
        };
        $.ajax({
            url:'?m=permissions&a=state_save',
            type:'post',
            data:{
                id:user_id,
                state:state,
            },
            success:function (msg) {
                layer.msg(msg);
            }
        })
    })
</script>
<?php require APP_ROOT.'public/bottom.php';?>
