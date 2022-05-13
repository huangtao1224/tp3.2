<?php
class inputAction extends Action {

    public function index(){
        $this->display();
    }
	public function add_save()
	{
		$data=pg('data');
		M('input')->data($data)->add();
		$list=M('data_type')->where(array('data_type_id'=>$data['data_type_id']))->find();
		if($list['value']=='longtext')
		{
			$field_type=$list['value'];
		} 
		else if($list['value'] == 'decimal')
		{
            $field_type=$list['value'].'('.$data['data_length'].','.$data['decimal_point'].')';
        }
		else
		{
			$field_type=$list['value'].'('.$data['data_length'].')';
		}
		$list=M('classify_type')->where(array('type_id'=>$data['type_id']))->find();
				
		$sql='ALTER TABLE index_'.$list['table_name'].' add '.$data['field_name'].' '.$field_type;
		$res=M()->query($sql);

			
		echo '操作成功！';
		
		
		
	}
	
	public function del_save()
	{
		$input_id=pg('input_id');

		$type_id=pg('type_id');

		if($input_id!='' && $type_id!='')
		{
			$list=M('input')->where(array('input_id'=>$input_id))->find();

			$field_name=$list['field_name'];

			$list=M('data_type')->where(array('data_type_id'=>$list['data_type_id']))->find();

			$list=M('classify_type')->where(array('type_id'=>$type_id))->find();

			$table_name='index_'.$list['table_name'];
			
			M('input')->where(array('input_id'=>$input_id))->delete();

			$sql='ALTER TABLE '.$table_name.' drop column '.$field_name;
			
			M()->query($sql);


			echo '操作成功';

		}
	}
	
	public function batch_edit_save()
	{
		$data=pg('data');
		$type_id=pg('type_id');
		foreach($data as $k=>$v)
		{
			foreach($v as $key=>$value)
			{
				$array[$key][$k]=$value;
			}
		}
		foreach($array as $k=>$v)
		{
			$v['date']=strtotime($v['date']);
			$list=M('input')->where(array('input_id'=>$v['input_id']))->find();
			$arr['z']=$list['field_name'];

			M('input')->where(array('input_id'=>$v['input_id']))->save($v);

			$list=M('data_type')->where(array('data_type_id'=>$v['data_type_id']))->find();

			if($list['value']=='longtext')
			{
				$arr['l']=$list['value'];
			}
			else
			{
				$arr['l']=$list['value'].'('.$v['data_length'].')';
			}

			$arr['new_z']=$v['field_name'];

			$list=M('classify_type')->where(array('type_id'=>$type_id))->find();
			
			$sql='ALTER TABLE index_'.$list['table_name'].' change '.$arr['z'].' '.$arr['new_z'].' '.$arr['l'].' NULL';

			M()->query($sql);
			//echo M()->getlastsql();
			
		}

		echo '操作成功';
	}
	public function child_add_save()
	{
		$data=pg('data');
		M('input')->data($data)->add();
		echo '操作成功';
	}
}
