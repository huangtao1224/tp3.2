<?php require APP_ROOT.'public/head.php';?>
<div class="top">
    <a href="admin.php" class="logo"><?php echo $site['company_name']?:'后台管理系统';?><img src="<?php echo $site['logo_img'];?>" style="display: none;"></a>
    <div class="top_li">
        <i class="bi bi-list"></i>
        <a target="_blank" href="./">网站首页</a>
        <a href="javascript:;" onclick="confirm('确定删除吗!','admin.php?m=Index&a=cache','')" class="confirm">清除缓存</a>
        <?php $site = M('site')->select(); if(count($site)>1){ foreach($site as $k=>$v){?>
        <a class="<?php if(session('version_id')==$v['version_id'])echo ' on';?>" href="admin.php?m=site&a=switch_version&version_id=<?php echo $v['version_id'];?>"><?php echo $v['version_name'];?></a>
        <?php } }?>
    </div>
    <div class="user">
        <p><a href="admin.php?m=site&a=password_edit&admin_classify_id=2"><?php echo $user['name']?></a></p>
        <div class="skin">
            <i class="bi bi-palette-fill"></i>
            <ul>
                <li><a href="javascript:;" data-val="default" title="默认（黑色）"><span style="background: #000"></span>默认（黑色）</a></li>
                <li><a href="javascript:;" data-val="blue" title="蓝色"><span style="background:blue"></span>蓝色</a></li>
                <li><a href="javascript:;" data-val="green" title="绿色"><span style="background: green"></span>绿色</a></li>
                <li><a href="javascript:;" data-val="red" title="红色"><span style="background: red"></span>红色</a></li>
                <li><a href="javascript:;" data-val="orange" title="橙色"><span style="background:orange"></span>橙色</a></li>
            </ul>
        </div>
        <p><a href="admin.php?m=login&a=login_exit"><i class="bi bi-power"></i></a></p>
    </div>
</div>
<script>
    $('.skin').hover(function(){
        $(this).children('ul').stop().slideToggle();
    })
    $('.skin ul li').hover(function(){
        var color = $(this).children('a').attr('data-val');
        if(color!='default'){
            $(this).children('a').css('color',color);
        }
    },function(){
        $(this).children('a').css('color','#000');
    })
    $(function(){
        if($.cookie("bg-pic")==""||$.cookie("bg-pic")==null){
            //$('.tables tr.title').removeClass($.cookie("bg-pic"));
            $('#left').removeClass('left_'+$.cookie("bg-pic"));
        }else{
            $('.top').addClass($.cookie("bg-pic"));
            $('.tables tr.title').addClass($.cookie("bg-pic"));
            $('#left').addClass('left_'+$.cookie("bg-pic"));
        }
        $('.skin ul li a').click(function(){
            var color = $(this).attr("data-val");
            $('.top').removeClass().addClass('top '+color);
            $('.tables tr.title').removeClass().addClass('title '+color);
            $('#left').removeClass().addClass('left_panel left_'+color);
            $.cookie("bg-pic",color);
        });
        $('.bi-list').click(function(){
            if (!$('#left').hasClass('nav-mini')) {
                $('#left').addClass('nav-mini');
                $('.side-nav-li').removeClass('open');
                $('.side-nav-li').children('ul').removeAttr('style');
                //$('.side-nav-li').children('ul').children('li').removeClass('active');
                $('#right').stop().animate({'left':'60px','width':$(".content"). outerWidth(true)-60+'px'},500);
            }else{
                $('#left').removeClass('nav-mini');
                $('#right').stop().animate({'left':'200px','width':$(".content"). outerWidth(true)-200+'px'},500);
            }
            // if($('#left').css('left')=='0px'){
            //     $('#left').stop().animate({'left':'-200px'},1000);
            //     $('#right').stop().animate({'left':'0','width':$(".content"). outerWidth(true)+'px'},1000);
            // }else{
            //     $('#left').stop().animate({'left':'0'},1000);
            //     $('#right').stop().animate({'left':'200px','width':$(".content"). outerWidth(true)-200+'px'},1000);
            // }
        })
    });
</script>