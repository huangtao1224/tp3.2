<?php
// 本类由系统自动生成，仅供测试用途
class classifyAction extends Action {

    public function index(){
        $this->display();
    }
	public function add_save()
	{
		$data=pg('data');
		$list = M('classify')->field('classify_id,level_id,classify_pid,classify_name')->where(array('classify_id'=>$data['classify_pid']))->select();
		$data['level_id']=$list[0]['level_id']+1;
		$type_id=4;
		
		$list = M('input')->where(array('type_id' => $type_id, 'edit_switch' => 2, 'input_type_id' => 7))->select();
		foreach($list as $k => $v)
		{
			if(!empty($_FILES[$v['field_name']]['tmp_name']))
			{
				if(file_exists($content[0][$v['field_name']]))
				{
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
		M('classify')->data($data)->add();
		echo '操作成功';
	}
	public function edit_save()
	{
		$data=pg('data');
		if($data['classify_pid']!='')
		{
			$list = M('classify')->field('classify_id,level_id,classify_pid,classify_name')->where(array('classify_id'=>$data['classify_pid']))->select();
			$data['level_id']=$list[0]['level_id']+1;
		}
		$classify_id=pg('classify_id');
		session('classify_id',$classify_id);
		$type_id=4;
        $content = M('classify')->where(array('classify_id' => $classify_id))->find();
		$list = M('input')->where(array('type_id' => $type_id, 'edit_switch' => 2, 'input_type_id' => 7))->select();
		foreach($list as $k => $v)
		{
			if(!empty($_FILES[$v['field_name']]['tmp_name']))
			{
				if(file_exists($content[0][$v['field_name']]))
				{
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
        $list = M('input')->where(array('type_id' => $type_id, 'edit_switch' => 2, 'input_type_id' => 9))->select();
        foreach($list as $k => $v)
        {
            if(!empty($data[$v['field_name']])){
                if(file_exists($content[$v['field_name']]))
                {
                    unlink($content[$v['field_name']]);	//通过文件路径来删除
                }
            }

        }
		M('classify')->where(array('classify_id'=>$classify_id))->save($data);
		echo '操作成功';
	}
	public function del_save()//删除分类
	{
		$classify_id=pg('classify_id');
		if($classify_id!=""){
			M('classify')->where(array('classify_id'=>$classify_id))->delete();
			echo '操作成功';
		}
		//$classify_id!=''?M('classify')->where(array('classify_id'=>$classify_id))->delete():'';
		
	}
	public function batch_edit_save()
	{
		$classify_id=pg('classify_id');
		$data=pg('data');
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
			M('classify')->where(array('classify_id'=>$classify_id[$k]))->save($v);
		}
		echo '操作成功';
	}

}


