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
    <form action="admin.php?m=permissions&a=page_edit_save" method="post" enctype="multipart/form-data" id="form">
        <?php
        global $page_edit_arr;
        $user_id=pg('user_id');
        $user_page = M('user_page')->where(array('user_id'=>$user_id))->select();
        foreach($user_page as $k=>$v)
        {
            $page_edit_arr[]=$v['page'];
        }
        ?>
        <input name="user_id" type="hidden" id="user_id" value="<?php echo $user_id?>" />
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
            <?php
            function listDir2($dir,$directory)
            {
            ?>
            <tr>
                <td><strong><?php echo $directory;?></strong></td>
            </tr>
            <tr>
                <td>
                    <?php
                    if(is_dir($dir))
                    {
                        if ($dh = opendir($dir))
                        {
                            while (($file = readdir($dh)) !== false)
                            {
                                if((is_dir($dir.$file)) && $file!="." && $file!="..")
                                {
                                    $path=$file;
                    ?>
                    <br />
                    <div class="menu_pname"><?php echo $directory;?></div>
                    <?php
                                }
                                else
                                {
                                    if($file!="." && $file!="..")
                                    {
                                        global $page_edit_arr;
                                ?>
                                <div style="margin-right: 15px;display: inline-block;vertical-align: middle;">
                                    <input name="page[]"<?php if(in_array($directory.'_'.$file,$page_edit_arr)){?> checked="checked"<?php }?> type="checkbox" class="checkbox" value="<?php echo $directory.'_'.$file;?>" id="<?php echo $directory.'_'.substr($file,0,strpos($file,'.'));?>"/>
                                    <label class="checkbox" for="<?php echo $directory.'_'.substr($file,0,strpos($file,'.'));?>"><?php echo $file;?></label>
                                </div>

                                <?php
                                    }
                                }
                            }
                            closedir($dh);
                        }
                    }
                    ?>
                </td>
            </tr>
            <?php
            }
            //开始运行
            $list=each_dir(APP_ROOT);
            foreach($list as $k=>$v)
            {
                if(!in_array($v['filename'],array('images','css','js','font-awesome-4.5.0')))
                {
                listDir2(APP_ROOT.$v['filename'],$v['filename']);
                }
            }
            ?>
        </table>
        <input name="" class="submit" type="submit" value="确认提交" />
    </form>
</div>
<?php require APP_ROOT.'public/foot.php';?>
