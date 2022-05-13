<?php require APP_ROOT.'public/head.php';?>

<div class="login">
	<div class="content">
        <div class="form">
            <form action="index.php?m=member&a=login" method="post" id="login_form">
                <h2>账户登陆</h2>
                <div class="check" id="prompt_login"></div>
                <label class="input">
                    <span>用户名</span>
                    <input type="text" name="data[username]" id="username" value="" placeholder="请输入用户名" onblur="onblur_login($(this))" onfocus="onfocus_login($(this))"  />
                </label>
                <label class="input">
                    <span>密码</span>
                    <input type="password" name="data[password]" id="password" value="" placeholder="请输入密码" onblur="onblur_login($(this))" onfocus="onfocus_login($(this))"  />
                </label>
                <div class="clear"></div>
                <label>
                    <input type="button" class="submit" id="login_submit"  value="登录" onclick="login()" />
                </label>
                <label>
                    <a href="member-retrieve_pwd.html" style="display:none;" class="find_password">找回密码</a>
                    <a href="index.php?m=member&a=register" class="login_register">免费注册</a>
                </label>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
function onblur_login(obj)
{
	obj.removeClass("error").removeClass("on");
}
function onfocus_login(obj)
{
	obj.removeClass("error").addClass("on");
}
function login()
{
	if($("#username").val()=='' && $("#password").val()=='')
	{
		$('#prompt_login').css('display','block').html('<i class="login_i error"></i><em class="red">&nbsp;请输入账户名和密码</em>');
		$("#username").addClass("error")
		$("#password").addClass("error")		
		return false;
	}
	else if($("#username").val()=='')
	{
		$('#prompt_login').css('display','block').html('<i class="login_i error"></i><em class="red">&nbsp;请输入账户名</em>');
		$("#username").addClass("error")
		return false;
	}
	else if($("#password").val()=='')
	{
		$('#prompt_login').css('display','block').html('<i class="login_i error"></i><em class="red">&nbsp;请输入密码</em>');
		$("#password").addClass("error")		
		return false;
	}
	else
	{
		var html=ajax_load("index.php?m=member&a=login_save&username="+$("#username").val()+"&password="+$("#password").val());
		if(html==1)
		{
			goto_url('index.php?m=member&a=index');
		}
		else
		{
			$('#prompt_login').css('display','block').html('<i class="login_i error"></i><em class="red">&nbsp;用户密码错误</em>');
		}
	}
	return false;
}
</script>
<?php require APP_ROOT.'public/bottom.php';?>
