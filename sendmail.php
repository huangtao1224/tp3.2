<?php

//发送激活邮件
	require('./phpmailer/class.phpmailer.php');//引入邮件发送类
	$mail = new PHPMailer();
	/*
	设置phpmailer发信用放方式
	可用win下mail()函数来发
	可以用linux下sendmail,qmail组件来发
	可以利用smtp协议登录到某个账户上来发
	 */
	$mail->IsSMTP();//用smtp协议来发
	$mail->Host = 'smtp.163.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'admlink';
	$mail->Password = '3533nodonodie';

	//可以发信了
	$mail->CharSet = 'utf8';
	$mail->From = 'admlink@163.com';
	$mail->FromName = '月澜坊';
	$mail->AltBody = "text/html";
	$mail->SMTPSecure = 'ssl';     
	$mail->Port=994; 
    $mail->Encoding = 'base64';                 
	$mail->IsHTML(true);      
	$mail->Subject = '欢迎您注册,这是你的激活邮件';
	$mail->Body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
    <body>
      <p>hehe</p>
      <a href="https://www.baidu.com/">ahaha</a>
     <img src="https://ss0.bdstatic.com/5aV1bjqh_Q23odCf/static/superman/img/logo/bd_logo1_31bdc765.png" alt="" />
    </body>
</html>';
	//设置收信人
	$email='2357825009@qq.com';
	$mail->AddAddress($email,'尊敬的用户');
	//添加一个抄送
	// $mail->AddCC('274275276@qq.com','274275276');

	//发信
	if($mail->send()){
		echo 'ok';
	}else{
		echo 'fail';
	} 





?>