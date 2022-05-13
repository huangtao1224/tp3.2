<?php
// require_once '../../config.php';
// require_once "../lib/WxPay.Api.php";
// require_once '../lib/WxPay.Notify.php';
// require_once 'log.php';

vendor('Wxpay.lib.Exception');
vendor('Wxpay.lib.Data');
vendor('Wxpay.lib.config');
vendor('Wxpay.lib.WxWay#Api');
vendor('Wxpay.lib.WxPay#Notify');
vendor('Wxpay.example.log');
//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{

			$attach=$result['attach'];
			$attach_arr=explode(",",$attach);
			$member_id=$attach_arr[0];
			$payee_id=$attach_arr[1];
			$hongbao_id=$attach_arr[2];
			$price=$result['cash_fee']/100;
			/*
			if($hongbao_id!='')
			{
				$hongbao=$func->select(array('b'=>'index_hongbao','t'=>array('hongbao_id'=>$hongbao_id)));
				$price=$price+$hongbao[0]['price'];
			}
			*/
			

			$func =  new func();
			//查询付款账户余额
			$member_account=$func->select(array('b'=>'index_member_account','t'=>array('member_id'=>$member_id),'p'=>'member_account_id desc'));
			$member_balance=$member_account[0]['balance'];
			if($member_balance=='')$member_balance=0;

			//查询付款会员信息
			$member=$func->select(array('b'=>'index_member','t'=>array('member_id'=>$member_id),'p'=>'date asc'));
			//查询收款账户余额
			$payee_account=$func->select(array('b'=>'index_member_account','t'=>array('member_id'=>$payee_id),'p'=>'member_account_id desc'));
			$payee_balance=$payee_account[0]['balance'];
			if($payee_balance=='')$payee_balance=0;

			//查询收款会员信息
			$payee_member=$func->select(array('b'=>'index_member','t'=>array('member_id'=>$payee_id),'p'=>'date asc'));
			$arr['b']='index_member_account';
			$arr['data']=array('balance'=>$member_balance,'transfer'=>$price,'member_id'=>$member_id,'transfer_type'=>230,'state'=>232,'date'=>time(),'note'=>'收款人：'.$payee_member[0]['nickname']);
			//$func->insert($arr);//插入关联表

// $a='';
// 			$k = fopen("../../1.dat", "r+");
// 			fwrite($k, $a);
// 			fclose($k);


			//更新付款账户余额
			$arr['data']=array('balance'=>($payee_balance+$price),'transfer'=>$price,'member_id'=>$payee_id,'transfer_type'=>349,'state'=>232,'date'=>time(),'payee_id'=>$member_id,'note'=>'付款人： '.$member[0]['nickname'],'source'=>'微信支付');
			$func->insert($arr);//插入关联表

$member_account=$func->select(array('b'=>'index_site'));
 $a=rand(1,100);
 $time=time();

$aa=serialize($member_account[0]);
$k = fopen("../../1.dat", "r+");
 			fwrite($k, $aa);
 			fclose($k);
 if($member_account[0]['hongbao_time_begin']<$time && $member_account[0]['hongbao_time_end']>$time && $member_account[0]['hongbao_number']>=1 && $a<=$member_account[0]['hongbao_risk'] && $member_account[0]['danci_price']<=$price){
	
	$arr['b']='index_hongbao';
	$arr['data']=array('price'=>$member_account[0]['hongbao_price'],'state'=>385,'member_id'=>$member_id,'hongbao_dole'=>419,'store_member_id'=>$payee_id,'date'=>$time,'expiry'=>($time+($member_account[0]['hongbao_lifespan']*24*60*60)));
	$func->insert($arr);//插入关联表
 	
 	$func->update(array('b'=>'index_site','t'=>array('site_id'=>1),'data'=>array('hongbao_number' => $member_account[0]['hongbao_number']-1)));
 }

	if($hongbao_id!=''){

	$func->update(array('b'=>'index_hongbao','t'=>array('hongbao_id'=>$hongbao_id),'data'=>array('state' =>386)));
	}
			$url='http://gcb.bjjun.cn/index.php';
			$content='支付【'.$payee_member[0]['store_name'].'】成功消费'.$price.'元 \n时间：'.cover_time(time(),'Y-m-d H:i:s');
			$curldata=array('m'=>'weixin','a'=>'newsseed','openid'=>$member[0]['openid'],'title'=>'光彩8提醒','content'=>$content);

			$httpstr = curl_http($url, $curldata, 'GET', array("Content-type: text/html; charset=utf-8"));

			$url='http://gcb.bjjun.cn/index.php';
			$content='收入【'.$member[0]['nickname'].'】成功消费'.$price.'元 \n账户余额：'.($payee_balance+$price).'元\n时间：'.cover_time(time(),'Y-m-d H:i:s');
			$curldata=array('m'=>'weixin','a'=>'newsseed','openid'=>$payee_member[0]['openid'],'title'=>'光彩8提醒','content'=>$content);

			$httpstr = curl_http($url, $curldata, 'GET', array("Content-type: text/html; charset=utf-8"));

			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		Log::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		return true;
	}
}

Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
