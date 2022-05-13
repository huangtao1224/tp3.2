function ajax_load(path){
  var htmls;
  $.ajax({
  type: "post",
  url: path,
  data: '',
  async:false,
  dataType:"html",
  success: function(html){
    htmls=html;
  }
  });
  return htmls;
}


function link_login(){
  var username = $.trim($("input[name='username']").val()); 
  var password = $.trim($("input[name='password']").val()); 
  var code = $.trim($("input[name='code']").val());


   if(!username){
    layer.msg('请输入用户名', {icon: 2});
    return false;
   } 
   if(!password){
    layer.msg('请输入用密码', {icon: 2});
    return false;
   } 
   if(code){
      var htm = ajax_load('admin.php?m=Index&a=code&verify='+code);

      if(htm==1){
      layer.msg('验证码错误', {icon: 2});
      return false;         
      }
   }else{
    layer.msg('请输入验证码', {icon: 2});
    return false;    
   }


   $.post('admin.php?m=login&a=login_save',{
        username:username,
        password:password
   },function(data){
          if(data=='success'){
           location.href="admin.php";
          }else{
          layer.msg(data, {icon: 2});
          }      
   })    
         

}