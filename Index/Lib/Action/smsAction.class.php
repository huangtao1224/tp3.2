<?php
class smsAction extends Action {
	public $accountSid='aaf98f89539b228f01539c476c25012c';  //用户ID
	//说明：主账号，登陆云通讯网站后，可在"控制台-应用"中看到开发者主账号ACCOUNT SID。
	public $accountToken='d7587b6c72414fe99eabbd683f82d477'; 
	//说明：主账号Token，登陆云通讯网站后，可在控制台-应用中看到开发者主账号AUTH TOKEN。

	public $appId='8aaf0708721773ab0172266e3b6e0360';
	//说明：应用Id，如果是在沙盒环境开发，请配置"控制台-应用-测试DEMO"中的APPID。如切换到生产环境，
	//请使用自己创建应用的APPID。

	public $serverIP='sandboxapp.cloopen.com'; 
	//说明：请求地址。
	//沙盒环境配置成sandboxapp.cloopen.com，
	//生产环境配置成app.cloopen.com。

	public $serverPort='8883'; 
	//说明：请求端口 ，无论生产环境还是沙盒环境都为8883.

	public $softVersion='2013-12-26'; 
	//说明：REST API版本号保持不变。

	public function index()//列表
	{
		$this->display();
	}
	public function send_sms()//增加
	{
		
		$to=pg('tel');
		$site = M('site')->where(array('version_id'=>1))->find();
		$function_switch = M('function_switch')->where(array('function_switch_id'=>1))->find();
		if($site['sms_number']>0 && $function_switch['sms_switch']==2)
		{
			$tempId=pg('temp_id')==''?1164558:pg('temp_id');//328286  396272 模板id
			$sms_code=mt_rand(100000,999999);
			session('sms_code',md5($sms_code));
			$datas=array($sms_code,'15分钟','参数3');
			if(pg('data'))$datas=pg('data');
			
			// 初始化REST SDK
         	import('Thinkphp.ORG.REST'); //导入类
          	include  './ThinkPHP/Lib/ORG/REST.class.php';
			$rest = new REST($this->serverIP,$this->serverPort,$this->softVersion);
			$rest->setAccount($this->accountSid,$this->accountToken);
			$rest->setAppId($this->appId);
			
			
			// 发送模板短信
			$result = $rest->sendTemplateSMS($to,$datas,$tempId); 
			//print_r($result);
			if($result == NULL )
			{
				echo "result error!"; 
				exit(); 
			}
			if($result->statusCode!=0)
			{
				$array['text']='<em class="red">短信发送失败</em>';
				$array['msg']=$smsmessage->dateCreated;
				$array['id']=$smsmessage->smsMessageSid;
				echo json_encode($array);
				//下面可以自己添加错误处理逻辑
			}
			else
			{
				M('site')->where(array('version_id'=>1))->save(array('sms_number'=>($site['sms_number']-1)));
				// 获取返回信息
				$smsmessage = $result->TemplateSMS;
				$array[text]='短信发送成功';
				$array[msg]=$smsmessage->dateCreated;
				$array[id]=$smsmessage->smsMessageSid;
				//echo json_encode($array);
				//下面可以自己添加成功处理逻辑
				echo 1;
			}
		}
	}
}


