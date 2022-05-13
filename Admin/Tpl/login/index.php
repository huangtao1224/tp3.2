<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php $site=M('site')->order('date asc')->find() ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $site['company_name']?:'百家骏网站';?>-后台管理系统</title>
    <link href="<?php echo APP_ROOT.'css/main.css';?>" rel="stylesheet" type="text/css" />
    <script src="<?php echo APP_ROOT;?>js/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo APP_ROOT;?>js/index.js"></script>
    <script src="<?php echo APP_ROOT;?>js/layui/layui.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo APP_ROOT;?>css/style.css">
</head>
<body>
<div id="particles">
    <div class="login">
        <div class="login_con">
            <div class="login_tit">后台管理系统</div>
            <div class="login_subtit">System Management Center </div>
            <div class="login_line"></div>
            <div class="login_submit">
                <form autocomplete="off">
                    <p><img class="logo_icon" src="<?php echo APP_ROOT;?>images/user.png"><input type="text" name="username" class="item" placeholder="用户名" autocomplete="off"/></p>
                    <p><img class="logo_icon" src="<?php echo APP_ROOT;?>images/pass.png"><input type="password" name="password" class="item" placeholder="密码" autocomplete="off"/></p>
                    <p>
                        <img class="logo_icon" src="<?php echo APP_ROOT;?>images/yzm.png">
                        <input type="text" class="item" placeholder="验证码" name="code" style="width:220px;float:left">
                        <img style="height:40px; width:80px;margin-left:10px;" title="点击可更换验证码" src="admin.php?m=Index&a=verify" alt="" onclick="this.src='admin.php?m=Index&a=verify&code='+Math.random()">
                    </p>
                    <p><a class="login_a" onclick="link_login();">登录</a></p>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo APP_ROOT;?>js/canvas-particle.js"></script>
<script src="<?php echo APP_ROOT;?>js/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    window.onload = function() {
        //配置
        var config = {
            vx: 4,	//小球x轴速度,正为右，负为左
            vy: 4,	//小球y轴速度
            height: 2,	//小球高宽，其实为正方形，所以不宜太大
            width: 2,
            count: 200,		//点个数
            color: "121, 162, 185", 	//点颜色
            stroke: "130,255,255", 		//线条颜色
            dist: 6000, 	//点吸附距离
            e_dist: 20000, 	//鼠标吸附加速距离
            max_conn: 10 	//点到点最大连接数
        }
        //调用
        CanvasParticle(config);
    }
</script>
</body>
</html>
