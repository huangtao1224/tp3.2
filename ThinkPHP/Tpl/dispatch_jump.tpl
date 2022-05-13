<html>
    <head>
        <?php $company = M('site')->getField('company')?:'百家骏网络';?>
        <title><?php echo $company;?>-网站管理系统</title>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0'>
        <link href='Admin/Tpl/css/bootstrap-icons.css' rel='stylesheet' type='text/css' />
        <style>
            *{padding:0;margin:0;}
            body{background:#f1f1f1;height: 100%;}
            .box{width: 100%;max-width: 700px;padding:3px;position: relative;top: 50%;left: 50%;transform: translate(-50%,-50%);}
            .wait{text-align:center;color: #9f9f9f;margin-left:20px;}
            #wait{color:#f00;font-weight:bold;}
        </style>
    </head>
    <body>
        <div class="box">
            <?php if($message){ ?>
            <img src="Admin/Tpl/images/flight.png" width="100%">
            <div style="margin: 40px 0;">
                <div style="display: flex;align-items: center;justify-content: center;font-size: 1.5rem;margin-bottom: 30px;color: #9f9f9f;text-shadow: 1px 6px 3px #cdc9c9;">
                    <i class="fa fa-check-circle" style="margin-right: 10px;font-size: 30px;"></i>
                    <?php echo($message); ?>
                    <div class="wait">等待时间：<b id="wait"><?php echo($waitSecond); ?></b></div>
                </div>
                <a id="href" href="<?php echo($jumpUrl); ?>" style="display: block;text-align: center;color: #35a8ff;text-decoration: none;">如果你的浏览器没反应，请点击这里......</a>
            </div>
            <?php }else{ ?>
            <img src="Admin/Tpl/images/flight.png" width="100%">
            <div style="margin: 40px 0;">
                <div style="display: flex;align-items: center;justify-content: center;font-size: 1.5rem;margin-bottom: 30px;color: #9f9f9f;text-shadow: 1px 6px 3px #cdc9c9;">
                    <i class="fa fa-check-circle" style="margin-right: 10px;font-size: 30px;"></i>
                    <?php echo($error); ?>
                    <div class="wait">等待时间：<b id="wait"><?php echo($waitSecond); ?></b></div>
                </div>
                <a id="href" href="<?php echo($jumpUrl); ?>" style="display: block;text-align: center;color: #35a8ff;text-decoration: none;">如果你的浏览器没反应，请点击这里......</a>
            </div>
            <?php }?>
        </div>
        <script type="text/javascript">
            (function(){
                var wait = document.getElementById('wait');
                var href = document.getElementById('href').href;
                var interval = setInterval(function(){
                    var time = --wait.innerHTML;
                    if(time <= 0) {
                        location.href = href;
                        clearInterval(interval);
                    };
                }, 1000);
            })();
        </script>
    </body>
</html>