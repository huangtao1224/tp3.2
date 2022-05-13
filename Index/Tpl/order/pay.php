


<?php require APP_ROOT.'public/top.php';?>

<div class="header-box header-box2">
  <p class="currents">在线支付</p>
  <div class="progress-bar">
    <dl>
      <dt class="on">1</dt>
      <dd>我的购物车</dd>
    </dl>
    <dl>
      <dt class="on">2</dt>
      <dd>确认订单</dd>
    </dl>
    <dl>
      <dt class="on">3</dt>
      <dd style="background-color:#e93034;color:#fff;">付款</dd>
    </dl>
    <dl>
      <dt class="on" >4</dt>
      <dd >支付成功</dd>
    </dl>
  </div>
  <!--progress-bar-->
  <div class="cart-bg1 cart-bg2 cart-bg3 "></div>
</div>
<!--header-box-->
<?php
$order_id=pg('order_id');
$order=M('order')->where(array('order_id'=>$order_id))->find();
?>
<input type="hidden" name="order_id" id="order_id" value="<?php echo $order_id;?>" />
<div class="cart-main main2">
  <div class="suc-payment">
    <form action="index.php?f=order&w=mycart2" method="post" enctype="application/x-www-form-urlencoded">
      <!--cart-thead-->
      <div class="payment_list">
        <div class="submitted"><img src="<?php echo APP_ROOT;?>images/submitted.png" /></div>
        <ul>
          <li class="title">您的订单已经提交成功，感谢您的订购！</li>
          <li>在线支付订单号：<font class="num"><?php echo $order['order_number'];?></font> ｜ 应付款金额：<font class="num">￥
            <?php  echo $order['price'];?>
            </font> <font class="num details"><a href="index.php?m=order&a=detail&order_id=<?php echo $order['order_id'];?>">订单付款详情 &gt;&gt;</a></font> </li>
          <li class="horizontal"></li>
          
          <?php if($order['state']>381){?>
          <li>订单已经完成支付</li>
          <?php }?>
        </ul>
      </div>
    </form>
  </div>



  <?php if(1){?>

  <form method="post" action="https://api.teegon.com/charge/pay" onsubmit="" id="topup_form" target="_blank">
    <input type="hidden" name="order_no" id="order_no" value="">
    <input type="hidden" name="subject" id="subject" value="">
    <input type="hidden" name="metadata" id="metadata" value="">
    <input type="hidden" name="client_ip" id="client_ip" value="">
    <input type="hidden" name="return_url" id="return_url" value="">
    <input type="hidden" name="notify_url" id="notify_url" value="">
    <input type="hidden" name="sign" id="sign" value="">
    <input type="hidden" name="client_id" id="client_id" value="">
    <input type="hidden" name="amount" id="amount" value="<?php  echo $order['price'];?>" />
    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
    <div class="topup">
      <div class="topupdiv method" style="height:auto;"> <span>支付方式：</span>
        <div class="text">
          <label>
            <input type="radio" name="channel" value="alipay" checked="checked" class="dxk"/>
            <img class="pay" src="<?php echo APP_ROOT.'images/alipay.png'?>" /> </label>
          <label>
            <input type="radio" name="channel" value="wxpay"  class="dxk"/>
            <img class="pay" src="<?php echo APP_ROOT.'images/wxpay.png'?>" /> </label>
          <label>
            <input type="radio" name="channel" value="unionpay"  class="dxk"/>
            <img class="pay" src="<?php echo $site['yl_img'];?>" /> </label>
          <label>
            <input type="radio" name="channel" value="cash"  class="dxk"/>
            <img class="pay" src="<?php echo $site['fk_img'];?>" /> </label>
      </div>
      <div style="clear:both;"></div>
      <div class="yinl" style="display:none;padding: 10px 0;"><?php echo $site['yl_intro']; ?></div>
      <div style="clear:both;"></div>
      <div class="topupdiv">
        <input type="button" class="submit" value="确认支付" onclick="online_pay()">
        <input type="submit" class="submit" id="submit1" value="确认支付" style="display:none;">
      </div>
    </div>
  </form>
  <?php }?>
</div></form></div></div></div>

