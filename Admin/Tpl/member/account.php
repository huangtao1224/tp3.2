<?php require APP_ROOT.'public/top.php';?>
<div class="content">
    <?php $member_id=pg('member_id');
	    $type_id=51;
	    $data=pg('data');
	    $search_type=pg('search_type');
	    $search=pg('search');
	    $lt_date=pg('lt_date');
	    $gt_date=pg('gt_date');
	    if($search_type=='member_id')
	    {
		    $data[$search_type]=$search;
	    }
	    else if($search!='')
	    {
		    $member=M('member')->where(array($search_type=>$search))->field('member_id')->find();
		    $data['member_id']=array('in',$member);
	    }
	    if($member_id!='')
	    {
		    $where['member_id']=$member_id;
		    $search=$member_id;
	    }
        if($lt_date && $gt_date)
        {
            $where['date']=array(array('gt',strtotime($gt_date)),array('lt',strtotime($lt_date)));
        }
        else if($lt_date)
        {
            $where['date']=array('lt',strtotime($lt_date));
        }
        else if($gt_date)
        {
            $where['date']=array('gt',strtotime($gt_date));
        }

        foreach($data as $k=>$v)
        {
            if($v)$where[$k]=$v;
        }
        $member_add =  M('permission')->where('admin_classify_id='.pg('admin_classify_id').' and user_id='.$user['user_id'].' and permission_name=2')->find();
        $member_edit =  M('permission')->where('admin_classify_id='.pg('admin_classify_id').' and user_id='.$user['user_id'].' and permission_name=3')->find();
        $member_del =  M('permission')->where('admin_classify_id='.pg('admin_classify_id').' and user_id='.$user['user_id'].' and permission_name=4')->find();
        ?>
    <?php require APP_ROOT.'public/left.php';?>
    <div id="right">
        <div class="right_data">
            <?php if($member_add||$user['permissions']!=3){?>
            <div class="button_box flex">
                <a href="javascript:;" data-url="admin.php?m=member&a=add_account&ajax=1&member_id=<?php echo $member_id; ?>&time=<?php echo time() ?>" class="button model_btn">财务操作</a>
            </div>
            <?php }?>
            <form action="admin.php?m=member&a=account" method="post" class="button_box flex member_form align-items">
                <select id="search_type" name="search_type">
                    <option value="member_id"<?php if($search_type=='member_id')echo ' selected="selected"';?>>会员ID</option>
                    <option value="username"<?php if($search_type=='username')echo ' selected="selected"';?>>用户名</option>
                    <option value="qq"<?php if($search_type=='qq')echo ' selected="selected"';?>>QQ</option>
                    <option value="mobile"<?php if($search_type=='mobile')echo ' selected="selected"';?>>手机</option>
                </select>
                <input id="search" name="search" type="text" value="<?php echo $search;?>" />
                筛选:
                <select id="amount_type" name="data[amount_type]">
                    <option value="" selected>财务类型</option>
                    <?php $input=M('input')->where(array('input_pid'=>425))->select();foreach($input as $k=>$v){?>
                        <option value="<?php echo $v['input_value'];?>"<?php if($data['amount_type']==$v['input_value'])echo ' selected="selected"';?>><?php echo $v['input_name'];?></option>
                    <?php }?>
                </select>
                <select id="state" name="data[state]">
                    <option value="" selected>状态</option>
                    <?php $input=M('input')->where(array('input_pid'=>430))->select();foreach($input as $k=>$v){?>
                        <option value="<?php echo $v['input_value'];?>"<?php if($data['state']==$v['input_id'])echo ' selected="selected"';?>><?php echo $v['input_name'];?></option>
                    <?php }?>
                </select>
                <div class="time_box">
                    时间范围:
                    <input type="text" class="laydate-input" id="start_time" value="<?php echo $gt_date;?>" size="25" name="gt_date" placeholder="开始时间"/>
                    -
                    <input type="text" class="laydate-input" id="end_time" value="<?php echo $lt_date;?>" size="25" name="lt_date" placeholder="结束时间" style="margin-left: 10px;"/>
                </div>

                <input type="submit" class="submit" value="搜索" />
            </form>
            <div style="background: #fff;padding: 10px;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>财务ID</th>
                            <th>会员ID</th>
                            <th>用户名</th>
                            <th>财务类型</th>
                            <th>交易金额</th>
                            <th>余额</th>
                            <th>备注</th>
                            <th>状态</th>
                            <th>操作时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <?php
                    $perpage=20;
                    $offset=($p-1)*$perpage;//偏移量
                    $account=M('account')->where($where)->limit($offset,$perpage)->order('account_id desc')->select();
                    //echo M()->getlastsql();
                    $total_num=M('account')->where($where)->count();
                    foreach($account as $key=>$v){
                    ?>
                    <tr class="row_list">
                        <td><?php echo $v['account_id'];?></td>
                        <td><?php echo $v['member_id'];?></td>
                        <td><?php echo M('member')->where(array('member_id'=>$v['member_id']))->getField('username');?></td>
                        <td><?php echo M('input')->where(array('input_value'=>$v['amount_type'],'input_pid'=>425))->getField('input_name');?></td>
                        <td>
                            <?php
                            if($v['amount_type']==1)
                            {
                                echo "+".$v['amount'];
                            }
                            else
                            {
                                echo "-".$v['amount'];
                            }?>
                        </td>
                        <td><?php echo $v['balance'];?></td>
                        <td><?php echo $v['note'];?></td>
                        <td><?php $state=M('input')->where(array('input_value'=>$v['state'],'input_pid'=>430))->find();?>
                            <span style="color:<?php echo $state['color'];?>"><?php echo $state['input_name'];?></span>
                        </td>
                        <td><?php echo cover_time($v['date'],'Y-m-d H:i:s');?></td>

                        <td>
                            <?php if($member_edit||$user['permissions']!=3){?>
                            <a href="javascript:;" data-url="admin.php?m=cms&a=edit&ajax=1&type_id=<?php echo $type_id;?>&table_name=account&content_id=<?php echo $v['account_id'];?>" class="edit model_btn">修改</a>
                            <?php }?>
                        </td>
                    </tr>
                    <?php }?>
                </table>
                <?php require APP_ROOT.'public/page.php';?>
            </div>
        </div>
    </div>
</div>
<script>
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        laydate.render({
            elem: '.time_box',
            type: 'datetime',
            range: ['#start_time', '#end_time']
        });
    });
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
