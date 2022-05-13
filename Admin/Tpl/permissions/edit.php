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
    <form action="admin.php?m=permissions&a=edit_save" method="post" enctype="multipart/form-data" id="form">
        <?php
        $user_id=pg('user_id');
        $user2 = M('user')->where(array('user_id'=>$user_id))->find();
        ?>
        <input name="user_id" type="hidden" id="date" value="<?php echo $user_id;?>" />
        <div class="tabconent">
            <div class="flex align-items tabconent_row">
                <div class="tabconent_row_title flex align-items">名称</div>
                <div class="tabconent_row_input"><input name="data[name]" id="name" type="text" value="<?php echo $user2['name'];?>"></div>
            </div>
            <div class="flex align-items tabconent_row">
                <div class="tabconent_row_title flex align-items">用户名</div>
                <div class="tabconent_row_input"><input name="data[username]" id="username" type="text" value="<?php echo $user2['username'];?>"></div>
            </div>
            <div class="flex align-items tabconent_row">
                <div class="tabconent_row_title flex align-items">密码</div>
                <div class="tabconent_row_input"><input name="data[password]" id="password" type="text"></div>
            </div>
            <div class="flex align-items tabconent_row">
                <div class="tabconent_row_title flex align-items">管理员身份</div>
                <div class="tabconent_row_input">
                    <?php
                    if($user['user_id']==1){
                        $role = M('role')->select();
                    }else{
                        $role = M('role')->where('role_id>1')->select();
                    }
                    foreach ($role as $k=>$v){?>
                        <input type="radio" name="data[permissions]" value="<?php echo $v['role_id']?>" id="radio_<?php echo $v['role_id'];?>" <?php if($user2['permissions']==$v['role_id']){ echo 'checked';}?>/>
                        <label class="radio_label" for="radio_<?php echo $v['role_id'];?>"><?php echo $v['role_name'];?></label>
                    <?php }?>
                </div>
            </div>
            <?php if($user['permissions']==1&&$user2['permissions']==2){?>
            <div class="admin_classify_box">
                <div class="flex align-items tabconent_row">
                    <div class="tabconent_row_title flex align-items">超级管理员权限</div>
                    <div class="tabconent_row_input">
                        <?php
                        if($user['user_id']==1){
                            $admin_classify = M('admin_classify')->where('classify_pid=1')->select();
                        }else{
                            $admin_classify = M('user_classify')->where('user_id='.$user['classify_id'])->select();
                        }
                        foreach ($admin_classify as $k=>$v){?>
                            <?php $user_classify = M('user_classify')->where(array('user_id'=>$user_id['user_id'],'classify_id'=>$v['classify_id']))->find();?>
                            <input type="checkbox" name="data[admin_classify_id][]" value="<?php echo $v['classify_id'];?>" id="admin_classify_<?php echo $v['classify_id'];?>" <?php if($user_classify){ echo 'checked';}?>/>
                            <label class="checkbox" for="admin_classify_<?php echo $v['classify_id'];?>" style="margin-right: 10px;"><?php echo $v['classify_name'];?></label>
                        <?php }?>
                    </div>
                </div>
            </div>
            <?php }?>
        </div>
        <input type="submit" class="submit" value="确认提交">
    </form>
</div>
<script>
    $('input[name="data[permissions]"]').click(function () {
        if($(this).val()==3){
            $('.role_box').show();
            $('.admin_classify_box').hide();
        }else if($(this).val()==2){
            $('.admin_classify_box').show();
            $('.role_box').hide();
        }else{
            $('.role_box').hide();
            $('.admin_classify_box').hide();
        }
    })
</script>
<?php require APP_ROOT.'public/bottom.php';?>
