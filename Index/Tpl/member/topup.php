<?php require APP_ROOT.'public/member_top.php';?>

<div class="bg">
  <div class="member_main">
    <?php require APP_ROOT.'public/member_left.php';?>
    <div class="member_right">
      <div class="title">
        <h3>在线充值</h3>
      </div>
      <form method="post" action="https://api.teegon.com/charge/pay" onsubmit="" id="topup_form" target="_blank">
        <input type="hidden" name="order_no" id="order_no" value="">        
        <input type="hidden" name="subject" id="subject" value="">
        <input type="hidden" name="metadata" id="metadata" value="">
        <input type="hidden" name="client_ip" id="client_ip" value="">
        <input type="hidden" name="return_url" id="return_url" value="">
        <input type="hidden" name="notify_url" id="notify_url" value="">
        <input type="hidden" name="sign" id="sign" value="">
        <input type="hidden" name="client_id" id="client_id" value=""> 
         <div class="topup">
          <div class="topupdiv"><span>账户余额：</span>
            <div class="text">
              <p><em class="price">￥<?php echo $balance;?></em></p>
            </div>
          </div>
          <div class="topupdiv"> <span>充值金额：</span>
            <div class="text">
              <p>
                <input name="amount" type="text" id="amount" onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" maxlength="10"  onafterpaste="this.value=this.value.replace(/[^0-9.]/g,'')" placeholder="充值金额" />
                元 </p>
              <div class="check" id="prompt_price"></div>
            </div>
          </div>
          <div class="topupdiv method"> <span>支付方式：</span>
            <div class="text">
              <label>
                <input type="radio" name="channel" value="alipay" checked="checked" />
                <img class="pay" src="<?php echo APP_ROOT.'images/alipay.png'?>" /> </label>
                
                <label>
                <input type="radio" name="channel" value="wxpay" />
                <img class="pay" src="<?php echo APP_ROOT.'images/wxpay.png'?>" /> </label>
              
              <label>
                <input type="radio" name="channel" value="chinapay_b2c" />
                <img class="pay" src="<?php echo APP_ROOT.'images/chinapay_b2c.png'?>" /> </label>

            </div>
          </div>
          <div class="topupdiv">
            <input type="button" class="submit" value="充值" onclick="topup()">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
function topup()
{
	if($("#amount").val()=='')
	{
		$('#prompt_price').css('display','block').html('<i class="price_i error"></i><em class="red">&nbsp;请输入充值金额</em>');
		return false;
	}
	else
	{
		var html=ajax_load("index.php?m=member&a=topup_pay&amount="+$("#amount").val()+"&channel="+$('input[name="channel"]:checked').val());		
		var json_obj = jQuery.parseJSON(html);
		$.each(json_obj, function(i, n){
			$("#"+i).val(n);
		});
		$("#topup_form").submit();
	}
}
</script>
<?php require APP_ROOT.'public/bottom.php';?>
