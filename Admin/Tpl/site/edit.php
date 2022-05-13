<?php require APP_ROOT.'public/head.php';?>
<script type="text/javascript">
    $('#form').ajaxForm({ success:function showResponse(responseText) {
        layer.msg(responseText, {time: 1500}, function () {
            parent.location.reload();
        });
    }});
</script>
<div class="ajaxcontent">
    <?php
    $classify_id = pg('classify_id');
    $type_id = pg('type_id');
    $content_id = pg('id');
    $table_name = pg('table_name');
    $list = M('region')->where(array('region_id' => $content_id))->find();
    ?>
    <form action="admin.php?m=site&a=address_edit" method="post" enctype="multipart/form-data" id="form">
        <input type="hidden" name="id" value="<?php echo pg('id');?>" />
        <input type="hidden" name="type" value="<?php echo pg('type');?>" />
        <div class="tabouter">
            <div class="tabcon">
                <div class="tabconent">
                    <div class="flex align-items tabconent_row">
                        <div class="tabconent_row_title flex align-items">
                        <?php
                        if(pg('type')==1){
                            echo '省份名称';
                        }
                        if(pg('type')==2){
                            echo '城市名称';
                        }
                        if(pg('type')==3){
                            echo '地区名称';
                        }
                        ?>
                        </div>
                        <div class="tabconent_row_input">
                            <input type="text" name="region_name" value="<?php echo $list['region_name'];?>" required />
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <input type="submit" class="submit" value="确认提交">
    </form>
</div>
<?php require APP_ROOT.'public/foot.php';?>
