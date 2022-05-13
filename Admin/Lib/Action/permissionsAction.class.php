<?php
class permissionsAction extends Action {

    public function index(){
        $this->display();
    }
	public function add_save()
	{
		$data=pg('data');
		$data['password']=md5($data['password']);
		$id = M('user')->data($data)->add();
		if($id){
		    if($data['permissions']==2){
                foreach ($data['admin_classify_id'] as $k=>$v){
                    $data2['user_id'] = $id;
                    $data2['classify_id'] = $v;
                    M('user_classify')->data($data2)->add();
                }
            }else if($data['permissions']==3){
                foreach ($data['permission_id'] as $k=>$v){
                    $data3['user_id'] = $id;
                    $data3['admin_classify_id'] = $v;
                    $data3['permission_name'] = explode('_',$v)[1];
                    M('permission')->data($data3)->add();
                }
                foreach ($data['c_permission_id'] as $k=>$v){
                    $data4['user_id'] = $id;
                    $data4['classify_id'] = $v;
                    $data4['permission_name'] = explode('_',$v)[1];
                    M('permission')->data($data4)->add();
                }
            }
            echo '操作成功';
        }
	}
	public function edit_save()
	{
		$data=pg('data');
		$user_id=pg('user_id');
		$user=M('user')->where(array('user_id'=>$user_id))->find();
		if($user_id!='')
		{
			if($data['password']!='')
			{
				if(intval($user['secret'])){
					$data['password']=md5(md5($data['password']).$user['secret']);
				}else{
					$data['password']=md5($data['password']);
				}
			}
			else
			{
				unset($data['password']);
			}
			M('user')->where(array('user_id'=>$user_id))->save($data);
			if($data['permissions']==2) {
                $list = M('user_classify')->where(array('user_id' => $user_id))->select();
                foreach ($list as $k => $v) {
                    if (!in_array($v['classify_id'], $data['admin_classify_id'])) {
                        M('user_classify')->where(array('user_id' => $user_id, 'classify_id' => $v['classify_id']))->delete();
                    }
                }
                foreach ($data['admin_classify_id'] as $k => $v) {
                    $data2 = array('user_id' => $user_id, 'classify_id' => $v);
                    $admin_classify = M('user_classify')->where($data2)->find();
                    if (empty($admin_classify)) {
                        M('user_classify')->data($data2)->add();
                    }
                }
            }
		}
		echo '操作成功';
	}
	public function permissions_edit_save()
	{
	    $data = pg('data');
		$classify_id=pg('classify_id');
		$user_id=pg('user_id');
		$list=M('user_classify')->where(array('user_id'=>$user_id))->select();
		foreach($list as $k=>$v)
		{
			if(!in_array($v['classify_id'],$classify_id))
			{
				M('user_classify')->where(array('user_id'=>$user_id,'classify_id'=>$v['classify_id']))->delete();
			}
		}
		foreach($classify_id as $k=>$v)
		{
			$data=array('user_id'=>$user_id,'classify_id'=>$v);
			$user_classify=M('user_classify')->where($data)->find();
			if(empty($user_classify))
			{
				M('user_classify')->data($data)->add();
			}
		}
		echo '操作成功';
	}
    public function permissions_edit_save2()
    {
        $data = pg('data');
        $user_id=pg('user_id');

        $list = M('permission')->where(array('user_id'=>$user_id,'admin_classify_permission'=>array('neq','')))->select();
        foreach ($list as $k=>$v){
            if(!in_array($v['admin_classify_permission'],$data['permission_id']))
            {
                M('permission')->where(array('user_id'=>$user_id,'admin_classify_permission'=>$v['admin_classify_permission']))->delete();
            }
        }
        foreach ($data['permission_id'] as $k=>$v){
            $data2=array('user_id'=>$user_id,'admin_classify_permission'=>$v,'admin_classify_id'=>explode('_',$v)[0],'permission_name'=>explode('_',$v)[1]);
            $admin_classify=M('permission')->where($data2)->find();
            if(empty($admin_classify)) {
                M('permission')->data($data2)->add();
            }
        }
        $list2 = M('permission')->where(array('user_id'=>$user_id,'classify_permission'=>array('neq','')))->select();
        foreach ($list2 as $k=>$v){
            if(!in_array($v['classify_permission'],$data['c_permission_id']))
            {
                M('permission')->where(array('user_id'=>$user_id,'classify_permission'=>$v['classify_permission']))->delete();
            }
        }
        foreach ($data['c_permission_id'] as $k=>$v){
            $data3=array('user_id'=>$user_id,'classify_permission'=>$v,'classify_id'=>explode('_',$v)[0],'permission_name'=>explode('_',$v)[1]);
            $admin_classify=M('permission')->where($data3)->find();
            if(empty($admin_classify)) {
                M('permission')->data($data3)->add();
            }
        }
        echo '操作成功';
    }
	public function page_edit_save()//页面权限
	{
		$page=pg('page');
		$user_id=pg('user_id');
		$list=M('user_page')->where(array('user_id'=>$user_id))->select();
		foreach($list as $k=>$v)
		{
			if(!in_array($v['page'],$page))
			{
				M('user_page')->where(array('user_id'=>$user_id,'page'=>$v['page']))->delete();
			}
		}
		foreach($page as $k=>$v)
		{
			$data=array('user_id'=>$user_id,'page'=>$v);
			$user_page=M('user_page')->where($data)->find();
			if(empty($user_page))
			{
				M('user_page')->data($data)->add();
			}
		}
		echo '操作成功';
	}
	public function state_save(){

        $user_id = pg('id');
        $state = pg('state');
        $data['state'] = $state;
        $data['date'] = time();
        M('user')->where(array('user_id'=>$user_id))->save($data);
        if($state==1){
            echo '已启用';
        }else{
            echo '已禁用';
        }
    }
    public function del_save()//删除分类
    {
        $user_id=pg('user_id');
        if($user_id!=""){
            M('user')->where(array('user_id'=>$user_id))->delete();
            echo '操作成功';
        }
    }
    public function role_add_save(){ //角色添加

    }
    public function role_del_save(){ //角色删除
        $role_id=pg('role_id');
        if($role_id!=""){
            M('role')->where(array('role_id'=>$role_id))->delete();
            echo '操作成功';
        }
    }
}



