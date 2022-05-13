<?php require APP_ROOT.'public/head.php';?>
<div class="ajaxcontent">
    <?php
    $classify_id = pg('classify_id');
    $type_id = pg('type_id');
    $content_id = pg('content_id');
    $table_name = pg('table_name');
    $list = M($table_name)->where(array($table_name . '_id' => $content_id))->find();
    ?>
    <script type="text/javascript">
        $('#form').ajaxForm({ success:function showResponse(responseText)  {
            console.log(responseText);
            // layer.msg(responseText,{time:1500},function(){
            //     parent.location.reload();
            // });
        }});
    </script>
    <style>
        body{overflow: auto}
        body::-webkit-scrollbar {width : 8px;height: 8px;background: transparent;}
        body::-webkit-scrollbar-thumb {border-radius: 8px;box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);background:#666;}
        body::-webkit-scrollbar-track {background:transparent;}
    </style>
    <form action="admin.php?m=content&a=edit_save" method="post" enctype="multipart/form-data" id="form">
        <input name="classify_id" type="hidden" value="<?php echo $classify_id;?>" />
        <input name="type_id" type="hidden" value="<?php echo $type_id;?>" />
        <input name="content_id" type="hidden" value="<?php echo $content_id;?>" />
        <?php if($list['price_array']!=''){
             $list['price_array']=unserialize($list['price_array']);
             foreach($list['price_array'] as $k=>$v){
                 $val=$v;
                 unset($val['price']);
                 unset($val['inventory']);
                 unset($val['coding']);
                 unset($val['volume']);
                 unset($val['weight']);
                 $val_id=implode('',$val);
        ?>
        <input type="hidden" value="<?php echo $v['price'];?>" id="price_<?php echo $val_id;?>" />
	    <input type="hidden" value="<?php echo $v['inventory'];?>" id="inventory_<?php echo $val_id;?>" />
	    <input type="hidden" value="<?php echo $v['coding'];?>" id="coding_<?php echo $val_id;?>" />
	    <input type="hidden" value="<?php echo $v['volume'];?>" id="volume_<?php echo $val_id;?>" />
	    <input type="hidden" value="<?php echo $v['weight'];?>" id="weight_<?php echo $val_id;?>" />
        <?php } }?>
        <?php if($user['user_id']==1){?>
        <textarea id="biao1"></textarea>
        <?php }?>
        <div class="tabouter">
            <?php $user=session('user'); if($user['user_id']==1){?>
            <div class="tabnav">
                <ul>
                    <li>基本设置</li>
                    <li>高级设置</li>
                </ul>
            </div>
            <?php }?>
            <div class="tabcon">
                <div class="tabconent">
                    <?php $user=session('user'); if($user['user_id']==1){?>
                    <div class="flex align-items tabconent_row">
                        <div class="tabconent_row_title">功能</div>
                        <div class="tabconent_row_input">
                            <a href="javascript:;" class="copycode" onClick="content_code(<?php echo $classify_id;?>,'<?php echo $type_id;?>')">循环代码</a>
                            <a href="javascript:;" class="copycode" onClick="content_url_code()">链接</a>
                            <a href="javascript:;" class="copycode" onClick="content_date_code()">时间</a>
                        </div>
                    </div>
                    <?php }?>
                    <?php
                    $input = M('input')->where(array('type_id'=>$type_id,'edit_switch'=>2,'input_pid'=>array('exp','is null')))->order('date asc')->select();
                    foreach($input as $k=>$v){
                    ?>
                    <div class="flex align-items tabconent_row">
                        <div class="tabconent_row_title flex align-items">
                            <?php echo $v['input_name']; if($v['price_switch']==2){?>
                            <input name="attribute_name[]" type="hidden" value="<?php echo $v['input_name'];?>" />
                            <input name="field_name[]" type="hidden" value="<?php echo $v['field_name'];?>" />
                            <div id="specifications<?php echo $v['field_name'];?>"></div>
                            <?php }?>
                            <?php if($v['prompt']){?>
                                <div class="tips flex align-items" data-tips="<?php echo $v['prompt'];?>"><i class="bi bi-question-circle-fill" ></i></div>
                            <?php }?>
                        </div>
                        <div class="tabconent_row_input">
                            <?php if($v['field_name']=='price_array'){?>
                                <div class="product_table"></div>
                            <?php }else{?>
                                <?php
                                switch($v['input_type_id']){
                                    case 1:
                                    ?>
                                    <input name="data[<?php echo $v['field_name'];?>]" type="text" id="<?php echo $v['field_name'];?>" value="<?php echo $list[$v['field_name']];?>" size="80"<?php if($v['price_switch']==2){?> onblur="product_specifications(4)" priceattr="specifications[<?php echo $v['field_name']?>]"<?php }?> />
                                    <?php
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
                                    foreach($input_p as $k2=>$v2){
                                    ?>
                                        <input type="checkbox" name="data[<?php echo $v['field_name'];?>][]" <?php if(in_array($v2['input_value'],$valarr))echo ' checked="checked"';?> value="<?php echo $v2['input_value']?>" id="checkbox_<?php echo $v2['input_id'];?>"/>
                                        <label class="checkbox" for="checkbox_<?php echo $v2['input_id'];?>"><?php echo $v2['input_name'];?></label>
                                    <?php }?>
                                    <?php
                                    break;
                                    case 5:
                                    ?>
                                    <?php
                                    $input_p = M('input')->where(array('input_pid'=>$v['input_id']))->select();
                                    foreach($input_p as $k2=>$v2){
                                    ?>
                                        <input type="radio" name="data[<?php echo $v['field_name'];?>]" value="<?php echo $v2['input_value']?>" <?php if($list[$v['field_name']]==$v2['input_value'])echo ' checked="checked"';?>/>
                                        <label class="radio_label" for="radio_<?php echo $v2['input_id'];?>"><?php echo $v2['input_name'];?></label>
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
                                        <input class="link_up_size <?php if(!$v['note']){ echo 'state';}?>" data-cid="<?php echo $v['input_id'] ?>" name="<?php echo $v['field_name'];?>" id="<?php echo $v['field_name'];?>" type="file" />
                                    </span>

                                    <?php
                                    if($list[$v['field_name']]!='')
                                    {
                                        echo '<a href="'.$list[$v['field_name']].'" target="_blank"><img src="'.$list[$v['field_name']].'" width="50" height="25"/></a>';
                                    ?>
                                    <a href="javascript:;" onclick="confirm_img('确定删除吗!','admin.php?m=content&a=delete_img&content_id=<?php echo $content_id;?>&table_name=<?php echo $table_name;?>&type_id=<?php echo $type_id;?>&field_name=<?php echo $v['field_name'];?>',$(this))" class="delete">删除</a>
                                    <?php echo $v['note'];?>
                                    <?php
                                    }
                                    break;
                                    case 8:
                                    ?>
                                    <div class="laydate-box">
                                        <input name="data[<?php echo $v['field_name'];?>]" type="text" class="laydate-input" id="<?php echo $v['field_name'];?>" value="<?php echo cover_time($list[$v['field_name']],'Y-m-d H:i:s')?>" size="<?php echo $v['edit_size'];?>" />
                                        <i class="bi bi-calendar3" aria-hidden="true"></i>
                                    </div>
                                    <?php
                                    break;
                                    case 9:
                                    $field_name=$v['field_name'];
                                    ?>
                                        <div style="display:flex;">
                                            <div style="flex: 1;">
                                                <div style="position: relative;">
                                                    <input type="file" class="file" style="position:absolute;opacity: 0;top: 0;left: 0;bottom: 0;">
                                                    <span style="display: block;width: 100%;height: 30px;background: #5a98de;border-radius: 5px;line-height: 30px;text-align: center;color: #fff;padding:0 20px;">未选择文件</span>
                                                </div>
                                                <div style="width: 100%;height: 15px;border-radius: 10px;background: #ccc;margin-top: 5px;display: none">
                                                    <span class="progress" style="width:0;font-size: 10px;display: inline-block;background: #4ce1e1;color:#fff;border-radius: 50px;text-align: center;">0</span>
                                                </div>
                                            </div>
                                            <div style="margin: 0 10px;">
                                                <button type="button" class="upload" style="background: #45c6ed;border: none;color: #fff;padding: 5px 16px;border-radius: 5px;">上传</button>
                                                <input name="data[<?php echo $v['field_name'];?>]" type="hidden" id="<?php echo $v['field_name'];?>" size="<?php echo $v['edit_size'];?>" />
                                            </div>
                                            <div class="big_del_box">
                                                <?php if($list[$v['field_name']]!='') {?>
                                                    <a href="<?php echo $list[$v['field_name']];?>"><img src="<?php echo $list[$v['field_name']];?>" width="50" height="25"/></a>
                                                    <a href="javascript:;" onclick="confirm_img('确定删除吗!','admin.php?m=content&a=delete_img&content_id=<?php echo $content_id;?>&table_name=<?php echo $table_name;?>&type_id=<?php echo $type_id;?>&field_name=<?php echo $v['field_name'];?>',$(this))" class="delete">删除</a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php
                                    break;
                                    case 10:
                                        ?>
                                        <div class="example circle">
                                            <div class="clr-field" style="color: <?php echo $list[$v['field_name']]?:'#000';?>;"><button aria-labelledby="clr-open-label"></button><input name="data[<?php echo $v['field_name'];?>]" class="coloris" type="text" id="<?php echo $v['field_name'];?>" value="<?php echo $list[$v['field_name']];?>"  size="<?php echo $v['edit_size'];?>" autocomplete="off"/></div>
                                        </div>
                                    <?php /*
                                    case 10:
                                    $sarr=explode('-',$list[$v['field_name']]);
                                    ?>
                                    <input type="hidden" name="data[<?php echo $v['field_name'];?>]" value="<?php echo $list[$v['field_name']];?>" />
                                    <select name="pro" class="pro" data-type="1" data-id="<?php echo $sarr[1];?>" data-aid="<?php echo $sarr[2];?>">
                                        <option value="">省选择</option>
                                        <?php
                                        $pro=M('region')->where('region_pid=1')->select();
                                        foreach ($pro as $ks => $vs) {
                                            ?>
                                                <option value="<?php echo $vs['region_id'];?>" <?php if($sarr[0]==$vs['region_id']){echo 'selected';}?>><?php echo $vs['region_name'];?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <select name="city" class="city" data-type="2">
                                            <option value="">城市选择</option>
                                        </select>
                                        <select name="area" class="area" data-type="3">
                                            <option value="">地区选择</option>
                                            <?php
                                            $area=M('region')->where('region_pid='.$sarr[1])->select();
                                            foreach ($area as $ks => $vs) {
                                                ?>
                                                <option value="<?php echo $vs['region_id'];?>" <?php if($sarr[2]==$vs['region_id']){echo 'selected';}?>><?php echo $vs['region_name'];?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    <?php */
                                }
                            }
                            ?>
                        </div>
                        <?php $user=session('user'); if($user['user_id']==1){?>
                            <div class="operate operate2">
                                <a href="javascript:;" class="copycode" onClick="classify_children(<?php echo $type_id;?>,<?php echo $v['input_type_id'];?>,'<?php echo $v['field_name'];?>')">字段</a>
                            </div>
                        <?php }?>
                    </div>
                    <?php }?>
                </div>
                <div class="tabconent">
                    <div class="flex align-items tabconent_row">
                        <div class="tabconent_row_title">浏览器标题</div>
                        <div class="tabconent_row_input">
                            <input name="data[title]" id="title" size="80" value="<?php echo $list['title'];?>" type="text">
                        </div>
                    </div>
                    <div class="flex align-items tabconent_row">
                        <div class="tabconent_row_title">浏览器关键词</div>
                        <div class="tabconent_row_input">
                            <input name="data[keywords]" id="keywords" size="80" value="<?php echo $list['keywords'];?>" type="text">
                        </div>
                    </div>
                    <div class="flex align-items tabconent_row">
                        <div class="tabconent_row_title">浏览器描述</div>
                        <div class="tabconent_row_input">
                            <input name="data[description]" id="description" size="80" value="<?php echo $list['description'];?>" type="text">
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">jQuery(".tabouter").slide({titCell:".tabnav ul li",mainCell:".tabcon",trigger:"click",delayTime:0});</script>
        </div>
        <input type="submit" class="submit" value="确认提交">
    </form>
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
    $('.tips .bi').on({
        mouseover: function () {
            tips = layer.tips($(this).parent().attr('data-tips'), $(this).parent(), {time:0});
        },
        mouseout: function () {
            layer.close(tips);
        }
    });
    $(function(){
	//城市三级联动
        $('.pro,.city').change(function(){
            var id = $(this).attr('data-id');
            var aid = $(this).attr('data-aid');
            var type = $(this).attr('data-type');
            $.ajax({
                url:"?m=content&a=getInfo",
                data:{
                    type:$(this).attr('data-type'),
                    pid:$(this).val()
                },
                dataType:'json',
                success:function(msg){
                    if(type==1){
                        var str="<option value=''>城市选择</option>";
                        for(i in msg){
                            if(id==msg[i].region_id){
                                str+="<option value='"+msg[i].region_id+"' selected >"+msg[i].region_name+"</option>";
                            }else{
                                str+="<option value='"+msg[i].region_id+"'>"+msg[i].region_name+"</option>";
                            }
                        }
                        $('.city').html(str);
                        if(!aid) {
                            $('.area').html('<option value="">地区选择</option>');
                        }
                        $('.pro').removeAttr('data-aid');
                    }else if(type==2){
                        var str="<option value=''>地区选择</option>";
                        for(i in msg){
                            str+="<option value='"+msg[i].region_id+"'>"+msg[i].region_name+"</option>";
                        }
                        $('.area').html(str);
                    }
                }
            })
        });
        $(".area").change(function(){
            var val=$(this).val();
            if(val){
                var html=$(this).children('option:selected').val();
                $(this).prev('select').prev('select').prev('input').val($(this).prev('select').prev('select').children('option:selected').val()+'-'+$(this).prev('select').children('option:selected').val()+'-'+html);
            }
        });
	    $(".pro").change();
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
        if($(this).hasClass('state')) {
            layer.confirm('设为默认图片尺寸?', {
                btn: ['确定', '取消'] //按钮
            }, function (index) {
                if (input.files) {
                    //读取图片数据
                    var f = input.files[0];
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var data = e.target.result;
                        //加载图片获取图片真实宽度和高度
                        var image = new Image();
                        image.onload = function () {
                            var width = image.width;
                            var height = image.height;
                            rs = width + 'px*' + height + "px";
                            $.ajax({
                                url: "admin.php?m=content&a=link_size&input_id=" + i + "&value=" + rs,
                                type: "GET",
                                success: function (data) {
                                    // alert('设置成功,如需修改,请前往表单设置');
                                }
                            });

                        };
                        image.src = data;
                    };
                    reader.readAsDataURL(f);
                } else {
                    var image = new Image();
                    image.onload = function () {
                        var width = image.width;
                        var height = image.height;
                        var fileSize = image.fileSize;
                        rs = width + 'px*' + height + "px";

                        $.ajax({
                            url: "admin.php?m=content&a=link_size&input_id=" + i + "&value=" + rs,
                            type: "GET",
                            success: function (data) {
                                // alert('设置成功,如需修改,请前往表单设置');
                            }
                        });
                    }
                    image.src = input.value;
                }
                layer.close(index);
            }, function () {
            })
        }
    });

    //上传功能
    $(".upload").click(function(){
        //文件对象
        var _this = $(this);
        var file = $(this).parents('.tabconent_row_input').find('input[type="file"]')[0].files[0];
        var qp = {};
        if (file == undefined) {
            layer.msg('请先选择文件');
            return;
        }
        var file_name = file.name;                      //文件名
        var file_size = file.size;                      //文件总大小
        var succeed = 0;                                //请求成功次数
        var shardSize = 1024*1024*10//10485760;                       //10M分片大小单位字节 记得去php配置调整文件大小
        var shardCount = Math.ceil(file_size/shardSize);//总片数
        var rate = 1/shardCount*100;                    //进度条
        for (var i = 0; i < shardCount; i++) {
            var start = i * shardSize;                  // 计算每一片数据的起始与结束位置
            var end = Math.min(file_size, start + shardSize);
            var form = new FormData();
            var loading = '';
            form.append("data", file.slice(start, end));    //切片数据
            form.append("file_name", file_name);
            form.append("total", shardCount);               // 总片数
            form.append("index", i + 1);                    // 当前是第几片
            // Ajax提交
            $.ajax({
                url: "?m=Index&a=uplife&act=upload",
                type: "POST",
                data: form,
                async: true,
                processData: false,
                dataType : "json",
                contentType: false,
                beforeSend: function(){
                    loading = layer.msg('文件上传中请稍等', {
                        icon: 16
                        ,shade: 0.11
                        ,time:888888
                    });
                },
                success: function(data){
                    if(data.errno == 10000) {
                        succeed ++;
                        //element.progress('demo', (succeed*rate).toFixed(2)+'%');
                        //_this.parents('.tabconent_row_input').find('.progress').css('width',(succeed*rate).toFixed(2)+'%');
                        //_this.parents('.tabconent_row_input').find('.progress').html((succeed*rate).toFixed(2)+'%');
                        if (succeed == shardCount) {
                            qp.total = shardCount;
                            qp.file_name = file_name;
                            layer.close(loading);
                            $.ajax({
                                url: "?m=Index&a=uplife&act=join",
                                type: "POST",
                                data: qp,
                                dataType:'json',
                                beforeSend: function(){
                                    loading = layer.msg('等待文件合并', {
                                        icon: 16
                                        ,shade: 0.11
                                        ,time:888888
                                    });
                                },
                                success: function(msg){
                                    layer.close(loading);
                                    if(msg.errno==10000) {
                                        _this.next('input[type="hidden"]').val(msg.src);
                                        $('.big_del_box').append();
                                    } else {
                                        layer.open({content: '上传失败', time: 2});
                                    }
                                }
                            });
                        }
                    } else {
                        layer.open({content: '上传失败', time: 2});
                    }

                }
            });
        }
    });
    //获取文件名
    $('.file').change(function(){
        var file = $(this)[0].files[0];
        $(this).next('span').html(file['name']);
    });
</script>

<script type="text/javascript">
var str,table_name_arr= new Array(),value_arr= new Array(),code_str="",price_str='',inventory_str='',coding_str='',volume_str='',weight_str='',checkbox_val_str,checkbox_val_arr= new Array()//定义一数组
function recursive_specifications(name_arr,number,length)
{

		code_str=code_str+"$(\"input[name='specifications["+name_arr[number]+"][]']\").each(function(){checkbox_val_arr["+number+"]=$(this).val();value_arr["+number+"]=$(this).val();";
		if(number<length)
		{
			number++;
			recursive_specifications(name_arr,number,length);
		}
		else
		{
			code_str=code_str+"checkbox_val_str='';str=str+'<tr>';";
			code_str=code_str+"for(var i=0;i<"+(length+1)+";i++){checkbox_val_str=checkbox_val_str+checkbox_val_arr[i];str=str+'<td class=th_menu>'+value_arr[i]+'<input type=\"hidden\" name=\"attributes['+table_name_arr[i]+'][]\" value=\"'+checkbox_val_arr[i]+'\" size=\"10\" ></td>';}if($('#price_'+checkbox_val_str).length >0){price_str=$('#price_'+checkbox_val_str).val();}else{price_str='';}if($('#inventory_'+checkbox_val_str).length >0){inventory_str=$('#inventory_'+checkbox_val_str).val();}else{inventory_str='';}if($('#coding_'+checkbox_val_str).length >0){coding_str=$('#coding_'+checkbox_val_str).val();}else{coding_str='';}if($('#volume_'+checkbox_val_str).length >0){volume_str=$('#volume_'+checkbox_val_str).val();}else{volume_str='';}if($('#weight_'+checkbox_val_str).length >0){weight_str=$('#weight_'+checkbox_val_str).val();}else{weight_str='';}";

			code_str=code_str+"str=str+'<td><input type=\"text\" name=\"attributes[price][]\" value=\"'+price_str+'\" size=\"10\" ></td><td><input type=\"text\" name=\"attributes[inventory][]\" value=\"'+inventory_str+'\" size=\"10\" ></td><td><input type=\"text\" name=\"attributes[coding][]\" value=\"'+coding_str+'\" size=\"10\" ></td><td><input type=\"text\" name=\"attributes[volume][]\" value=\"'+volume_str+'\" size=\"10\" ></td><td><input type=\"text\" name=\"attributes[weight][]\" value=\"'+weight_str+'\" size=\"10\" ></td></tr>';";

		}
		code_str=code_str+"});";
}

function product_specifications(id)
{
	str='<table border="0" cellspacing="0" width="100%"><thead><tr>';
	code_str='';
	$("input[name='attribute_name[]']").each(function()
	{
		str=str+'<th class="th_menu">'+$(this).val()+'</th>';
	});

	$("input[name='field_name[]']").each(function(i, n)
	{
		table_name_arr[i]=$(this).val();
		$("#specifications"+$(this).val()).html('');
		$.each($("input[priceattr='specifications["+$(this).val()+"]']").val().split("|"), function(si, sn){
			$("#specifications"+table_name_arr[i]).append('<input type="hidden" name="specifications['+table_name_arr[i]+'][]" value="'+sn+'" />');
		});
	});
	

	str=str+'<th class="th_price">价格</th><th class="th_inventory">数量</th><th class="th_coding">商家编码</th><th class="th_coding">体积m³</th><th class="th_coding">重量kg</th></tr></thead><tbody>';
	recursive_specifications(table_name_arr,0,(table_name_arr.length-1));
	eval(code_str);

	str=str+'</tbody></table>';


	$(".product_table").html(str);
	var n=0;
	//alert(str);
}
product_specifications(4);

	var swfu;
		var settings = {
			flash_url : "<?php echo APP_ROOT.'js/swfupload.swf';?>",
			upload_url: "admin.php?m=content&a=batch_upload_save",
			post_params: {"PHPSESSID" : "<?php echo session_id(); ?>"},
			file_size_limit : "2000 MB",
			file_types : "*.*",
			file_types_description : "All Files",
			file_upload_limit : 0,  //配置上传个数
			file_queue_limit : 0,
			custom_settings : {
				progressTarget : "fsUploadProgress",
				cancelButtonId : "btnCancel"
			},
			debug: false,
			// Button settings
			button_image_url: "",
			button_width: "100",
			button_height: "30",
			button_placeholder_id: "spanButtonPlaceHolder",
			button_text: '<span class="theFont" style="color:#f00;">点击上传</span>',
			button_text_style: ".theFont { font-size: 16; }",
			button_text_left_padding: 12,
			button_text_top_padding: 3,

			file_queued_handler : fileQueued,
			file_queue_error_handler : fileQueueError,
			file_dialog_complete_handler : fileDialogComplete,
			upload_start_handler : uploadStart,
			upload_progress_handler : uploadProgress,
			upload_error_handler : uploadError,
			upload_success_handler : uploadSuccess,
			upload_complete_handler : uploadComplete,
			queue_complete_handler : queueComplete	
		};

		swfu = new SWFUpload(settings);
	 
	 function uploadSuccess(file, serverData){
		 $("#<?php echo $field_name;?>").val(serverData);
		 $("#divStatus").show();
	}


</script>
</div>
<?php require APP_ROOT.'public/foot.php';?>
