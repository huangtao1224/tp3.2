<?php require APP_ROOT.'public/head.php';?>
<script type="text/javascript">
    $('#form').ajaxForm({ success:function showResponse(responseText)  {
        layer.msg(responseText,{time:1500},function(){
            parent.location.reload();
        });
    }});
</script>
<style>
    body{overflow: auto}
    body::-webkit-scrollbar {width : 8px;height: 8px;background: transparent;}
    body::-webkit-scrollbar-thumb {border-radius: 8px;box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);background:#666;}
    body::-webkit-scrollbar-track {background:transparent;}
    .perssion_box{border:1px solid #eee;}
    .perssion_list{padding: 10px 25px;display: flex;align-items: center;}
    .perssion_list label{margin-right: 10px;}
</style>
<div class="ajaxcontent">
    <form action="admin.php?m=permissions&a=permissions_edit_save2" method="post" enctype="multipart/form-data" id="form">
        <?php $user_id=pg('user_id'); ?>
        <input name="user_id" type="hidden" id="date" value="<?php echo $user_id;?>" />
        <div class="flex tabconent_row" style="align-items: flex-start">
            <div class="tabconent_row_title flex align-items">权限分配</div>
            <div class="tabconent_row_input">
                <?php
                $list = M('admin_classify ac')->join('index_user_classify uc on ac.classify_id=uc.classify_id')->where(array('level_id'=>2,'is_delete'=>2,'user_id'=>$user['user_id']))->order('ac.classify_id asc')->select();
                foreach ($list as $k=>$v){
                    $check = M('permission')->where(array('user_id'=>$user_id,'admin_classify_permission'=>$v['classify_id'].'_1'))->find();
                    $add = M('permission')->where(array('user_id'=>$user_id,'admin_classify_permission'=>$v['classify_id'].'_2'))->find();
                    $edit = M('permission')->where(array('user_id'=>$user_id,'admin_classify_permission'=>$v['classify_id'].'_3'))->find();
                    $del = M('permission')->where(array('user_id'=>$user_id,'admin_classify_permission'=>$v['classify_id'].'_4'))->find();
                    $list2 = M('admin_classify')->where('classify_pid='.$v['classify_id'].' and is_delete=2')->select();
                    $classify = M('classify')->field('classify_id,level_id,classify_pid,type_id,classify_name,version_id')->where(array('level_id'=>2))->order('classify_id asc')->select();
                    ?>
                    <div class="perssion_box">
                        <div style="background: #efefef;padding: 5px 10px;">
                            <input name="" type="checkbox" class="checkbox" onclick="SelectAll2('<?php if($v['classify_id']==3){ echo 'c_classify_'.$v['classify_id'];}else { echo 'perssion_'.$v['classify_id'];}?>')" value="" id="chechbox_all_<?php echo $v['classify_id'];?>"/>
                            <label for="chechbox_all_<?php echo $v['classify_id'];?>" class="checkbox"><?php echo $v['classify_name'];?></label>
                        </div>
                        <?php
                        if($list2){foreach ($list2 as $k2=>$v2){?>
                            <?php
                            $check2 = M('permission')->where(array('user_id'=>$user_id,'admin_classify_permission'=>$v2['classify_id'].'_1'))->find();
                            $add2 = M('permission')->where(array('user_id'=>$user_id,'admin_classify_permission'=>$v2['classify_id'].'_2'))->find();
                            $edit2 = M('permission')->where(array('user_id'=>$user_id,'admin_classify_permission'=>$v2['classify_id'].'_3'))->find();
                            $del2 = M('permission')->where(array('user_id'=>$user_id,'admin_classify_permission'=>$v2['classify_id'].'_4'))->find();
                            ?>
                            <div class="perssion_list">
                                <div>
                                    <input name="" type="checkbox" class="checkbox" onclick="SelectAll2('perssion_<?php echo $v2['classify_id'];?>')" value="" id="chechbox_all_<?php echo $v2['classify_id'];?>"/>
                                    <label for="chechbox_all_<?php echo $v2['classify_id'];?>" class="checkbox"><?php echo $v2['classify_name'];?></label>
                                </div>
                                <div>
                                    <input type="checkbox" name="data[permission_id][]" value="<?php echo $v['classify_id'].'_1';?>" id="list_<?php echo $v['classify_id'];?>" class="perssion_<?php echo $v2['classify_id'];?>" <?php if($check2){ echo 'checked';}?>/>
                                    <label class="checkbox" for="list_<?php echo $v['classify_id'];?>">查看</label>
                                    <input type="checkbox" name="data[permission_id][]" value="<?php echo $v['classify_id'].'_2';?>" id="add_<?php echo $v['classify_id'];?>" class="perssion_<?php echo $v2['classify_id'];?>" <?php if($add2){ echo 'checked';}?>/>
                                    <label class="checkbox" for="add_<?php echo $v['classify_id'];?>">添加</label>
                                    <input type="checkbox" name="data[permission_id][]" value="<?php echo $v['classify_id'].'_3';?>" id="edit_<?php echo $v['classify_id'];?>" class="perssion_<?php echo $v2['classify_id'];?>" <?php if($edit2){ echo 'checked';}?>/>
                                    <label class="checkbox" for="edit_<?php echo $v['classify_id'];?>">编辑</label>
                                    <input type="checkbox" name="data[permission_id][]" value="<?php echo $v['classify_id'].'_4';?>" id="del_<?php echo $v['classify_id'];?>" class="perssion_<?php echo $v2['classify_id'];?>" <?php if($del2){ echo 'checked';}?>/>
                                    <label class="checkbox" for="del_<?php echo $v['classify_id'];?>">删除</label>
                                </div>
                            </div>
                        <?php } }else if($v['classify_id']==3){  foreach ($classify as $k2=>$v2){?>
                            <?php
                            $check3 = M('permission')->where(array('user_id'=>$user_id,'classify_permission'=>$v2['classify_id'].'_1'))->find();
                            $add3 = M('permission')->where(array('user_id'=>$user_id,'classify_permission'=>$v2['classify_id'].'_2'))->find();
                            $edit3 = M('permission')->where(array('user_id'=>$user_id,'classify_permission'=>$v2['classify_id'].'_3'))->find();
                            $del3 = M('permission')->where(array('user_id'=>$user_id,'classify_permission'=>$v2['classify_id'].'_4'))->find();
                            $con3 = M('permission')->where(array('user_id'=>$user_id,'classify_permission'=>$v2['classify_id'].'_5'))->find();
                            ?>
                            <div class="perssion_list">
                                <div>
                                    <input name="" type="checkbox" class="checkbox c_classify_<?php echo $v['classify_id'];?>" onclick="SelectAll2('classify_<?php echo $v2['classify_id'];?>')" value="" id="chechbox_classify_<?php echo $v2['classify_id'];?>"/>
                                    <label for="chechbox_classify_<?php echo $v2['classify_id'];?>" class="checkbox"><?php echo $v2['classify_name'];?>(<span style="color: #f00;"><?php echo M('site')->where('version_id='.$v2['version_id'])->getField('version_name');?></span>)</label>
                                </div>
                                <div>
                                    <input type="checkbox" name="data[c_permission_id][]" value="<?php echo $v2['classify_id'].'_1';?>" id="c_list_<?php echo $v2['classify_id'];?>" class="classify_<?php echo $v2['classify_id'];?> c_classify_<?php echo $v['classify_id'];?>" <?php if($check3){ echo 'checked';}?>/>
                                    <label class="checkbox" for="c_list_<?php echo $v2['classify_id'];?>">查看</label>
                                    <input type="checkbox" name="data[c_permission_id][]" value="<?php echo $v2['classify_id'].'_2';?>" id="c_add_<?php echo $v2['classify_id'];?>" class="classify_<?php echo $v2['classify_id'];?> c_classify_<?php echo $v['classify_id'];?>" <?php if($add3){ echo 'checked';}?>/>
                                    <label class="checkbox" for="c_add_<?php echo $v2['classify_id'];?>">添加</label>
                                    <input type="checkbox" name="data[c_permission_id][]" value="<?php echo $v2['classify_id'].'_3';?>" id="c_edit_<?php echo $v2['classify_id'];?>" class="classify_<?php echo $v2['classify_id'];?> c_classify_<?php echo $v['classify_id'];?>" <?php if($edit3){ echo 'checked';}?>/>
                                    <label class="checkbox" for="c_edit_<?php echo $v2['classify_id'];?>">编辑</label>
                                    <input type="checkbox" name="data[c_permission_id][]" value="<?php echo $v2['classify_id'].'_4';?>" id="c_del_<?php echo $v2['classify_id'];?>" class="classify_<?php echo $v2['classify_id'];?> c_classify_<?php echo $v['classify_id'];?>" <?php if($del3){ echo 'checked';}?>/>
                                    <label class="checkbox" for="c_del_<?php echo $v2['classify_id'];?>">删除</label>
                                    <input type="checkbox" name="data[c_permission_id][]" value="<?php echo $v2['classify_id'].'_5';?>" id="c_con_<?php echo $v2['classify_id'];?>" class="classify_<?php echo $v2['classify_id'];?> c_classify_<?php echo $v['classify_id'];?>" <?php if($con3){ echo 'checked';}?>/>
                                    <label class="checkbox" for="c_con_<?php echo $v2['classify_id'];?>">内容操作</label>
                                </div>
                            </div>
                        <?php } }else{?>
                            <div class="perssion_list">
                                <input type="checkbox" name="data[permission_id][]" value="<?php echo $v['classify_id'].'_1';?>" id="list_<?php echo $v['classify_id'];?>" class="perssion_<?php echo $v['classify_id'];?>" <?php if($check){ echo 'checked';}?>/>
                                <label class="checkbox" for="list_<?php echo $v['classify_id'];?>">查看</label>
                                <?php if($v['classify_id']!=2){?>
                                <input type="checkbox" name="data[permission_id][]" value="<?php echo $v['classify_id'].'_2';?>" id="add_<?php echo $v['classify_id'];?>" class="perssion_<?php echo $v['classify_id'];?>" <?php if($add){ echo 'checked';}?>/>
                                <label class="checkbox" for="add_<?php echo $v['classify_id'];?>">添加</label>
                                <?php }?>
                                <input type="checkbox" name="data[permission_id][]" value="<?php echo $v['classify_id'].'_3';?>" id="edit_<?php echo $v['classify_id'];?>" class="perssion_<?php echo $v['classify_id'];?>" <?php if($edit){ echo 'checked';}?>/>
                                <label class="checkbox" for="edit_<?php echo $v['classify_id'];?>">编辑</label>
                                <?php if($v['classify_id']!=2){?>
                                <input type="checkbox" name="data[permission_id][]" value="<?php echo $v['classify_id'].'_4';?>" id="del_<?php echo $v['classify_id'];?>" class="perssion_<?php echo $v['classify_id'];?>" <?php if($del){ echo 'checked';}?>/>
                                <label class="checkbox" for="del_<?php echo $v['classify_id'];?>">删除</label>
                                <?php }?>
                            </div>
                        <?php }?>
                    </div>
                <?php }?>
            </div>
        </div>
        <input type="submit" class="submit" value="确认提交">
    </form>
</div>
<?php require APP_ROOT.'public/foot.php';?>
