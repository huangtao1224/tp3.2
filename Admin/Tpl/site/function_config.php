<?php require APP_ROOT.'public/top.php';?>
<script type="text/javascript">
    $('#form').ajaxForm({ success:function showResponse(responseText)  {
        layer.msg(responseText,{time:1500},function(){
            parent.location.reload();
        });
    }});
    $('#form_menu1').ajaxForm({ success:function showResponse(responseText)  {
        layer.msg(responseText,{time:1500},function(){
            parent.location.reload();
        });
    }});
    $('#form_menu2').ajaxForm({ success:function showResponse(responseText)  {
        layer.msg(responseText,{time:1500},function(){
            parent.location.reload();
        });
    }});
</script>
<div class="content">
    <?php require APP_ROOT.'public/left.php';?>
    <div id="right">
        <div class="right_data">
            <div class="white config_box">
                <?php $list = M('site')->where(array('version_id'=>session('version_id')))->find();
                $type_id = 57;
                $table_name = M('classify_type')->where(array('type_id' => $type_id))->getField('table_name');
                ?>
                <h1 class="config_title">常用代码</h1>
                <div class="flex align-items config_box1">
                    <div>查询当前分类所以子类内容</div>
                    <div>&lt;?php $list = M()->table('index_goods c left join index_relevance r on r.content_id = c.goods_id')->where(array('r.classify_id'=>array('in',get_child_classify(2))))->order('c.date desc')->select();?&gt;</div>
                </div>
            </div>
            <form action="admin.php?m=cms&a=edit_save" method="post" enctype="multipart/form-data" id="form" class="white config_box">
                <input name="type_id" type="hidden" value="57" />
                <input name="content_id" type="hidden" value="1" />
                <h1 class="config_title">扩展功能开关</h1>
                <?php
                $list = M('function_switch')->where(array('function_switch_id' => 1))->find();
                $input = M('input')->where(array('type_id'=>57,'edit_switch'=>2,'input_pid'=>array('exp','is null')))->order('date asc')->select();
                foreach($input as $k=>$v){
                ?>
                <div class="flex align-items config_box2">
                    <div><?php echo $v['input_name']?></div>
                    <div>
                        <?php
                        switch($v['input_type_id']){
                            case 1:
                                if($v['field_name']=='password' && $type_id==46)
                                {
                                    $list[$v['field_name']]='';
                                }
                                ?>
                                <input name="data[<?php echo $v['field_name'];?>]" type="text" id="<?php echo $v['field_name'];?>" value="<?php echo $list[$v['field_name']];?>" size="<?php echo $v['edit_size'];?>" />
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
                                    <input type="checkbox" name="data[<?php echo $v['field_name'];?>][]"<?php if(in_array($v2['input_value'],$valarr))echo ' checked="checked"';?> value="<?php echo $v2['input_value']?>" id="checkbox_<?php echo $v['input_id'];?>"/>
                                    <?php echo $v2['input_name'];?>
                                    <label class="checkbox" for="checkbox_<?php echo $v['input_id'];?>"></label>
                                <?php } ?>
                                <?php
                                break;
                            case 5:
                                ?>
                                <?php
                                $input_p = M('input')->where(array('input_pid'=>$v['input_id']))->select();
                                foreach($input_p as $k2=>$v2){
                                    ?>
                                    <input type="radio" id="<?php echo $v['field_name'].$v2['input_value'];?>" name="data[<?php echo $v['field_name'];?>]" value="<?php echo $v2['input_value']?>" <?php if($list[$v['field_name']]==$v2['input_value'])echo ' checked="checked"';?>/>
                                    <label for="<?php echo $v['field_name'].$v2['input_value'];?>" class="radio_label"><?php echo $v2['input_name'];?></label>
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
                                        <option value="<?php echo $v2['input_value'];?>"<?php if($list[$v['field_name']]==$v2['input_value'])echo ' selected="selected"';?>><?php echo $v2['input_name'];?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                break;
                            case 7:
                                ?>
                                <span class="file-box">
                                    <span>请选择文件</span>
                                    <input name="<?php echo $v['field_name'];?>" id="<?php echo $v['field_name'];?>" type="file" />
                                </span>
                                <?php
                                if($list[$v['field_name']]!='')
                                {
                                    echo '<a href="'.$list[$v['field_name']].'" target="_blank"><img src="'.$list[$v['field_name']].'" width="50" height="25"/></a>';
                                    ?>
                                    <a href="javascript:;" onclick="if(confirm('确定删除吗!')){delete_img('admin.php?m=content&a=delete_img&content_id=<?php echo $content_id;?>&table_name=<?php echo $table_name;?>&type_id=<?php echo $type_id;?>&field_name=<?php echo $v['field_name'];?>',$(this))}" class="delete">删除</a>
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
                        }
                        ?>
                        <?php echo $v['note'];?>
                    </div>
                </div>
                <?php }?>
                 <input name="" type="submit" class="submit" value="确认提交" />
            </form>
            <form action="admin.php?m=cms&a=edit_save" method="post" enctype="multipart/form-data" id="form_menu1" class="white config_box">
                <input name="type_id" type="hidden" value="54" />
                <input name="content_id" type="hidden" value="1" />
                <h1 class="config_title">微信支付配置</h1>
                <?php
                $list = M('wxpay')->where(array('wxpay_id' => 1))->find();
                $input = M('input')->where(array('type_id'=>54,'edit_switch'=>2,'input_pid'=>array('exp','is null')))->order('date asc')->select();
                foreach($input as $k=>$v){
                ?>
                <div class="flex align-items config_box2">
                    <div><?php echo $v['input_name']?></div>
                    <div>
                        <?php
                        switch($v['input_type_id']){
                            case 1:
                                if($v['field_name']=='password' && $type_id==46)
                                {
                                    $list[$v['field_name']]='';
                                }
                                ?>
                                <input name="data[<?php echo $v['field_name'];?>]" type="text" id="<?php echo $v['field_name'];?>" value="<?php echo $list[$v['field_name']];?>" size="<?php echo $v['edit_size'];?>" />
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
                                foreach($input_p as $k2=>$v2){
                                    ?>
                                    <label>
                                        <input type="radio" name="data[<?php echo $v['field_name'];?>]" value="<?php echo $v2['input_value']?>" <?php if($list[$v['field_name']]==$v2['input_value'])echo ' checked="checked"';?>/>
                                        <?php echo $v2['input_name'];?>
                                    </label>
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
                                        <option value="<?php echo $v2['input_value'];?>"<?php if($list[$v['field_name']]==$v2['input_value'])echo ' selected="selected"';?>><?php echo $v2['input_name'];?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                break;
                            case 7:
                                ?>
                                <span class="file-box">
                                    <span>请选择文件</span>
                                    <input name="<?php echo $v['field_name'];?>" id="<?php echo $v['field_name'];?>" type="file" />
                                </span>
                                <?php
                                if($list[$v['field_name']]!='')
                                {
                                    echo '<a href="'.$list[$v['field_name']].'" target="_blank"><img src="'.$list[$v['field_name']].'" width="50" height="25"/></a>';
                                    ?>
                                    <a href="javascript:;" onclick="if(confirm('确定删除吗!')){delete_img('admin.php?m=content&a=delete_img&content_id=<?php echo $content_id;?>&table_name=<?php echo $table_name;?>&type_id=<?php echo $type_id;?>&field_name=<?php echo $v['field_name'];?>',$(this))}" class="delete">删除</a>
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
                        }
                        ?>
                        <?php echo $v['note'];?>
                    </div>
                </div>
                <?php }?>
                <input name="" type="submit" class="submit" value="确认提交" />
            </form>
            <form action="admin.php?m=cms&a=edit_save" method="post" enctype="multipart/form-data" id="form_menu2" class="white config_box">
                <input name="type_id" type="hidden" value="56" />
                <input name="content_id" type="hidden" value="1" />
                <h1 class="config_title">支付宝支付配置</h1>
                <?php
                $list = M('alipay')->where(array('alipay_id' => 1))->find();
                $input = M('input')->where(array('type_id'=>56,'edit_switch'=>2,'input_pid'=>array('exp','is null')))->order('date asc')->select();
                foreach($input as $k=>$v){
                ?>
                <div class="flex align-items config_box2">
                    <div><?php echo $v['input_name']?></div>
                    <div>
                        <?php
                        switch($v['input_type_id']){
                            case 1:
                                if($v['field_name']=='password' && $type_id==46)
                                {
                                    $list[$v['field_name']]='';
                                }
                                ?>
                                <input name="data[<?php echo $v['field_name'];?>]" type="text" id="<?php echo $v['field_name'];?>" value="<?php echo $list[$v['field_name']];?>" size="<?php echo $v['edit_size'];?>" />
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
                                foreach($input_p as $k2=>$v2){
                                    ?>
                                    <label>
                                        <input type="radio" name="data[<?php echo $v['field_name'];?>]" value="<?php echo $v2['input_value']?>" <?php if($list[$v['field_name']]==$v2['input_value'])echo ' checked="checked"';?>/>
                                        <?php echo $v2['input_name'];?>
                                    </label>
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
                                        <option value="<?php echo $v2['input_value'];?>"<?php if($list[$v['field_name']]==$v2['input_value'])echo ' selected="selected"';?>><?php echo $v2['input_name'];?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                break;
                            case 7:
                                ?>
                                <span class="file-box">
                                    <span>请选择文件</span>
                                    <input name="<?php echo $v['field_name'];?>" id="<?php echo $v['field_name'];?>" type="file" />
                                </span>
                                <?php
                                if($list[$v['field_name']]!='')
                                {
                                    echo '<a href="'.$list[$v['field_name']].'" target="_blank"><img src="'.$list[$v['field_name']].'" width="50" height="25"/></a>';
                                    ?>
                                    <a href="javascript:;" onclick="if(confirm('确定删除吗!')){delete_img('admin.php?m=content&a=delete_img&content_id=<?php echo $content_id;?>&table_name=<?php echo $table_name;?>&type_id=<?php echo $type_id;?>&field_name=<?php echo $v['field_name'];?>',$(this))}" class="delete">删除</a>
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
                        }
                        ?>
                        <?php echo $v['note'];?>
                    </div>
                </div>
                <?php }?>
                <input name="" type="submit" class="submit" value="确认提交" />
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
    $('input[type="file"]').change(function() {
        var n = $(this).attr('id');
        var input = document.getElementById(n);
        var len = input.files.length;
        for (var i = 0; i < len; i++) {
            var temp = input.files[i].name;
            $('#' + n).siblings('span').text(temp);
        }
    })
</script>
<?php require APP_ROOT.'public/bottom.php';?>
