<?php require APP_ROOT.'public/top.php';?>
<script type="text/javascript">
    $('#form_menu0').ajaxForm({ success:function showResponse(responseText)  {
        layer.msg(responseText,{time:1500},function(){
            parent.location.reload();
        });
    }});
</script>
<div class="content">
    <?php require APP_ROOT.'public/left.php';?>
    <?php
    $search=trim(pg('search'));
    $lt_date=pg('lt_date');
    $gt_date=pg('gt_date');
    $state=pg('state');
    $order_add =  M('permission')->where('admin_classify_id='.pg('admin_classify_id').' and user_id='.$user['user_id'].' and permission_name=2')->find();
    $order_edit =  M('permission')->where('admin_classify_id='.pg('admin_classify_id').' and user_id='.$user['user_id'].' and permission_name=3')->find();
    $order_del =  M('permission')->where('admin_classify_id='.pg('admin_classify_id').' and user_id='.$user['user_id'].' and permission_name=4')->find();
    ?>
    <div id="right">
        <div class="right_data">
            <div class="remind flex align-items">
                <?php
                $dfk_count=M('order')->where(array('state'=>1))->count();//待付款
                $dfh_count=M('order')->where(array('state'=>2))->count();//待发货
                $dsh_count=M('order')->where(array('state'=>3))->count();//待收货
                ?>
                <div class="<?php if(pg('state')==1){ echo 'state_'.pg('state');}?>" data-state="1">待付款订单(<?php echo $dfk_count;?>)</div>
                <div class="<?php if(pg('state')==2){ echo 'state_'.pg('state');}?>" data-state="2">已支付订单(<?php echo $dfh_count;?>)</div>
                <div class="<?php if(pg('state')==3){ echo 'state_'.pg('state');}?>" data-state="3">已完成订单(<?php echo $dsh_count;?>)</div>
            </div>
            <form action="admin.php?m=order&a=index&admin_classify_id=15" method="post" id="myForm" class="flex align-items" style="display: flex;align-items: center;">
                <input type="hidden" name="state" id="state" value="<?php echo pg('state');?>"/>
                <input type="hidden" name="p" id="orderp" value=""/>
                搜索：
                <input id="search" name="search" type="text" value="<?php echo $search;?>"/>
                <div id="date_box" style="margin: 0 10px;">
                    时间范围：
                    <input type="text" class="laydate-input" id="start_time" value="<?php echo $gt_date;?>" size="25" name="gt_date" placeholder="开始日期"/>
                    -
                    <input type="text" class="laydate-input" id="end_time" value="<?php echo $lt_date;?>" size="25" name="lt_date" placeholder="结束时间"/>
                </div>
                <input type="submit" class="submit" value="搜索"/>
            </form>
            <div style="background: #fff;">
                <form method="post" id="form_menu" action="admin.php?m=order&a=batch_edit_save">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="table_checkbox_all">
                                    <input name="" type="checkbox" class="checkbox" onclick="SelectAll('order_id[]')" value="" id="checkbox_all"/>
                                    <label for="checkbox_all" class="checkbox"></label>
                                </th>
                                <th>订单编号</th>
                                <th>商品名</th>
                                <th>信息</th>
                                <th>订单金额</th>
                                <th>下单时间</th>
                                <th>状态</th>
                                <th class="td5">操作</th>
                            </tr>
                        </thead>
                        <?php
                        $perpage=20;
                        $offset=($p-1)*$perpage;//偏移量
                        //条件
                        if($state){
                            $where['state']=$state;
                        }
                        if($search){
                            $map['order_number']=$search;
                            $map['member_id']=$search;
                            $map['note']=array('like','%'.$search.'%');
                            $map['_logic']='or';
                            $where['_complex']=$map;
                        }
                        if($gt_date && !$lt_date){
                            $where['date']=array('egt',strtotime($gt_date));
                        }
                        if(!$gt_date && $lt_date){
                            $where['date']=array('elt',strtotime($gt_date));
                        }
                        if($gt_date && $lt_date){
                            $where['date']=array(array('egt',strtotime($gt_date)),array('elt',strtotime($lt_date)));
                        }
                        $order=M('order')->where($where)->order('date desc')->limit($offset,$perpage)->select();
                        $total_num=M('order')->where($where)->count();
                        foreach($order as $k=>$v){
                        ?>
                        <tbody>
                            <tr class="row_list">
                                <td>
                                    <input name="order_id[]" type="checkbox" value="<?php echo $v['order_id']?>" id="checkbox_<?php echo $v['order_id'];?>" />
                                    <label class="checkbox" for="checkbox_<?php echo $v['order_id'];?>" style="margin-left: 7px;"></label>
                                </td>
                                <td><?php echo $v['order_number'];?></td>
                                <?php
                                $goods=M('goods')->where('goods_id='.$v['goods_id'])->find();
                                $g_list=M('g_list')->where('g_list_id='.$v['g_list_id'])->find();
                                ?>
                                <td><?php echo $goods['goods_name'];?></td>
                                <td class="td1" style="display: none">
                                    <div class="gsattr">
                                        <div class="red">
                                            <img src="<?php echo $goods['goods_img'];?>" />
                                            <img src="<?php echo $g_list['pic'];?>" />
                                        </div>
                                    </div>
                                </td>
                                <td class="td2" style="display: none">
                                    <div class="consignee">
                                        <?php
                                        if(!$v['type_id'] && !$v['beian'] && !$v['bzj']){
                                        ?>
                                        <div><?php echo $goods['goods_name'];?>（<?php echo $g_list['name'];?>）</div>
                                        <p>手机号码：<?php echo $v['phone'];?>&nbsp;&nbsp;&nbsp;&nbsp;入住人数：<?php echo $v['nums'];?></p>
                                        <p>入住时间：<?php echo date('Y-m-d',$v['start_time']);?>&nbsp;&nbsp;&nbsp;&nbsp;离开时间：<?php echo date('Y-m-d',$v['end_time']);?><span style="color:red;">（共 <b><?php echo ($v['end_time']-$v['start_time'])/(24*3600);?></b> 晚）</span></p>
                                        <?php }else{?>
                                        <p><?php echo $v['note'];?></p>
                                        <?php }?>
                                    </div>
                                </td>
                                <td class="td3" style="display: none">
                                    <?php
                                    if(!$v['type_id'] && !$v['beian'] && !$v['bzj']){
                                      echo M('g_list')->where('g_list_id='.$v['g_list_id'])->getField('price');
                                    }else{
                                        echo $v['price'];
                                    }
                                    ?>
                                </td>
                                <td><?php echo $v['note'];?></td>
                                <td>总价：<b>¥<?php echo $v['price'];?></b></td>
                                <td><?php echo date('Y-m-d H:i:s',$v['date']); ?></td>
                                <?php $input=M('input')->where(array('input_value'=>$v['state'],'input_pid'=>380))->field('input_name,color')->find(); ?>
                                <td style="color:<?php echo $input['color'];?>; font-weight:600;"><?php echo $input['input_name'];?></td>
                                <td>
                                    <a href="javascript:;" data-url="admin.php?m=order&a=details&order_id=<?php echo $v['order_id'] ?>&ajax=1&time=<?php echo time()?>" class="remains2 model_btn">订单详情</a>
                                    <?php if($order_edit||$user['permissions']!=3){?>
                                    <a href="javascript:;" data-url="admin.php?m=cms&a=edit&content_id=<?php echo $v['order_id'] ?>&table_name=order&type_id=47&ajax=1&time=<?php echo time()?>" class="model_btn edit">修改订单信息</a>
                                    <?php }?>
                                    <?php if($order_del||$user['permissions']!=3){?>
                                    <a href="javascript:;" onclick="confirm('确定删除吗!','admin.php?m=order&a=del_save&order_id=<?php echo $v['order_id'];?>','')" class="delete" style="margin-left: 0;">删除订单</a>
                                    <?php }?>

                                </td>
                            </tr>
                        </tbody>
                        <?php }?>
                    </table>
                    <div class="content_btn_w flex align-items" style="margin-bottom: 15px;">
                        <?php if($order_del||$user['permissions']!=3){?>
                        <select class="shared_select" name="batch_delete_id" id="batch_delete_id">
                            <option value="">批量删除</option>
                            <option value="1">确定删除</option>
                        </select>
                        <?php }?>
                        <?php if($function_switch['xls_order']==2){?>
                        <select class="shared_select" name="export_order" id="export_order">
                            <option value="">导出订单</option>
                            <option value="1">确定导出</option>
                        </select>
                        <?php }?>
                        <input name="" class="submit" type="submit" value="提交" style="margin-bottom: 0;"/>
                    </div>
                </form>
                <?php require APP_ROOT.'public/page.php';?>
            </div>
        </div>
    </div>
</div>
<script>
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        laydate.render({
            elem: '#date_box',
            type: 'datetime',
            range: ['#start_time', '#end_time']
        });
    });
    $('.remind div').click(function(){
        $('#state').val($(this).attr('data-state'));
        $('#myForm')[0].submit();
    })
	$(".page a").click(function(){
        $("#orderp").val($(this).attr('data-num'));
        $("#myForm")[0].submit();
        return false;
    });
    function on_page(num){
        $("#orderp").val(num);
        $("#sForm")[0].submit();
        return false;
    }
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

