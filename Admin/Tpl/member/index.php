<?php require APP_ROOT.'public/top.php';?>
<script type="text/javascript">
    $('#form_menu').ajaxForm({ success:function showResponse(responseText)  {
        msg_show(responseText,500000);
        goto_url("");
    }});
</script>
<div class="content">
    <?php require APP_ROOT.'public/left.php';?>
    <?php
    $type_id = 46;
    $export_content_id=pg('export_content_id');
    $table_name = M('classify_type')->where(array('type_id' => $type_id))->getField('table_name');
    $member_add =  M('permission')->where('admin_classify_id='.pg('admin_classify_id').' and user_id='.$user['user_id'].' and permission_name=2')->find();
    $member_edit =  M('permission')->where('admin_classify_id='.pg('admin_classify_id').' and user_id='.$user['user_id'].' and permission_name=3')->find();
    $member_del =  M('permission')->where('admin_classify_id='.pg('admin_classify_id').' and user_id='.$user['user_id'].' and permission_name=4')->find();
    ?>
    <div id="right">
        <div class="right_data">
            <div class="button_box flex">
                <?php if($member_add||$user['permissions']!=3){?>
                <a href="javascript:;" data-url="admin.php?m=cms&a=add&ajax=1&type_id=<?php echo $type_id; ?>&time=<?php echo time()?>" class="button model_btn">添加会员</a>
                <?php }?>
                <?php $search=pg('search') ?>
                <form action="admin.php?m=member&a=index&admin_classify_id=16" method="post">
                    <input type="text" name='search' placeholder="请输入用户名">
                    <input type="submit" class="submit" value="搜索"/>
                </form>
            </div>
            <div style="background: #fff;">
                <form method="post"<?php if($export_content_id==''){?> id="form_menu"<?php }?> action="admin.php?m=content&a=batch_edit_save" >
                    <table class="table">
                        <thead>
                            <tr>
                                <input name="table_name" type="hidden" id="table_name" value="<?php echo $table_name;?>" />
                                <input name="type_id" type="hidden" id="type_id" value="<?php echo $type_id;?>" />
                                <input name="classify_id" type="hidden" id="classify_id" value="<?php echo $classify_id;?>" />
                                <th class="table_checkbox_all">
                                    <input name="" type="checkbox" class="checkbox" onclick="SelectAll('content_id[]')" value="" id="checkbox_all"/>
                                    <label for="checkbox_all" class="checkbox"></label>
                                </th>
                                <th style="width: 85px">ID</th>
                                <?php
                                $where = array();
                                $where['type_id'] = $type_id;
                                $where['list_switch'] = 2;
                                $list_input=M('input')->where($where)->order('date asc')->select();
                                foreach($list_input as $k=>$v){
                                ?>
                                <th><?php echo $v['input_name']?></th>
                                <?php }?>
                                <th>余额</th>
                                <th style="width: 90px;">明细</th>
                                <th>排序时间</th>
                                <th style="width: 160px;">操作</th>
                            </tr>
                        </thead>
                        <?php
                        $perpage=20;
                        $offset=($p-1)*$perpage;//偏移量
                        $list = M($table_name)->limit($offset,$perpage)->order('date desc')->select();
                        $total_num = M($table_name)->count();
                        if($search){
                            $sear['member_id']=array('like',"%$search%");
                            $sear['username']=array('like',"%$search%");
                            $sear['_logic']='OR';
                            $list=M('member')->where($sear)->select();
                            $total_num=10;
                        }
                        foreach($list as $k=>$v){
                        ?>
                        <tr class="row_list">
						    <td>
						        <input name="content_id[]" type="checkbox" class="checkbox" value="<?php echo $v[$table_name.'_id']?>" id="checkbox_<?php echo $v['member_id'];?>"/>
                                <label for="checkbox_<?php echo $v['member_id'];?>" class="checkbox"></label>
							    <input name="data[content_id][]" type="hidden" value="<?php echo $v[$table_name.'_id']?>" />
						    </td>
						    <td><?php echo $v[$table_name.'_id']?></td>
						    <?php foreach($list_input as $key=>$val){ ?>
						    <td>
                                <?php if($val['input_type_id']==7){?>
                                <a href="<?php echo $v[$val['field_name']];?>" target="_blank"><img src="<?php echo $v[$val['field_name']];?>" width="50" height="15" /></a>
                                <?php }else if($val['input_type_id']==6){?>
                                <select name="data[<?php echo $val['field_name'];?>][]">
                                    <option value="0">请选择</option>
                                    <?php
                                    $input = M('input')->where(array('input_pid' => $val['input_id']))->order('date asc')->select();
                                    foreach($input as $k2=>$v2){
                                    ?>
                                        <option value="<?php echo $v2['input_id'];?>"<?php if($v[$val['field_name']]==$v2['input_id']){?> selected="selected"<?php }?>><?php echo $v2['input_name'];?></option>
                                    <?php } ?>
                                </select>
                                <?php }else{?>
                                <!--余额只能为正值-->
                                <input type="text" id="<?php echo $val['field_name'].'a';?>" name="data[<?php echo $val['field_name'];?>][]" style="width:<?php echo $val['list_size'];?>px;" value="<?php echo $v[$val['field_name']];?>" />
                                <?php }?>
							</td>
                            <?php }?>
                            <td style="color: #f00">
                            <?php $balance = M('account')->where(array('member_id' => $v['member_id']))->order('account_id desc')->getField('balance');if($balance=='')$balance=0;echo $balance;?>
                            </td>
                            <td>
                                <a class="remains" href="admin.php?m=member&a=account&admin_classify_id=<?php pg('admin_classify_id');?>&member_id=<?php echo $v['member_id'];?>">明细</a>
                            </td>
                            <td><input name="data[date][]" type="text" value="<?php echo cover_time($v['date'],'Y-m-d H:i:s')?>" size="20" /></td>
                            <!--
                            <td>
						        <a href="javascript:;" onclick="if(confirm('是否通过审核')){ajax_list('admin.php?m=member&a=check_member&member_id=<?php echo $v['member_id'];?>&state=<?php echo $v['state'] ?>&table_name=<?php echo $table_name;?>&type_id=<?php echo $type_id;?>','','<?php echo $_SESSION['url'];?>')}" class="delete">待审核</a>
						    </td>
                            -->
                            <td>
                                <?php if($member_edit||$user['permissions']!=3){?>
                                <a href="javascript:;" data-url="admin.php?m=cms&a=edit&type_id=<?php echo $type_id;?>&content_id=<?php echo $v[$table_name.'_id'];?>&table_name=<?php echo $table_name;?>&ajax=1&time=<?php echo time()?>" class="edit model_btn">修改</a>
                                <?php }?>
                                <?php if($member_del||$user['permissions']!=3){?>
                                <a href="javascript:;" onclick="confirm('确定删除吗!','admin.php?m=cms&a=del_save&content_id=<?php echo $v[$table_name.'_id'];?>&table_name=<?php echo $table_name;?>&type_id=<?php echo $type_id;?>','','<?php echo $_SESSION['url'];?>')" class="delete">删除</a>
                                <?php }?>
                            </td>
                        </tr>
                        <?php }?>
                    </table>
                    <div class="content_btn_w">
                        <?php if($function_switch['xls_member']==2){?>
                        <select class="shared_select" name="export_content_id" id="export_content_id" onChange="export_content()">
                            <option value="">导出会员</option>
                            <option value="1"<?php if($export_content_id==1)echo ' selected="selected"';?>>确定导出</option>
                        </select>
                        <?php }?>
                        <?php if($export_content_id){?>
                        <div class="export_field">
                            <?php
                            $input = M('input')->where(array('type_id'=>$type_id,'edit_switch'=>2,'input_type_id'=>array('neq',3),'input_pid'=>array('exp','is null')))->order('date asc')->select();
                            foreach($input as $k=>$v){
                            ?>
                            <label>
                                <input name="export[<?php echo $v['field_name'];?>]" class="export_check" type="checkbox" value="<?php echo $v['input_name'];?>" /><?php echo $v['input_name'];?>
                            </label>
                            <?php }?>
                        </div>
                        <select class="shared_select" name="export_content_check" id="export_content_check">
                            <option value="">导出选重内容</option>
                            <option value="1">导出所有内容(耗资大请慎用)</option>
                        </select>
                        <?php }?>
                        <input name="" class="submit" type="submit" value="提交" />
                    </div>
                </form>
                <?php require APP_ROOT.'public/page.php';?>
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
            area:['50%','70%'],
            content: url,
        });
    })
</script>
<?php require APP_ROOT.'public/bottom.php';?>
