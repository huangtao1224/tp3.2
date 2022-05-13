<style>
    .link_page{
        padding: 6px 12px;
        line-height: 1.428571429;
        background-color: #FFFFFF;
        border: 1px solid #DDDDDD;
        border-right:0;
        font-size: 14px;
    }
    .link_page.start{border-top-left-radius: 4px;border-bottom-left-radius: 4px;}
    .link_page.end{border-right: 1px solid #DDDDDD;border-top-right-radius: 4px;border-bottom-right-radius: 4px;}
    .link_page.cur{background-color: #f4f4f4;border-color: #DDDDDD;}
</style>
<?php if(REWRITE==TRUE){?>
<div style="text-align:center;display: flex;align-items: center;justify-content: center;">
    <?php
    $pageurl='http://'.($_SERVER["HTTP_X_FORWARDED_HOST"]?:$_SERVER["HTTP_HOST"]).$_SERVER["REQUEST_URI"];
    $pageurl=preg_replace('/\-p[0-9]+/','',$pageurl);//正则替换
    $pageurl=preg_replace('/-p[0-8]+/','',$pageurl);//正则替换
    $pageurl=preg_replace('/.html/','',$pageurl);//正则替换
    ?>
    <?php  $page_count=ceil($total_num/$perpage); if($p==1){ $prev=$page_count; }else{ $prev=$p-1; } if($p==$page_count){ $next=1;}else{ $next=$p+1;} ?>
        <a class="link_page start"  href="<?php echo $pageurl."-p".$prev;?>.html">&lt;</a>
    <?php if($page_count>8){ if($p<5){ for ($i=1;$i<=5;$i++){ if($p==$i){?>
        <span class="link_page cur"><?php echo $i;?></span>
    <?php }else{?>
        <a class="link_page" href="<?php echo $pageurl."-p".$i;?>.html"><?php echo $i;?></a>
    <?php } }?>
        <span class="link_page">...</span>
        <a class="link_page" href="<?php echo $pageurl."-p".$page_count;?>.html"><?php echo $page_count;?></a>
    <?php }else if($p>($page_count-4)){?>
        <a class="link_page" data-num="1" href="<?php echo $pageurl."-p1"?>.html">1</a>
        <span class="link_page">...</span>
    <?php for ($i=($page_count-4);$i<=$page_count;$i++){ if($p==$i){ ?>
        <span class="link_page cur"><?php echo $i;?></span>
    <?php }else{?>
        <a class="link_page" href="<?php echo $pageurl."-p".$i;?>.html"><?php echo $i;?></a>
    <?php } } }else{?>
        <a class="link_page" href="<?php echo $pageurl."-p1"?>.html">1</a>
        <span class="link_page">...</span>
        <a class="link_page" href="<?php echo $pageurl."-p".($p-1);?>.html"><?php echo ($p-1);?></a>
        <span class="link_page cur"><?php echo $p;?></span>
        <a class="link_page" href="<?php echo $pageurl."-p".($p+1);?>.html"><?php echo ($p+1);?></a>
        <span class="link_page">...</span>
        <a class="link_page" href="<?php echo $pageurl."-p".$page_count;?>.html"><?php echo $page_count;?></a>
    <?php } }else{ for($i=1;$i<=$page_count;$i++){ if($p==$i){?>
        <span class="link_page cur"><?php echo $i;?></span>
    <?php }else{?>
        <a class="link_page" href="<?php echo $pageurl."-p".$i;?>.html"><?php echo $i;?></a>
    <?php } }?>
        <a class="link_page end" href="<?php echo $pageurl."-p".$next;?>.html">&gt;</a>
    <?php }?>
</div>
<?php }else{?>
<div style="text-align:center;display: flex;align-items: center;justify-content: center;">
    <?php  $pageurl='http://'.($_SERVER["HTTP_X_FORWARDED_HOST"]?:$_SERVER["HTTP_HOST"]).$_SERVER["REQUEST_URI"];  $pageurl=preg_replace('/\&p=[0-9]+/','',$pageurl);  ?>
    <?php  $page_count=ceil($total_num/$perpage); if($p==1){ $prev=$page_count; }else{ $prev=$p-1; } if($p==$page_count){ $next=1;}else{ $next=$p+1;} ?>
        <a class="link_page start"  href="<?php echo $pageurl."&p=".$prev;?>">&lt;</a>
    <?php if($page_count>8){ if($p<5){ for ($i=1;$i<=5;$i++){ if($p==$i){?>
        <span class="link_page cur"><?php echo $i;?></span>
    <?php }else{?>
        <a class="link_page" href="<?php echo $pageurl."&p=".$i;?>"><?php echo $i;?></a>
    <?php } }?>
        <span class="link_page">...</span>
        <a class="link_page" href="<?php echo $pageurl."&p=".$page_count;?>"><?php echo $page_count;?></a>
    <?php }else if($p>($page_count-4)){?>
        <a class="link_page" data-num="1" href="<?php echo $pageurl."&p=1"?>">1</a>
        <span class="link_page">...</span>
    <?php for ($i=($page_count-4);$i<=$page_count;$i++){ if($p==$i){ ?>
        <span class="link_page cur"><?php echo $i;?></span>
    <?php }else{?>
        <a class="link_page" href="<?php echo $pageurl."&p=".$i;?>"><?php echo $i;?></a>
    <?php } } }else{?>
        <a class="link_page" href="<?php echo $pageurl."&p=1"?>">1</a>
        <span class="link_page">...</span>
        <a class="link_page" href="<?php echo $pageurl."&p=".($p-1);?>"><?php echo ($p-1);?></a>
        <span class="link_page cur"><?php echo $p;?></span>
        <a class="link_page" href="<?php echo $pageurl."&p=".($p+1);?>"><?php echo ($p+1);?></a>
        <span class="link_page">...</span>
        <a class="link_page" href="<?php echo $pageurl."&p=".$page_count;?>"><?php echo $page_count;?></a>
    <?php } }else{ for($i=1;$i<=$page_count;$i++){ if($p==$i){?>
        <span class="link_page cur"><?php echo $i;?></span>
    <?php }else{?>
        <a class="link_page" href="<?php echo $pageurl."&p=".$i;?>"><?php echo $i;?></a>
    <?php } }?>
        <a class="link_page end" href="<?php echo $pageurl."&p=".$next;?>">&gt;</a>
    <?php }?>
</div>
<?php }?>
