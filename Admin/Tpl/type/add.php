<?php require APP_ROOT.'public/head.php';?>
<div class="ajaxcontent"> 
    <script type="text/javascript">
        $('#form').ajaxForm({ success:function showResponse(responseText)  {
            layer.msg(responseText,{time:1500},function(){
              parent.location.reload();
            });
        }});
    </script>
<!--    <style>-->
<!--        li{display: flex;align-items: center;}-->
<!--        li div{width: calc(100% - 100px);}-->
<!--        label.checkbox::after{top: 1px;}-->
<!--    </style>-->
        <form action="admin.php?m=type&a=add_save" method="post" id="form">
            <input name="data[date]" type="hidden" value="<?php echo time();?>" />
            <div class="tabcon">
                <div class="flex align-items tabconent_row">
                    <div class="tabconent_row_title">类型名称:</div>
                    <div class="tabconent_row_input"><input name="data[type_name]" type="text"></div>
                </div>
                <div class="flex align-items tabconent_row">
                    <div class="tabconent_row_title">表名:</div>
                    <div class="tabconent_row_input"><input name="data[table_name]" type="text"></div>
                </div>
                <div class="flex align-items tabconent_row">
                    <div class="tabconent_row_title">页面名:</div>
                    <div class="tabconent_row_input"><input name="data[page_name]" type="text" value="index"></div>
                </div>
                <div class="flex align-items tabconent_row">
                    <div class="tabconent_row_title">关联表单:</div>
                    <div class="tabconent_row_input">
                        <div>
                            <?php
                            $list=M('classify_type')->where('show_id=2')->select();
                            foreach ($list as $k => $v) {
                                if(!$v['table_name']){continue;}
                                ?>
                                <span>
                                    <input type="checkbox" name="data[glids][]" value="<?php echo $v['type_id']?>" id="gl<?php echo $k;?>" />
                                    <label for="gl<?php echo $k;?>" class="checkbox"><?php echo $v['type_name'].'（'.$v['table_name'].'）';?></label>
                                </span>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <div style="color:red;margin-bottom: 20px;">*创建关联表会自动创建关联id，同时之前的操作也会自动完成（即创建关联表只需在此处选择即可）</div>
            </div>
            <input type="submit" class="submit" value="确认提交">
        </form>
    </div>
</div>
<?php require APP_ROOT.'public/foot.php';?>
