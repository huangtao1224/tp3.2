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
            <form method="post" id="form_menu" action="admin.php?m=classify&a=batch_edit_save" class="white">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="class_id">ID</th>
                            <th>栏目名称</th>
                            <th style="width: 7%;">类型</th>
                            <th style="width: 10%;">添加子分类</th>
                            <th style="width: 180px;">内容列表</th>
                            <th style="width: 15%;">排序</th>
                            <?php $user=session('user'); if($user['user_id']==1){?>
                            <th style="width: 8%;">显/隐</th>
                            <?php }?>
                            <th style="width: 160px;">操作</th>
                        </tr>
                    </thead>
                    <?php
                    $classify_add =  M('permission')->where('classify_id='.pg('recursive_classify_id').' and user_id='.$user['user_id'].' and permission_name=2')->find();
                    $classify_edit =  M('permission')->where('classify_id='.pg('recursive_classify_id').' and user_id='.$user['user_id'].' and permission_name=3')->find();
                    $classify_del =  M('permission')->where('classify_id='.pg('recursive_classify_id').' and user_id='.$user['user_id'].' and permission_name=4')->find();
                    $classify_con =  M('permission')->where('classify_id='.pg('recursive_classify_id').' and user_id='.$user['user_id'].' and permission_name=5')->find();
                    $classify = M('classify')->field('classify_id,level_id,classify_pid,type_id,classify_name,date,is_delete,classify_img,note,page_img')->where(array('classify_id'=>$recursive_classify_id,'level_id'=>array('gt',1)))->order('date asc')->select();
                    foreach($classify as $k=>$v){
                    ?>
                    <tr class="row_list" cate-id="<?php echo $v['classify_id'];?>" fid="<?php echo $v['classify_pid'];?>" <?php if($v['level_id']>3){echo 'style="display:none;"';}?>>
                        <td>
                            <?php echo $v['classify_id']?>
                            <input name="classify_id[]" type="hidden" value="<?php echo $v['classify_id']?>" />
                        </td>
                        <?php $count = M('classify')->where(array('classify_pid'=>$v['classify_id']))->count();?>
                        <td class="row_recursive" status="<?php echo $count?'false':'true';?>">
                            <i class="bi bi-caret-right-fill" <?php echo $count?:'style="transform: rotate(0deg);"';?>></i>
                            <?php for($i=3;$i<=$v['level_id'];$i++)echo "&nbsp;&nbsp;&nbsp;";?>
                            <a href="javascript:;"><?php echo $v['classify_name']?></a>
                            <?php
                            $arr=array('8888');
                            if(in_array($v['classify_id'],$arr)){ ?>
                                <a  class="link_nav" cid="<?php echo $v['classify_id'] ?>" state="1" href="javascript:;">-</a>
                            <?php } ?>
                            <?php if($v['note']!='')echo '<span class="classify_note">['.$v['note'].']</span>';?>
                            <?php if($v['classify_img']!='')echo '<a href="'.$v['classify_img'].'" target="_blank"><img src="'.$v['classify_img'].'" width="50" height="20"/></a>';?>
                            <?php if($v['page_img']!='')echo '<a href="'.$v['page_img'].'" target="_blank"><img src="'.$v['page_img'].'" width="50" height="20"/></a>';?>
                        </td>
                        <td><?php $type = M('classify_type')->where(array('type_id'=>$v['type_id']))->find();echo $type['type_name']; ?></td>
                        <td>
                            <?php if($classify_add||$user['permissions']!=3){?>
                            <a href="javascript:;" data-url="admin.php?m=classify&a=add&classify_id=<?php echo $v['classify_id'];?>&time=<?php echo time();?>" class="model_btn add">添加子分类</a>
                            <?php }else{ echo '无';}?>
                        </td>

                        <?php $count= M('relevance')->where(array('classify_id'=>$v['classify_id']))->count();?>
                        <td>
                            <?php if(($v['type_id']!= 13&&$user['permissions']!=3)||($v['type_id']!=13&&$classify_con2)){?>
                                <a class="con_list" href="admin.php?m=content&a=index&type_id=<?php echo $v['type_id']?>&classify_id=<?php echo $v['classify_id']?>">内容列表(<?php echo $count ;?>)</a>
                            <?php }else{ echo '无';}?>
                        </td>
                        <td><input name="data[date][]" type="text" value="<?php echo cover_time($v['date'],'Y-m-d H:i');?>" size="20" /></td>
                        <?php $user=session('user'); if($user['user_id']==1){?>
                        <td>
                            <select name="data[is_delete][]">
                                <?php
                                $input_p = M('input')->where(array('input_pid'=>351))->select();
                                foreach($input_p as $k2=>$v2){
                                    ?>
                                    <option value="<?php echo $v2['input_value'];?>"<?php if($v['is_delete']==$v2['input_value'])echo ' selected="selected"';?>><?php echo $v2['input_name'];?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <?php }?>
                        <td>
                            <?php if($classify_edit||$user['permissions']!=3){?>
                            <a href="javascript:;" data-url="admin.php?m=classify&a=edit&classify_id=<?php echo $v['classify_id'];?>" class="edit model_btn">编辑</a>
                            <?php }?>
                            <?php $classify = M('classify')->where(array('classify_pid'=>$v['classify_id']))->find();
                            if((empty($classify)&&$count==0&&$v['is_delete']!=2&&$user['permissions']!=3)||($classify_del&&empty($classify)&&$count==0&&$v['is_delete']!=2)){?>
                                <a  href="javascript:;" onclick="confirm('确定删除吗!','admin.php?m=classify&a=del_save&classify_id=<?php echo $v['classify_id'];?>','admin.php?m=classify&a=index&admin_classify_id=3')" class="delete">删除</a>
                            <?php }?>
                        </td>
                    </tr>
                    <?php }?>
                    <?php
                    global $list;global $classify_add2;global $classify_edit2;global $classify_del2;global $classify_con2;global $user2;
                    $list = M('classify')->field('classify_id,level_id,classify_pid,type_id,classify_name,date,is_delete,classify_img,note,page_img')->where(array('version_id'=>$version_id))->order('date asc')->select();
                    $classify_add2 =  M('permission')->where('classify_id='.pg('recursive_classify_id').' and user_id='.$user['user_id'].' and permission_name=2')->find();
                    $classify_edit2 =  M('permission')->where('classify_id='.pg('recursive_classify_id').' and user_id='.$user['user_id'].' and permission_name=3')->find();
                    $classify_del2 =  M('permission')->where('classify_id='.pg('recursive_classify_id').' and user_id='.$user['user_id'].' and permission_name=4')->find();
                    $classify_con2 =  M('permission')->where('classify_id='.pg('recursive_classify_id').' and user_id='.$user['user_id'].' and permission_name=5')->find();
                    $user2 = M('user')->where('user_id='.session('user')['user_id'])->find();
                    function recursive_classify($classify_pid='1')
                    {
                        global $list;global $classify_add2;global $classify_edit2;global $classify_del2;global $classify_con2;global $user2;
                        foreach($list as $k=>$v)
                        {
                            if($v['classify_pid']==$classify_pid)
                            {
                    ?>
                    <tr class="row_list" style="<?php if($v['classify_id']==$_SESSION['classify_id']){echo 'background: #ececec;';}?>" cate-id="<?php echo $v['classify_id'];?>" fid="<?php echo $v['classify_pid'];?>">
                        <td><?php echo $v['classify_id']?>
                            <input name="classify_id[]" type="hidden" value="<?php echo $v['classify_id']?>" />
                        </td>
                        <?php $count = M('classify')->where(array('classify_pid'=>$v['classify_id']))->count();?>
                        <td class="class_name cid_<?php echo $v['level_id'];?>" status="false">
                            <?php if($count){?>
                                <i class="bi bi-caret-right-fill" style="transform: rotate(90deg);"></i>
                            <?php }?>
                            <?php
                            $arr=array('8888');
                            $zi=M('classify')->where(array('classify_pid'=>$v['classify_id']))->find();
                            if($v['level_id']==3 && $zi){
                                ?>
                            <?php } ?>
                            <a href="javascript:;"><?php if(!$count){echo "|--";}?><?php echo $v['classify_name']?></a>
                            <?php if($v['note']!='')echo '<span class="classify_note">['.$v['note'].']</span>';?>
                            <?php if($v['classify_img']!='')echo '<a href="'.$v['classify_img'].'" target="_blank"><img src="'.$v['classify_img'].'" width="50" height="20"/></a>';?>
                            <?php if($v['page_img']!='')echo '<a href="'.$v['page_img'].'" target="_blank"><img src="'.$v['page_img'].'" width="50" height="20"/></a>';?>
                        </td>
                        <td><?php $type = M('classify_type')->where(array('type_id'=>$v['type_id']))->find();echo $type['type_name']; ?></td>
                        <td>
                            <?php if($classify_add2||$user2['permissions']!=3){?>
                            <a href="javascript:;" data-url="admin.php?m=classify&a=add&classify_id=<?php echo $v['classify_id'];?>&time=<?php echo time();?>" class="model_btn add">添加子分类</a>
                            <?php }else{ echo '无';}?>
                        </td>
                        <?php $count= M('relevance')->where(array('classify_id'=>$v['classify_id'],'type_id'=>$v['type_id']))->count();?>
                        <td>
                            <?php if(($v['type_id']!= 13&&$user2['permissions']!=3)||($v['type_id']!=13&&$classify_con2)){?>
                                <a class="con_list" href="admin.php?m=content&a=index&type_id=<?php echo $v['type_id']?>&classify_id=<?php echo $v['classify_id']?>">内容列表(<?php echo $count ;?>)</a>
                            <?php }else{ echo '无';}?>
                        </td>
                        <td><input name="data[date][]" type="text" value="<?php echo cover_time($v['date'],'Y-m-d H:i');?>" size="20" /></td>
                        <?php $user=session('user'); if($user['user_id']==1){?>
                            <td>
                                <select name="data[is_delete][]">
                                    <?php
                                    $input_p = M('input')->where(array('input_pid'=>351))->select();
                                    foreach($input_p as $k2=>$v2){
                                        ?>
                                        <option value="<?php echo $v2['input_id'];?>"<?php if($v['is_delete']==$v2['input_id'])echo ' selected="selected"';?>><?php echo $v2['input_name'];?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        <?php }?>
                        <td>
                            <?php if($classify_edit2||$user2['permissions']!=3){?>
                            <a href="javascript:;" class="edit model_btn" data-url="admin.php?m=classify&a=edit&classify_id=<?php echo $v['classify_id'];?>">编辑</a>
                            <?php }?>
                            <?php $classify = M('classify')->where(array('classify_pid'=>$v['classify_id']))->find();
                            if((empty($classify)&&$count==0&&$v['is_delete']!=353&&$user2['permissions']!=3)||(empty($classify)&&$count==0&&$v['is_delete']!=353&&$classify_del2)){?>
                                <a  href="javascript:;" onclick="confirm('确定删除吗!','admin.php?m=classify&a=del_save&classify_id=<?php echo $v['classify_id'];?>','')" class="delete">删除</a>
                            <?php }?>
                        </td>
                    </tr>
                    <?php
                        recursive_classify($v['classify_id']);
                            }
                        }
                    }
                    recursive_classify($recursive_classify_id);
                    ?>
                </table>
                <?php if($classify_edit||$user['permissions']!=3){?>
                <input name="" type="submit" class="submit" value="确认提交" />
                <?php }else{?>
                <div style="height: 20px;"></div>
                <?php }?>
            </form>
        </div>
    </div>
</div>
<?php require APP_ROOT.'public/bottom.php';?>
<script type="text/javascript">
    $(".right_data").scroll(function(){
        var scrollTop=$(".right_data").scrollTop();
        $.post('admin.php?m=content&a=save_top',{'scrollTop':scrollTop},function(d){

        });
    });
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
                area:['50%','80%'],
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
