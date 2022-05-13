<?php require APP_ROOT.'public/member_top.php';?>

<div class="bg">
  <div class="member_main">
    <?php require APP_ROOT.'public/member_left.php';?>
    <div class="member_right">
      <div class="title">
        <h3>修改密码</h3>
      </div>
      <div class="update">
        <div class="top">
          <ul>
            <li class="now">密码修改<span></span></li>
            <li class="nows">完成<span></span></li>
          </ul>
        </div>
        <div class="form">
          <form action="index.php?m=member&a=changepwd" method="post" id="changepwd_form">
            <label>
            <span>旧密码：</span>
            <div class="text">
              <p> <i class="oldpassword_i succeed"></i>
                <input type="password" value="" name="data[oldpassword]" id="oldpassword" placeholder="请输入您的旧密码" onfocus="onfocus_changeoldpwd($(this))" onblur="onblur_changeoldpwd($(this))" />
                <input type="hidden" id="check_oldpassword" />
              </p>
              <div class="check" id="prompt_oldpassword"></div>
            </div>
            </label>
            <label>
            <span>新密码：</span>
            <div class="text">
              <p> <i class="password_i succeed"></i>
                <input type="password" value="" name="data[password]" id="password" placeholder="请输入您的新密码" onfocus="onfocus_changepwd($(this))" onblur="onblur_changepwd($(this))" />
                <input type="hidden" id="check_password" />
              </p>
              <div class="check" id="prompt_password"></div>
            </div>
            </label>
            <label>
            <span>再次输入密码：</span>
            <div class="text">
              <p> <i class="againpassword_i succeed"></i>
                <input type="password" value="" name="data[againpassword]" id="againpassword" placeholder="请再次输入您的新密码" onfocus="onfocus_changeagainpwd($(this))" onblur="onblur_changeagainpwd($(this))" />
                <input type="hidden" id="check_againpassword" />
              </p>
              <div class="check" id="prompt_againpassword"></div>
            </div>
            </label>
            <label><span></span>
              <input class="submit" type="button" value="确定" onclick="changepwd()" />
            </label>
          </form>
          <div class="pwdsuccess">
            <div class="img"><img src="<?php echo APP_ROOT.'images/success.jpg';?>" /></div>
            <p class="big">修改密码成功！</p>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
function onfocus_changeoldpwd(obj)
{
	$(".password_i.succeed").hide();
	obj.removeClass("error").addClass("on");
	$('#prompt_oldpassword').css('display','block').html('<i class="password_i prompt"></i><em class="grey">&nbsp;6-20位字符，建议由字母，数据和符号两种以上组合</em>');
}

function onblur_changeoldpwd(obj)
{
	$("#capslock").hide();
	obj.removeClass("on");
	if(obj.val()!='')
	{
		if(obj.val().length<6)
		{
			obj.addClass("error");
			$('#prompt_oldpassword').css('display','block').html('<i class="oldpassword_i error"></i><em class="red">&nbsp;长度只能在6-20个字符之间</em>');
			$("#check_password").val('');
		}
		else
		{
			var html=ajax_load("index.php?m=member&a=checkoldpwd&password="+encodeURIComponent(obj.val()));
			if(html==1)
			{
				$('#prompt_oldpassword').css('display','block').html('');
				$("#check_oldpassword").val(1);
				$(".oldpassword_i.succeed").show();
			}
			else
			{
				obj.addClass("error");
				$('#prompt_oldpassword').css('display','block').html('<i class="password_i error"></i><em class="red">&nbsp;旧密码输入错误</em>');
				$("#check_againpassword").val('');
				$(".oldpassword_i.succeed").hide();
			}
		}
	}
	else
	{
		obj.addClass("error");
		$('#prompt_oldpassword').css('display','block').html('<i class="password_i error"></i><em class="red">&nbsp;旧密码不能为空</em>');
		$("#check_againpassword").val('');
		$(".oldpassword_i.succeed").hide();
	}

}



function onfocus_changepwd(obj)
{
	$(".password_i.succeed").hide();
	obj.removeClass("error").addClass("on");
	$('#prompt_password').css('display','block').html('<i class="password_i prompt"></i><em class="grey">&nbsp;6-20位字符，建议由字母，数据和符号两种以上组合</em>');
}

function onblur_changepwd(obj)
{
	$("#capslock").hide();
	obj.removeClass("on");
	if(obj.val()!='')
	{
		var selectpwd=ajax_load("index.php?m=member&a=checkoldpwd&password="+encodeURIComponent(obj.val()));
		if(obj.val().length<6)
		{
			obj.addClass("error");
			$('#prompt_password').css('display','block').html('<i class="password_i error"></i><em class="red">&nbsp;长度只能在6-20个字符之间</em>');
			$("#check_password").val('');
		}
		else if(selectpwd==1)
		{
			obj.addClass("error");
			$('#prompt_password').css('display','block').html('<i class="password_i error"></i><em class="red">&nbsp;新密码不能与旧密码相同</em>');
			$("#check_againpassword").val('');
		}		
		else if($("#againpassword").val()!='' && $("#againpassword").val()!=obj.val())
		{
			obj.addClass("error");
			$('#prompt_againpassword').css('display','block').html('<i class="password_i error"></i><em class="red">&nbsp;两次密码输入不一致</em>');
			$("#check_againpassword").val('');
		}
		else
		{
			$(".password_i.succeed").show();
			$("#check_password").val(1);
		}
	}
	else
	{
		obj.addClass("error");
		$('#prompt_password').css('display','block').html('<i class="password_i error"></i><em class="red">&nbsp;密码不能为空</em>');
		$("#check_password").val('');
	}

}

function onfocus_changeagainpwd(obj)
{
	$(".againpassword_i.succeed").hide();
	obj.removeClass("error").addClass("on");
	$('#prompt_againpassword').css('display','block').html('<i class="password_i prompt"></i><em class="grey">&nbsp;请再次输入新密码</em>');
}

function onblur_changeagainpwd(obj)
{
	if(obj.val()!='')
	{
		if(obj.val()!=$("#password").val())
		{
			obj.addClass("error");
			$('#prompt_againpassword').css('display','block').html('<i class="password_i error"></i><em class="red">&nbsp;两次密码输入不一致</em>');
			$("#check_againpassword").val('');
		}
		else
		{
			$(".againpassword_i.succeed").show();
			$("#check_againpassword").val(1);
		}
	}
	else
	{
		obj.addClass("error");
		$('#prompt_againpassword').css('display','block').html('<i class="password_i error"></i><em class="red">&nbsp;请输入确认密码</em>');
		$("#check_againpassword").val('');
	}
}

function changepwd()
{
	if($("#check_oldpassword").val()!=1)
	{
		onblur_changeoldpwd($("#oldpassword"));
		return false;
	}
	else if($("#check_password").val()!=1)
	{
		onblur_changepwd($("#password"));
		return false;
	}
	else if($("#check_againpassword").val()!=1)
	{
		onblur_changeagainpwd($("#againpassword"));
		return false;
	}
	else
	{
		var html=ajax_load("index.php?m=member&a=changepwd_save&password="+encodeURIComponent($("#password").val()));
		if(html==1)
		{
			$(".now").removeClass("now");
			$(".nows").addClass("now");
			$("#changepwd_form").slideUp(300);
			$(".pwdsuccess").slideDown(300);;
		}
		return true;
	}
}
</script> 
  </div>
</div>
<?php require APP_ROOT.'public/bottom.php';?>
