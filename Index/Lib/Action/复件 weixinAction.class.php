<?php
class weixinAction extends Action {

	private $appid='wx843aed193e9f4dbe';
	private $appsecret='dd3d930205bd07cb676d6dd1e64f09dd';
	public function index()
	{
		vendor('Wxpay.example.phpqrcode.phpqrcode');

		$code=pg('code');
		$state=pg('state');
		if($state=='member'){
			$state=1;
		}
		$member=array();
		if($code!='' && empty($member))
		{
			//通过code换取网页授权access_token
			$url='https://api.weixin.qq.com/sns/oauth2/access_token';
			$weixin=M('weixin')->where(array('website_id'=>WEBSITE_ID))->find();
			$data=array('appid'=>$weixin['appid'],'secret'=>$weixin['appsecret'],'code'=>$code,'grant_type'=>'authorization_code');

			$httpstr = curl_http($url, $data, 'GET', array("Content-type: text/html; charset=utf-8"));
			
			$httpstr_arr=object_array(json_decode($httpstr));
			// file_put_contents('./a.txt',$httpstr_arr['access_token']);
			
			
			//获取微信用户信息
			$url='https://api.weixin.qq.com/sns/userinfo';
			$data=array('openid'=>$httpstr_arr['openid'],'access_token'=>$httpstr_arr['access_token']);
			$wxinfostr = curl_http($url, $data, 'GET', array("Content-type: text/html; charset=utf-8"));


			$wxinfo_arr=object_array(json_decode($wxinfostr));
			// $wxinfo_arr['date']=time();
			$wxinfo_arr['website_id']=$state;
			
			$member=M('member')->where(array('yong_code'=>$httpstr_arr['openid'],'website_id'=>$state))->find();
			if(empty($member))
			{		
				$member_id=M('member')->add($wxinfo_arr);

				
				$save['yong_code']=$httpstr_arr['openid'];


			
			

				$yong=M('link_yong')->where(array('yong_code'=>$httpstr_arr['openid'],'website_id'=>$state))->find();

				$save['yong_shang']=$yong['yong_shang'];



			
				$save['website_id']=$state;





				M('member')->where(array('member_id'=>$member_id,'website_id'=>$state))->save($save);


				$member=M('member')->where(array('openid'=>$httpstr_arr['openid'],'website_id'=>$state))->find();

				

		

	
			}

			else
			{

				
				

				M('member')->where(array('yong_code'=>$httpstr_arr['openid'],'website_id'=>$state))->save($wxinfo_arr);

				$member=M('member')->where(array('openid'=>$httpstr_arr['openid'],'website_id'=>$state))->find();

				
			}


			
				
			
			
			session('member',$member);



			$demo=M('demo')->where(array('num'=>$state))->find();

			$url="location:".$demo['lian']."?&member_id=".$member['member_id'];

			// echo $url;exit;
			if(pg('state')=='member'){
				goto_url('index.php?m=member&a=index');
			}else{

			header($url);
			}
			
		}
	}
	

	



	// public function curl($url,$https=true,$method='GET',$data=NULL)
	// {
	// 	$ch=curl_init();
	// 	curl_setopt($ch,CURLOPT_URL,$url);
	// 	curl_setopt($ch,CURLOPT_HEADER,false);
	// 	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	// 	if($https)
	// 	{
	// 		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	// 		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,true);
	// 	}
	// 	if($method=='POST')
	// 	{
	// 		curl_setopt($ch,CURLOPT_POST,true);
	// 		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
	// 	}
	// 	$content=curl_exec($ch);
	// 	curl_close($ch);
	// 	return $content;
	// }
	
