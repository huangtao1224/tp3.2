<?php require APP_ROOT.'public/top.php';?>
<style>
    input[type="password"]{padding: 5px;border-radius: 5px;}
    input[type="submit"]{margin:10px;}
</style>
<div class="content">
    <?php require APP_ROOT.'public/left.php';?>
    <div id="right">
        <div class="right_data">
            <div style="background: #fff;">
                <form method="post" id="form_menu" action="admin.php?m=site&a=password_edit_save" enctype="multipart/form-data" onsubmit="return password_edit_save()" >
                    <input name="version_id" type="hidden" value="<?php echo session('version_id');?>" />
                    <table class="table">
                        <tr class="row">
                            <td colspan="2" style="font-weight: bold;font-size: 16px;">密码修改</td>
                        </tr>
                        <tr class="row">
                            <td width="7%">旧密码</td>
                            <td width="89%">
                                <input name="old_password" type="password" id="old_password" onblur="password_select()" />
                                <span class="password_select"></span>
                            </td>
                        </tr>
                        <tr class="row">
                            <td>新密码</td>
                            <td><input name="password" type="password" id="password" onblur="check_password()" />
                                <span class="confirm_password"></span>
                            </td>
                        </tr>
                        <tr class="row">
                            <td>确认密码</td>
                            <td><input name="confirm_password" type="password" id="confirm_password" onblur="check_password()" /></td>
                        </tr>
                        <tr>
                            <td colspan="2"><input name="" type="submit" class="submit" value="确认提交" /></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require APP_ROOT.'public/bottom.php';?>
