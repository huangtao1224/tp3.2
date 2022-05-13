<?php 

    //dump($_SERVER);

    $order_id= pg('order_id')?pg('order_id'):end(explode('/',$_SERVER['PATH_INFO']));

    //echo $order_id;exit;

    $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx0b2f1bb78b68bbf6&redirect_uri=http://".$_SERVER['HTTP_HOST']."/index.php?m=weixin&a=index&response_type=code&scope=snsapi_userinfo&state=".$order_id."#wechat_redirect";



    //print_r($order_id);exit;

    $openid=session('openid');



    //dump($openid);die();

    if(!$openid){

    	header("location:".$url);exit;

    }

      



ini_set('date.timezone','Asia/Shanghai');



vendor('Wxpay.lib.WxWay#Exception');

vendor('Wxpay.lib.WxWay#Data');

vendor('Wxpay.lib.WxWay#config');

vendor('Wxpay.lib.WxWay#Api');

vendor('Wxpay.example.WxPay#JsApiPay');

vendor('Wxpay.example.log');



// 初始化日志

// $logHandler= new CLogFileHandler(APP_PATH.'wxpay/logs/'.date('Y-m-d').'.log');

// $log = wxLog::Init($logHandler, 15);



$order_list=M('order')->where(array('order_id'=>$order_id))->find();

if($order_list['pay_state']>1){
	header('location:http://'.$_SERVER['HTTP_HOST'].'/index.php');
	exit();

}



//①、获取用户openid

$tools = new JsApiPay();

// $openId = $tools->GetOpenid();



//②、统一下单

$input = new WxPayUnifiedOrder();



$goods_name=M('goods')->where('goods_id='.$order_list['goods_id'])->getField('goods_name');

     

$input->SetBody($goods_name);

$input->SetAttach($order_list['order_number']);

// $input->SetAttach('110110110');

$input->SetOut_trade_no($order_list['order_number']);

$input->SetTotal_fee(($order_list['price']*$order_list['number']+$order_list['freight'])*100); //价格传入

//$input->SetTotal_fee("1");

$input->SetTime_start(date('YmdHis',time()));

$input->SetTime_expire(date('YmdHis',time()) + 600);

$input->SetGoods_tag("goods");

$input->SetNotify_url("http://".$_SERVER['HTTP_HOST']."/wxpay/notice.php");

$input->SetTrade_type("JSAPI");

$input->SetOpenid($openid);

$order = WxPayApi::unifiedOrder($input);

// echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';

// print_r($order);

//print_r($order);

//exit;

$jsApiParameters = $tools->GetJsApiParameters($order);





//获取共享收货地址js函数参数

// $editAddress = $tools->GetEditAddressParameters();



//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php

/**

 * 注意：

 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功

 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，

 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）

 */

?>



<html>

<head>

    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>

    <meta name="viewport" content="width=device-width, initial-scale=1"/> 

    <title>微信支付</title>

    <script type="text/javascript" src="https://apps.bdimg.com/libs/jquery/1.6.4/jquery.min.js"></script>

    <script type="text/javascript">

	//调用微信JS api 支付

	function jsApiCall()

	{

		WeixinJSBridge.invoke(

			'getBrandWCPayRequest',

			<?php echo $jsApiParameters; ?>,

			function(res){

				WeixinJSBridge.log(res.err_msg);

				// var msg=jQuery.parseJSON(res.err_msg);

				if(res.err_msg=='get_brand_wcpay_request:ok'){

					location.href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/index.php";

				}else{

					location.href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/index.php";

				}

			}

		);

	}



	function callpay()

	{

		if (typeof WeixinJSBridge == "undefined"){

		    if( document.addEventListener ){

		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);

		    }else if (document.attachEvent){

		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 

		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);

		    }

		}else{

		    jsApiCall();

		}

	}

	callpay();

	</script>

	<script type="text/javascript">

	//获取共享地址

	function editAddress()

	{

		WeixinJSBridge.invoke(

			'editAddress',

			<?php echo $editAddress; ?>,

			function(res){

				var value1 = res.proviceFirstStageName;

				var value2 = res.addressCitySecondStageName;

				var value3 = res.addressCountiesThirdStageName;

				var value4 = res.addressDetailInfo;

				var tel = res.telNumber;

				

				alert(value1 + value2 + value3 + value4 + ":" + tel);

			}

		);

	}

	

	window.onload = function(){

		if (typeof WeixinJSBridge == "undefined"){

		    if( document.addEventListener ){

		        document.addEventListener('WeixinJSBridgeReady', editAddress, false);

		    }else if (document.attachEvent){

		        document.attachEvent('WeixinJSBridgeReady', editAddress); 

		        document.attachEvent('onWeixinJSBridgeReady', editAddress);

		    }

		}else{

			editAddress();

		}

	};

	</script>

</head>

<body>

    <!-- <br/>

    <font color="#9ACD32"><b>该笔订单支付金额为<span style="color:#f00;font-size:20px"><?php echo $order_list['price']; ?>元</span>钱</b></font><br/><br/>

	<div align="center">

		<button style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onClick="callpay()" >立即支付</button>

	</div> -->

</body>

</html>