	public function payact()
	{

		$order_id=pg('order_id');
		//判断为空退出
		if(empty($order_id)){
			exit;
		}
		//查询数据库
		$order=M('order')->where(array('order_id'=>$order_id))->find();

		//订单状态大于381退出
		if($order['state']>381){
			exit;
		}
		//更新状态为382已付款
		$data['state']=382;
		$data['pay_type']=550;	
		M('order')->where('order_id='.$order_id)->save($data);//改变订单状态

		$member=M('member')->where(array('member_id'=>$order['member_id']))->find();

		// 三级分销
		// 
		$link_fen=C('link_fen_config');
		
		// 1级增加拥金
		
		if($member['yong_shang']!=''){ 
		$two=M('link_yong')->where(array('yong_code'=>$member['yong_shang'],'website_id'=>$member['website_id']))->find();
		$fan=round($order['price']*$link_fen['fen_one']/100,2);
		$save['yong_yu']=$two['yong_yu']+$fan;
		
		M('link_yong')->where(array('yong_code'=>$member['yong_shang'],'website_id'=>$member['website_id']))->save($save);
		// $yong=M('link_yong')->where(array('yong_code'=>$member['yong_code']))->save($save);

		$ting['ting_yu']=$two['yong_yu']+$fan;
		$ting['ting_name']=$member['yong_shang'];
		$ting['ting_bei']="返利".$fan."元";
		$ting['ting_jiao']=$fan;
		$ting['ting_type']="佣金返利";
		$ting['ting_state']="交易成功";
		$ting['date']=time();
		$ting['website_id']=$member['website_id'];
		M('ting_yong')->add($ting);

		// 2级增加拥金
		$one=M('member')->where(array('yong_code'=>$member['yong_shang'],'website_id'=>$member['website_id']))->find();
		

			if($one['yong_shang']!=''){ 
			$yu=M('link_yong')->where(array('yong_code'=>$one['yong_shang'],'website_id'=>$member['website_id']))->find();
			$fan2=round($order['price']*$link_fen['fen_two']/100,2);
			$save2['yong_yu']=$yu['yong_yu']+$fan2;
			

			M('link_yong')->where(array('yong_code'=>$one['yong_shang'],'website_id'=>$member['website_id']))->save($save2);
			
		$ting2['ting_yu']=$yu['yong_yu']+$fan2;
		$ting2['ting_name']=$one['yong_shang'];
		$ting2['ting_bei']="返利".$fan2."元";
		$ting2['ting_jiao']=$fan2;
		$ting2['ting_type']="佣金返利";
		$ting2['ting_state']="交易成功";
		$ting2['date']=time();
		$ting2['website_id']=$member['website_id'];
		M('ting_yong')->add($ting2);
			// 3级增加拥金
			$three=M('member')->where(array('yong_code'=>$one['yong_shang'],'website_id'=>$member['website_id']))->find();
		

			if($three['yong_shang']!=''){ 
			$yu2=M('link_yong')->where(array('yong_code'=>$three['yong_shang'],'website_id'=>$member['website_id']))->find();
			$fan3=round($order['price']*$link_fen['fen_three']/100,2);
			$save3['yong_yu']=$yu2['yong_yu']+$fan3;
			

			M('link_yong')->where(array('yong_code'=>$three['yong_shang'],'website_id'=>$member['website_id']))->save($save3);

			$ting3['ting_yu']=$yu2['yong_yu']+$fan3;
			$ting3['ting_name']=$three['yong_shang'];
			$ting3['ting_bei']="返利".$fan3."元";
			$ting3['ting_jiao']=$fan3;
			$ting3['ting_type']="佣金返利";
			$ting3['ting_state']="交易成功";
			$ting3['date']=time();
			$ting3['website_id']=$member['website_id'];
			M('ting_yong')->add($ting3);
			}

			}
		}



		
	 //  //微信公众号消息推送
	 //  
		$openid=$member['openid'];

		// $tempk='OPENTM202183094';
		$requestUrl="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->getAccessToken();

		$url='http://'.HTTP_HOST.'/index.php?m=order&a=index';

		$template_id="kYF4-WSOr9LT6rx2iYUpRU_UEylCkfVNZ-1FMz9GEQg";
		$goods=M('order_goods')->where(array('order_id'=>$order_id))->find();
		 $data= '{
           "touser":"'.$openid.'",
           "template_id":"'.$template_id.'",
           "url":"'.$url.'",            
           "data":{
                   "first": {
                       "value":"您好!'.$member['nickname'].'您购买的商品已经支付成功",
                       "color":"#173177"
                   },
                   "keyword1":{
                       "value":"￥'.$order['price'].'",
                       "color":"#173177"
                   },
                   "keyword2": {
                       "value":"'.$goods['goods_name'].'",
                       "color":"#173177"
                   },
                   "keyword3": {
                       "value":"微信支付",
                       "color":"#173177"
                   },
                   "keyword4": {
                       "value":"'.$order['order_number'].'",
                       "color":"#173177"
                   },
                   "keyword5": {
                       "value":"'.date('Y-m-d H:i:s',$order['date']).'",
                       "color":"#173177"
                   },
                   "remark":{
                       "value":"欢迎再次购买！",
                       "color":"#173177"
                   }
           }
       }'; 

