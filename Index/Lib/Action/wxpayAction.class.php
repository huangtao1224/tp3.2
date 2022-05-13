<?php
// 本类由系统自动生成，仅供测试用途
class wxpayAction extends Action {
    public function index()
	{
		$this->display();
    }
	
	//JS支付地址
	public function pay()
	{
		$this->display();
    }

    //扫码支付地址
	public function natpay()
	{
		$this->display();
    }
    
	//获取支付结果,更新数据库
    public function payact()
	{
		$id=md5('order_id'.date('Ym'));
		$order_id=$_GET[$id];

		//判断为空退出
		if(empty($order_id)){
			exit;
		}
		//查询数据库
		$info=M('order')->field('state')->where('order_id='.$order_id)->find();

		//订单状态大于1 退出
		if($info['state']>261){
			exit;
		}
		//更新状态为2已付款
		$data['state']=262;
		$cnt=M('order')->where('order_id='.$order_id)->save($data);
		
    }
	
	//用户充值页面
    public function mem_acc()
	{
		
		$id=md5('mem_acc_id'.date('Ym'));
		$mem_acc_id=$_GET[$id];
		//判断为空退出
		if(empty($mem_acc_id)){
			exit;
		}
		//查询数据库
		
		$info=M('member_account')->field('state')->where('member_account_id='.$mem_acc_id)->find();

		//订单状态大于1 退出
		if($info['state']==2){
			exit;
		}
		//更新状态为2已付款
		$data['state']=2;
		$cnt=M('member_account')->where('member_account_id='.$mem_acc_id)->save($data);
		
    }

    
	/**
	 * 查询订单号生成二维码
	 * @param  string $url    请求URL
	 * @return array  $data   响应数据
	 */
		
	public function qrcode_pay()
	{

		
		$order_id=pg('order_id');

		// echo $order_id;exit;
		ini_set('date.timezone','Asia/Shanghai');
		//引入类

		vendor('Wxpay.lib.WxPay#Exception');
		vendor('Wxpay.lib.WxPay#Data');
		vendor('Wxpay.lib.WxPay#config');
		vendor('Wxpay.lib.WxPay#Api');

		vendor('Wxpay.example.WxPay#NativePay');
		vendor('Wxpay.example.log');
		vendor('Wxpay.example.phpqrcode.phpqrcode');
	
		define('APPID','wxf15efb39c16f76b2');//商户API密钥
		define('MCHID','1502810471');//微支付商户号
		define('KEYS','fb8e938b95ad09bf2a52122144ab847a');//API密钥



		//获取订单号信息
		$order_list=M('order')->where(array('order_id'=>$order_id))->find();
		$order_goods=M('order')->where(array('order_id'=>$order_id))->find();
		
		//②、统一下单
		$input = new WxPayUnifiedOrder();
		$input->SetAppid(APPID);
		$input->SetMch_id(MCHID);

		$input->SetBody('商品');
		$input->SetAttach($order_id);
		// $input->SetAttach('110110110');
		$input->SetOut_trade_no(MCHID.date("YmdHis"));
		$input->SetTotal_fee($order_list['price']*100); //价格传入
		// $input->SetTotal_fee("1");
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("test");
		$input->SetNotify_url("http://szshijue.com/wxpay/notice.php");
		$input->SetTrade_type("NATIVE");
		$input->Setproduct_id($order_id);
		$rs = WxPayApi::unifiedOrder($input);


		
		$link_url=$rs['code_url'];
		if(empty($link_url)){
			return 'FAIL';
		}
	
		$dir='./Uploads/imgaes/'.date('Y/m/d');
		if(!is_dir($dir)){
			mkdir($dir,'0777',true);
		}
		$qrcode=$dir.'/'.$order_list['order_number'].'.png';
		QRcode::png($link_url,$qrcode,'M',5.7,3);
		echo '<img src="'.$qrcode.'" />';
	}

	

	public function order_state()
	{
		$order_id=pg('order_id');
		echo M('order')->where(array('order_id'=>$order_id))->getfield('state');
	}

