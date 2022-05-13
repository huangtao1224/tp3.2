<?php
class keyAction extends Action {
	public function __construct()
	{
		define('SERVER_NAME',$_SERVER['SERVER_NAME']);
		define('MD',md5(md5(md5(SERVER_NAME))));
		$link=mysql_connect('120.25.81.9','webkey','vX2vj3Ka6uB434Vt') or die ('连接失败');
		mysql_select_db('webkey',$link) or die('没该数据库：webkey');
		$sql='select * from index_website where webkey="'.MD.'"';
		if(!($result = mysql_fetch_array(mysql_query($sql))))echo mysql_error();
		echo $sql;
		echo MD;
		if(empty($result))die;
	}
	public function index(){
	}
}
