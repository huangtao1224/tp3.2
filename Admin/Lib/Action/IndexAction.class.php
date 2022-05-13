<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {

    public function index(){
		$this->display();
    }
	 public function detail(){
   			
        $this->display();
    }

    public function verify(){
 
      import('ORG.Util.Image');
      ob_end_clean();
      Image::buildImageVerify();

    }
        
   //    if($_SESSION['verify'] != md5($_POST['verify'])) {
   // echo '验证码错误';
   //   exit;}


    public function code(){
        if($_SESSION['verify'] != md5(pg('verify'))){
       echo 1;
        }
    }


    public function cache(){
        del_run();
        echo '操作成功';
    }
    public function server_info(){
        $id = pg('id');
        if($id==1){
            echo 'ftp账号';
        }else if($id==2){
            echo 'ftp密码';
        }else if($id==3){
            echo 'mysql账号';
        }else if($id==4){
            echo 'mysql密码';
        }
    }
    public function uplife(){
        $name       = strstr($_POST['file_name'],'.',1);//文件名称
        $act        = $_GET['act'];
        $path       = "./Uploads/bigfile/";
        if(!file_exists($path)) {
            mkdir($path,0777,true);//创建文件
        }
        $ext_suffix = substr($_POST['file_name'],strripos($_POST['file_name'],".")+1);//文件后缀
        if($act == 'upload') {
            $index = $_POST['index'];//当前片数
            $filename = $path."$index".$name.'.'.$ext_suffix;
            //断点上传已经存在的就跳过
            $result = move_uploaded_file($_FILES['data']['tmp_name'], $filename);
            if ($result) {
                echo json_encode(array('errno'=>10000, 'message'=>'ok'));
            } else {
                echo json_encode(array('errno'=>10001, 'message'=>'上传失败'));
            }

        } elseif ($_GET['act'] == 'join') {
            $total = intval($_POST['total']);
            @unlink(iconv('UTF-8', 'GBK', $path.$name.'.'.$ext_suffix));
            for($i = 1; $i<=$total; $i++){
                file_put_contents($path.iconv('UTF-8', 'GBK',$name.'.'.$ext_suffix), file_get_contents(iconv('UTF-8', 'GBK', $path."$i".$name.'.'.$ext_suffix)), FILE_APPEND);
                @unlink(iconv('UTF-8', 'GBK', $path."$i".$name.'.'.$ext_suffix));
            }

            echo json_encode(array('errno'=>10000, 'message'=>'上传成功'));
        }
    }
}