	public function http_post($url, $xml_data) {
	
	$header = "Content-type: text/xml";//定义content-type为xml
	$ch = curl_init(); //初始化curl
	curl_setopt($ch, CURLOPT_URL, $url);//设置链接
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//设置HTTP头
	curl_setopt($ch, CURLOPT_POST, 1);//设置为POST方式
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//设置为POST方式
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//设置为POST方式

	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);//POST数据

	$response = curl_exec($ch);//接收返回信息
	if(curl_errno($ch)){//出错则显示错误信息
		return curl_error($ch);
	}
	curl_close($ch); //关闭curl链接
	return $response;//显示返回信息
	}


	public function web_pay(){

		$order_id=pg('order_id');
		$order_list=M('order')->where(array('order_id'=>$order_id))->find();

		// print_r($order_list);
		 // echo M()->getlastsql();
		 // exit;

		$order_goods=M('order')->where(array('order_id'=>$order_id))->find();
		$appid="wxa2f6d82a316cba3b";
		$mch_id="1494683982";
		$key="CYcy1181181181181181181181181181";
		$subject=$order_goods['goods_name']; 
        $total_amount=$order_list['price']*100; //金额
        $additional=$order_id; ////附加数据
        $nonce_str=MD5(time());//随机字符串
        $spbill_create_ip=get_client_ip(); //终端ip
		//以上参数接收不必纠结，按照正常接收就行，相信大家都看得懂

      
        $trade_type='MWEB';//交易类型 具体看API 里面有详细介绍

        $notify_url="http://".$_SERVER['SERVER_NAME']."/wxpay/notice.php"; //回调地址

        $scene_info='{"h5_info":{"type":"Wap","wap_url":"http://www.3hutao.com","wap_name":"享胡桃"}}'; //场景信息
        //对参数按照key=value的格式，并按照参数名ASCII字典序排序生成字符串
    
		$data=array(
			'appid'=>$appid,
			'mch_id'=>$mch_id,
			'nonce_str'=>$nonce_str,
			'body'=>'goods',
			'attach'=>$order_id,
			'out_trade_no'=>$order_id,
			'total_fee'=>$total_amount,
			'spbill_create_ip'=>$spbill_create_ip,
			'notify_url'=>$notify_url,
			'scene_info'=>$scene_info,
			'trade_type'=>$trade_type ,
			);
		ksort($data);

		$signA="";
		foreach ($data as $k => $v) {
			$signA.=$k."=".$v."&";

		}

		// print_r($data);


        $strSignTmp = $signA."key=$key"; //拼接字符串



        $sign = strtoupper(MD5($strSignTmp)); // MD5 后转换成大写
 // echo $sign;
        $post_data = "<xml>
			<appid>$appid</appid>
			<attach>$order_id</attach>
			<body>goods</body>
			<mch_id>$mch_id</mch_id>
			<nonce_str>$nonce_str</nonce_str>
			<notify_url>$notify_url</notify_url>
			<out_trade_no>$order_id</out_trade_no>
			<scene_info>$scene_info</scene_info>
			<spbill_create_ip>$spbill_create_ip</spbill_create_ip>
			<total_fee>$total_amount</total_fee>
			<trade_type>MWEB</trade_type>
			<sign>$sign</sign>
			</xml>";

        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";//微信传参地址
        $dataxml = $this->http_post($url,$post_data); //后台POST微信传参地址  同时取得微信返回的参数，http_post方法请看下文




        // print_r($dataxml);
        $objectxml = (array)simplexml_load_string($dataxml, 'SimpleXMLElement', LIBXML_NOCDATA); //将微信返回的XML 转换成数组

        if($objectxml['return_code'] == 'SUCCESS')  {
            if($objectxml['result_code'] == 'SUCCESS'){//如果这两个都为此状态则返回mweb_url，详情看‘统一下单’接口文档

            	$arr['msg']='ok';
            	$arr['url']=$objectxml['mweb_url']; //mweb_url是微信返回的支付连接要把这个连接分配到前台
                echo  json_encode($arr);
            }
            if($objectxml['result_code'] == 'fail'){
				$arr['msg']='fail';
            	$arr['url']=$objectxml['err_code_des']; //mweb_url是微信返回的支付连接要把这个连接分配到前台
                echo  json_encode($arr);
 

            }}
}
	
	
	
	
	
}