       	$content = $this->curl($requestUrl,true,'POST',$data);
	 	
		// $pay_type=M('input')->where(array('input_pid'=>549,'input_id'=>$data['pay_type']))->getField('input_name');
		// $arr=array(
		// 	'first'=>'您好!'.$member['nickname'].'您购买的'.$order['goods_name'],
		// 	'keyword1'=>'￥'.$order['price'],
		// 	'keyword2'=>$order['goods_name'],
		// 	'keyword3'=>"微信支付",
		// 	'keyword4'=>$order['order_number'],
		// 	'keyword5'=>date('Y-m-d H:i:s',$order['date']),
		// 	'remark'=>'0.0'
		// );
		// $url='http://'.HTTP_HOST.'/index.php?m=order&a=index';
		// $curldata=array('m'=>'weixin','a'=>'templatesend','openid'=>$openid,'template_id'=>'EpBfAwCkoL6loyw7ql8IO8kdWPcXs-JTaqQokFF4xiU','url'=>$url,'content'=>$arr);
		// $content = $this->curl('http://store.bjjun.com/admin.php',true,'POST',$curldata);
		//  echo $content;
		if($order['score']>0){ 
	    $jifen=M('jifen')->where(array('member_id'=>$member['member_id'],'state'=>590))->order('jifen_id desc')->find();
		$link_save['member_id']=$member['member_id'];
		$link_save['nickname']=$member['nickname'];
		$link_save['type']="592";
		$link_save['money']=$order['score'];
		$link_save['note']="支付订单号".$order['order_number'];
		$link_save['state']="590";
		$link_save['date']=time();
		$link_save['score']=$jifen['score']+$order['score'];
		M('jifen')->add($link_save);
}
		
		
		
	}


	  public function chongact()
	{
		// $id=md5('order_id'.date('Ym'));
		$order_id=$_GET['id'];

		//判断为空退出
		if(empty($order_id)){
			exit;
		}
		//查询数据库
		$info=M('account')->where('account_id='.$order_id)->find();

		//订单状态大于1 退出
		if($info['state']>431){
			exit;
		}
		//更新状态为2已付款
		$da['state']=432;
		$cnt=M('account')->where('account_id='.$order_id)->save($da);
		

	 //微信公众号消息推送
		$member=M('member')->where('member_id='.$info['member_id'])->find();
		
		$openid=$member['openid'];
		// $openid="oWkX4wkDMwhJT-iIeB6zh6rb0YBI";

		// $tempk='OPENTM202183094';
		$requestUrl="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->getAccessToken();
		$url='http://'.HTTP_HOST.'/index.php?m=member&a=balance';

		$template_id="6XQW0QC9YxL3JaanOVyM4GtLxJLdgsirYqgS0nn80TI";
		 $data= '{
           "touser":"'.$openid.'",
           "template_id":"'.$template_id.'",
           "url":"'.$url.'",            
           "data":{
                   "first": {
                       "value":"您好，您已成功进行余额充值。",
                       "color":"#173177"
                   },
                   "accountType": {
                       "value":"充值单号",
                       "color":"#173177"
                   },
                   "account": {
                       "value":"'.$info['account_id'].'",
                       "color":"#173177"
                   },
                   "amount": {
                       "value":"'.$info['amount'].'元",
                       "color":"#173177"
                   },
                   "result": {
                       "value":"充值成功",
                       "color":"#173177"
                   },
                   "remark":{
                       "value":"如有疑问，请联系我们。",
                       "color":"#173177"
                   }
           }
       }'; 

       	$content = $this->curl($requestUrl,true,'POST',$data);
       	file_put_contents("./a.txt",serialize(json_decode($content)));
    }


	public function curl($url,$https=true,$method='GET',$data=NULL)
	{
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_HEADER,false);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		if($https)
		{
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,true);
		}
		if($method=='POST')
		{
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		}
		$content=curl_exec($ch);
		curl_close($ch);
		return $content;
	}
	public function getAccessToken()
	{
		$url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsecret;
		$content=$this->curl($url);
		$content=json_decode($content);
		return $content->access_token;
	}
	public function getTicket($sceneid,$type='temp',$expire_seconds=604800)
	{
		if($type=='temp')
		{
			$data='{"expire_seconds": %s, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": %s}}}';
			$data=sprintf($data,$expire_seconds,$sceneid);
		}
		else
		{
			$data='{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": %s}}}';
			$data=sprintf($data,$sceneid);
		}
		$url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->getAccessToken();
		$content=$this->curl($url,true,'POST',$data);
		$content=json_decode($content);
		return $content->ticket;
	}
	public function getQRCode($sceneid,$type='temp',$expire_seconds=604800)
	{
		$ticket=$this->getTicket($sceneid,$type='temp',$expire_seconds=604800);
		$url='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
		$content=$this->curl($url);
		return $content;
	}

	    public function tuiguang(){
    $id=pg('member_id');

    $member=M('member')->where(array('member_id'=>$id))->find();

   
    // $ticket=getTicket($member['member_id']);
    $con=$this->getQRCode($member['member_id']);


	$dir='./uploads/qrimages/'.date('Y/m/d');

	if(!is_dir($dir)){
		mkdir($dir,'0777',true);
	}
    
	$filename = "qrcode.jpg";
$local_file = fopen($filename, 'w');
if (false !== $local_file){
    if (false !== fwrite($local_file, $con)) {
        fclose($local_file);
    }
}

    // 背景图片             
       $site=M('site')->where(array('website_id'=>1))->find();
       $simg=trim(str_replace("http://store.bjjun.com/","",$site['link_bg']));
        
        // 二维码
        import('ORG.Util.Image');
        $Image = new Image();
        // 给avator.jpg 图片添加logo水印
        $qrimg=$dir.$member['yong_code'].".png";
        $pos['x']='120';
        $pos['y']='120';
    
        $Image->water($simg,$filename,$qrimg,99,$pos);

        $save['tui_time']=time();
        $save['tui_img']=$qrimg;
               
        M('member')->where(array('member_id'=>$id))->save($save);



		header("location:index.php?m=member&a=mydata");



}





