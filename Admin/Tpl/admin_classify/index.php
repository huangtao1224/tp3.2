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
            <form method="post" id="form_menu" action="admin.php?m=admin_classify&a=batch_edit_save" class="navbar-form white">
                <table class="table">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>后台分类</th>
                            <th>链接</th>
                            <th>添加子分类</th>
                            <th>排序</th>
                            <?php $user=session('user'); if($user['user_id']==1){?>
                            <th>显/隐</th>
                            <?php }?>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <?php
                    global $list;
                    $list = M('admin_classify')->where(array())->order('date asc')->select();
                    function recursive_classify($classify_pid='1')
                    {
                    global $list;
                    foreach($list as $k=>$v)
                    {
                    if($v['classify_pid']==$classify_pid)
                    {
                    ?>
                    <tr class="row_list" cate-id="<?php echo $v['classify_id'];?>" fid="<?php echo $v['classify_pid'];?>">
                        <td><?php echo $v['classify_id']?>
                            <input name="classify_id[]" type="hidden" value="<?php echo $v['classify_id']?>" />
                        </td>
                        <td class="cid_<?php echo $v['level_id'];?>" status="<?php echo $v['level_id']>3?'true':'false';?>">
                            <?php $count = M('admin_classify')->where(array('classify_pid'=>$v['classify_id']))->count();?>
                            <?php if($count){?>
                                <i class="bi bi-caret-right-fill"></i>
                            <?php }?>
                            <a href="javascript:;"><?php if($v['level_id']>2){echo "|--";}?><?php echo $v['classify_name']?></a>
                        </td>
                        <td>
                            <input name="data[classify_url][]" class="form-control" id="classify_url" type="text" value="<?php echo $v['classify_url']?>" size="60">
                        </td>
                        <td>
                            <?php if($v['level_id']<3){?>
                            <a href="javascript:;" data-url="admin.php?m=admin_classify&a=add&classify_id=<?php echo $v['classify_id'];?>&time=<?php echo time();?>" class="model_btn add">添加子分类</a>
                            <?php }?>
                        </td>
                        <td><input name="data[date][]" class="form-control" type="text" value="<?php echo cover_time($v['date'],'Y-m-d H:i:s');?>" size="20" /></td>
                        <?php $user=session('user'); if($user['user_id']==1){?>
                        <td>
                            <select name="data[is_delete][]">
                                <option value="2"<?php if($v['is_delete']==2)echo ' selected="selected"';?>>显示</option>
                                <option value="1"<?php if($v['is_delete']==1)echo ' selected="selected"';?>>隐藏</option>
                            </select>
                        </td>
                        <?php }?>
                        <td>
                            <a  href="javascript:;" data-url="admin.php?m=admin_classify&a=edit&classify_id=<?php echo $v['classify_id'];?>" class="edit model_btn">编辑</a>
                            <?php $classify = M('classify')->where(array('classify_pid'=>$v['classify_id']))->find();
                            if(empty($classify)&&$count==0&&$v['is_delete']!=1){?>
                            <a  href="javascript:;" onclick="confirm('确定删除吗!','admin.php?m=admin_classify&a=del_save&classify_id=<?php echo $v['classify_id'];?>','admin.php?m=admin_classify&a=index&admin_classify_id=3')" class="delete">删除</a>
                            <?php }?>
                        </td>
                    </tr>
                    <?php
                    recursive_classify($v['classify_id']);
                    }
                    }
                    }
                    recursive_classify();
                    ?>
                    </table>
                    <input name="" type="submit" class="submit" value="确认提交" />
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        //$(".table tbody tr[leval=='3']").hide();
        // 栏目多级显示效果
        $('.bi-caret-right-fill').parent('td').click(function () {
            if($(this).attr('status')=='true'){
                $(this).children('.bi-caret-right-fill').css({'transform': 'rotate( 90deg)'});
                $(this).attr('status','false');
                cateId = $(this).parents('tr').attr('cate-id');
                $("tbody tr[fid="+cateId+"]").show();
            }else{
                cateIds = [];
                $(this).children('.bi-caret-right-fill').css({'transform': 'rotate( 0deg)'});
                $(this).attr('status','true');
                cateId = $(this).parents('tr').attr('cate-id');
                getCateId(cateId);
                for (var i in cateIds) {
                    $("tbody tr[cate-id="+cateIds[i]+"]").hide().find('.bi-caret-right-fill').css({'transform': 'rotate( 0deg)'}).attr('status','true');
                }
            }
        })
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
    })
    var cateIds = [];
    function getCateId(cateId) {
        $("tbody tr[fid="+cateId+"]").each(function(index, el) {
            id = $(el).attr('cate-id');
            cateIds.push(id);
            getCateId(id);
        });
    }
</script>
<?php require APP_ROOT.'public/bottom.php';?>
