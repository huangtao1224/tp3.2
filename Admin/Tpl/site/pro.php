<?php require APP_ROOT.'public/top.php';?>
<script type="text/javascript">
    $('#form_menu').ajaxForm({ success:function showResponse(responseText)  {
        layer.msg(responseText, {time: 1500}, function () {
            parent.location.reload();
        });
    }});
</script>
<div class="content">
    <?php require APP_ROOT.'public/left.php';?>
    <div id="right">
        <div class="right_data">
            <?php
            $address_add =  M('permission')->where('admin_classify_id='.pg('admin_classify_id').' and user_id='.$user['user_id'].' and permission_name=2')->find();
            $address_edit =  M('permission')->where('admin_classify_id='.pg('admin_classify_id').' and user_id='.$user['user_id'].' and permission_name=3')->find();
            $address_del =  M('permission')->where('admin_classify_id='.pg('admin_classify_id').' and user_id='.$user['user_id'].' and permission_name=4')->find();
            ?>
            <?php if($address_add||$user['permissions']!=3){?>
            <div class="button_box flex align-items">
                <a href="javascript:;" data-url="admin.php?m=site&a=add&pid=<?php echo pg('pid');?>&time=<?php echo time()?>" class="button model_btn">添加省份</a>

            </div>
            <?php }?>
            <div class="white" style="padding: 10px;">
                <table class="address_table">
                    <thead>
                        <tr>
                            <input name="table_name" type="hidden" id="table_name" value="<?php echo $table_name;?>" />
                            <th>ID</th>
                            <th>省份名称</th>
                            <th>城市</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <?php
                    $perpage=20;
                    $offset=($p-1)*$perpage;//偏移量
                    $list=M('region')->where('region_pid=1')->order("region_id asc")->select();
                    $total_num=M('region')->where('region_pid=1')->count();
                    foreach($list as $k=>$v){
                    ?>
                        <tr class="address_list">
                            <input type="hidden" name="region_id[]" value="<?php echo $v['region_id']?>">
                            <td><?php echo $v['region_id'];?></td>
                            <td>
                                <input type="text" name="region_name[]" value="<?php echo $v['region_name'];?>">
                            </td>
                            <td><a href="?m=site&a=city&pid=<?php echo $v['region_id'];?>&region_type=<?php echo $v['region_type'];?>" class="con_list">城市列表(<?php echo M('region')->where('region_pid='.$v['region_id'])->count();?>)</a></td>
                            <td>
                                <?php if($address_edit||$user['permissions']!=3){?>
                                <a href="javascript:;" data-url="admin.php?m=site&a=edit&id=<?php echo $v['region_id'];?>&type=<?php echo $v['region_type'];?>&time=<?php echo time()?>" class="edit model_btn">修改</a>
                                <?php }?>
                                <?php if($address_del||$user['permissions']!=3){?>
                                <a href="javascript:;" onclick="confirm('确定删除吗!','admin.php?m=site&a=address_del&id=<?php echo $v['region_id'];?>','')" class="delete">删除</a>
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
            area:['50%','80%'],
            content: url,
        });
    })
</script>
<?php require APP_ROOT.'public/bottom.php';?>
