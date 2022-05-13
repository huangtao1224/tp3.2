<?php
class cartAction extends publicAction {
	public function add_cart()
	{
		$member=session('member');
		if(!empty($member))
		{
			$data['name']=serialize(explode(',',pg('name')));
			$data['value']=serialize(explode(',',pg('value')));
			$data['goods_id']=pg('goods_id');

			$cart=M('cart')->where($data)->find();
			if(empty($cart))
			{
				$data['member_id']=$member['member_id'];
				$data['date']=time();
				$data['number']=pg('number');
				$data['price']=get_price($data['goods_id'],pg('value'));
				$id = M('cart')->data($data)->add();
			}
			else
			{
				$id = M('cart')->where($data)->save(array('number'=>($cart['number']+pg('number'))));
			}
			$json_arr['cart_number']=M('cart')->where(array('member_id'=>$member['member_id']))->count();
			$json_arr['note']='加入购物车成功';
		}
		else
		{
			$json_arr['note']='请先登录';
		}
		echo json_encode($json_arr);
	}
	
	public function del_cart()//删除购物车商品
	{
		$cart_id=pg('cart_id');
		if($cart_id!='')
		{
			M('cart')->where(array('cart_id'=>$cart_id))->delete();//删除关联
			echo "删除成功";
		}
	}
	public function cart_total_amount()//统计购车物总额
	{
		$cart_id=explode(',',pg('cart_id'));
		$arr=array();
		foreach($cart_id as $k=>$v)
		{
			$cart=M('cart')->where(array('cart_id'=>$v))->find();
			$arr['cart_total_amount']+=$cart['price']*$cart['number'];
		}
		echo json_encode($arr);
	}
	
	public function edit_cart_save()//修改购物车
	{
		$cart_id=pg('cart_id');
		$number=pg('number');
		if($cart_id!='')
		{
			M('cart')->where(array('cart_id'=>$cart_id))->save(array('number'=>$number));
			$cart=M('cart')->where(array('cart_id'=>$cart_id))->find();
			$arr['sum_price']=$cart['price']*$cart['number'];
		}
		echo json_encode($arr);
	}
	
	public function area_region()//获取收货地址运费ID
	{
		$shopping_address=M('shopping_address')->where(array('shopping_address_id'=>$this->shopping_address_id))->find();
		
		$area_region_list = M()->table('index_area_region a,index_shipping_area s')->where('a.region_id ='.$shopping_address['area'].' and a.shipping_area_id=s.shipping_area_id')->find();
		
		if(empty($area_region_list))
		{
			$area_region_list = M()->table('index_area_region a,index_shipping_area s')->where('a.region_id ='.$shopping_address['city'].' and a.shipping_area_id=s.shipping_area_id')->find();
		
		}
		
		if(empty($area_region_list))
		{
			$area_region_list = M()->table('index_area_region a,index_shipping_area s')->where('a.region_id ='.$shopping_address['province'].' and a.shipping_area_id=s.shipping_area_id')->find();
		}
		return $area_region_list;
	}
	
	public function freight()//运费计算
	{
		$this->shopping_address_id=pg('shopping_address_id');
		if($this->shopping_address_id!='')
		{
			$area_region_list=self::area_region();
			
			$id=explode(',',pg('id'));
			$name=explode(',',pg('name'));
			$goods_id=pg('goods_id');
			$number=pg('number');
			$goods_attribute_value_array = array();
			foreach($id as $k=>$v)
			{
				$goods_attribute_value_array[] =
				array(
					'id' => $v,
					'name' => $name[$k]
				);
			}
			

			$data['goods_attribute_value_array']=serialize($goods_attribute_value_array);
			$goods_attribute=M('goods_attribute')->where(array('goods_id'=>$goods_id,'goods_attribute_value_array'=>$data['goods_attribute_value_array']))->find();

			$json_arr[$v]=self::freight_formula(array('weight'=>$goods_attribute['weight'],'shipping_area_id'=>$area_region_list['shipping_area_id']))*$number;
			$freight_val+=$json_arr[$v];
			$json_arr['freight']=$freight_val;
			echo $freight_val;
			//echo json_encode($json_arr);
		}
	}

	public function freight_total()//多产品运费计算
	{
		$this->shopping_address_id=pg('shopping_address_id');
		if($this->shopping_address_id!='')
		{
			$area_region_list=self::area_region();

			$shopping_cart_id=explode(',',pg('shopping_cart_id'));
			foreach($shopping_cart_id as $k=>$v)
			{
				$shopping_cart=M('shopping_cart')->where(array('shopping_cart_id'=>$v))->find();
				$goods_attribute=M('goods_attribute')->where(array('goods_id'=>$shopping_cart['goods_id'],'goods_attribute_value_array'=>$shopping_cart['goods_attribute_value_array']))->find();
				
				$json_arr[$v]=self::freight_formula(array('weight'=>$goods_attribute['weight'],'shipping_area_id'=>$area_region_list['shipping_area_id']))*$shopping_cart['number'];
				$freight_val+=$json_arr[$v];
			}
			$json_arr['freight']=$freight_val;
			echo $freight_val;
			//echo json_encode($json_arr);
		}
	}

	public function freight_formula($arr=array())//运费计算公式
	{
		$shipping_area=M('shipping_area')->where(array('shipping_area_id'=>$arr['shipping_area_id']))->find();
		$arr['weight']=ceil($arr['weight']);
		if($arr['weight']>1)
		{
			return (($arr['weight']-1)*$shipping_area['step_fee'])+$shipping_area['base_fee'];//按重量计算运费
		}
		else
		{
			return $shipping_area['base_fee'];//按重量计算运费
		}
	}


}