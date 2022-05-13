<?php
// 本类由系统自动生成，仅供测试用途
class advancedAction extends Action {

    public function index(){
        $this->display();
    }
	public function add_save()
	{
		$data=pg('data');
		$list = M('classify')->field('classify_id,level_id,classify_pid,classify_name')->where(array('classify_id'=>$data['classify_pid']))->select();
		$data['level_id']=$list[0]['level_id']+1;
		$arr['data']=$data;
		M('classify')->data($data)->add();
		echo '操作成功';
	}
	public function edit_save()
	{
		$data=$this->_post('data');
		$list = M('classify')->field('classify_id,level_id,classify_pid,classify_name')->where(array('classify_id'=>$data['classify_pid']))->select();
		$data['level_id']=$list[0]['level_id']+1;
		$classify_id=pg('classify_id');
		if($classify_id!='')
		{
			M('classify')->where(array('classify_id'=>$classify_id))->save($data);
		}
		echo '操作成功';
	}
	public function del_save()//删除分类
	{
		$classify_id=pg('classify_id');
		$classify_id!=''?M('classify')->where(array('classify_id'=>$classify_id))->delete():'';
		echo '操作成功';
	}

}
