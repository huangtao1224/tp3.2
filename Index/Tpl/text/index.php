<?php require APP_ROOT.'public/top.php';?>
<div class="g00002">
  <div class="g00003" id="left">
    <?php require APP_ROOT.'public/left.php';?>
  </div>
  <div class="g00004" id="right">
    <div class="f14460179191">
      <div class="f14460179192">
        <?php require APP_ROOT.'public/right.php';?>
      </div>
      <div class="f14460179195">
        <?php  			$text = M()->table('index_text t,index_relevance r')->where('r.classify_id ='.default_classify_id(315).' and r.content_id=t.text_id')->find();   			echo $text['text_content'];  		?>
      </div>
    </div>
  </div>
</div>
<?php require APP_ROOT.'public/bottom.php';?>
