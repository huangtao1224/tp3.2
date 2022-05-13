<?php
class memberAction extends Action {
	public function add_account_save()
	{
		$data = pg('data');
		$balance = M('account')->where(array('member_id'=>$data['member_id']))->order('account_id desc')->getField('balance');		
		if($data['amount_type']==1)
		{
			$data['balance']=$balance+$data['amount'];//余额累加
		}
		else
		{
			$data['balance']=$balance-$data['amount'];//余额去除
		}
		$list = M('input')->where(array('type_id' => $type_id, 'edit_switch' => 2, 'input_type_id' => 4))->select();
		foreach($list as $k => $v){
			$data[$v['field_name']]=serialize($data[$v['field_name']]);
		}

		$list = M('input')->where(array('type_id' => $type_id, 'edit_switch' => 2, 'input_type_id' => 7))->select();
		foreach($list as $k => $v){
			if(!empty($_FILES[$v['field_name']]['tmp_name'])){
				$data[$v['field_name']] = $this->up_file(array('name' => $v['field_name']));
			}
		}
		$list = M('input')->where(array('type_id' => $type_id, 'edit_switch' => 2, 'input_type_id' => 8))->select();
		foreach($list as $k => $v)
		{
			$data[$v['field_name']]=strtotime($data[$v['field_name']]);
		}
		$id = M('account')->data($data)->add();
		echo '操作成功';
	}
}