/**
 * 	作用：格式化参数，签名过程需要使用
 */
public function formatBizQueryParaMap($paraMap, $urlencode)
{
	$buff = "";
	ksort($paraMap);
	foreach ($paraMap as $k => $v)
	{
		if($urlencode)
		{
			$v = urlencode($v);
		}
		//$buff .= strtolower($k) . "=" . $v . "&";
		$buff .= $k . "=" . $v . "&";
	}
	$reqPar;
	if (strlen($buff) > 0)
	{
		$reqPar = substr($buff, 0, strlen($buff)-1);
	}
	return $reqPar;
}

/**
 * 	作用：生成签名
 */
public function getSign($Obj)
{
	foreach ($Obj as $k => $v)
	{
		$Parameters[$k] = $v;
	}
	//签名步骤一：按字典序排序参数
	ksort($Parameters);
	$String = $this->formatBizQueryParaMap($Parameters, false);
	//echo '【string1】'.$String.'</br>';
	//签名步骤二：在string后加入KEY
	$String = $String."&key=fb8e938b95ad09bf2a52122144ab847a";
	//echo "【string2】".$String."</br>";
	//签名步骤三：MD5加密
	$String = md5($String);
	//echo "【string3】 ".$String."</br>";
	//签名步骤四：所有字符转为大写
	$result_ = strtoupper($String);
	//echo "【result】 ".$result_."</br>";
	return $result_;
}

