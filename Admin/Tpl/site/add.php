<?php require APP_ROOT.'public/head.php';?>
<script type="text/javascript">
    $('#form').ajaxForm({ success:function showResponse(responseText)  {
        layer.msg(responseText, {time: 1500}, function () {
            parent.location.reload();
        });
    }});
</script>
<div class="ajaxcontent">
    <?php
    $classify_id = pg('classify_id');
    $type_id = pg('type_id');
    $content_id = pg('pid');
    $table_name = pg('table_name');
    $list = M('region')->where(array('region_id' => $content_id))->find();
    ?>
    <form action="admin.php?m=site&a=address_add" method="post" enctype="multipart/form-data" id="form">
        <input type="hidden" name="pid" value="<?php echo pg('pid');?>" />
        <input type="hidden" name="region_type" value="<?php echo $list['region_type'];?>" />
        <div class="tabouter">
            <div class="tabcon">
                <div class="tabconent">
                    <div class="flex align-items tabconent_row">
                        <div class="tabconent_row_title flex align-items">
                            <?php
                            if(!$list){
                                echo '省份名称';
                            }
                            if($list['region_type']==1){
                                echo '城市名称';
                            }
                            if($list['region_type']==2){
                                echo '市区名称';
                            }
                            ?>
                        </div>
                        <div class="tabconent_row_input">
                            <input type="text" name="region_name" required />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="submit" class="submit" value="确认提交">
    </form>
</div>
<?php require APP_ROOT.'public/foot.php';?>
