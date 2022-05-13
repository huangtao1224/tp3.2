<?php require APP_ROOT.'public/head.php';?>




    <link rel="stylesheet" href="css/mobile_reg_v20171123144136.css">
    <img src="images/reg-bg.png" alt="" style="z-index:-1;position:fixed;_position:absolute;left:0;right:0;bottom:0;top:0;_left:expression(eval(document.documentElement.scrollLeft+document.documentElement.clientWidth-this.offsetWidth)-(parseInt(this.currentStyle.marginLeft,10)||0)-(parseInt(this.currentStyle.marginRight,10)||0));_top:expression(eval(document.documentElement.scrollTop+document.documentElement.clientHeight-this.offsetHeight-(parseInt(this.currentStyle.marginTop,10)||0)-(parseInt(this.currentStyle.marginBottom,10)||0)));" height="100%" width="100%">
    <div id="overlay"></div>
    <div id="regContent">
        <div id="outregBox">
            <div id="backtoindex"><a href="index.php">返回首页</a></div>
            <div id="goLogin">已有账户？<a href="index.php?m=member&a=login">去登录</a></div>
        </div>
        <div class="wrap">

            <div id="regLogo" class="regLogo regLogo58"><a id="logoHref" href="index.php"><img src="<?php echo $site['logo_img'] ?> " alt=""></a></div>

   <form action="index.php?m=member&a=register_save" method="post" id="myform" onsubmit="return form_submit()" >

    <!--手机号-->
    <li id="regMobileLi" class="regLi regMobile">
        <span class="regLable">手机号</span>
        <input placeholder="手机号" class="regMobile regInput" required="required" id="tel"    onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" maxlength="11" onafterpaste="this.value=this.value.replace(/[^0-9]/g,'')"  name="data[mobile]" datatype="m-s" nullmsg="请填写手机" errormsg="手机号码格式错误" type="text">
        <input id="regGetcodeBtn" class="regGetcodeBtn" value="获取动态码" onclick="link_more()" type="button">
    </li>
<!-- 
    <li id="regMobileTip" class="regMobileTip regMobileTipClear regTip">
        <span id="regMobileTipText" class="regTipText"></span>
        <span id="regVoicecodeTip" class="regVoicecodeTip">
        <span>动态码收不到?</span>
        <input id="regVoicecodeBtn" class="regVoicecodeBtn" value="使用语音验证" type="button">
        </span>
    </li>
 -->
    <!--短信验证码-->
    <li id="regMobileCodeLi" class="regLi regMobileCodeLi">
        <span class="regLable">动态码</span>
        <input placeholder="动态码" required="required" name='sms_code'  onkeyup="this.value=this.value.replace(/[^0-9]/g,'')"  onafterpaste="this.value=this.value.replace(/[^0-9]/g,'')"  id="sms_code" class="regMobileCode regInput" maxlength="6" autocomplete="off" type="text">
    </li>

  <!--   <li id="regMobileCodeTip" class="regMobileCodeTip regMobileCodeTipClear regTip">
        <span id="regMobileCodeTipText" class="regTipText"></span>
            <span id="regMobileSure" class="regMobileSure">该手机号已注册，
               	<span id="regLoginBtn" class="regLoginBtn">立即登录</span>&nbsp;或&nbsp;<span id="regContinue" class="regContinue">继续注册</span>
            </span>
            <span id="regMobileSureOnlyLogin" class="regMobileSure">该手机号已注册，
                <span id="regLoginBtnOnlyLogin" class="regLoginBtn">立即登录</span> 
            </span>
    </li> -->
    
    <!--username-->
    <li id="regMobileUsernameLi" class="regLi regMobileUsernameLi">
     	<span class="regLable">用户名</span>
    	<input placeholder="用户名" name="data[username]" required="required"  id="regMobileUsername" class="regMobileUsername regInput" maxlength="20" autocomplete="off" type="text" style="width:290px;">

    	<select name="data[lei]"  class="regGetcodeBtn" style="border:1px solid  #EBEBEB;color:#000;">
		<option value="个人">个人</option>
		<option value="企业">企业</option>
		</select>

	</li>

	
    
    
    <li id="regMobileUsernameTip" class="regMobileUsernameTip regMobileUsernameTipClear regTip">
   		<span id="regMobileUsernameTipText" class="regTipText"></span>
	</li>
    
	
    <!--设置密码-->
    <li id="regPasswordLi" class="regLi">
        <span class="regLable">设置密码</span>

  
        <input placeholder="设置密码" id="regPasswordText" required="required"  name="data[password]" class="regPassword regInput" maxlength="16" autocomplete="off" type="password">
      
    </li>

    <!--  <li id="regPasswordTip" class="regPasswordTip regPasswordTipClear regTip">
     	<span id="regPasswordTipText" class="regTipText"></span>
        <div id="passwordlevelBox" class="passwordlevelBox">
        	<span id="passwordlevel1" class="passwordlevel passwordlevelon">弱</span>
            <span id="passwordlevel2" class="passwordlevel">中</span>
            <span id="passwordlevel3" class="passwordlevel">强</span>
         </div>
      </li> -->

    <!--确认密码-->
    <li id="regRepasspordLi" class="regLi">
        <span class="regLable">确认密码</span>
        <input placeholder="确认密码" id="regRepasswordText" required="required"  name="data[repassword]"  class="regRepassword regInput" maxlength="16" autocomplete="off" type="password">
       

    </li>
    <li id="regRepasswordTip" class="regRepasswordTip regRepasswordTipClear regTip">
        <span id="regRepasswordTipText" class="regTipText"></span>
    </li>


    <li id="regButtonLi" class="regButtonLi regLi">

        <div class="regButtonBox">
            <input value="注册" id="link_submit" class="regButton" type="submit">
        </div>
    </li>


    <div class=".j_form_tips"></div>
