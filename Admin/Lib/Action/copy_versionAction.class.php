<?php
class copy_versionAction extends Action {
	public function index(){
		$this->display();
	}
	public function detail(){
        $this->display();
	}
	public function add_save()
	{
		$data = pg('data');
		$res=M('site')->where(array('version_name'=>$data['version_name']))->find();
		if($res){
			echo "请勿重复添加！";
			die();
		}
		$site=M('site')->order('version_id desc')->find();
		$data['version_id']=$site['version_id']+1;
		$id = M('site')->data($data)->add();
		$classify_id=1;
		for($i=1;$i<=$data['version_id'];$i++)
		{
			$classify_id=$classify_id*10;
		}
		$classify = M('classify')->where('classify_id='.$classify_id)->find();
		if($classify){
		    $classify_id=$classify_id*10;
        }
		$classify = M('classify')->where(array('version_id'=>1))->select();
		foreach($classify as $k=>$v)
		{
			$old_classify_id=$v['classify_id'];
			$v['version_id']=$data['version_id'];
			$v['classify_id']=$v['classify_id']*$classify_id;
			$v['classify_pid']=$v['classify_pid']*$classify_id;
			$id = M('classify')->data($v)->add();
			
			$table_name = M('classify_type')->where(array('type_id' => $v['type_id']))->getField('table_name');
			$relevance=M('relevance')->where(array('classify_id'=>$v['classify_id']))->select();
			$where = array();
			$where['r.classify_id'] = $old_classify_id;
			$where['r.type_id'] = $v['type_id'];
			$list = M()->table(C('DB_PREFIX') . $table_name . ' c left join ' . C('DB_PREFIX') . 'relevance r on r.content_id = c.' . $table_name . '_id')->where($where)->order('c.date desc')->select();
			foreach($list as $k2=>$v2)
			{
				$r=array();
				$r['main_id']=$v2['main_id'];
				$r['classify_id']=$v['classify_id'];
				$r['type_id']=$v2['type_id'];
				$v2['version_id']=$data['version_id'];
				unset($v2[$table_name.'_id']);
				unset($v2['content_id']);
				unset($v2['main_id']);
				unset($v2['classify_id']);
				foreach($v2 as $k3=>$v3)
				{
					if(file_exists($v3))
					{
						$img=preg_replace('/([0-9]{10})/isU','\1c'.mt_rand(10000,99999),$v3);
						copy($v3,$img);
						$v2[$k3]=$img;
					}
				}

				
				
				$r['content_id'] = M($table_name)->data($v2)->add();
				//echo M()->getlastsql().'<br>';
				$id= M('relevance')->data($r)->add();
				//echo M()->getlastsql().'<br>';
			}
		}
		
		copy_dir('Index',$data['version_directory']);
		copy('index.php',$data['version_directory'].'.php');
		$content=read_file($data['version_directory'].'.php');
		$content=str_replace('Index',$data['version_directory'],$content);
		write_file($data['version_directory'].'.php',$content);
		echo "操作成功！";
	}
	//删除版本
	public function del_save(){
		die();
		$site_id=pg('site_id');
		$res=M('site')->where('site_id = '.$site_id)->delete();
		if($res){
			echo "删除成功！";
		}
	}
}
