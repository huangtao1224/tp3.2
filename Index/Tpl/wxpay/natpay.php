<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
//设置时区
ini_set('date.timezone','Asia/Shanghai');
//引入类
vendor('Wxpay.lib.WxWay#Exception');
vendor('Wxpay.lib.WxWay#Data');
vendor('Wxpay.lib.WxWay#config');
vendor('Wxpay.lib.WxWay#Api');
vendor('Wxpay.example.WxPay#JsApiPay');
vendor('Wxpay.example.log');
vendor('Wxpay.example.phpqrcode.phpqrcode');

//获取订单号
// $order_id=pg('order_id');
$order_id='87';
$order_list=M('order')->where(array('order_id'=>$order_id))->find();

//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody('商品');
$input->SetAttach($order_id);
// $input->SetAttach('110110110');
$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
$input->SetTotal_fee($order_list['price']*100); //价格传入
// $input->SetTotal_fee("1");
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url("http://www.jujiacn.com/wxpay/notice.php");
$input->SetTrade_type("NATIVE");
$input->Setproduct_id($order_id);
$rs = WxPayApi::unifiedOrder($input);
$link_url=$rs['code_url'];
if(empty($link_url)){
	exit;
}

$dir='./uploads/qrimages/'.date('Y/m/d');
if(!is_dir($dir)){
	mkdir($dir,'0777',true);
}
$a=QRcode::png($link_url,$dir.'/'.$order_list['order_number'].'.png','M',4,2);
echo 'OK';

?>






