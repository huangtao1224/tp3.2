<?php
class orderAction extends Action {

	/*
	添加我的地址
	*/
	public function add_address_save()
	{
		$data['consignee']=pg('consignee');		
		$data['province']=pg('province');		
		$data['city']=pg('city');		
		$data['area']=pg('area');		
		$data['address']=pg('address');		
		$data['phone']=pg('phone');		
		$member=session('member');
		$data['member_id']=$member['member_id'];
		if(!empty($data))
		{
			$arr['insert_id']=M('address')->add($data);
		}
		$arr['address_prompt']='地址保存成功';
		echo json_encode($arr);
	}
	

	public function buy_now_save()
	{
		$member=session('member');
		$address_id=pg('address_id');
		$goods_id=pg('goods_id');
		$number=pg('number');
		$sum=pg('sum');
		$coupons=pg('coupons');//优惠券
		$coupons_member_id=pg('coupons_member_id');
		$time=time();
		if(!empty($member) && $goods_id!='')
		{
			/*
			收货地址构造
			*/
			$address=M('address')->where(array('address_id'=>$address_id))->find();
			$data['consignee']=$address['consignee'];
			$data['phone']=$address['phone'];
			$data['zip']=$address['zip'];
			$data['freight']=pg('freight');			
			$data['order']=$address['consignee'];
			$province=M('region')->where(array('region_id'=>$address['province']))->find();
			$city=M('region')->where(array('region_id'=>$address['city']))->find();
			$area=M('region')->where(array('region_id'=>$address['area']))->find();
			$data['address']=$province['region_name'].' '.$city['region_name'].' '.$area['region_name'].' '.$address['address'];
			$data['order_number']=cover_time(time(),'YmdHis');
			$data['member_id']=$member['member_id'];
			$data['state']=381;
			$data['date']=$time;
			$data['address_id']=$address_id;
			$data['price']=$sum+$data['freight'];//订单付款金额
			$order_id = M('order')->data($data)->add();
			$order_goods=M('goods')->where(array('goods_id'=>$goods_id))->field('goods_id,goods_name,goods_img')->find();
			if(!empty($order_goods))
			{
				$order_goods['date']=$time;
				$order_goods['member_id']=$member['member_id'];
				$order_goods['price']=get_price($goods_id,pg('value'));
				$order_goods['number']=$number;
				$order_goods['order_number']=$data['order_number'];
				$order_goods['order_id']=$order_id;
				$order_goods['name']=serialize(explode(',',pg('name')));
				$order_goods['value']=serialize(explode(',',pg('value')));
				$id = M('order_goods')->data($order_goods)->add();
			}
			$this->success('订单提交成功',U('order/pay?order_id='.$order_id));
		}
		else
		{
			$this->error('请先登录',U('member/login'));
		}
	}
	
	public function del_save()
	{
		$order_id=pg('order_id');
		$order_id!=''?M('order')->where(array('order_id'=>$order_id))->delete():'';
		$order_id!=''?M('order_goods')->where(array('order_id'=>$order_id))->delete():'';
		echo '操作成功';
	}
	
