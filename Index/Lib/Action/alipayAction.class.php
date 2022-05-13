<?php

// 支付宝支付
class alipayAction extends Action {
	public $alipay_config;	
	public function __construct(){
		vendor('Alipay.core');
        vendor('Alipay.md5');
        vendor('Alipay.notify');
        vendor('Alipay.submit');
		
		$this->alipay_config=array(
        'partner' =>'2088021059321304',   //这里是你在成功申请支付宝接口后获取到的PID；
        'key'=>'suk9nn6ao5fqplmdywevymij548nzzwo',//这里是你在成功申请支付宝接口后获取到的Key
        'sign_type'=>strtoupper('MD5'),
        'input_charset'=> strtolower('utf-8'),
        'cacert'=> getcwd().'\\alipay\cacert.pem',
        'transport'=> 'http',
        'seller_id'=>'2088021059321304',
        'payment_type'=> "1",
        'service' => "create_direct_pay_by_user",
        'notify_url'=> "http://".$_SERVER['HTTP_HOST'].__ROOT__."/index.php/alipay/notify",
        'return_url'=> "http://".$_SERVER['HTTP_HOST'].__ROOT__."/index.php/alipay/link_return",
      );
	}


		public function mobile(){
		$order_id=pg('order_id');
		$order_list=M('order')->where(array('order_id'=>$order_id))->find();

        //商户订单号，商户网站订单系统中唯一订单号，必填
        $WIDout_trade_no = $order_list['order_id'];

        //订单名称，必填
        $WIDsubject = $order_list['order_number'];

        //付款金额，必填
        $total_amount = $order_list['price']+$order_list['freight'];

        //商品描述，可空
        $WIDbody = 'goods';


        header('location:zhifubao/wappay/pay.php?WIDout_trade_no='. $WIDout_trade_no.'&WIDsubject='.$WIDsubject.'&WIDtotal_amount='.$total_amount.'&WIDbody='.$WIDbody);



		
	}

	public function pay(){
    	$order_id=pg('order_id');
		session('order_id',$order_id);
		$alipay_config=$this->alipay_config;
		$order_list=M('order')->where(array('order_id'=>$order_id))->find();

        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $order_list['order_id'];

        //订单名称，必填
        $subject = $order_list['order_number'];

        //付款金额，必填
        $total_fee = $order_list['price'];

        //商品描述，可空
        $body = $_POST['WIDbody'];

		//构造要请求的参数数组，无需改动
		$parameter = array(
			"service"       => $alipay_config['service'],
			"partner"       => $alipay_config['partner'],
			"seller_id"     => $alipay_config['seller_id'],
			"payment_type"	=> $alipay_config['payment_type'],
			"notify_url"	=> $alipay_config['notify_url'],
			"return_url"	=> $alipay_config['return_url'],
			'extra_common_param'=>$out_trade_no,
			"out_trade_no"	=> $order_id,
			"subject"	=> $subject,
			"total_fee"	=> $total_fee,
			"body"	=> $body,
			"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);

		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
		echo $html_text;
    }
   
	// 异步通知页面
	public function notify()
	{
			//创建文件夹
		/*
		$dir='./alipay/'.date('Y/m');
		if(!is_dir($dir)){
			mkdir($dir,'0777',true);
		}
		*/
		
		$alipay_config=$this->alipay_config;
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();

		if($verify_result) {//验证成功

		

		//商户订单号
		$out_trade_no = $_POST['out_trade_no'];

		//支付宝交易号
		$trade_no = $_POST['trade_no'];

		//交易状态
		$trade_status = $_POST['trade_status'];

			//生成日志
		//file_put_contents($dir.'/'.date('d').'log.txt', date('H:i:s')." ".$out_trade_no."\r\n", FILE_APPEND);

		

	    if($_POST['trade_status'] == 'TRADE_FINISHED') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
			//如果有做过处理，不执行商户的业务程序
				
		//注意：
		//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
	    }
	    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
			//查询数据库
			$info=M('order')->field('state')->where('order_id='.$out_trade_no)->find();
	
			//订单状态大于1 退出
			if($info['state']>1){
				exit('success');
			}
			//更新状态为262已付款
			$data['state']=2;
			$cnt=M('order')->where('order_id='.$out_trade_no)->save($data);
	    }

		//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	        
		echo "success";		//请不要修改或删除
			
		}
		else {
		    //验证失败
		    echo "fail";

		    //调试用，写文本函数记录程序运行情况是否正常
		    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}
    }

    // 同步通知页面
	public function link_return()
	{
		goto_url(__ROOT__.'/index.php?m=order&a=pay_success&order_id='.session('order_id'));
		// $this->display();
    }
}

?>