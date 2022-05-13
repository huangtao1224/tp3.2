<?php
class orderAction extends Action {
	
		/*
		订单列表
		*/
	
	public function index(){
		
		$this->display();
	}
	
	public function express(){
		header("Content-type:text/html;charset=utf-8");
		
		
		$typeNu=pg('typeNu');
		$typeCom=pg('typeCom');
		
		$typeCom=M('shipping')->where(array('shipping_id'=>$typeCom))->getField('shipping_en');
		$AppKey='6cde403d468648a6';
		$url ='http://www.kuaidi100.com/applyurl?key='.$AppKey.'&com='.$typeCom.'&nu='.$typeNu.' ';
		//http://api.kuaidi100.com/api?id=6cde403d468648a6&com=zhongtong&nu=403375443273&show=3&muti=1&order=asc
		$get_content=file_get_contents($url);
		$this->assign('get_content',$get_content);
		$this->display();
	}
	
	public function order(){
		$this->display();
		}	
	//修改商品样式
	public function edit_goods_save(){
		if(IS_POST){
		$order_goods_id=pg('content_id');
		$goods_id=pg('goods_id');
		$number=pg('number');
		$goods_name=pg('goods_name');
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

		$goods_attribute=M('goods_attribute')->where(array('goods_id'=>$goods_id,'goods_attribute_value_array'=>serialize($goods_attribute_value_array)))->find();
				$data['goods_attribute_array']=$goods_attribute['goods_attribute_array'];
				$data['number']=$number;
				$data['goods_name']=$goods_name;
		M('order_goods')->where(array('order_goods_id'=>$order_goods_id,))->save($data);
		
     	}
		echo '操作成功';
	}	
		
	public function edit_save()
	{
		$data = pg('data');
		$type_id = pg('type_id');
		$content_id = pg('content_id');

		$table_name = M('classify_type')->where(array('type_id' => $type_id))->getField('table_name');
		$content = M($table_name)->where(array($table_name.'_id' => $content_id))->select();
		$list = M('input')->where(array('type_id' => $type_id, 'edit_switch' => 2, 'input_type_id' => 7))->select();
		foreach($list as $k => $v){
			if(!empty($_FILES[$v['field_name']]['tmp_name'])){
				if(file_exists($content[0][$v['field_name']])){
					unlink($content[0][$v['field_name']]);	//通过文件路径来删除
				}
				$data[$v['field_name']] = $this->up_file(array('name' => $v['field_name']));
			}
		}
		$list = M('input')->where(array('type_id' => $type_id, 'edit_switch' => 2, 'input_type_id' => 8))->select();
		foreach($list as $k => $v)
		{
			$data[$v['field_name']]=strtotime($data[$v['field_name']]);
		}		
		if($content_id != ''){
			M($table_name)->where($table_name . '_id = ' . $content_id)->save($data);
		}
		echo '操作成功';
	}

	// public function edit_save()
	// {
	// 	$data = pg('data');
	// 	$type_id = pg('type_id');
	// 	$content_id = pg('content_id');

	// 	$table_name = M('classify_type')->where(array('type_id' => $type_id))->getField('table_name');
	// 	$content = M($table_name)->where(array($table_name.'_id' => $content_id))->select();
	// 	$list = M('input')->where(array('type_id' => $type_id, 'edit_switch' => 2, 'input_type_id' => 7))->select();
	// 	foreach($list as $k => $v){
	// 		if(!empty($_FILES[$v['field_name']]['tmp_name'])){
	// 			if(file_exists($content[0][$v['field_name']])){
	// 				unlink($content[0][$v['field_name']]);	//通过文件路径来删除
	// 			}
	// 			$data[$v['field_name']] = $this->up_file(array('name' => $v['field_name']));
	// 		}
	// 	}
	// 	$list = M('input')->where(array('type_id' => $type_id, 'edit_switch' => 2, 'input_type_id' => 8))->select();
	// 	foreach($list as $k => $v)
	// 	{
	// 		$data[$v['field_name']]=strtotime($data[$v['field_name']]);
	// 	}		

	// 	if($content_id != ''){
	// 		M($table_name)->where($table_name . '_id = ' . $content_id)->save($data);
	// 	}
	// 	echo '操作成功';
	// }
  		/*
		
		订单详情
		*/
		
	public function order_detail(){
		
		
		$this->display();
		}
	
