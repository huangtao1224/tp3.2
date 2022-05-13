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
</style>
<div class="ajaxcontent">
    <?php $type_id=pg('type_id'); ?>
    <form action="admin.php?m=cms&a=add_save" method="post" enctype="multipart/form-data" id="form">
        <input name="data[date]" type="hidden" id="date" value="<?php echo time();?>" />
        <input name="data[version_id]" type="hidden" id="version_id" value="1" />
        <input name="data[type_id]" type="hidden" id="type_id" value="<?php echo $type_id;?>" />
        <?php
        $list = M('input')->where(array('type_id'=>$type_id,'edit_switch'=>2,'input_pid'=>array('exp','is null')))->order('date asc')->select();
        foreach($list as $k=>$v){
        ?>
        <div class="flex align-items tabconent_row">
            <div class="tabconent_row_title flex align-items">
                <?php echo $v['input_name']?>
                <?php if($v['note']){?>
                    <div class="tips flex align-items" data-tips="<?php echo $v['note'];?>"><i class="bi bi-question-circle-fill" ></i></div>
                <?php }?>
            </div>
            <div class="tabconent_row_input">
                <?php
                switch($v['input_type_id']){
                    case 1:
                        ?>
                        <input name="data[<?php echo $v['field_name'];?>]" type="text" id="<?php echo $v['field_name'];?>" size="80" />
                        <?php
                        break;
                    case 2:
                        ?>
                        <textarea name="data[<?php echo $v['field_name'];?>]" cols="60" rows="<?php echo $v['edit_size'];?>" id="<?php echo $v['field_name'];?>"></textarea>
                        <?php
                        break;
                    case 3:
                        echo fckeditor('data['.$v['field_name'].']',$v['edit_size'],'');
                        break;
                    case 4:
                        ?>
                        <?php
                        $input_p = M('input')->where(array('input_pid'=>$v['input_id']))->select();
                        foreach($input_p as $k2=>$v2){
                            ?>
                            <input type="checkbox" name="data[<?php echo $v['field_name'];?>][]" value="<?php echo $v2['input_value']?>" id="checkbox_<?php echo $v2['input_id'];?>"/>
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
                            <input type="radio" name="data[<?php echo $v['field_name'];?>]" value="<?php echo $v2['input_value']?>" id="radio_<?php echo $v2['input_id'];?>"/>
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
                                <option value="<?php echo $v2['input_value'];?>"><?php echo $v2['input_name'];?></option>
                            <?php } ?>
                        </select>
                        <?php
                        break;
                    case 7:
                        ?>
                        <span class="file-box">
                        <span>请选择文件</span>
                        <input name="<?php echo $v['field_name'];?>" id="<?php echo $v['field_name'];?>" type="file" class="link_up_size" style="cursor: pointer"/>
                    </span>
                        <?php
                        break;
                    case 8:
                        ?>
                        <div class="laydate-box">
                            <input name="data[<?php echo $v['field_name'];?>]" class="laydate-input" type="text" id="<?php echo $v['field_name'];?>" size="<?php echo $v['edit_size'];?>" />
                            <i class="bi bi-calendar3" aria-hidden="true"></i>
                        </div>
                        <?php
                        break;
                    case 9:
                        $field_name=$v['field_name'];
                        ?>
                        <div class="fieldset flash" id="fsUploadProgress" align="left"></div>
                        <div id="divStatus">0 个文件已上传</div>
                        <div>
                            <span id="spanButtonPlaceHolder"></span><br />
                            <input id="btnCancel" type="button" value="断开上传" onClick="swfu.cancelQueue();" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
                        </div>
                        <input name="data[<?php echo $v['field_name'];?>]" type="hidden" id="<?php echo $v['field_name'];?>" size="<?php echo $v['edit_size'];?>" />
                        <?php
                        break;
                    case 10:
                        ?>
                        <div class="example circle">
                            <div class="clr-field"><button aria-labelledby="clr-open-label"></button><input name="data[<?php echo $v['field_name'];?>]" class="coloris" type="text" id="<?php echo $v['field_name'];?>" value=""  size="<?php echo $v['edit_size'];?>" autocomplete="off"/></div>
                        </div>
                        <?php /*
                    case 10:
                        ?>
                        <input type="hidden" name="data[<?php echo $v['field_name'];?>]" />
                        <select name="pro" class="pro" data-type='1'>
                            <option value="">省选择</option>
                            <?php
                            $pro=M('region')->where('region_pid=1')->select();
                            foreach ($pro as $ks => $vs) {?>
                                <option value="<?php echo $vs['region_id'];?>"><?php echo $vs['region_name'];?></option>
                            <?php } ?>
                        </select>
                        <select name="city" class="city" data-type='2'>
                            <option value="">城市选择</option>
                        </select>
                        <select name="area" class="area" data-type='3'>
                            <option value="">地区选择</option>
                        </select>

                <?php */}
                ?>
                <?php echo $v['note'];?>
            </div>
        </div>
        <?php }?>
        <input type="submit" class="submit" value="确认提交">
    </form>
</div>
<script type="text/javascript">
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
    //城市三级联动
    $('.pro,.city').change(function(){
        var type = $(this).attr('data-type');
        $.ajax({
            url:"?m=content&a=getInfo",
            data:{
                type:$(this).attr('data-type'),
                pid:$(this).val()
            },
            dataType:'json',
            success:function(msg){
                console.log(msg);
                if(type==1){
                    var str="<option value=''>城市选择</option>";
                    for(i in msg){
                        str+="<option value='"+msg[i].region_id+"'>"+msg[i].region_name+"</option>";
                    }
                    console.log(str);
                    $('.city').html(str);
                    $('.area').html('<option value="">地区选择</option>');
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
    $('.link_up_size').change(function(){
        var n=$(this).attr('id');
        var input = document.getElementById(n);
        var len = input.files.length;
        for (var i = 0; i < len; i++) {
            var temp = input.files[i].name;
            $('#'+n).siblings('span').text(temp);
            console.log(temp);
        }
    })
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
<?php require APP_ROOT.'public/foot.php';?>
