<?php require APP_ROOT.'public/head.php';?>
<div class="ajaxcontent">
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
    </style>
    <form action="admin.php?m=classify&a=edit_save" method="post" enctype="multipart/form-data" id="form" onsubmit="return return_classify()">
        <?php
        global $lists,$classify_pids,$classify_id;
        $classify_id=pg('classify_id');
        $classify = M('classify')->where(array('classify_id'=>$classify_id))->find();
        $lists = M('classify')->field('classify_id,level_id,classify_pid,type_id,classify_name')->where(array('version_id'=>session('version_id')))->select();
        $classify_pids=$classify['classify_pid'];
        $classify_del =  M('permission')->where('classify_id='.pg('recursive_classify_id').' and user_id='.$user['user_id'].' and permission_name=4')->find();
        ?>
        <input name="classify_id" type="hidden" value="<?php echo $classify_id;?>" />
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
                            <a href="javascript:;" class="copycode" onClick="classify_code(<?php echo $classify_id;?>,'classify_pid')">循环子类</a>
                        </div>
                    </div>
                    <?php }?>
                    <div class="flex align-items tabconent_row">
                        <div class="tabconent_row_title">分类名称</div>
                        <div class="tabconent_row_input">
                            <input name="data[classify_name]" id="classify_name" type="text" size="80" value="<?php echo $classify['classify_name']?>">
                        </div>
                        <?php $user=session('user'); if($user['user_id']==1){?>
                        <div class="operate">
                            <a href="javascript:;" class="copycode" onClick="classify_code(<?php echo $classify_id;?>,'classify_name')">代码</a>
                            <a href="javascript:;" class="copycode" onClick="classify_children(4,1,'classify_name')">字段</a>
                        </div>
                        <?php }?>
                    </div>
                    <?php $user=session('user'); if($user['user_id']==1){?>
                    <div class="flex align-items tabconent_row">
                        <div class="tabconent_row_title">父级</div>
                        <div class="tabconent_row_input">
                            <select name="data[classify_pid]" id="classify_pid">
                                <option value="<?php echo session('version_classify_id');?>">根目录</option>
                                <?php
                                function recursive_classify($classify_pid='1')
                                {
                                    global $lists,$classify_pids,$classify_id;
                                    foreach($lists as $key=>$val)
                                    {
                                        if($val['classify_pid']==$classify_pid)
                                        {
                                            ?>
                                            <option value="<?php echo $val['classify_id']?>"<?php if($classify_id==$val['classify_id']){?> disabled="disabled"<?php }?><?php if($classify_pids==$val['classify_id']){?> selected="selected"<?php }?>>┣<?php for($i=3;$i<=$val['level_id'];$i++)echo "━";?><?php echo $val['classify_name']?></option>
                                            <?php
                                            recursive_classify($val['classify_id']);
                                        }
                                    }
                                }
                                recursive_classify(session('version_classify_id'));
                                ?>
                            </select>
                        </div>
                        <?php $user=session('user'); if($user['user_id']==1){?>
                        <div class="operate"></div>
                        <?php }?>
                    </div>
                    <?php }else{?>
                    <div class="flex align-items tabconent_row">
                        <div class="tabconent_row_title">父级</div>
                        <div class="tabconent_row_input">
                            <?php echo M('classify')->where(array('classify_id'=>$classify['classify_pid']))->getField('classify_name');?>
                        </div>
                    </div>
                    <?php }?>
                    <?php $count= M('relevance')->where(array('classify_id'=>$classify_id))->count();if($count<1){?>
                    <div class="flex align-items tabconent_row">
                        <div class="tabconent_row_title">类型</div>
                        <div class="tabconent_row_input">
                            <select name="data[type_id]" id="type_id">
                                <?php $list = M('classify_type')->where(array('show_id'=>2))->order('date asc')->select();foreach($list as $k=>$val){?>
                                <option value="<?php echo $val['type_id']?>" <?php if($classify['type_id']==$val['type_id']){?> selected="selected"<?php }?>><?php echo $val['type_name']?></option>
                                <?php }?>
                            </select>
                        </div>
                        <?php $user=session('user'); if($user['user_id']==1){?>
                        <div class="operate"></div>
                        <?php }?>
                    </div>
                    <?php }?>
                    <?php
                    $input = M('input')->where(array('type_id'=>4,'edit_switch'=>2,'input_pid'=>array('exp','is null')))->order('date asc')->select();
                    foreach($input as $k=>$v){
                    ?>
                    <div class="flex align-items tabconent_row">
                        <div class="tabconent_row_title"><?php echo $v['input_name']?></div>
                        <div class="tabconent_row_input">
                            <?php
                            switch($v['input_type_id']){
                                case 1:
                                ?>
                                <input name="data[<?php echo $v['field_name'];?>]" type="text" id="<?php echo $v['field_name'];?>" value="<?php echo $classify[$v['field_name']];?>" size="80" />
                                <?php
                                break;
                                case 2:
                                ?>
                                <textarea name="data[<?php echo $v['field_name'];?>]" cols="60" rows="<?php echo $v['edit_size'];?>" id="<?php echo $v['field_name'];?>"><?php echo $classify[$v['field_name']];?></textarea>
                                <?php
                                break;
                                case 3:
                                echo fckeditor('data['.$v['field_name'].']',$v['edit_size'],$classify[$v['field_name']]);
                                break;
                                case 4:
                                ?>
                                <?php
                                $input_p = M('input')->where(array('input_pid'=>$v['input_id']))->select();
                                $valarr=unserialize($classify[$v['field_name']]);
                                foreach($input_p as $k2=>$v2){
                                    ?>
                                    <input type="checkbox" name="data[<?php echo $v['field_name'];?>][]"<?php if(in_array($v2['input_value'],$valarr))echo ' checked="checked"';?> value="<?php echo $v2['input_value']?>" id="checkbox_<?php echo $v2['input_id'];?>"/>
                                    <label for="checkbox_<?php echo $v2['input_id'];?>" class="checkbox"><?php echo $v2['input_name'];?></label>
                                <?php } ?>
                                <?php
                                break;
                                case 5:
                                ?>
                                <?php
                                $input_p = M('input')->where(array('input_pid'=>$v['input_id']))->select();
                                foreach($input_p as $k2=>$v2){
                                    ?>
                                    <input type="radio" name="data[<?php echo $v['field_name'];?>]" value="<?php echo $v2['input_value']?>" <?php if($classify[$v['field_name']]==$v2['input_value'])echo ' checked="checked"';?> id="radio_<?php echo $v2['input_id'];?>" />
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
                                    foreach($input_p as $k2=>$v2){
                                        ?>
                                        <option value="<?php echo $v2['input_value'];?>"<?php if($classify[$v['field_name']]==$v2['input_value'])echo ' selected="selected"';?>><?php echo $v2['input_name'];?></option>
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
                                if($classify[$v['field_name']]!='')
                                {
                                    echo '<a href="'.$classify[$v['field_name']].'" target="_blank"><img src="'.$classify[$v['field_name']].'" width="50" height="25"/></a>';
                                    ?>
                                    <?php if($classify_del||$user['permissions']!=3){?>
                                    <a href="javascript:;" onclick="confirm_img('确定删除吗!','admin.php?m=content&a=delete_img&content_id=<?php echo $classify_id;?>&table_name=classify&type_id=4&field_name=<?php echo $v['field_name'];?>',$(this))" class="delete">删除</a>
                                    <?php }?>
                                    <?php
                                }
                                break;
                                case 8:
                                ?>
                                <div class="laydate-box">
                                    <input name="data[<?php echo $v['field_name'];?>]" type="text" class="laydate-input" id="<?php echo $v['field_name'];?>"  value="<?php echo cover_time($list[$v['field_name']],'Y-m-d H:i:s')?>" size="<?php echo $v['edit_size'];?>" />
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
                                            <?php if($classify[$v['field_name']]!='') {?>
                                                <a href="<?php echo $classify[$v['field_name']];?>"><img src="<?php echo $classify[$v['field_name']];?>" width="50" height="25"/></a>
                                                <a href="javascript:;" onclick="confirm_img('确定删除吗!','admin.php?m=content&a=delete_img&content_id=<?php echo $classify_id;?>&table_name=classify&type_id=4&field_name=<?php echo $v['field_name'];?>',$(this))" class="delete">删除</a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php
                                break;
                                case 10:
                                ?>
                                    <div class="example circle">
                                        <div class="clr-field" style="color: <?php echo $classify[$v['field_name']]?:'#000';?>;"><button aria-labelledby="clr-open-label"></button><input name="data[<?php echo $v['field_name'];?>]" class="coloris" type="text" id="<?php echo $v['field_name'];?>" value="<?php echo $classify[$v['field_name']];?>"  size="<?php echo $v['edit_size'];?>" autocomplete="off"/></div>
                                    </div>
                            <?php }
                            ?>
                        </div>
                        <?php $user=session('user'); if($user['user_id']==1){?>
                        <div class="operate">
                            <a href="javascript:;" class="copycode" onClick="classify_code(<?php echo $classify_id;?>,'<?php echo $v['field_name'];?>')">代码</a>
                            <a href="javascript:;" class="copycode" onClick="classify_children(4,<?php echo $v['input_type_id'];?>,'<?php echo $v['field_name'];?>')">字段</a>
                        </div>
                        <?php }?>
                    </div>
                    <?php }?>
                </div>
                <?php $user=session('user'); if($user['user_id']==1){?>
                <div class="tabconent">
                    <div class="flex align-items tabconent_row">
                        <div class="tabconent_row_title">注释</div>
                        <div class="tabconent_row_input">
                            <input name="data[note]" id="note" size="80" value="<?php echo $classify['note'];?>" type="text">
                        </div>
                    </div>
                    <div class="flex align-items tabconent_row">
                        <div class="tabconent_row_title">浏览器标题</div>
                        <div class="tabconent_row_input">
                            <input name="data[title]" id="title" size="80" value="<?php echo $classify['title'];?>" type="text">
                        </div>
                    </div>
                    <div class="flex align-items tabconent_row">
                        <div class="tabconent_row_title">浏览器关键词</div>
                        <div class="tabconent_row_input">
                            <input name="data[keywords]" id="keywords" size="80" value="<?php echo $classify['keywords'];?>" type="text">
                        </div>
                    </div>
                    <div class="flex align-items tabconent_row">
                        <div class="tabconent_row_title">浏览器描述</div>
                        <div class="tabconent_row_input">
                            <input name="data[description]" id="description" size="80" value="<?php echo $classify['description'];?>" type="text">
                        </div>
                    </div>
                </div>
                <?php }?>
            </div>
            <script type="text/javascript">jQuery(".tabouter").slide({titCell:".tabnav ul li",mainCell:".tabcon",trigger:"click",delayTime:0});</script>
        </div>
        <input type="submit" class="submit" value="确认提交">
    </form>
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
    var beizhu=$('#beizhu').val();
    if(beizhu.indexOf('分类图片')>-1){
        $('#classify_img').removeClass('state');
    }
    if(beizhu.indexOf('内页插图')>-1){
        $('#page_img').removeClass('state');
    }
    $('.link_up_size').each(function(ii,e){
        var name_arr=new Array('分类图片','内页插图');
        var _index=ii;
        $(this).change(function(){
            var area=$('#beizhu').val();
            var j=$(this).attr('data-cid');
            var rs='';
            var n=$(this).attr('id');
            var input = document.getElementById(n);
            var len = input.files.length;
            for (var i = 0; i < len; i++) {
                var temp = input.files[i].name;
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
                                rs= name_arr[_index]+width+'px*'+height+"px";
                                if(area.indexOf(name_arr[_index])<0){
                                    $('#beizhu').val(area+rs+"\r\n");
                                }
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
                            rs= name_arr[_index]+width+'px*'+height+"px";
                            if(area.indexOf(name_arr[_index])<0){
                                $('#beizhu').val(area+rs+"\r\n");
                            }
                        }
                        image.src = input.value;
                    }
                    layer.close(index);
                },function(){})
            }
        });
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

<?php require APP_ROOT.'public/foot.php';?>
