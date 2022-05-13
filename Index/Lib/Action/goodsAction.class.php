<?php
class goodsAction extends Action {
    public function index(){
		$this->display();
    }

    public function details(){
        $this->display();
    }
    public function group(){
		$this->display();
    }

    public function group_detail(){
        $this->display();
    }
    public function convert_list(){
        $this->display();
    }
    public function convert_detail(){
        $this->display();
    }
    public function looking(){
        $this->display();
    }
    public function looking_detail(){
        $this->display();
    }
    public function recommend_list(){
        $this->display();
    }
    public function recommend_detail(){
        $this->display();
    }
    public function snapping_list(){
        $this->display();
    }
	public function snapping_detail(){
        $this->display();
    }
	public function whole(){
        $this->display();
    }
	public function search_list(){
        $this->display();
    }
	public function specifications_select()
	{
		$goods_id=pg('goods_id');

		$id=explode(',',pg('id'));
		$name=explode(',',pg('name'));
		$goods_attribute_value_array = array();
		foreach($id as $k=>$v)
		{
			$goods_attribute_value_array[] =
			array(
				'id' => $v,
				'name' => $name[$k]
			);
		}
		$goods_attribute=M('goods_attribute')->field('goods_attribute_value_array,goods_id,price')->where(array('goods_id'=>$goods_id,'goods_attribute_value_array'=>serialize($goods_attribute_value_array)))->find();
        $goods_id=$goods_attribute['goods_id'];
        //$goods=M('activity_goods')->where(array('goods_id'=>$goods_id))->select();
        $model=new Model();
        $goods=$model->query("select relevance_id,goods_id,price,activity_price,activity_discount,activity_goods_number,goods_attribute_value_array,activity_name from  index_activity_goods left join index_activity on index_activity.activity_id=index_activity_goods.activity_id  where index_activity_goods.goods_id=$goods_id");
        if(!$goods){
            $goods_attribute['type']='no_disc';
            echo json_encode($goods_attribute);
        }else{
            $g_arr=array();
            foreach($goods as $v){
                $g_arr[]=$v;
                if($v['goods_attribute_value_array']==$goods_attribute['goods_attribute_value_array']){

                    $g_arr['type']='disc';
                    $g_arr['raw_price']=$v['price'];
                    $g_arr['font_price']=$v['activity_price'];
                    $g_arr['num']=sizeof($goods);
                }
            }
            echo json_encode($g_arr);
        }
	}

	public function package_specifications()
	{
		$goods_id=pg('goods_id');
		
		$id=explode(',',pg('id'));
		$name=explode(',',pg('name'));
		$goods_attribute_value_array = array();
		foreach($id as $k=>$v)
		{
			$goods_attribute_value_array[] =
			array(
				'id' => $v,
				'name' => $name[$k]
			);
		}
		$goods_attribute=M('goods_attribute')->field('goods_attribute_value_array,goods_id,price')->where(array('goods_id'=>$goods_id,'goods_attribute_value_array'=>serialize($goods_attribute_value_array)))->find();
		$goods_id=$goods_attribute['goods_id'];
		echo json_encode($goods_attribute);
	}
	
	public function get_coupons()
	{
		$coupons_id=pg('coupons_id');
		$member=session('member');
		if(!empty($member))
		{
			if($coupons_id!='')
			{
				$coupons_member = M('coupons_member')->where(array('coupons_id'=>$coupons_id,'member_id'=>$member['member_id']))->find();
				if(empty($coupons_member))
				{
					$coupons=M('coupons')->where(array('coupons_id'=>$coupons_id))->find();
					M('coupons_member')->data(array(
					'type_id'=>40,
					'date'=>time(),
					'version_id'=>1,
					'coupons_id'=>$coupons_id,
					'price'=>$coupons['price'],
					'effective_date'=>$coupons['effective_date'],
					'member_id'=>$member['member_id'],
					'state'=>277
					))->add();
					echo '领取成功';
				}
				else
				{
					echo '您已经领过优惠券了';
				}
			}
		}
		else
		{
			echo '请先登录';
		}
	}
}



