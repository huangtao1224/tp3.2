<?php require APP_ROOT.'public/top.php';?>
<script type="text/javascript">
    $('#form').ajaxForm({ success:function showResponse(responseText)  {
        layer.msg(responseText,{time:1500},function(){
            parent.location.reload();
        });
    }});
</script>
<div class="content">
    <?php require APP_ROOT.'public/left.php';?>
    <div id="right">
        <div class="right_data">
            <?php
            $site_edit = M('permission')->where('admin_classify_id='.pg('admin_classify_id').' and user_id='.$user['user_id'].' and permission_name=3')->find();
            $list = M('site')->where(array('version_id'=>session('version_id')))->find();
            ?>
            <form method="post" id="form" action="admin.php?m=site&a=edit_save" enctype="multipart/form-data" class="white">
                <input name="version_id" type="hidden" value="<?php echo session('version_id');?>" />
                <?php if($user['user_id']==1){?>
                    <textarea id="biao1"><?php echo strtotime('10:00');echo date('Y-m-d H:i:s','1636164000');?></textarea>
                <?php }?>
                <div class="tables">
                    <h1 class="version_title"><?php echo $list['version_name'];?></h1>
                    <?php
                    $input = M('input')->where(array('type_id'=>1,'edit_switch'=>2,'input_pid'=>array('exp','is null')))->order('date asc')->select();foreach($input as $k=>$v){
                    if($v['input_id']==453 && $function_switch['sms_switch']==1){
                    }else{?>
                    <div class="flex from_box align-items">
                        <div class="form_tit"><?php echo $v['input_name']?></div>
                        <div class="form_con flex align-items">
                        <?php
                        switch($v['input_type_id']){
                            case 1:
                            if($v['input_id']==453 && $user['user_id']>1){
                                echo $list[$v['field_name']].'条';
                            }else{
                            ?>
                            <input name="data[<?php echo $v['field_name'];?>]" class="type_1" type="text" id="<?php echo $v['field_name'];?>" value="<?php echo $list[$v['field_name']];?>"  size="<?php echo $v['edit_size'];?>"/>
                            <?php }
                            break;
                            case 2:
                            ?>
                            <textarea name="data[<?php echo $v['field_name'];?>]" cols="60" rows="<?php echo $v['edit_size'];?>" id="<?php echo $v['field_name'];?>"><?php echo $list[$v['field_name']];?></textarea>
                            <?php
                            break;
                            case 3:
                            echo fckeditor('data['.$v['field_name'].']',$v['edit_size'],$list[$v['field_name']]);
                            break;
                            case 4:
                            ?>
                            <?php
                            $input_p = M('input')->where(array('input_pid'=>$v['input_id']))->select();
                            $valarr=unserialize($list[$v['field_name']]);
                            foreach($input_p as $k2=>$v2){?>
                            <label>
                                <input type="checkbox" name="data[<?php echo $v['field_name'];?>][]"<?php if(in_array($v2['input_value'],$valarr))echo ' checked="checked"';?> value="<?php echo $v2['input_value']?>" />
                                <?php echo $v2['input_name'];?>
                            </label>
                            <?php } ?>
                            <?php
                            break;
                            case 5:
                            ?>
                            <?php
                            $input_p = M('input')->where(array('input_pid'=>$v['input_id']))->select();
                            foreach($input_p as $k2=>$v2){?>
                                <div class="radio_box">
                                    <input type="radio" name="data[<?php echo $v['field_name'];?>]" value="<?php echo $v2['input_value']?>" <?php if($list[$v['field_name']]==$v2['input_value'])echo ' checked="checked"';?> id="<?php echo $v['field_name'].$v2['input_value'];?>"/>
                                    <label for="<?php echo $v['field_name'].$v2['input_value'];?>" class="radio_label" style=""><?php echo $v2['input_name'];?></label>
                                </div>
                            <?php } ?>
                            <?php
                            break;
                            case 6:
                            ?>
                            <select name="data[<?php echo $v['field_name'];?>]" id="<?php echo $v['field_name'];?>">
                                <option value="0">请选择</option>
                                <?php
                                $input_p = M('input')->where(array('input_pid'=>$v['input_id']))->select();
                                foreach($input_p as $k2=>$v2){?>
                                <option value="<?php echo $v2['input_value'];?>"<?php if($list[$v['field_name']]==$v2['input_value'])echo ' selected="selected"';?>><?php echo $v2['input_name'];?></option>
                                <?php } ?>
                            </select>
                            <?php
                            break;
                            case 7:
                            ?>
                            <span class="file-box">
                                <span>请选择文件</span>
                                <input class="link_up_size <?php if(!$v['note']){ echo 'state';}?>"  data-cid="<?php echo $v['input_id'] ?>" name="<?php echo $v['field_name'];?>" id="<?php echo $v['field_name'];?>" type="file" />
                            </span>
                            <?php
                            if($list[$v['field_name']]!=''){
                                echo '<a href="'.$list[$v['field_name']].'" target="_blank" class="img_link"><img src="'.$list[$v['field_name']].'" width="50" height="25"/></a>';
                                ?>
                                <?php if($site_edit){?>
                                <a href="javascript:;" onclick="confirm_img('确定删除吗!','admin.php?m=content&a=delete_img&content_id=<?php echo $list['site_id'];?>&table_name=site&type_id=1&field_name=<?php echo $v['field_name'];?>',$(this))" class="delete">删除</a>
                                <?php }?>
                                <?php
                            }
                            echo $v['note'];
                            break;
                            case 8:
                            ?>
                            <div class="laydate-box">
                                <input name="data[<?php echo $v['field_name'];?>]" type="text" class="laydate-input" id="<?php echo $v['field_name'];?>"  value="<?php echo cover_time($list[$v['field_name']],'Y-m-d H:i:s')?>" size="<?php echo $v['edit_size'];?>" />
                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                            </div>
                            <?php
                            break;
                            case 10:
                            ?>
                                <div class="example circle">
                                    <div class="clr-field" style="color: <?php echo $list[$v['field_name']]?:'#000';?>;"><button aria-labelledby="clr-open-label"></button><input name="data[<?php echo $v['field_name'];?>]" class="coloris" type="text" id="<?php echo $v['field_name'];?>" value="<?php echo $list[$v['field_name']];?>"  size="<?php echo $v['edit_size'];?>" autocomplete="off"/></div>
                                </div>
                            <?php
                        } ?>
                        </div>
                        <div class="note">
                            <?php $user=session('user'); if($user['user_id']==1){?>
                                <a href="javascript:;" class="copycode" onClick="site_children('<?php echo $v['field_name'];?>')">字段代码</a>
                            <?php }?>
                            <?php if($v['input_type_id']!=7){ echo $v['note']; }?>
                        </div>
                    </div>
                    <?php } }?>
                </div>
                <?php if($site_edit||$user['permissions']!=3){?>
                <div class="flex align-items">
                    <input name="" type="submit" class="submit" value="确认提交" />
                </div>
                <?php }?>
            </form>
        </div>
    </div>
