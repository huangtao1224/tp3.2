<?php require APP_ROOT.'public/head.php';?>
<div class="ajaxcontent">
    <script type="text/javascript">
        $('#form').ajaxForm({ success:function showResponse(responseText)  {
            layer.msg(responseText,{time:1500},function(){
                parent.location.reload();
            });
        }});
    </script>
    <form action="admin.php?m=copy_version&a=add_save" method="post" enctype="multipart/form-data" id="form" onSubmit="return copy_version()">
        <input name="data[date]" type="hidden" id="date" value="<?php echo time();?>" />
        <table class="table">
            <tr class="row">
                <td>版本名称</td>
                <td><input name="data[version_name]" type="text" id="version_name" size="40" /></td>
                <td>如:英文版</td>
            </tr>
            <tr class="row">
                <td>版本文件名</td>
                <td><input name="data[version_directory]" type="text" id="version_directory" size="40" /></td>
                <td>如:english</td>
            </tr>
        </table>
        <input type="submit" class="submit" value="确认提交">
    </form>
</div>
<?php require APP_ROOT.'public/foot.php';?>