public function macpay(){
$member=session('member');
$info=M('wxpay')->where(array('wxpay_id'=>1))->find();

$price= pg('price');
$link_amount= pg('amount');
if($price){

	$yong=M('link_yong')->where(array('yong_code'=>$member['yong_code'],'website_id'=>$member['website_id']))->find();
	if($price<1){
		$this->error('最小提现金额为1元');
	}

	if($yong['yong_yu']<$price){
		$this->error('余额不足');
	}
	$desc='佣金提现';//描述
	$amount=$price*100;//金额（以分为单位，必须大于100）
	$flag="1";
}else{
	$balance = M('account')->where(array('member_id'=>$member['member_id']))->order('account_id desc')->getField('balance');
	if($link_amount<1){
		$this->error('最小提现金额为1元');
	}

	if($balance<$link_amount){
		$this->error('余额不足');
	}

	$desc='余额提现';//描述
	$amount=$link_amount*100;//金额（以分为单位，必须大于100）
	$flag="2";
}


$secret = $this->secret;


//获取openid
$mch_appid=$info['appid'];
$mchid=$info['mchid'];//商户号
$nonce_str='qyzf'.rand(100000, 999999);//随机数
$partner_trade_no='HW'.time().rand(10000, 99999);//商户订单号
$openid=$member['openid'];//用户唯一标识
$check_name='NO_CHECK';//校验用户姓名选项，NO_CHECK：不校验真实姓名 FORCE_CHECK：强校验真实姓名（未实名认证的用户会校验失败，无法转账）OPTION_CHECK：针对已实名认证的用户才校验真实姓名（未实名认证用户不校验，可以转账成功）
$re_user_name=$member['nickname'];//用户姓名
// $amount=$price*100;//金额（以分为单位，必须大于100）
// $desc='佣金提现';//描述
$spbill_create_ip=$_SERVER["REMOTE_ADDR"];//请求ip
//封装成数据
$dataArr=array();
$dataArr['amount']=$amount;
$dataArr['check_name']=$check_name;
$dataArr['desc']=$desc;
$dataArr['mch_appid']=$mch_appid;
$dataArr['mchid']=$mchid;
$dataArr['nonce_str']=$nonce_str;
$dataArr['openid']=$openid;
$dataArr['partner_trade_no']=$partner_trade_no;
$dataArr['re_user_name']=$re_user_name;
$dataArr['spbill_create_ip']=$spbill_create_ip;

$sign=$this->getSign($dataArr);


//echo "-----<br/>签名：".$sign."<br/>*****";//die;

$data="<xml>
<mch_appid>".$mch_appid."</mch_appid>
<mchid>".$mchid."</mchid>
<nonce_str>".$nonce_str."</nonce_str>
<partner_trade_no>".$partner_trade_no."</partner_trade_no>
<openid>".$openid."</openid>
<check_name>".$check_name."</check_name>
<re_user_name>".$re_user_name."</re_user_name>
<amount>".$amount."</amount>
<desc>".$desc."</desc>
<spbill_create_ip>".$spbill_create_ip."</spbill_create_ip>
<sign>".$sign."</sign>
</xml>";

$ch = curl_init ();
$MENU_URL="https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
curl_setopt ( $ch, CURLOPT_URL, $MENU_URL );
curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );



$z1=trim(str_replace("http://store.bjjun.com/","",$info['apiclient_cert']));
$z2=trim(str_replace("http://store.bjjun.com/","",$info['apiclient_key']));



curl_setopt($ch,CURLOPT_SSLCERT,$z1);
curl_setopt($ch,CURLOPT_SSLKEY,$z2);
// curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01;
// Windows NT 5.0)');
curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 );
curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );

$info = curl_exec ( $ch );

if (curl_errno ( $ch )) {
	echo 'Errno' . curl_error ( $ch );
}

curl_close ( $ch );

