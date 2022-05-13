<?php require APP_ROOT.'public/top.php';?>
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
                <a href="javascript:;" data-url="admin.php?m=copy_version&a=add&ajax=1&time=<?php echo time()?>'" class="button model_btn">添加版本</a>
            </div>
            <form method="post" id="form_menu" action="admin.php?m=content&a=batch_edit_save" class="white">
                <table class="table">
                    <thead>
                        <tr>
                            <input name="table_name" type="hidden" id="table_name" value="<?php echo $table_name;?>" />
                            <input name="type_id" type="hidden" id="type_id" value="<?php echo $type_id;?>" />
<!--                            <td><input name="" type="checkbox" class="checkbox" onclick="SelectAll('content_id[]')" value="" />全选</td>-->
                            <th>ID</th>
                            <th>版本名称</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <?php
                    $list = M('site')->select();
                    foreach($list as $k=>$v){
                    ?>
                        <tr class="row_list">
                            <td><?php echo $v['site_id'];?></td>
                            <td><?php echo $v['version_name'];?></td>
                            <td><a href="javascript:;" onclick="confirm('确定删除吗!','admin.php?m=copy_version&a=del_save&site_id=<?php echo $v['site_id'];?>','')" class="delete">删除</a></td>
                        </tr>
                    <?php }?>
                    </table>
                </form>
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
</script>
<?php require APP_ROOT.'public/bottom.php';?>
