<?php require APP_ROOT.'public/head.php';?>
<div class="ajaxcontent">
    <script type="text/javascript">
        $('#form').ajaxForm({ success:function showResponse(responseText)  {
            console.log(responseText);
            // $('.submit').attr('disabled','disabled');
            // if(responseText){
            //     console.log(responseText);
            //     alert(responseText);
            //     $('.submit').removeAttribute('disabled');
            // }
                //dialog_div_close('ajax_dialog');
                //msg_show(responseText,50000);
            }});

    </script>
    <?php
    $classify_id=pg('classify_id');
    $type_id=pg('type_id');
    $content_id=pg('content_id');
    ?>
    <form action="admin.php?m=content&a=collection_save" method="post" enctype="multipart/form-data" id="form">
        <input name="collection[version_id]" type="hidden" id="version_id" value="<?php echo session('version_id');?>" />
        <input name="classify_id" type="hidden" id="classify_id" value="<?php echo $classify_id;?>" />
        <input name="collection[type_id]" type="hidden" id="type_id" value="<?php echo $type_id;?>" />
        <?php
        if(pg('name')){
            ?>
            <input name="data[<?php echo pg('name');?>]" type="hidden" id="<?php echo pg('name');?>" value="<?php echo $content_id;?>" />
            <?php
        }
        ?>

        <div class="tabouter">
            <div class="tabcon">
                <div style="color: #f00;">结束位置为对应的结束标签，如抓取范围内含有相同的结束标签，请填写对应的结束标签的下一个开始标签符，尽量填写下一个开始标签符，更容易抓取到内容。只抓取标题、简介、内容、封面图以及产品图片集（适用于商品和新闻）</div>
                <div class="tabconent">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
                        <tr class="row">
                            <td>产品列表链接</td>
                            <td><input name="collection[url]" type="text" size="80" placeholder="例：http://www.gzthsy.cn/xwzx-1.html"/></td>
                        </tr>
                        <tr class="row">
                            <td>列表开始采集位置</td>
                            <td><input name="collection[start]" type="text" size="80" placeholder='例：<div class="pro_main">'/></td>
                        </tr>
                        <tr class="row">
                            <td>列表结束采集位置</td>
                            <td><input name="collection[end]" type="text" size="80" placeholder="例：</div>"/></td>
                        </tr>
                        <tr class="row">
                            <td>详情页开始采集位置</td>
                            <td><input name="collection[detailsStart]" type="text" size="80" placeholder='例：<div id="printableview">'/></td>
                        </tr>
                        <tr class="row">
                            <td>详情页结束采集位置</td>
                            <td><input name="collection[detailsEnd]" type="text" size="80" placeholder='例：<div class="RandomNews mt10">'/></td>
                        </tr>
                        <tr class="row">
                            <td>标题开始采集位置</td>
                            <td><input name="collection[tStart]" type="text" size="80" placeholder='例：<h4 class="corange">'/></td>
                        </tr>
                        <tr class="row">
                            <td>标题开始结束采集位置</td>
                            <td><input name="collection[tEnd]" type="text" size="80" placeholder='例：</h4>'/></td>
                        </tr>
                        <tr class="row">
                            <td>简介开始采集位置</td>
                            <td><input name="collection[introStart]" type="text" size="80" placeholder='例：<div class="cps pd_short">'/></td>
                        </tr>
                        <tr class="row">
                            <td>简介结束采集位置</td>
                            <td><input name="collection[introEnd]" type="text" size="80" placeholder="例：</div>"/></td>
                        </tr>
                        <tr class="row">
                            <td>内容开始采集位置</td>
                            <td><input name="collection[contentStart]" type="text" size="80" placeholder='例：<div class="proinfo " id="detailvalue0">'/></td>
                        </tr><tr class="row">
                            <td>内容结束采集位置</td>
                            <td><input name="collection[contentEnd]" type="text" size="80" placeholder='例：<div class="pro_key">'/></td>
                        </tr>
                        <?php if($type_id==3){?>
                        <tr class="row">
                            <td>图片集是否含有封面图</td>
                            <td>
                                <label>
                                    <input type="radio" name="collection[is_contain]" value="1" />
                                    含有
                                </label>
                                <label>
                                    <input type="radio" name="collection[is_contain]" value="2" />
                                    不含
                                </label>

                            </td>
                        </tr>
                        <tr class="row">
                            <td>图片集开始采集位置</td>
                            <td><input name="collection[imgStart]" type="text" size="80" placeholder=""/></td>
                        </tr>
                        <tr class="row">
                            <td>图片集结束采集位置</td>
                            <td><input name="collection[imgEnd]" type="text" size="80" placeholder=""/></td>
                        </tr>
                        <?php }?>
                    </table>
                </div>
            </div>
        </div>
        <input type="submit" class="submit" value="确认无误，提交">
    </form>
</div>
<?php require APP_ROOT.'public/foot.php';?>
