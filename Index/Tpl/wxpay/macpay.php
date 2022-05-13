<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>提现测试</title>
</head>

<body>
<?php
vendor('Wxpay.lib.WxWay#config');

/**
 * 	作用：格式化参数，签名过程需要使用
 */
function formatBizQueryParaMap($paraMap, $urlencode)
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
function getSign($Obj)
{
	foreach ($Obj as $k => $v)
	{
		$Parameters[$k] = $v;
	}
	//签名步骤一：按字典序排序参数
	ksort($Parameters);
	$String = formatBizQueryParaMap($Parameters, false);
	//echo '【string1】'.$String.'</br>';
	//签名步骤二：在string后加入KEY
	$String = $String."&key=e5f184c9ea3f159eae4bc06a932b04a7";
	//echo "【string2】".$String."</br>";
	//签名步骤三：MD5加密
	$String = md5($String);
	//echo "【string3】 ".$String."</br>";
	//签名步骤四：所有字符转为大写
	$result_ = strtoupper($String);
	//echo "【result】 ".$result_."</br>";
	return $result_;
}


$appid = "wx843aed193e9f4dbe";  
$secret = "dd3d930205bd07cb676d6dd1e64f09dd";
$price= 1;
//获取openid
$mch_appid=$appid;
$mchid='1272242901';//商户号
$nonce_str='qyzf'.rand(100000, 999999);//随机数
$partner_trade_no='HW'.time().rand(10000, 99999);//商户订单号
$openid='oWkX4wkDMwhJT-iIeB6zh6rb0YBI';//用户唯一标识
$check_name='NO_CHECK';//校验用户姓名选项，NO_CHECK：不校验真实姓名 FORCE_CHECK：强校验真实姓名（未实名认证的用户会校验失败，无法转账）OPTION_CHECK：针对已实名认证的用户才校验真实姓名（未实名认证用户不校验，可以转账成功）
$re_user_name='测试';//用户姓名
$amount=$price*100;//金额（以分为单位，必须大于100）
$desc='光彩8提现';//描述
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

$sign=getSign($dataArr);


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

$zs1= vendor('Wxpay.cert.apiclient_cert','','.pem');
$zs2= vendor('Wxpay.cert.apiclient_key','','.pem');
curl_setopt($ch,CURLOPT_SSLCERT,$zs1);
curl_setopt($ch,CURLOPT_SSLKEY,$zs2);
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
echo $info;
?>
</body>
</html>
