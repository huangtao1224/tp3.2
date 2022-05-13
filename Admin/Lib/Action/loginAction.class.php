<?php
class loginAction extends Action {

    public function index(){
        $this->display();
    }
	public function login_save()
	{
		$data['username'] = pg('username');
		//$data['password']= md5(pg('password'));
		$user = M('user')->where($data)->find();
		/*if(intval($user['secret'])){
			echo md5(md5(pg('password')).$user['secret']);die();
		}else{
			echo md5(pg('password'));die();
		}*/
		if($user['state']!=1){
            echo '账号已禁用，请联系管理员';
            exit();
        }
		//dump($user);die();
		if(intval($user['secret']) && $user['password'] != md5(md5(pg('password')).$user['secret'])){
			echo '账号密码错误';
			exit();
		}
		if(empty($user))
		{
			echo '查无此号，请联系管理员';
			exit();
		}
		else
		{
			$udata['user_id'] = $user['user_id'];
			$udata['secret'] = rand(100000, 900000);
	        $udata['password'] = md5(md5(pg('password')).$udata['secret']);
	        M('user')->save($udata);
			
	        unset($user['password'], $user['secret']);
			session('user',$user);
			setcookie('user',$user, time()+86400);
			echo 'success';
		}
	}
	public function login_exit()
	{
		session('user',null);
		setcookie('user');
		header('location:admin.php?m=login&a=index');
	}
	public function del_save()//删除分类
	{
		$classify_id=pg('classify_id');
		$classify_id!=''?M('classify')->where(array('classify_id'=>$classify_id))->delete():'';
		echo '操作成功';
	}

}
