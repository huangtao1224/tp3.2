<?php
    $type_id=M('classify')->where('classify_id='.$classify_id)->getField('type_id');
    if($type_id==3){
        $goods=M('goods')->where('goods_id='.$content_id)->find();
    }
    $appid="wx45e50ef766851414";
    $appsecret="3eb70937380faf04087b9dbfa56da364";
    $redirect_uri = urlencode('https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    $code = $_GET['code'];
    session('share_ty',$code);
    //unset($_SESSION['share_ty']);unset($_SESSION['share_openid']);die();
    //授权登录
    if(empty($_SESSION['share_openid'])){
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        if(empty($_SESSION['share_ty'])){
            header('location:'.$url);
            die();
        }
    
        //获取access_token
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=$code&grant_type=authorization_code";
        $WxCon = file_get_contents($url);
        $WxCon = json_decode($WxCon,true);
        $access_token = $WxCon['access_token'];
        //获取用户信息
        $userInfoUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=".$appid."&lang=zh_CN";
        $userInfo = file_get_contents($userInfoUrl);
        $userArr = json_decode($userInfo,true);

        session('share_openid',$userArr['openid']);
        
    }
    $site2=M('share2')->find();
    $time=time();
    $access_token=$site2['access_token'];
    $ticket=$site2['ticket'];
    $redirect_url = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    //获取$access_token
    if($time>$site2['date2']){
        $str=file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret);
        $arr=json_decode($str,true);
        $access_token = $arr['access_token'];
        $data['date2']=$time+6000;
        $data['access_token']=$access_token;

        //获取签名
        $str=file_get_contents('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi');
        $arr=json_decode($str,true);
        $ticket=$arr['ticket'];
        $data['ticket']=$ticket;

        //存储数据到数据库
        if($site2){
            M('share2')->where('share2_id = 1')->save($data);
        }else{
            M('share2')->add($data);
        }
    }
    //dump($site2);
    $noncestr="Wm3WZYTPz0wzccnW";
    $timestamp=1414587457;
    $jsapi_ticket="jsapi_ticket=".$ticket."&noncestr=".$noncestr."&timestamp=".$timestamp."&url=".$redirect_url;
    $signature=sha1($jsapi_ticket);
    $arr=explode('&',$_SERVER['REQUEST_URI']);
    if($arr[1]){
        $redirect_url2 = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'&mid='.$_SESSION['member']['member_id'];
    }else{
        $redirect_url2 = 'https://'.$_SERVER['HTTP_HOST']."/mobile.php?mid=".$_SESSION['member']['member_id'];
    }
?>
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<?php
    $shareInfo=M('share')->find();
?>
<script type="text/javascript">
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: 'wx45e50ef766851414', // 必填，公众号的唯一标识
        timestamp: 1414587457, // 必填，生成签名的时间戳
        nonceStr:'Wm3WZYTPz0wzccnW', // 必填，生成签名的随机串
        signature: '<?php echo $signature;?>',// 必填，签名
        jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage'] // 必填，需要使用的JS接口列表
    });
    wx.ready(function () { 
        //分享朋友圈
        wx.onMenuShareTimeline({
            title: "<?php if($type_id==3){echo $goods['goods_name'];}else{echo $shareInfo['share_title'];}?>", // 分享标题
            link: "<?php echo $redirect_url2;?>", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: "http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo $shareInfo['share_img'];?>", // 分享图标
            success: function () {
                // 用户点击了分享后执行的回调函数
                $.ajax({
                    'url':'index.php?m=goods&a=share',
                    type:'post',
                    success:function(res){}
                });
            }
        });
        //分享朋友
        wx.onMenuShareAppMessage({
            title: "<?php if($type_id==3){echo $goods['goods_name'];}else{echo $shareInfo['share_title'];}?>", // 分享标题
            desc: "<?php if($type_id==3){echo $goods['goods_share'];}else{echo $shareInfo['intro'];}?>", // 分享描述
            link: "<?php echo $redirect_url2;?>", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: "http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo $shareInfo['share_img'];?>", // 分享图标
            success: function () {
                // 用户点击了分享后执行的回调函数
                $.ajax({
                    'url':'index.php?m=goods&a=share',
                    type:'post',
                    success:function(res){}
                });
            }
        });
    });
</script>