$data = simplexml_load_string($info, null, LIBXML_NOCDATA);
$code=$data->return_code;
if($code=='SUCCESS'){

	if($flag=="1"){ 
		$yu=$yong['yong_yu']-$price;
		$save['yong_yu']=$yu;
		$yong=M('link_yong')->where(array('yong_code'=>$member['yong_code'],'website_id'=>$member['website_id']))->save($save);
		$ting['ting_yu']=$yu;
		$ting['ting_name']=$member['yong_code'];
		$ting['ting_bei']="微信提现".$price."元";
		$ting['ting_jiao']=$price;
		$ting['ting_type']="微信提现";
		$ting['ting_state']="交易成功";
		$ting['date']=time();
		$ting['website_id']=$member['website_id'];
		M('ting_yong')->add($ting);
	}else{

	   $cdata['member_id']=$member['member_id'];
	   $cdata['balance']=$balance-$link_amount;
	   $cdata['amount']=$link_amount;
	   $cdata['amount_type']=428;
	   $cdata['date']=time();
	   $cdata['note']='微信提现'.$link_amount.'元';
	   $cdata['state']=432;
	   $cdata['website_id']=$member['website_id'];
	   $id=M('account')->add($cdata);

	}

		$openid=$member['openid'];
		// $openid="oWkX4wkDMwhJT-iIeB6zh6rb0YBI";

		// $tempk='OPENTM202183094';
		$requestUrl="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->getAccessToken();
		if($flag=="1"){ 
			$url='http://'.HTTP_HOST.'/index.php?m=member&a=yong';
			$va=$price;
			$ti="恭喜，您的佣金提现已经处理,请注意查收";
			$go="index.php?m=member&a=yong";
		}else{
			$url='http://'.HTTP_HOST.'/index.php?m=member&a=balance';
			$va=$link_amount;
			$ti="恭喜，您的余额提现已经处理,请注意查收";
			$go="index.php?m=member&a=balance";
		}

		$template_id="QS_WNJG84b2EaXuhLQzb9wjVs9OZHNHa1768WcyX9tw";
		 $data= '{
           "touser":"'.$openid.'",
           "template_id":"'.$template_id.'",
           "url":"'.$url.'",            
           "data":{
                   "first": {
                       "value":"'.$ti.'",
                       "color":"#173177"
                   },
                   "keyword1": {

                       "value":"'.$va.'元",
                       "color":"#173177"
                   },
                   "keyword2": {
                       "value":"'.date('Y-m-d H:i:s').'",
                       "color":"#173177"
                   },
                   "keyword3": {
                       "value":"微信提现",
                       "color":"#173177"
                   },
                   "remark":{
                       "value":"如有疑问，请联系我们。",
                       "color":"#173177"
                   }
           }
       }'; 

       	$content = $this->curl($requestUrl,true,'POST',$data);
       	// file_put_contents("./a.txt",serialize(json_decode($content)));
	$this->success('提现成功',$go);






}else{
	$this->error('提现失败');
	// $yu=$yong['yong_yu']-$price;
	// $save['yong_yu']=$yu;
	// $yong=M('link_yong')->where(array('yong_code'=>$member['yong_code']))->save($save);
	// $ting['ting_yu']=$yu;
	// $ting['ting_name']=$member['yong_code'];
	// $ting['ting_bei']="微信提现".$price."元";
	// $ting['ting_jiao']=$price;
	// $ting['ting_type']="微信提现";
	// $ting['ting_state']="交易失败";
	// $ting['date']=time();
	// $ting['website_id']=1;
	// $id=M('ting_yong')->add($ting);

	// $yu2=$yong['yong_yu'];
	// $save3['ting_yu']=$yu2;
	// M('ting_yong')->where(array('ting_yong_id'=>$id))->save($save3);
	// $save4['yong_yu']=$yu2;
	// $yong=M('link_yong')->where(array('yong_code'=>$member['yong_code']))->save($save4);
	// $this->error('提现失败');
}

}


	public function bao_save()
	{

		$member=session('member');
		$bao_active_id=pg('bao_active_id');
		$number=pg('number');

$goods= M()->table('index_bao n,index_bao_active r')->where('r.bao_active_id ='.$bao_active_id.' and r.bao_id=n.bao_id')->order('bao_active_id desc')->find();

		$sum=$goods['price']*$number;
		$time=time();
		$yu=M('account')->where(array('member_id'=>$member['member_id']))->order('account_id desc')->find();
		if($member){
			
		  if(intval($yu['balance'])>=intval($sum)){
		  	
			if($bao_active_id!='')
			{

				$data['balance']=$yu['balance'] - $sum;//余额去除
				$data['member_id']=$member['member_id'];
				$data['amount']=$sum;
				$data['amount_type']=427;
				$data['note']='支付夺宝期号 '.$bao_active_id.':'.$number.'人次';
				$data['state']=432;
				$data['date']=time();
				$data['website_id']=WEBSITE_ID;
				$id = M('account')->data($data)->add();

				if($id>0){


					for ($i=0; $i <$number ; $i++) { 

					$last=M('bao_list')->where(array('bao_active_id'=>$bao_active_id))->order('bao_list_id desc')->find();


						if(empty($last)){
							$last['haoma']=10000000;
						}
						$ldata['member_id']=$member['member_id'];
						$ldata['haoma']=$last['haoma']+1;
						$ldata['date']=time();
						$ldata['website_id']=WEBSITE_ID;
						$ldata['bao_active_id']=$bao_active_id;
						M('bao_list')->add($ldata);
					}
				}
			echo 1;
			}
		  }else{
			echo 2;
		  }

		}else{
			echo 3;
		}
	}