<script type="text/javascript">
    $(".dxk").click(function(){ 
        if($("input[type='radio']:checked").val()=='unionpay'){
            // $(".yinl").show();
        }else{
            $(".yinl").hide();
        }
        if($("input[type='radio']:checked").val()=='cash'){
            $("#topup_form").attr("action","index.php?m=order&a=order_pay");
            $("#topup_form").attr("target","");
            $(".submit").hide();
            $("#submit1").show();
        }else{
            $("#topup_form").attr("action","https://api.teegon.com/charge/pay");
        }
    });
    // $("#submit1").click(function(){
    //     $("#topup_form").submit();
    // });
</script>
<script>


function online_pay()
{
  /*
  var html=ajax_load("index.php?m=order&a=online_pay&amount="+$("#amount").val()+"&channel="+$('input[name="channel"]:checked').val()+"&order_id="+$("#order_id").val());   
  var json_obj = jQuery.parseJSON(html);
  $.each(json_obj, function(i, n){
    $("#"+i).val(n);
  });
  $("#topup_form").submit();
  */
    if($('input[name="channel"]:checked').val()=='alipay')
    {
        window.open("index.php?m=alipay&a=alipay&order_id=<?php echo $order_id?> ");
    }
    else if($('input[name="channel"]:checked').val()=='wxpay')
    {
        var html=ajax_load("index.php?m=wxpay&a=qrcode_pay&order_id=<?php echo $order_id?>");
        $(document.body).append('<div id="divLock" class="divLock"><iframe style="position:absolute;width:100%;height:100%;_filter:alpha(opacity=0);opacity=0;border-style:none;"></iframe></div>');
        $("#weixin_bg").show();
        $(".qrcode").html(html);
        setInterval("order_state()",3000);
    }
    else if($('input[name="channel"]:checked').val()=='unionpay')
    {
        $(document.body).append('<div id="divLock" class="divLock"><iframe style="position:absolute;width:100%;height:100%;_filter:alpha(opacity=0);opacity=0;border-style:none;"></iframe></div>');
        $("#weixinalert").show();

         $.ajax({
              url : "index.php?m=order&a=bank&id=<?php echo $order_id ?>",  
               type : "GET",  
               success : function(data) {  
                
               }
              });


    }
    else if($('input[name="channel"]:checked').val()=='cash')
    {
        $("#topup_form").attr("action","index.php?m=order&a=order_pay");
        $("#topup_form").attr("target","");
        $("#topup_form").submit();
    }
}

function order_state()
{
  $.ajax({
  type: "post",
  url: "index.php?m=wxpay&a=order_state&order_id=<?php echo $order_id?>",
  data: "",
  dataType:"html",
  success: function(html){
      if(html>381)goto_url('index.php?m=order&a=index&state=382');
    }
  });
}

</script>


<div id="weixin_bg" style="">

  <div class="weixin_top"><span><img src="<?php echo APP_ROOT;?>images/weixin_gb.png" onclick="goto_url('');" /></span><dt>微信支付</dt></div>
  <div class="weixin_zuo fl" style="float:left;"><em class="qrcode"><img src="<?php echo APP_ROOT;?>images/weixin_ma.jpg"/></em>
    <div class="weixin_zuo_di"><span><img src="<?php echo APP_ROOT;?>images/weixin_shao.png"/></span><dt><strong>请使用微信扫一扫<br />扫描二维码支付</strong></dt>
    </div>
  </div>
  <div class="weixin_you fr" style="float:right;"><img src="<?php echo APP_ROOT;?>images/weixin_iphone.jpg" /></div>
  <div style="clear:both;"></div>

  <a class="weixin_choose" href="javascript:goto_url('');">< 请选择其他支付方式</a>
  
  <br />
    <br />
      <br />
</div>


<div id="weixinalert" style="">
<div class="weixin_top" style="background:none;"><span><img src="<?php echo APP_ROOT;?>images/weixin_gb.png" onclick="goto_url('');" /></span></div>
<?php echo $site['yl_intro'];?>
</div>


<script>
  //判断是否微信登陆
function isWeiXin() {
var ua = window.navigator.userAgent.toLowerCase();
console.log(ua);//mozilla/5.0 (iphone; cpu iphone os 9_1 like mac os x) applewebkit/601.1.46 (khtml, like gecko)version/9.0 mobile/13b143 safari/601.1
if (ua.match(/MicroMessenger/i) == 'micromessenger') {
return true;
} else {
return false;
}
}

</script>
<?php require APP_ROOT.'public/bottom.php';?>