</ul>

</form>


</div>
        </div>
    </div>


    <script>

    $('body').css('background','none');
     var wait=60;//时间
      var code=$('#regGetcodeBtn');
      code.removeAttr("disabled");

      // 倒计时
      function link_time()
	  {
		  if (wait == 0) {
				code.removeClass('on');
				code.attr("onclick", 'link_more()');
			  $('#text').css('display','none')
			  code.val("发送验证码");//改变按钮中value的值
			  wait = 60;
		  } else {
		  code.addClass('on');
		  code.attr("onclick", '');//倒计时过程中禁止点击按钮
		  code.val(wait+" 秒后重新获取");//改变按钮中value的值
		  wait--;
		  setTimeout(function() {
		  link_time();//循环调用
		  },
		  1000)
		  }
      }


   
  function link_more(){

	var tel=$('#tel').val();
   	var reg=/1[345678]\d{9}/i;
		if(!reg.test(tel)){
			     	popTipShow.alert('提示','手机号错误', ['知道了'],
			function(e){
			  //callback 处理按钮事件		  
			  var button = $(e.target).attr('class');
			  if(button == 'ok'){
				//按下确定按钮执行的操作
				//todo ....
				this.hide();
			  }	
			}
		);
			return false;

		}else{

			$.get("index.php?m=sms&a=send_sms&tel="+$("#tel").val(),function(rs){
				  var  data=rs.replace(/\s/g,''); 
				if(data==1){
					 	link_time();

					
				}

			});

		}
   }





    function form_submit(){

    		 $.ajax({
    	      url : "index.php?m=member&a=register_save",  
    	       type : "POST",  
    	       data : $('#myform').serialize(),  
    	       success : function(data) { 

    	       var data=$.parseJSON(data); 

    	       	popTipShow.alert('提示信息',data.msg, ['知道了'],
			function(e){
			  //callback 处理按钮事件		  
			  var button = $(e.target).attr('class');
			  if(button == 'ok'){
				location.href="index.php?m=member&a=index";
				//todo ....
				this.hide();
			  }	
			}
		);
    	       
    	       }
    	      });

    		 return false;
    }

   

    </script>