//余额支付
public function balance_pay()
	{
		$order_id=pg('order_id');
		$ye=pg('ye');
		$list=M('order')->where(array('order_id'=>$order_id))->find();
		if($list['state']>381){
			echo "订单已支付,请勿重复提交";
			exit;
		}
		$member=session('member');
		$time=time();
		$balance=M('account')->where(array('member_id'=>$member['member_id']))->order('account_id desc')->getfield('balance');
		$order=M('order')->where(array('order_id'=>$order_id))->find();
		
		if($balance>=$order['price'])
		{
			$data['balance']=$balance-$order['price'];//余额去除
			$data['member_id']=$member['member_id'];
			$data['amount']=$order['price'];
			$data['amount_type']=427;
			$data['note']='支付订单号'.$order['order_number'].':'.$order['price'].'元';
			$data['state']=432;
			$data['date']=time();
			$data['website_id']=WEBSITE_ID;
			$id = M('account')->data($data)->add();

			M('order')->where(array('order_id'=>$order_id))->save(array('state'=>382,'pay_date'=>$time,'pay_type'=>551));

			//  //微信公众号消息推送
	 //  
			$openid=$member['openid'];

		// $tempk='OPENTM202183094';
			$requestUrl="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->getAccessToken();

			$url='http://'.HTTP_HOST.'/index.php?m=order&a=index';

			$template_id="kYF4-WSOr9LT6rx2iYUpRU_UEylCkfVNZ-1FMz9GEQg";
			$goods=M('order_goods')->where(array('order_id'=>$order_id))->find();
		 	$data= '{
           "touser":"'.$openid.'",
           "template_id":"'.$template_id.'",
           "url":"'.$url.'",            
           "data":{
                   "first": {
                       "value":"您好!'.$member['nickname'].'您购买的商品已经支付成功",
                       "color":"#173177"
                   },
                   "keyword1":{
                       "value":"￥'.$order['price'].'",
                       "color":"#173177"
                   },
                   "keyword2": {
                       "value":"'.$goods['goods_name'].'",
                       "color":"#173177"
                   },
                   "keyword3": {
                       "value":"余额支付",
                       "color":"#173177"
                   },
                   "keyword4": {
                       "value":"'.$order['order_number'].'",
                       "color":"#173177"
                   },
                   "keyword5": {
                       "value":"'.date('Y-m-d H:i:s',$order['date']).'",
                       "color":"#173177"
                   },
                   "remark":{
                       "value":"欢迎再次购买！",
                       "color":"#173177"
                   }
           }
       		}'; 

			if($order['score']>0){ 
		    $jifen=M('jifen')->where(array('member_id'=>$member['member_id'],'state'=>590))->order('jifen_id desc')->find();
			$link_save['member_id']=$member['member_id'];
			$link_save['nickname']=$member['nickname'];
			$link_save['type']="592";
			$link_save['money']=$order['score'];
			$link_save['note']="支付订单号".$order['order_number'];
			$link_save['state']="590";
			$link_save['date']=time();
			$link_save['score']=$jifen['score']+$order['score'];
			M('jifen')->add($link_save);
			}
       		$content = $this->curl($requestUrl,true,'POST',$data);

			if($ye=="cart"){
				$this->success('支付成功','index.php?m=order&a=index');
			}else{ 
			echo 1;
			}
		}
	}


