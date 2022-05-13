<?php
class memberAction extends Action {
	public function check_username()
	{
		$username=pg('username');
		$member=M('member')->where(array('username'=>$username))->find();
		if(!empty($member))
		{
			echo 1;
		}
	}
	public function check_sms()
	{
		$sms=md5(pg('sms'));
		if($sms==session('sms_code'))
		{
			echo 1;
		}
	}
		public function register_save()
	{
		$data=pg('data');
		$data['date']=time();
		$sms_code=md5(pg('sms_code'));
		$sms=session('sms_code');


		$username=$data['username'];
		$rs=M('member')->where(array('username'=>$username))->find();

		if($rs){
			$arr['msg']='注册失败,用户名已存在';
			echo json_encode($arr);
			exit;
		}

		if($sms!=$sms_code){
			$arr['msg']='注册失败,验证码错误';
			echo json_encode($arr);
			exit;
		}

		if($data['password']!=$data['repassword']){
			$arr['msg']='注册失败,密码不一致';
			echo json_encode($arr);
			exit;
		}

	
		$data['password']=md5($data['password']);
		$id=M('member')->data($data)->add();
		$member=M('member')->where(array('member_id'=>$id))->find();
		session('member',$member);

		$arr['msg']='恭喜,注册成功';
			echo json_encode($arr);
		exit;
	
	}
	public function login_save()
	{
		$data['username']=pg('username');
		$data['password']=md5(pg('password'));
		if($data['username']!='' && $data['password']!='')
		{
			$member=M('member')->where($data)->find();
			if(!empty($member))
			{
				session('member',$member);
				//$str=file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip='.get_client_ip());
				//$arr=json_decode($str,true);
				//$ip_address=$arr['data']['region'].$arr['data']['city']." ".$arr['data']['isp'];
				//M('memberlog')->add(array('ip'=>get_client_ip(),'ip_address'=>$ip_address,'member_id'=>$member['member_id'],'date'=>time()));
				echo 1;
			}
		}
	}
	public function mydata()
	{
		$data=pg('data');
		$type_id=46;
		if(!empty($data))
		{
			$member=session('member');
			
			$list = M('input')->where(array('type_id' => $type_id, 'show_switch' => 2, 'input_type_id' => 4))->select();
			foreach($list as $k => $v){
				$data[$v['field_name']]=serialize($data[$v['field_name']]);
			}
				
			$list = M('input')->where(array('type_id' => $type_id, 'show_switch' => 2, 'input_type_id' => 7))->select();
			foreach($list as $k => $v){
				if(!empty($_FILES[$v['field_name']]['tmp_name'])){
					if(file_exists($content[0][$v['field_name']])){
						unlink($content[0][$v['field_name']]);	//通过文件路径来删除
					}
					$data[$v['field_name']] = $this->up_file(array('name' => $v['field_name']));
				}
			}
			$list = M('input')->where(array('type_id' => $type_id, 'show_switch' => 2, 'input_type_id' => 8))->select();
			foreach($list as $k => $v)
			{
				$data[$v['field_name']]=strtotime($data[$v['field_name']]);
			}
			M('member')->where(array('member_id'=>$member['member_id']))->save($data);
		}
		$this->display();
		
		//goto_url('index.php?m=member&a=mydata');
	}
	public function checkoldpwd()
	{
		$member=session('member');
		$password=md5(pg('password'));
		$list=M('member')->where(array('member_id'=>$member['member_id'],'password'=>$password))->find();
		if(!empty($list))
		{
			echo 1;
		}
	}
	public function changepwd_save()
	{
		$member=session('member');
		$data['password']=md5(pg('password'));
		$list=M('member')->where(array('member_id'=>$member['member_id']))->save($data);
		if(!empty($list))
		{
			echo 1;
		}
	}
	
	/*
	地址
	*/	
	public function menu_cascade()
	{		
		$region_id=pg('region_id');		
		if($region_id)
		{
		  $list=M('region')->where(array('region_pid'=>$region_id))->select();
		  $str="<option value=''>请选择</option>";
		  foreach($list as $k=>$v)
			{
				$str.="<option value='".$v['region_id']."'>".$v['region_name']."</option>";
			}
			echo $str;	
			
		}
	}
	
	/*
	添加我的地址
	*/
	public function add_address_save()
	{
		$data=pg('data');		
		if(!empty($data))
		{
			M('address')->add($data);
			$this->redirect(U('member/myaddress'), 5);
		}
	}
	
	/*
	设为默认收货地址
	*/
	public function set_default_address()
	{
		$address_id=pg('address_id');
		$member=session('member');
		if($address_id!='' && $member['member_id']!='')
		{
			M('address')->where(array('member_id'=>$member['member_id']))->save(array('state'=>412));
			M('address')->where(array('address_id'=>$address_id))->save(array('state'=>411));
		}
	}
	
	/*
	修改我的地址
	*/
	public function edit_address_save()
	{
		$data=pg('data');
		$address_id=pg('address_id');
		if(!empty($data))
		{
			M('address')->where(array('address_id'=>$address_id))->save($data);
			$this->redirect(U('member/myaddress'), 5);
		}
	}
	/*
	删除我的地址
	*/	
	public function del_address()
	{
		$member=session('member');
		$member_id=$member['member_id'];
		$address_id=pg('address_id');
		M('address')->where(array('member_id'=>$member_id,'address_id'=>$address_id))->delete();
		echo '操作成功';
	}	
	/*
	会员退出
	*/
	public function logout()
	{
		session('member',null);
		$this->redirect(U('member/login'), 5);
	}
	public function topup_pay()
	{
		$member=session('member');
		header("Content-type: text/html; charset=UTF-8");
		vendor('Teegon.Teegon');
		$param['amount']=pg('amount');//充值金额
		$param['channel']=pg('channel');//支付方式
		$param['order_no']=time(); //订单号
		$param['return_url']='http://2.cn.bjjun.com/index.php?m=member&a=topup_success';//前台跳转页面
		$param['subject']='会员在线充值';//商品名称
		$param['metadata']=serialize($member);//附加信息,这里传的是会员信息
		$param['notify_url']='http://2.cn.bjjun.com/index.php?m=member&a=topup_save';//支付成功后天工支付网关通知
		$param['client_ip']='127.0.0.1';
		$param['client_id']=TEE_CLIENT_ID;
		$srv = new TeegonService(TEE_API_URL);
		$param['sign']=$srv->sign($param);
		echo json_encode($param);
	}
	public function topup_save()
	{
		$post=($_POST);
		if($post['is_success']==TRUE)
		{
			$member=unserialize($post['metadata']);
			$balance = M('account')->where(array('member_id'=>$member['member_id']))->order('account_id desc')->getField('balance');
			$data['balance']=$balance+$post['amount'];//余额累加
			$data['amount']=$post['amount'];
			$data['member_id']=$member['member_id'];
			$data['amount_type']=426;
			$data['state']=432;
			$data['date']=time();
			$data['note']=cover_time(time(),'Y-m-d H:i:s').'充值'.$post['amount'].'元';
			$id = M('account')->data($data)->add();
		}
		/*
		$a = fopen('1.dat', "w");
		fwrite($a, serialize($_POST));
		fclose($a);
		*/
	}
}
