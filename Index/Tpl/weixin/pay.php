<?php


		$type_arr=explode('-',$_GET['_URL_'][2]);

		// print_r($type_arr);exit;
		// echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		if($type_arr[0]=='order_id')
		{
			$order_id=$type_arr[1];
			$list=M('order')->where(array('order_id'=>$order_id))->find();
			if($list['state']>381){
				header('location:http://'.HTTP_HOST.'/index.php?m=member&a=index');
			exit;} ?>
	
		<?php
			if($order_id=='')$order_id=pg('order_id');
				$order_list=M('order')->where(array('order_id'=>$order_id))->find();
				$wxpay=M('wxpay')->where(array('wxpay_id'=>1))->find();
				$member=session('member');
				define('APPID',$wxpay['appid']);//商户API密钥
				define('MCHID',$wxpay['mchid']);//微支付商户号
				define('KEYS',$wxpay['apikey']);//API密钥
				
				

		//微信支付
		ini_set('date.timezone','Asia/Shanghai');
		vendor('Wxpay.example.WxPay#JsApiPay');
		// 初始化日志
		$logHandler= new CLogFileHandler('./wxpay/logs/'.date('Y-m-d').'.log');
		$log = wxLog::Init($logHandler, 15);
		//print_r($member);
		
		//①、获取用户openid
		$openid=$member['openid'];
		$tools = new JsApiPay();
		$openid = $tools->GetOpenid();

		//②、统一下单
		$input = new WxPayUnifiedOrder();
		$input->SetBody('订单号：'.$order_list['order_number']);//备注
		$input->SetAttach($order_id);//member_account_id
		$input->SetAppid(APPID);//商户APPID
		$input->SetMch_id(MCHID);//商户号
		// $input->SetAttach('110110110');
		$input->SetOut_trade_no($wxpay['mchid'].date("YmdHis"));
		$input->SetTotal_fee($order_list['price']*100); //价格传入
		// $input->SetTotal_fee("1");
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("test");
		$input->SetNotify_url("http://".HTTP_HOST."/wxpay/notice.php");
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openid);
		
		
		$order = WxPayApi::unifiedOrder($input);
		//print_r($order);
		// printf_info($order);
		$jsApiParameters = $tools->GetJsApiParameters($order);
		$jsda['orderid']=$order_id;
		$jsda['jsApiParameters']=$jsApiParameters;
	}else{
			$member=session('member');
			$balance = M('account')->where(array('member_id'=>$member['member_id']))->order('account_id desc')->getField('balance');
			$price =$type_arr[1];
			$data['balance']=$balance+$price;//余额累加
			$data['amount']=$price;
			$data['member_id']=$member['member_id'];
			$data['amount_type']=426;
			$data['state']=431;
			$data['date']=time();
			$data['note']=cover_time(time(),'Y-m-d H:i:s').'充值'.$price.'元';
			$data['website_id']=1;
			$id = M('account')->data($data)->add();

		
		$wxpay=M('wxpay')->where(array('website_id'=>1))->find();
	
		
		define('APPID',$wxpay['appid']);//商户API密钥
		define('MCHID',$wxpay['mchid']);//商户API密钥
		define('KEYS',$wxpay['keys']);//商户API密钥
		
		//微信支付
		ini_set('date.timezone','Asia/Shanghai');
		vendor('Wxpay.example.WxPay#JsApiPay');
		// 初始化日志
		$logHandler= new CLogFileHandler('./wxpay/logs/'.date('Y-m-d').'.log');
		$log = wxLog::Init($logHandler, 15);
		//print_r($member);
		
		//①、获取用户openid
		$openid=$member['openid'];
		$tools = new JsApiPay();
		//$openid = $tools->GetOpenid();

		//②、统一下单
		$input = new WxPayUnifiedOrder();
		$input->SetBody('在线充值');//备注
		$input->SetAttach($id);//member_account_id
		$input->SetAppid(APPID);//商户APPID
		$input->SetMch_id(MCHID);//商户号		
		// $input->SetAttach('110110110');
		$input->SetOut_trade_no($wxpay['mchid'].date("YmdHis"));
		$input->SetTotal_fee($price*100); //价格传入
		// $input->SetTotal_fee("1");
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("在线充值");
		$input->SetNotify_url("http://".HTTP_HOST."/wxpay/chong.php");
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openid);
		
		
		$order = WxPayApi::unifiedOrder($input);
		//print_r($order);
		// printf_info($order);
		$jsApiParameters = $tools->GetJsApiParameters($order);
		$jsda['orderid']=$order_id;
		$jsda['jsApiParameters']=$jsApiParameters;





	}
?>
    <script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				WeixinJSBridge.log(res.err_msg);
				<?php if($type_arr[0]=='order_id'){?>
					window.location='<?php echo 'http://'.HTTP_HOST.'/index.php?m=order';?>';
				<?php }?>
				//alert(res.err_code+','+res.err_desc+','+res.err_msg);
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