	public function card_save()
	{
		$member=session('member');
		$address_id=pg('address_id');
		$cart_id=pg('cart_id');

		$number=pg('number');
		$sum=pg('sum');
		$coupons=pg('coupons');//优惠券
		$coupons_member_id=pg('coupons_member_id');
		$time=time();
		if(!empty($member))
		{
			/*
			收货地址构造
			*/
			$address=M('address')->where(array('address_id'=>$address_id))->find();
			$data['consignee']=$address['consignee'];
			$data['phone']=$address['phone'];
			$data['zip']=$address['zip'];
			$data['freight']=pg('freight');			
			$data['order']=$address['consignee'];
			$province=M('region')->where(array('region_id'=>$address['province']))->find();
			$city=M('region')->where(array('region_id'=>$address['city']))->find();
			$area=M('region')->where(array('region_id'=>$address['area']))->find();
			$data['address']=$province['region_name'].' '.$city['region_name'].' '.$area['region_name'].' '.$address['address'];
			$data['order_number']=cover_time(time(),'YmdHis');
			$data['member_id']=$member['member_id'];
			$data['state']=381;
			$data['date']=$time;
			$data['address_id']=$address_id;
			$data['price']=$sum+$data['freight'];//订单付款金额
			$order_id = M('order')->data($data)->add();
			
			
			foreach($cart_id as $k=>$v)
			{
				$order_goods=M('cart')->where(array('cart_id'=>$v,'member_id'=>$member['member_id']))->field('goods_id,member_id,number,name,value')->find();
				$goods=M('goods')->where(array('goods_id'=>$order_goods['goods_id']))->field('goods_id,goods_name,goods_img')->find();
				$order_goods['goods_name']=$goods['goods_name'];
				$order_goods['goods_img']=$goods['goods_img'];
				if(!empty($order_goods))
				{
					$order_goods['date']=$time;
					$order_goods['member_id']=$member['member_id'];					
					$order_goods['price']=get_price($order_goods['goods_id'],implode(",",unserialize($order_goods['value'])));					
					$order_goods['order_number']=$data['order_number'];
					$order_goods['order_id']=$order_id;
					$id = M('order_goods')->data($order_goods)->add();
					M('cart')->where(array('cart_id'=>$v,'member_id'=>$member['member_id']))->delete();
				}
			}
			$this->success('订单提交成功',U('order/pay?order_id='.$order_id));
		}
		else
		{
			$this->error('请先登录',U('member/login'));
		}
	}
	
	public function balance_pay()
	{
		$order_id=pg('order_id');
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
			$id = M('account')->data($data)->add();
			M('order')->where(array('order_id'=>$order_id))->save(array('state'=>382,'pay_date'=>$time));
			echo 1;
		}
	}
	public function online_pay()
	{
		$member=session('member');
		$order_id=pg('order_id');
		$order=M('order')->where(array('order_id'=>$order_id))->find();
		header("Content-type: text/html; charset=UTF-8");
		vendor('Teegon.Teegon');
		$param['amount']=pg('amount');//充值金额
		$param['channel']=pg('channel');//支付方式
		$param['order_no']=time(); //订单号
		$param['return_url']='http://2.cn.bjjun.com/index.php?m=order&a=pay_success';//前台跳转页面
		$param['subject']='会员在线充值';//商品名称
		$param['metadata']=serialize($order);//附加信息,这里传的是订单信息
		$param['notify_url']='http://2.cn.bjjun.com/index.php?m=order&a=pay_save';//支付成功后天工支付网关通知
		$param['client_ip']='127.0.0.1';
		$param['client_id']=TEE_CLIENT_ID;
		$srv = new TeegonService(TEE_API_URL);
		$param['sign']=$srv->sign($param);
		echo json_encode($param);
	}
	public function pay_save()
	{
		$post=($_POST);
		if($post['is_success']==TRUE)
		{
			$time=time();
			$order=unserialize($post['metadata']);
			$balance = M('account')->where(array('member_id'=>$order['member_id']))->order('account_id desc')->getField('balance');
			$data['balance']=$balance;//余额累加
			$data['amount']=$post['amount'];
			$data['member_id']=$order['member_id'];
			$data['amount_type']=427;
			$data['state']=432;
			$data['date']=$time;
			$data['note']='订单号在线支付'.$order['order_number'].':'.$order['price'].'元';
			$id = M('account')->data($data)->add();
			M('order')->where(array('order_id'=>$order['order_id']))->save(array('state'=>382,'pay_date'=>$time));
		}
		/*
		$a = fopen('1.dat', "w");
		fwrite($a, serialize($_POST));
		fclose($a);
		*/
	}
	public function weixin_qrcode()
	{
		$order_id=pg('order_id');
		$qrcode=link_qrcode($order_id);
		echo '<img src="'.$qrcode.'" />';
	}
	
}
