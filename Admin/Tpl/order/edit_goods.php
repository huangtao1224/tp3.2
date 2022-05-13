<?php
$classify_id = pg('classify_id');
$type_id = pg('type_id');
$content_id = pg('content_id');
$table_name = pg('table_name');
$goods=M('order_goods')->where(array('order_goods_id'=>$content_id))->find();
?>
<script type="text/javascript">
$('#form').ajaxForm({ success:function showResponse(responseText)  {
    layer.msg(responseText,{time:1500},function(){
        parent.location.reload();
    });
}});
</script>

<form action="admin.php?m=order&a=edit_goods_save" method="post" enctype="multipart/form-data" id="form">
<input name="type_id" type="hidden" value="<?php echo $type_id;?>" />
<input name="content_id" type="hidden" value="<?php echo $content_id;?>" />
<input name="goods_id" type="hidden" value="<?php echo $goods['goods_id'];?>" />
<input name="id" type="hidden" id="id" value="" />
<input name="name" type="hidden" id="name" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">

<tr class="row">
 <?php foreach($goods_attribute_array as $gaa_k=>$gaa_v) {?>
         <li>
          <div ><?php echo $gaa_v['name'];?></div>
          <?php foreach($goods_attribute_value_array[$gaa_k] as $gava_k=>$gava_v){?>
          
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
          
          
          
         <div class="selectval" onclick="specifications_select($(this))">
            <label>
             <input name="<?php echo $gaa_k;?>" class="specifications_radio" type="radio" value="<?php echo $gava_v['id'];?>"
              <?php foreach($un_goods_attribute_value_array as $k_gava=>$v_gava)
              {
              	if($v_gava['id']==$gava_v['id'])echo ' checked=checked';
              }?>
              >
            <input type="hidden" value="<?php echo $gava_v['name'];?>" />
             <?php echo $gava_v['name'];?>
              </label>
              </div>
          <?php }?>
        </li> 
        <?php }?>
</tr>
<tr class="row">
 数量 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="number" value="<?php echo $goods['number'];?>"></input><br /><br />
 商品名 &nbsp;&nbsp;&nbsp;<input name="goods_name" value="<?php echo $goods['goods_name'];?>"></input>
</tr>



</table>

<input type="submit" class="submit" value="确认无误，提交">
</form>
<script>
	function specifications_select(obj)
{ 

  var id = new Array(),name = new Array();
  
  obj.children().children("input").attr("checked","checked");
  obj.addClass("on").siblings().removeClass("on");
  $("input[class='specifications_radio']:checked").each(function(i){
    id[i]=($(this).val());
    name[i]=$(this).next().val();
    
  });
	$("#id").val(id);
    $("#name").val(name);
}

specifications_select($(this));
</script>