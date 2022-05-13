

 <?php





 

//获取数据

$xml = file_get_contents('php://input');



file_put_contents('a.txt', $xml);
file_put_contents('a.txt', date('Y-m-d H:i:s')." xml进来了||".$xml."\r\n", FILE_APPEND);
//处理数据

$rss=new SimpleXMLElement($xml);

$attach= $rss->attach;//订单号

$result_code= $rss->result_code;//交易成功



//创建文件夹

$dir='./notice_goods/'.date('Y/m');

if(!is_dir($dir)){

	mkdir($dir,'0777',true);

}

//按日期创建文件名

$log_filename = date('Ymd').".xml";



if($result_code=='SUCCESS'){//成功



	//生成日志

	file_put_contents($dir.'/'.$log_filename, date('H:i:s')." ".$xml."\r\n", FILE_APPEND);



	//更新数据库

	$order_id=md5('order_id'.date('Ym'));//加密order_id

	

	$url="https://".$_SERVER['HTTP_HOST']."/mobile.php?m=wxpay&a=payact&".$order_id."=".$attach;

	file_put_contents($dir.'/'.$log_filename, date('H:i:s')." 回调进来了".$attach."\r\n", FILE_APPEND);
	 //初始化

    $curl = curl_init();

    //设置抓取的url

    curl_setopt($curl, CURLOPT_URL, $url);

    //设置头文件的信息作为数据流输出

    curl_setopt($curl, CURLOPT_HEADER, 1);

    //设置获取的信息以文件流的形式返回，而不是直接输出。

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    //执行命令

    $data = curl_exec($curl);

    //关闭URL请求

    curl_close($curl);

    //显示获得的数据

   

	// header("Location: http://store.cn.bjjun.com/index.php?m=wxpay&a=payact&".$order_id."=".$order_num);



	//返回数据

	echo "<xml><return_code><![CDATA[SUCCESS]]></return_code>

		  <return_msg><![CDATA[OK]]></return_msg></xml>"; 

	 exit;



	

}



//返回错误

echo "<xml><return_code><![CDATA[FAIL]]></return_code></xml>";

exit;





 

 ?>