</div>
<script>
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        laydate.render({
            elem: '.laydate-input',
            type: 'datetime',
        });
    });
    Coloris({
        el: '.coloris',
        swatches: [
            '#264653',
            '#2a9d8f',
            '#e9c46a',
            '#f4a261',
            '#e76f51',
            '#d62828',
            '#023e8a',
            '#0077b6',
            '#0096c7',
            '#00b4d8',
            '#48cae4',
        ]
    });
	$('.link_up_size').change(function(){
        var i=$(this).attr('data-cid');
        var rs='';
        var n=$(this).attr('id');
        var input = document.getElementById(n);
        var len = input.files.length;
        for (var j = 0; j < len; j++) {
            var temp = input.files[j].name;
            $('#'+n).siblings('span').text(temp);
        }
        if($(this).hasClass('state')){
            layer.confirm('设为默认图片尺寸?', {
                btn: ['确定','取消'] //按钮
            }, function(index){
                if(input.files){
                    //读取图片数据
                    var f = input.files[0];
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var data = e.target.result;
                        //加载图片获取图片真实宽度和高度
                        var image = new Image();
                        image.onload=function(){
                            var width = image.width;
                            var height = image.height;
                            rs=width+'px*'+height+"px";
                            $.ajax({
                                url : "admin.php?m=content&a=link_size&input_id="+i+"&value="+rs,
                                type : "GET",
                                success : function(data){
                                    alert(rs);
                                    //alert('设置成功,如需修改,请前往表单设置');
                                }
                            });
                        };
                        image.src= data;
                    };
                    reader.readAsDataURL(f);
                }else{
                    var image = new Image();
                    image.onload =function(){
                        var width = image.width;
                        var height = image.height;
                        var fileSize = image.fileSize;
                        rs=width+'px*'+height+"px";
                        $.ajax({
                            url : "admin.php?m=content&a=link_size&input_id="+i+"&value="+rs,
                            type : "GET",
                            success : function(data){
                                // alert('设置成功,如需修改,请前往表单设置');
                            }
                        });
                    }
                    image.src = input.value;
                }
                layer.close(index);
            }, function(){});
        }
    });
</script>
<?php require APP_ROOT.'public/bottom.php';?>
