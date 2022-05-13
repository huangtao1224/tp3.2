<?php require APP_ROOT.'public/top.php';?>
<script type="text/javascript">
    $('#form_menu').ajaxForm({ success:function showResponse(responseText)  {
        layer.msg(responseText,{time:1500},function(){
            parent.location.reload();
        });
    }});
</script>
<div class="content">
    <?php require APP_ROOT.'public/left.php';?>
    <?php
    global $list,$type_id,$main_classify_id;
    $type_id = pg('type_id');
    $export_content_id=pg('export_content_id');
    $classify_id = pg('classify_id');
    $search=pg('search');
    $table_name = M('classify_type')->where(array('type_id' => $type_id))->getField('table_name');
    $classify = M('classify')->where(array('classify_id' => $classify_id))->find();
    ?>
        <div id="right">
            <div class="right_data">
                <div class="button_box flex align-items">
                    <a href="javascript:;" data-url="admin.php?m=cms&a=add&type_id=<?php echo $type_id;?>&ajax=1&time=<?php echo time()?>" class="button model_btn">添加表单</a>
                </div>
                <div style="background: #fff;">
                    <form method="post" id="form_menu" class="form_content" action="admin.php?m=content&a=batch_edit_save" class="white">
                        <table class="table">
                            <thead>
                                <tr>
                                    <input name="table_name" type="hidden" id="table_name" value="<?php echo $table_name;?>" />
                                    <input name="type_id" type="hidden" id="type_id" value="<?php echo $type_id;?>" />
                                    <input name="classify_id" type="hidden" id="classify_id" value="<?php echo $classify_id;?>" />
                                    <th class="table_checkbox_all">
                                        <input name="" type="checkbox" class="checkbox" onclick="SelectAll('content_id[]')" value="" id="checkbox_all"/>
                                        <label class="checkbox" for="checkbox_all"></label>
                                    </th>
                                    <th style="width: 85px">ID</th>
                                    <?php
                                    $where = array();
                                    $where['type_id'] = $type_id;
                                    $where['list_switch'] = 2;
                                    $list_input=M('input')->where($where)->order('date asc')->select();
                                    foreach($list_input as $k=>$v){?>
                                    <th><?php echo $v['input_name']?></th>
                                    <?php }?>
                                    <th>排序时间</th>
                                    <th style="width: 160px;">操作</th>
                                </tr>
                            </thead>
                            <?php
                            $perpage=20;
                            $offset=($p-1)*$perpage;//偏移量
                            $list=M($table_name)->order('date desc')->limit($offset,$perpage)->select();
                            foreach($list as $k=>$v){
                                $v['content_id']=$v[$table_name.'_id'];
                            ?>
                            <tr class="row_list">
                                <td>
                                    <input name="content_id[]" type="checkbox" class="checkbox" value="<?php echo $v['content_id']?>" id="checkbox_<?php echo $v['content_id'];?>"/>
                                    <label class="checkbox" for="checkbox_<?php echo $v['content_id'];?>"></label>
                                    <input name="data[content_id][]" type="hidden" value="<?php echo $v['content_id']?>" />

                                </td>
                                <td><?php echo $v['content_id']?></td>
                                <?php foreach($list_input as $key=>$val){ ?>
                                <td>
                                    <?php if($val['input_type_id']==7){?>
                                    <a href="<?php echo $v[$val['field_name']];?>" target="_blank"><img src="<?php echo $v[$val['field_name']];?>" width="50" height="15" /></a>
                                    <?php }else if($val['input_type_id']==8){?>
                                    <?php echo date('Y-m-d H:i:s',$v[$val['field_name']]);?>
                                    <?php }else if($val['input_type_id']==6){?>
                                    <select name="data[<?php echo $val['field_name'];?>][]">
                                        <option value="0">请选择</option>
                                        <?php
                                        $input = M('input')->where(array('input_pid' => $val['input_id']))->order('date asc')->select();
                                        foreach($input as $k2=>$v2){ ?>
                                        <option value="<?php echo $v2['input_value'];?>"<?php if($v[$val['field_name']]==$v2['input_value']){?> selected="selected"<?php }?>><?php echo $v2['input_name'];?></option>
                                        <?php } ?>
                                    </select>
                                    <?php }else{?>
                                    <input type="text" class="type_2" name="data[<?php echo $val['field_name'];?>][]" style="width:<?php echo $val['list_size'];?>px;" value="<?php echo $v[$val['field_name']];?>" />
                                    <?php }?>
                                </td>
                                <?php }?>
                                <td><input name="data[date][]" type="text" value="<?php echo cover_time($v['date'],'Y-m-d H:i:s');?>" size="20" /></td>
                                <td>
                                    <a href="javascript:;" data-url="admin.php?m=content&a=edit&type_id=<?php echo $type_id;?>&classify_id=<?php echo $classify_id;?>&table_name=<?php echo $table_name;?>&content_id=<?php echo $v[$table_name.'_id'];?>&ajax=1&time=<?php echo time()?>" class="edit model_btn">修改</a>
                                    <a href="javascript:;" onclick="confirm('确定删除吗!','admin.php?m=content&a=del_save&content_id=<?php echo $v['content_id'];?>&table_name=<?php echo $table_name;?>&type_id=<?php echo $type_id;?>','')" class="delete">删除</a>
                                </td>
                            </tr>
                            <?php }?>
                        </table>
                        <input name="" class="submit" type="submit" value="提交" />
                    </form>
                    <?php require APP_ROOT.'public/page.php';?>
                </div>
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
            area:['50%','80%'],
            content: url,
        });
    })
</script>
<?php require APP_ROOT.'public/bottom.php';?>
