<?php
$pageurl='http://'.($_SERVER["HTTP_X_FORWARDED_HOST"]?:$_SERVER["HTTP_HOST"]).$_SERVER["REQUEST_URI"];
$pageurl=preg_replace('/\&p=[0-9]+/','',$pageurl);//正则替换
?>
<!-- 翻页-->
<div class="page">
    <a href="<?php echo $pageurl."&p=1"?>" data-num="1">首页</a>
    <?php
    $page_count=ceil($total_num/$perpage);
    $prev=$p-1;
    $next=$p+1;
    $pagenum=5;//固定页码数
    $floorpage=floor($pagenum/2);//页码偏移量
    $star=$p-$floorpage;
    $end=$p+$floorpage;
    if($p>1){
    ?>
    <a href="<?php echo $pageurl."&p=".$prev;?>" data-num="<?php echo $prev;?>">&lt;</a>
    <?php
    }else{
    ?>
    <span>&lt;</span>
    <?php }
    if($star<1)$star=1;
    if($end>$page_count)$end=$page_count;
    if($end==0)$end=1;
    if($page_count==0)$page_count=1;
    for($i=$star;$i<=$end;$i++){
        if($p==$i){
    ?>
        <span class="cur"><?php echo $i;?></span>
        <?php }else{?>
        <a href="<?php echo $pageurl."&p=".$i;?>" data-num="<?php echo $i;?>"><?php echo $i;?></a>
        <?php }
    }
    if($p<$page_count){?>
    <a href="<?php echo $pageurl."&p=".$next;?>" data-num="<?php echo $next;?>">&gt;</a>
    <?php }else{?>
    <span>&gt;</span>
    <?php }?>
    <a href="<?php echo $pageurl."&p=".$page_count?>" data-num="<?php echo $page_count;?>">尾页</a>
    <p>
        到第
        <select name="page_number" id="page_number" onchange="on_page($(this).val())">
            <?php for($i=1;$i<=$page_count;$i++){?>
            <option value="<?php echo $i;?>"<?php if($p==$i){?> selected="selected"<?php }?>><?php echo $i;?></option>
            <?php }?>
        </select>
        页
    </p>
</div>
<form action="" method="post" id="sForm">
    <input type="hidden" name="p" id="p" />
    <input type="hidden" name="search" id="search" value="<?php echo pg('search');?>" />
</form>
<script type="text/javascript">
    $(".page a").click(function(){
        $("#p").val($(this).attr('data-num'));
        $("#sForm")[0].submit();
        return false;
    });
    function on_page(num){
        $("#p").val(num);
        $("#sForm")[0].submit();
        return false;
    }
</script>