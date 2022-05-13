<?php
class cmsAction extends Action {
	public function add_save()
	{
		$data = pg('data');
		$type_id = $data['type_id'];
		if($data['password']!='' && $type_id==46)
		{
			$data['password']=md5($data['password']);
		}
		$table_name = M('classify_type')->where(array('type_id' => $type_id))->getField('table_name');
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

		$id = M($table_name)->data($data)->add();
		echo '操作成功';
	}
	public function edit_save()
	{
		$data = pg('data');
		$type_id = pg('type_id');
		$content_id = pg('content_id');
		if($type_id==46)
		{
			if($data['password']!='')
			{
				$data['password']=md5($data['password']);
			}
			else
			{
				unset($data['password']);
			}
		}
		$table_name = M('classify_type')->where(array('type_id' => $type_id))->getField('table_name');
		$content = M($table_name)->where(array($table_name.'_id' => $content_id))->select();
		$list = M('input')->where(array('type_id' => $type_id, 'edit_switch' => 2, 'input_type_id' => 4))->select();
		foreach($list as $k => $v){
			$data[$v['field_name']]=serialize($data[$v['field_name']]);
		}

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

	public function del_save()//删除内容
	{
		$content_id = pg('content_id');
		$table_name = pg('table_name');
		$type_id = pg('type_id');

		$content = M($table_name)->where(array($table_name.'_id' => $content_id))->find();
		if(!$content || !$type_id){
			echo '操作失败';
			die;
		}

		$list = M('input')->where(array('type_id' => $type_id, 'edit_switch' => 2, 'input_type_id' => 7))->select();
		foreach($list as $k => $v){
			if(file_exists($content[$v['field_name']])){
				unlink($content[$v['field_name']]);	//通过文件路径来删除
			}
		}

		M($table_name)->where(array($table_name.'_id' => $content_id))->delete();
		echo '操作成功';
	}
}