	/*
	提交订单
	*/
	public function submit_order()
	{
		$member=session('member');
		if(!empty($member))
		{
			$shopping_address_id=pg('shopping_address_id');
			$shopping_cart_id=pg('shopping_cart_id');
			$coupons=pg('coupons');//优惠券
			$coupons_member_id=pg('coupons_member_id');
			$sum=pg('sum');
			$time=time();
			
			/*
			收货地址构造
			*/
			$shopping_address=M('shopping_address')->where(array('shopping_address_id'=>$shopping_address_id))->find();
			$data['consignee']=$shopping_address['consignee'];
			$data['phone']=$shopping_address['phone'];
			$data['zip']=$shopping_address['zip'];
			$data['freight']=pg('freight');
			$data['order']=$shopping_address['consignee'];
			$province=M('region')->where(array('region_id'=>$shopping_address['province']))->find();
			$city=M('region')->where(array('region_id'=>$shopping_address['city']))->find();
			$area=M('region')->where(array('region_id'=>$shopping_address['area']))->find();
			$data['address']=$province['region_name'].' '.$city['region_name'].' '.$area['region_name'].' '.$shopping_address['address'];
			$data['order_number']=cover_time(time(),'YmdHis');
			$data['member_id']=$member['member_id'];
			$data['state']=261;
			$data['date']=$time;
			$data['shopping_address_id']=$shopping_address_id;
			$data['price']=$sum+$data['freight'];//订单付款金额			
			if($coupons==1)
			{
				$coupons_member = M('coupons_member')->where(array('state'=>277,'member_id'=>$member['member_id'],'coupons_member_id'=>$coupons_member_id))->find();
				$data['price']-=$coupons_member['price'];//使用优惠券
				$data['coupons_member_id']=$coupons_member_id;
				$data['coupons_price']=$coupons_member['price'];
			}
			$order_id = M('order')->data($data)->add();
			foreach($shopping_cart_id as $k=>$v)
			{
				$order_goods=M('shopping_cart')->where(array('shopping_cart_id'=>$v,'member_id'=>$member['member_id']))->field('goods_id,member_id,goods_name,goods_img,number,goods_attribute_value_array,goods_attribute_array,goods_classify_id')->find();
				if(!empty($order_goods))
				{
					$goods_attribute=M('goods_attribute')->where(array('goods_id'=>$order_goods['goods_id'],'goods_attribute_value_array'=>$order_goods['goods_attribute_value_array']))->find();
					$order_goods['date']=$time;
					$order_goods['price']=$goods_attribute['price'];
					$order_goods['order_number']=$data['order_number'];
					$order_goods['order_id']=$order_id;
					$order_goods['shopping_address_id']=$shopping_address_id;
					$id = M('order_goods')->data($order_goods)->add();
					M('coupons_member')->where(array('coupons_member_id'=>$coupons_member_id,'state'=>277,'member_id'=>$member['member_id']))->save(array('state'=>278));//更新优惠券已使用
					
					M('shopping_cart')->where(array('shopping_cart_id'=>$v,'member_id'=>$member['member_id']))->delete();
				}
			}
			$this->success('订单提交成功',U('order/pay?order_id='.$order_id));
		}
		else
		{
			$this->error('请先登录',U('member/login'));
		}
	}

