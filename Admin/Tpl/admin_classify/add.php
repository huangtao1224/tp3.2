<?php require APP_ROOT.'public/head.php';?>
<style>
    .ajaxcontent{padding-bottom: 50px;}
    .icon_box{display: none;}
    body{overflow: auto}
    body::-webkit-scrollbar {width : 8px;height: 8px;background: transparent;}
    body::-webkit-scrollbar-thumb {border-radius: 8px;box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);background:#666;}
    body::-webkit-scrollbar-track {background:transparent;}
    .classify_icon{position: absolute;top:35px;left: 0;display: none;height: 150px;overflow: auto;background: #e5e5e5;z-index: 10;border-radius: 5px;}
    .classify_icon::-webkit-scrollbar {width : 8px;height: 8px;background: transparent;}
    .classify_icon::-webkit-scrollbar-thumb {border-radius: 8px;box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);background:#666;}
    .classify_icon::-webkit-scrollbar-track {background:transparent;}
</style>
<script type="text/javascript">
    $('#form').ajaxForm({ success:function showResponse(responseText)  {
        layer.msg(responseText,{time:1500},function(){
            parent.location.reload();
        });
    }});
</script>
<div class="ajaxcontent">
    <?php
    global $lists,$classify_id;
    $classify_id=pg('classify_id');
    $classify = M('admin_classify')->where(array('classify_id'=>$classify_id))->find();
    ?>
    <form action="admin.php?m=admin_classify&a=add_save" method="post" enctype="multipart/form-data" id="form" onsubmit="return return_classify()" class="navbar-form white">
        <input name="data[date]" type="hidden" id="date" value="<?php echo time();?>" />
        <div class="flex align-items tabconent_row">
            <div class="tabconent_row_title flex align-items">分类名称</div>
            <div class="tabconent_row_input">
                <input name="data[classify_name]" class="form-control" id="classify_name" type="text">
            </div>
        </div>
        <div class="flex align-items tabconent_row">
            <div class="tabconent_row_title flex align-items">父级</div>
            <div class="tabconent_row_input">
                <select name="data[classify_pid]" id="classify_pid">
                    <option value="<?php echo session('version_classify_id');?>">根目录</option>
                    <?php
                    $lists = M('admin_classify')->field('classify_id,level_id,classify_pid,classify_name')->select();
                    function recursive_classify($classify_pid='1')
                    {
                        global $lists,$classify_id;
                        foreach($lists as $k=>$v)
                        {
                            if($v['classify_pid']==$classify_pid)
                            {
                                ?>
                                <option value="<?php echo $v['classify_id']?>"<?php if($classify_id==$v['classify_id']){?> selected="selected"<?php }?>>┣<?php for($i=3;$i<=$v['level_id'];$i++)echo "━";?> <?php echo $v['classify_name']?></option>
                                <?php
                                recursive_classify($v['classify_id']);
                            }
                        }
                    }
                    recursive_classify();
                    ?>
                </select>
            </div>
        </div>
        <div class="flex align-items tabconent_row icon_box">
            <div class="tabconent_row_title flex align-items">图标</div>
            <div class="tabconent_row_input" style="position: relative;">
                <input type="text" name="data[classify_icon]" id="classify_icon" readonly>
                <div class="classify_icon">
                    <?php require APP_ROOT.'admin_classify/icon.php';?>
                </div>
            </div>
        </div>
        <div class="flex align-items tabconent_row">
            <div class="tabconent_row_title flex align-items">链接</div>
            <div class="tabconent_row_input">
                <input name="data[classify_url]" class="form-control" id="classify_url" type="text">
            </div>
        </div>
        <input type="submit" class="submit" value="确认提交">
    </form>
</div>
<script>
    $('#classify_pid').change(function () {
        if($(this).val()==1){
            $('.icon_box').css('display','flex');
        }else{
            $('.icon_box').css('display','none');
        }
    })
    $('#classify_icon').click(function () {
        $('.classify_icon').toggle();
        $('.icon').each(function(){
            console.log($(this).attr('data-icon'));
            if($(this).attr('data-icon')==$('#classify_icon').val()){
                $(this).addClass('active').siblings().removeClass('active');
            }
        })
    })
    $('.icon').click(function () {
        $('#classify_icon').val($(this).attr('data-icon'));
        $('.classify_icon').hide();
    })
</script>
<?php require APP_ROOT.'public/foot.php';?>
