<div class="" >
  <div class="">

  <style>
  .link_page{
     height: 36px;
    text-align: center;
    line-height: 36px;
    font-size: 14px;
     margin: 0 -1px 0 0px;
    position: relative;
    z-index: 9;
    text-decoration: none;
    color: #666;
    padding: 8px 14px;
    border: 1px solid #ccc;

  }
  span.link_page.abc{
    background-color:#EAEAEA ;
  }
</style>
<?php if(REWRITE==TRUE){?>
<div class="" >



<?php 
$pageurl=get_url();
$pageurl=preg_replace('/\-p[0-9]+/','',$pageurl);//正则替换
$pageurl=preg_replace('/-p[0-8]+/','',$pageurl);//正则替换
$pageurl=preg_replace('/.html/','',$pageurl);//正则替换
?>
      <a class="link_page" href="<?php echo $pageurl."-p1"?>.html"><?php echo L('_SHOUYE_')?></a>
      <?php    $page_count=ceil($total_num/$perpage);      $prev=$p-1;    $next=$p+1;    $pagenum=5;    $floorpage=floor($pagenum/2);    $star=$p-$floorpage;    $end=$p+$floorpage;    if($p>1){    ?>
      <a class="f14466107055" href="<?php echo $pageurl."-p".$prev;?>.html">&lt;</a>
      <?php    }else{   ?>
      <span class="f14466107056">&lt;</span>
      <?php }   if($star<1){      $star=1;      $end=$page_count<$pagenum?$page_count:$pagenum;     }   if($end>$page_count){     $star=$page_count-$pagenum+1;     $end=$page_count;     }     if($end==0)$end=1;      if($page_count==0)$page_count=1;      ?>
      <?php  for($i=$star;$i<=$end;$i++){  if($i>0){  if($p==$i){   ?>
      <span class="f14466107057"><?php echo $i;?></span>
      <?php }else{?>
      <a class="f14466107058" href="<?php echo $pageurl."-p".$i;?>.html"><?php echo $i;?></a>
      <?php }  } }    if($p<$page_count){?>
      <a class="f14466107059" href="<?php echo $pageurl."-p".$next;?>.html">&gt;</a>
      <?php }else{?>
      <span class="f144661070510">&gt;</span>
      <?php }?>
      <a class="f144661070510" href="<?php echo $pageurl."-p".$page_count?>.html"><?php echo L('_WEIYE_')?></a>
      </div>
<?php }else{?>


    <div class="" style="text-align:center;">
      <?php  $pageurl=get_url();  $pageurl=preg_replace('/\&p=[0-9]+/','',$pageurl);  ?>
      <a class="link_page link_ajax" data-page="1" ><?php echo L('_SHOUYE_')?></a>
      <?php    $page_count=ceil($total_num/$perpage);      $prev=$p-1;    $next=$p+1;    $pagenum=5;    $floorpage=floor($pagenum/2);    $star=$p-$floorpage;    $end=$p+$floorpage;    if($p>1){    ?>
      <a class="link_page link_ajax" data-page="<?php echo $prev; ?>" >&lt;</a>
      <?php    }else{   ?>
      <span class="link_page ">&lt;</span>
      <?php }   if($star<1){      $star=1;      $end=$page_count<$pagenum?$page_count:$pagenum;     }   if($end>$page_count){     $star=$page_count-$pagenum+1;     $end=$page_count;     }     if($end==0)$end=1;      if($page_count==0)$page_count=1;      ?>
      <?php  for($i=$star;$i<=$end;$i++){  if($i>0){  if($p==$i){   ?>
      <span class="link_page  abc" data-page="<?php echo $i; ?>"><?php echo $i;?></span>
      <?php }else{?>
      <a class="link_page link_ajax" data-page="<?php echo $i; ?>" ><?php echo $i;?></a>
      <?php }  } }    if($p<$page_count){?>
      <a class="link_page link_ajax" data-page="<?php echo $next; ?>" >&gt;</a>
      <?php }else{?>
      <span class="link_page ">&gt;</span>
      <?php }?>
      <a class="link_page link_ajax" data-page="<?php echo $page_count; ?>" ><?php echo L('_WEIYE_')?></a>

      </div>
  <?php }?>
  </div>
</div>