	/*
	套餐提交订单
	*/
	public function package_buy_save()
	{
		$member=session('member');
		$shopping_address_id=pg('shopping_address_id');
		$package_id=pg('package_id');
		$number=pg('number');
		$sum=pg('sum');
		$coupons=pg('coupons');//优惠券
		$coupons_member_id=pg('coupons_member_id');
		$id=pg('id');
		$name=pg('name');
		$time=time();
		if(!empty($member) && $package_id!='')
		{
			/*
			收货地址构造
			*/

			$shopping_address=M('shopping_address')->where(array('shopping_address_id'=>$shopping_address_id))->find();
			$data['consignee']=$shopping_address['consignee'];
			$data['phone']=$shopping_address['phone'];
			$data['zip']=$shopping_address['zip'];
			$data['freight']=pg('freight');
			$data['order']=$shopping_address['consignee'];
			$province=M('region')->where(array('region_id'=>$shopping_address['province']))->find();
			$city=M('region')->where(array('region_id'=>$shopping_address['city']))->find();
			$area=M('region')->where(array('region_id'=>$shopping_address['area']))->find();
			$data['address']=$province['region_name'].' '.$city['region_name'].' '.$area['region_name'].' '.$shopping_address['address'];
			$data['order_number']=cover_time(time(),'YmdHis');
			$data['member_id']=$member['member_id'];
			$data['state']=261;
			$data['date']=$time;
			$data['shopping_address_id']=$shopping_address_id;
			$data['price']=$sum+$data['freight'];//订单付款金额
			if($coupons==1)
			{
				$coupons_member = M('coupons_member')->where(array('state'=>277,'member_id'=>$member['member_id'],'coupons_member_id'=>$coupons_member_id))->find();
				$data['price']-=$coupons_member['price'];//使用优惠券
				$data['coupons_member_id']=$coupons_member_id;
				$data['coupons_price']=$coupons_member['price'];
			}
			$order_id = M('order')->data($data)->add();
			
			$package  = M('package')->where(array('package_id'=>$package_id))->find();
			$goods_id_array=unserialize($package['goods_id_array']);
			$goods=M('goods')->field('goods_id')->where(array('goods_id'=>array('in',$goods_id_array)))->select();
			
			foreach($goods as $k=>$v)
			{
				$order_goods=M('goods')->where(array('goods_id'=>$v['goods_id']))->field('goods_id,goods_name,goods_img,goods_classify_id')->find();
				if(!empty($order_goods))
				{
					$id[$k]=explode(',',$id[$k]);
					$name[$k]=explode(',',$name[$k]);
					$goods_attribute_value_array = array();

					foreach($id[$k] as $key=>$val)
					{
						$goods_attribute_value_array[] =
						array(
							'id' => $val,
							'name' => $name[$k][$key]
						);
					}
					
					$goods_attribute=M('goods_attribute')->where(array('goods_id'=>$order_goods['goods_id'],'goods_attribute_value_array'=>serialize($goods_attribute_value_array)))->find();

					$order_goods['date']=$time;
					$order_goods['member_id']=$member['member_id'];
					$order_goods['goods_attribute_array']=$goods_attribute['goods_attribute_array'];
					$order_goods['goods_attribute_value_array']=$goods_attribute['goods_attribute_value_array'];
					$order_goods['price']=$goods_attribute['price'];
					$order_goods['number']=$number;
					$order_goods['order_number']=$data['order_number'];
					$order_goods['order_id']=$order_id;
					$order_goods['shopping_address_id']=$shopping_address_id;
					M('order_goods')->data($order_goods)->add();
				}
			}
			M('coupons_member')->where(array('coupons_member_id'=>$coupons_member_id,'state'=>277,'member_id'=>$member['member_id']))->save(array('state'=>278));//更新优惠券已使用
			$this->success('订单提交成功',U('order/pay?order_id='.$order_id));
		}
		else
		{
			$this->error('请先登录',U('member/login'));
		}
	}
		public function buy_now_save()
	{
		$member=session('member');
		$shopping_address_id=pg('shopping_address_id');
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

			$shopping_address=M('shopping_address')->where(array('shopping_address_id'=>$shopping_address_id))->find();
			$data['consignee']=$shopping_address['consignee'];
			$data['phone']=$shopping_address['phone'];
			$data['zip']=$shopping_address['zip'];
			$data['freight']=pg('freight');			
			$data['order']=$shopping_address['consignee'];
			$province=M('region')->where(array('region_id'=>$shopping_address['province']))->find();
			$city=M('region')->where(array('region_id'=>$shopping_address['city']))->find();
			$area=M('region')->where(array('region_id'=>$shopping_address['area']))->find();
			$data['address']=$province['region_name'].' '.$city['region_name'].' '.$area['region_name'].' '.$shopping_address['address'];
			$data['order_number']=cover_time(time(),'YmdHis');
			$data['member_id']=$member['member_id'];
			$data['state']=261;
			$data['date']=$time;
			$data['shopping_address_id']=$shopping_address_id;
			$data['price']=$sum+$data['freight'];//订单付款金额
			if($coupons==1)
			{
				$coupons_member = M('coupons_member')->where(array('state'=>277,'member_id'=>$member['member_id'],'coupons_member_id'=>$coupons_member_id))->find();
				$data['price']-=$coupons_member['price'];//使用优惠券
				$data['coupons_member_id']=$coupons_member_id;
				$data['coupons_price']=$coupons_member['price'];
			}
			$order_id = M('order')->data($data)->add();
			
			$order_goods=M('goods')->where(array('goods_id'=>$goods_id))->field('goods_id,goods_name,goods_img,goods_classify_id')->find();
			if(!empty($order_goods))
			{
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
					
				$goods_attribute=M('goods_attribute')->where(array('goods_id'=>$order_goods['goods_id'],'goods_attribute_value_array'=>serialize($goods_attribute_value_array)))->find();
				$order_goods['date']=$time;
				$order_goods['member_id']=$member['member_id'];
				$order_goods['goods_attribute_array']=$goods_attribute['goods_attribute_array'];
				$order_goods['goods_attribute_value_array']=$goods_attribute['goods_attribute_value_array'];
				$order_goods['price']=$goods_attribute['price'];
				$order_goods['number']=$number;
				$order_goods['order_number']=$data['order_number'];
				$order_goods['order_id']=$order_id;
				$order_goods['shopping_address_id']=$shopping_address_id;
				$id = M('order_goods')->data($order_goods)->add();
				M('coupons_member')->where(array('coupons_member_id'=>$coupons_member_id,'state'=>277,'member_id'=>$member['member_id']))->save(array('state'=>278));//更新优惠券已使用
			}
			$this->success('订单提交成功',U('order/pay?order_id='.$order_id));
		}
		else
		{
			$this->error('请先登录',U('member/login'));
		}
	}

	public function batch_edit_save()
	{
		$batch_delete_id=pg('batch_delete_id');
		$order_id=pg('order_id');
		$type_id= 31;
		$table_name='order';
		if($batch_delete_id!='')
		{
			foreach($order_id as $k=>$v)
			{
				if($v!='' && $type_id!='')
				{
					$content = M($table_name)->where(array($table_name.'_id' => $v))->find();
					if(!$content || !$type_id){
						echo '操作失败';
						die;
					}
			
					$list = M('input')->where(array('type_id' => $type_id, 'edit_switch' => 2, 'input_type_id' => 7))->select();
					foreach($list as $key => $val){
						if(file_exists($content[$val['field_name']])){
							unlink($content[$val['field_name']]);	//通过文件路径来删除
						}
					}
					
					M($table_name)->where(array($table_name.'_id' => $v))->delete();
					M('relevance')->where(array('content_id' => $v, 'type_id' => $type_id))->delete();
					//echo '操作成功';
				}
			}
		}
		$export_order=pg('export_order');
		if($export_order!='')
		{
			$xlsName  = "order";
			$xlsCell  = array(
			//array('order_id','订单号'),
			array('consignee','收件人姓名'),
			array('address','收件人详细地址'),
			array('company','收件人单位名称'),
			array('phone','收件人手机电话'),
			array('zip','收件人邮编'),
			array('goods_name','物品名称'),
			array('number','物品数量'),
			array('weight','重量'),
			array('order_number','订单编号'),
			array('message','买家留言'),
			array('price','代收货款'),
			array('note','备注')
			);
	
			foreach($order_id as $k=>$v)
			{
				$order = M('order')->where(array('order_id'=>$v))->find();
				$order_goods=M('order_goods')->where(array('order_id'=>$order['order_id']))->find();
				//$xlsData[$k]['order_id']=' '.$order['order_number'];
				$xlsData[$k]['consignee']=$order['consignee'];
				$xlsData[$k]['address']=$order['address'];
				$xlsData[$k]['company']=$order['consignee'];
				$xlsData[$k]['zip']=$order['zip'];
				$xlsData[$k]['phone']=' '.$order['phone'];
				$xlsData[$k]['goods_name']=$order_goods['goods_name'];
				$xlsData[$k]['number']=$order_goods['number'];
				$xlsData[$k]['weight']='';
				$xlsData[$k]['order_number']=' '.$order['order_number'];
				$xlsData[$k]['message']=$order['message'];
				$xlsData[$k]['price']=$order['price'];
				$xlsData[$k]['note']='请开箱检查再签收';
			}
			$this->exportExcel($xlsName,$xlsCell,$xlsData);
	
		}
		goto_url(U("order/index"));
		//echo '操作成功';
	}
	public function exportExcel($expTitle,$expCellName,$expTableData)
	{
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = date('YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        vendor("PHPExcel.PHPExcel");
       
        $objPHPExcel = new PHPExcel();
		$cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
		
        //$objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
       // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));  
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'1', $expCellName[$i][1]);
			$objPHPExcel->getActiveSheet(0)->getStyle($cellName[$i].'1')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet(0)->getStyle($cellName[$i])->getFont()->setSize(9);
			$objPHPExcel->getActiveSheet(0)->getStyle($cellName[$i].'1')->getFont()->setSize(9);
			
			$objPHPExcel->getActiveSheet(0)->getStyle($cellName[$i].'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}

		$objPHPExcel->getActiveSheet(0)->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('B')->setWidth(35);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('C')->setWidth(18);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('D')->setWidth(18);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('F')->setWidth(60);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('G')->setWidth(13);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('H')->setWidth(8);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('I')->setWidth(15);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('J')->setWidth(13);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('K')->setWidth(15);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('L')->setWidth(20);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('M')->setWidth(20);


          // Miscellaneous glyphs, UTF-8
        for($i=0;$i<$dataNum;$i++){
          for($j=0;$j<$cellNum;$j++){
            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+2), $expTableData[$i][$expCellName[$j][0]]);
          }
        }
        
		header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
		ob_end_clean();//清除缓冲区
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output');
		//exit;
	}
/**
     *
     * 导出Excel
     */
	public function expUser(){//导出Excel
    }
	public function del_save()
	{
		$order_id=pg('order_id');
		$order_id!=''?M('order')->where(array('order_id'=>$order_id))->delete():'';
		$order_id!=''?M('order_goods')->where(array('order_id'=>$order_id))->delete():'';
		echo '操作成功';
	}

}