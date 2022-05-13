<?php
class complaintsAction extends Action {
    public function index(){
		$this->display();
    }

    public function details(){
        $this->display();
    }
	public function add_save()
	{
		$data = pg('data');
		$type_id = $data['type_id'];
		$classify_id = pg('classify_id');
		$table_name = M('classify_type')->where(array('type_id' => $type_id))->getField('table_name');
		$content = M($table_name)->where(array($table_name.'_id' => $content_id))->select();
		$list = M('input')->where(array('type_id' => $type_id, 'show_switch' => 2, 'input_type_id' => 4))->select();
		foreach($list as $k => $v){
			$data[$v['field_name']]=serialize($data[$v['field_name']]);
		}

		$list = M('input')->where(array('type_id' => $type_id, 'show_switch' => 2, 'input_type_id' => 7))->select();
		foreach($list as $k => $v){
			if(!empty($_FILES[$v['field_name']]['tmp_name'])){
				$data[$v['field_name']] = $this->up_file(array('name' => $v['field_name']));
			}
		}

		$list = M('input')->where(array('type_id' => $type_id, 'show_switch' => 2, 'input_type_id' => 8))->select();
		foreach($list as $k => $v)
		{
			$data[$v['field_name']]=strtotime($data[$v['field_name']]);
		}

		$id = M($table_name)->data($data)->add();
		M('relevance')->data(array('classify_id'=> $classify_id, 'content_id' => $id, 'main_id' => 1, 'type_id' => $type_id))->add();
		$this->success('提交成功');
	}
}
