<?php require APP_ROOT.'public/top.php';?>

<div class="g00002">
  <div class="g00003" id="left">
    <?php require APP_ROOT.'public/left.php';?>
  </div>
  <div class="g00004" id="right">    
    <div class="f14729874921">
      <div class="f14729822wc">
        <div class="f14729874922" id="previewid">
          <?php $goods=M('goods')->where(array('goods_id'=>$content_id))->find();?>
          <div id="vertical" class="f14729874923"> <img class="f14729874924" src="<?php echo $goods['goods_bigimg'];?>" width="400" height="400" alt="" id="midimg" />
            <div class="f14729874925" style="display:none;" id="winSelector"></div>
          </div>
          <div class="f14729874926">
            <div class="f14729874927 fa fa-angle-left"></div>
            <div class="f14729874928" id="imageMenu">
              <ul class="f14729874929">
                <li id="onlickImg" class="f147298749212"><img class="f147298749213 on" src="<?php echo $goods['goods_bigimg'];?>"/></li>
                <?php for($i=1;$i<10;$i++){
			if($goods['additional_img'.$i]!=''){
			?>
                <li class="f147298749212"><img class="f147298749213" src="<?php echo $goods['additional_img'.$i];?>"/></li>
                <?php } } ?>
              </ul>
            </div>
            <div class="f147298749214 fa fa-angle-right"></div>
            <script type="text/javascript"> jQuery(".f14729874926").slide({mainCell:".f14729874929",prevCell:".f14729874927",nextCell:".f147298749214",autoPage:true,autoPlay:true,effect:"left",vis:5});
  </script> </div>
          
          <!--smallImg end-->
          <div id="bigView" class="f147298749215" style="display:none;"><img class="f147298749216" width="800" height="800" alt="" src="<?php echo $goods['goods_bigimg'];?>" /></div>
          <script src="<?php echo APP_ROOT;?>js/goods.details.js" type="text/javascript"></script> 
        </div>
        <div class="f14464854882">
        <div class="goods_name"><?php echo $goods['goods_name'];?></div>
	<?php
	 $goods['price_array']=unserialize($goods['price_array']);
	 foreach($goods['price_array'] as $k=>$v){
		 $val=$v;
		 unset($val['price']);
		 unset($val['inventory']);
		 unset($val['coding']);
		 unset($val['volume']);
		 unset($val['weight']);
		 $val=implode(',',$val);
	?>
          <label>
            <input type="hidden" value="<?php echo $val;?>" />
            <input type="hidden" class="price" value="<?php echo $v['price'];?>" />
            <input type="hidden" class="inventory" value="<?php echo $v['inventory'];?>" />
            <input type="hidden" class="coding" value="<?php echo $v['coding'];?>" />
            <input type="hidden" class="volume" value="<?php echo $v['volume'];?>" />
            <input type="hidden" class="weight" value="<?php echo $v['weight'];?>" />
          </label>
          <?php }?>
          <div class="goods_attr">
          <dl class="fcs_panel">
              <dt>价格</dt>
              <dd class="ddprice"></dd>
              <input id="goods_id" name="goods_id" type="hidden" value="<?php echo $goods['goods_id'];?>" />
              <input id="price" name="price" type="hidden" value="" />
              <input id="inventory" name="inventory" type="hidden" value="" />
            </dl>
            
            <dl>
              <dt>颜色</dt>
              <dd>
                <?php foreach(explode('|',$goods['color']) as $k=>$v)
	{?>
                <label <?php if($k==0)echo ' class="on"';?> onclick="get_price($(this))">
                  <input class="specifications_radio"<?php if($k==0)echo ' checked="checked"';?>type="radio" name="color" value="<?php echo $v;?>" />
                  <input type="hidden" value="颜色" />
                  <?php echo $v;?> </label>
                <?php }?>
              </dd>
            </dl>
            <dl>
              <dt>尺码</dt>
              <dd>
                <?php foreach(explode('|',$goods['size']) as $k=>$v)
	 {?>
                <label <?php if($k==0)echo ' class="on"';?> onclick="get_price($(this))">
                  <input class="specifications_radio"<?php if($k==0)echo ' checked="checked"';?> type="radio" name="size" value="<?php echo $v;?>" />
                  <input type="hidden" value="尺码" />
                  <?php echo $v;?> </label>
                <?php }?>
              </dd>
            </dl>
            
            
            <dl class="dlnumber">
              <dt>数量</dt>
              <dd>
              <a href="javascript:;" onclick="control_number('reduction','number')">-</a>
              <input name="number" type="text" id="number" value="1" maxlength="8" onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9.]/g,'')" onblur="control_number('input','number')" />
              <a href="javascript:;" onclick="control_number('add','number')">+</a>
              <div class="ddinventory">库存<i></i>件</div>
              </dd>
            </dl>
            
          </div>
          
          <div class="goods_btn">
          <a href="javascript:;" onclick="buy_now()" class="buynowa">立即购买</a>
          <a href="javascript:;" onclick="add_cart()" class="addcarda">加入购物车</a> 
          </div>
          <script type="text/javascript">
function get_price(obj)
{
	var id = new Array(),name = new Array();
	obj.children("input").attr("checked","checked");
	obj.addClass("on").siblings().removeClass("on");
	$("input[class='specifications_radio']:checked").each(function(i){
	  id[i]=($(this).val());
	  name[i]=$(this).next().val();
	});
	var price=$("input[value='"+id+"']").siblings(".price").val();
	var inventory=$("input[value='"+id+"']").siblings(".inventory").val();
	$(".ddprice").html("￥"+price);
	$("#price").val(price);
	$("#inventory").val(inventory);
	$(".ddinventory i").html(inventory);
	if(parseInt($("#number").val())>parseInt(inventory))
	{
		$("#number").val(inventory);
	}
}
get_price($(this));
</script> 
        </div>
      </div>
      <div class="f147298749217">
        <div class="f147298749218">
          <ul class="f147298749219">
            <li class="f147298749220">详细介绍</li>
          </ul>
        </div>
        <div class="f147298749221">
          <div class="f147298749222"> <?php echo $goods['goods_content'];?> </div>
        </div>
        <script type="text/javascript">jQuery(".f147298749217").slide({titCell:".f147298749220",mainCell:".f147298749221"});</script> 
      </div>
    </div>
  </div>
</div>
<?php require APP_ROOT.'public/bottom.php';?>
