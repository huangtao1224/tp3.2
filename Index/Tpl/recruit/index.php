<?php require APP_ROOT.'public/top.php';?>
<div class="g00002">
  <div class="g00003" id="left">
    <?php require APP_ROOT.'public/left.php';?>
  </div>
  <div class="g00004" id="right">
        <?php require APP_ROOT.'public/right.php';?>
    <div class="vdwdA7fvUj">
      <ul class="dxLQC3LfVK">
        <li class="FSOqVas0QX">
          <div class="LUwV8Kk4i3"><?php echo L('_ZHAOPINGZHIWEI_')?></div>
          <div class="aTs8pcDM3J"><?php echo L('_GONGZUODIDIAN_')?></div>
          <div class="PHXNHKcfej"><?php echo L('_ZHAOPINGRENSHU_')?></div>
          <div class="yrk1QIgFNr"><?php echo L('_XIANGQIANG_')?></div>
        </li>
        <?php $perpage=15;$offset=($p-1)*$perpage;$recruit = M()->table('index_recruit n,index_relevance r')->where('r.classify_id ='.default_classify_id(232).' and r.content_id=n.recruit_id')->order('date desc')->limit($offset,$perpage)->select();$total_num=M()->table('index_recruit n,index_relevance r')->where('r.classify_id ='.default_classify_id(232).' and r.content_id=n.recruit_id')->count();  	  foreach($recruit as $k=>$v){  ?>
        <li class="zBen22HXPq">
          <div class="LUwV8Kk4i3"><?php echo $v['job_name'];?></div>
          <div class="aTs8pcDM3J"><?php echo $v['work_place'];?></div>
          <div class="PHXNHKcfej"><?php echo $v['number'];?></div>
          <div class="yrk1QIgFNr"><a class="LMhM1h9nZK" href="<?php echo content_url($v['type_id'],$v['content_id']) ?>"><?php echo L('_XIANGQIANG_')?></a></div>
        </li>
        <?php }?>
      </ul>
    </div>
  </div>
</div>
<?php require APP_ROOT.'public/bottom.php';?>
