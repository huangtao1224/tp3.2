<?php
// 本类由系统自动生成，仅供测试用途
class typeAction extends Action {

    public function index(){
        $this->display();
    }
	public function add_save()
	{
		$data=pg('data');

		if($data['glids']){
			$str='';
			for($i=0; $i<count($data['glids']);$i++){
				$table_name=M('classify_type')->where('type_id='.$data['glids'][$i])->getField('table_name');
				$str.=$table_name.'_id int(10) NOT NULL,';
			}
			$data['glids']=implode(',',$data['glids']);
			$data['show_id']=1; $data['biaoshi']=2;
		}
		
		//创建数据库表
		$sql='
		CREATE TABLE index_'.$data['table_name'].' (
		  '.$data['table_name'].'_id int(10) NOT NULL AUTO_INCREMENT,
		  type_id int(10) NOT NULL,
		  date int(10) NOT NULL,
		  title varchar(99) NOT NULL,
		  keywords varchar(99) NOT NULL,
		  description varchar(10) NOT NULL,
		  version_id int(10) NOT NULL,'.$str.'
		  PRIMARY KEY ('.$data['table_name'].'_id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
		M()->query($sql);

		//记录类型
		M('classify_type')->data($data)->add();
		
		//创建前台相关页面
		if(!file_exists('Index/Lib/Action/'.$data['table_name'].'Action.class.php')){
			copy('Index/Lib/Action/messageAction.class.php','Index/Lib/Action/'.$data['table_name'].'Action.class.php');
			$content=read_file('Index/Lib/Action/'.$data['table_name'].'Action.class.php');
			$content=str_replace('messageAction',$data['table_name'].'Action',$content);
			write_file('Index/Lib/Action/'.$data['table_name'].'Action.class.php',$content);
			copy_dir('Index/Tpl/message','Index/Tpl/'.$data['table_name']);
		}
		echo '操作成功';
	}
	public function show_save()
	{
        $type_id = pg('type_id');
        $show_id = pg('show_id');
        $data['show_id'] = $show_id;
        M('classify_type')->where(array('type_id'=>$type_id))->save($data);
        if($show_id==2){
            echo '已显示';
        }else{
            echo '已隐藏';
        }
	}
	public function del_save()//删除类型
	{
		$type_id=pg('type_id');
		if($type_id!='')
		{
			$classify_type=M('classify_type')->where(array('type_id'=>$type_id))->find();
			$sql='DROP TABLE IF EXISTS index_'.$classify_type['table_name'].';';
			M()->query($sql);//删除表

			M('classify_type')->where(array('type_id'=>$type_id))->delete();//删除类型
			M('input')->where(array('type_id'=>$type_id))->delete();//删除表单

			echo '操作成功';

		}

	}

	//编辑
	public function edit_save(){
		$type_id=pg('type_id');$data=pg('data');
		$res=M('classify_type')->where('type_id='.$type_id)->find();
		$glids=implode(',',$data['glids']);
		

		//判断值是否一致，如有新增数据库追加字段，如有减少删除数据库字段
		if($glids != $res['glids'] && $glids){
			$arr=explode(',',$res['glids']);
			$newArr=$data['glids'];
			//查找数据中不同的值
			$c = array_merge(array_diff($arr,$newArr),array_diff($newArr,$arr));
		}
		//dump($c);die();
		if($c){
			$str="";$str2="";
			for($i=0; $i<count($c); $i++){
				$rs=M('classify_type')->where('type_id='.$c[$i])->find();
				if(in_array($c[$i],explode(',',$res['glids']))){
					$str2.=" DROP ".$rs['table_name'].'_id,';
				}else{
					$str.=' ADD '.$rs['table_name'].'_id int(10) NOT NULL,';
				}
				
			}
			$str=$str?trim($str,','):'';$str2=$str2?trim($str2,','):'';
			if($str){
				//数据库追加字段
				$sql = "ALTER TABLE index_".$res['table_name'].$str;
			}
			if($str2){
				//删除数据库字段
				$sql = "ALTER TABLE index_".$res['table_name'].$str2;
			}
			M()->query($sql);
		}
		M('classify_type')->where('type_id='.$type_id)->data(['glids'=>$glids])->save();
		echo "操作成功！";
	}
}