//积分支付
public function integral_pay()
	{
		$order_id=pg('order_id');
		$ye=pg('ye');
		$list=M('order')->where(array('order_id'=>$order_id))->find();
		if($list['state']>381){
			echo "订单已支付,请勿重复提交";
			exit;
		}
		$member=session('member');
		$time=time();
		$score=M('jifen')->where(array('member_id'=>$member['member_id']))->order('date desc')->getfield('score');
		$order=M('order')->where(array('order_id'=>$order_id))->find();
	
		if($score>=$order['price'])
		{
			$data['score']=$score-$order['price'];//积分去除
			$data['member_id']=$member['member_id'];
			$data['money']=$order['price'];
			$data['type']=593;
			$data['note']='支付订单号'.$order['order_number'].':'.$order['price'].'积分';
			$data['state']=590;
			$data['date']=time();
			$data['website_id']=WEBSITE_ID;
			$id = M('jifen')->data($data)->add();
			M('order')->where(array('order_id'=>$order_id))->save(array('state'=>382,'pay_date'=>$time,'pay_type'=>607));

			//  //微信公众号消息推送
	 //  
			$openid=$member['openid'];

		// $tempk='OPENTM202183094';
			$requestUrl="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->getAccessToken();

			$url='http://'.HTTP_HOST.'/index.php?m=order&a=index';

			$template_id="kYF4-WSOr9LT6rx2iYUpRU_UEylCkfVNZ-1FMz9GEQg";
			$goods=M('order_goods')->where(array('order_id'=>$order_id))->find();
		 	$data= '{
           "touser":"'.$openid.'",
           "template_id":"'.$template_id.'",
           "url":"'.$url.'",            
           "data":{
                   "first": {
                       "value":"您好!'.$member['nickname'].'您兑换的商品已经支付成功",
                       "color":"#173177"
                   },
                   "keyword1":{
                       "value":"积分'.$order['price'].'",
                       "color":"#173177"
                   },
                   "keyword2": {
                       "value":"'.$goods['goods_name'].'",
                       "color":"#173177"
                   },
                   "keyword3": {
                       "value":"积分支付",
                       "color":"#173177"
                   },
                   "keyword4": {
                       "value":"'.$order['order_number'].'",
                       "color":"#173177"
                   },
                   "keyword5": {
                       "value":"'.date('Y-m-d H:i:s',$order['date']).'",
                       "color":"#173177"
                   },
                   "remark":{
                       "value":"欢迎再次购买！",
                       "color":"#173177"
                   }
           }
       		}'; 
       		$content = $this->curl($requestUrl,true,'POST',$data);
       		echo 1;
		}else{
			echo "积分不足";
		}
	}


	// 优惠券领取
	public function quan(){
		$quan_id=pg('quan_id');
		$member=session('member');
		$quan=M('quan')->where(array('quan_id'=>$quan_id))->find();
		$count=M('quan_list')->where(array('quan_id'=>$quan_id,'member_id'=>$member['member_id']))->count();
		
		if($quan['quan_ling']>=$quan['number']){
			$this->error('优惠券已经领完','index.php?m=goods&a=quan');
		}

		if($count>=$quan['xian']){
			$this->error('每人限领'.$quan['xian'].'张','index.php?m=goods&a=quan');
		}

		$save['quan_ling']=$quan['quan_ling']+1;
		M('quan')->where(array('quan_id'=>$quan_id))->save($save);
	
		$data['member_id']=$member['member_id'];
		$data['website_id']=$member['website_id'];
		$data['nickname']=$member['nickname'];
		$data['note']="成功领取优惠券1张";
		$data['quan_name']=$quan['quan_name'];
		$data['money']=$quan['money'];
		$data['type']=612;
		$data['quan_id']=$quan['quan_id'];
		$data['state']=619;
		$data['date']=time();
		M('quan_list')->add($data);
		$this->success('恭喜，您已成功领取优惠券');

		
	} 

	
}
