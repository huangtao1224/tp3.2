<?php require APP_ROOT.'public/top.php';?>
<div class="content">
    <?php require APP_ROOT.'public/left.php';?>
    <div id="right">
        <div class="right_data">
            <div class="welcome_box flex align-items">
                <div>欢迎管理员<span class="admin_name"><?php echo $user['name'];?></span></div>
                <div>当前时间：<span class="nowTime"><?php echo date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']);?></span></div>
            </div>
            <div class="white card">
                <h1>数据统计</h1>
                <div class="flex align-items card_list">
                    <?php $member_total = M('member')->count(); if($member_total){?>
                    <div class="card_item">
                        <h3>会员数</h3>
                        <p><?php echo $member_total;?></p>
                    </div>
                    <?php }?>
                    <?php $classify = M()->table('index_classify c left join index_classify_type ct on c.type_id = ct.type_id')->where('ct.type_id>0 and ct.type_id!=13')->field('ct.type_name,ct.type_id')->group('ct.type_id')->select();foreach ($classify as $k=>$v){?>
                    <div class="card_item">
                        <h3><?php echo $v['type_name'];?></h3>
                        <p><?php echo M('relevance')->where('type_id='.$v['type_id'])->count();?></p>
                    </div>
                    <?php }?>
                </div>
            </div>
            <div class="server_info">
                <h1>系统信息</h1>
                <div class="server_info_table">
                    <table>
                        <tr>
                            <th>服务器地址</th>
                            <td>127.0.0.1</td>
                        </tr>
                        <tr>
                            <th>服务器语言</th>
                            <td><?php echo $_SERVER['HTTP_ACCEPT_LANGUAGE'];?></td>
                        </tr>
                        <tr>
                            <th>服务器操作系统</th>
                            <td><?php echo php_uname();?></td>
                        </tr>
                        <tr>
                            <th>域名</th>
                            <td><?php echo $_SERVER['HTTP_HOST'];?></td>
                        </tr>
                        <tr>
                            <th>运行环境</th>
                            <td><?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
                        </tr>
                        <tr>
                            <th>php版本</th>
                            <td><?php echo PHP_VERSION;?></td>
                        </tr>
                        <tr>
                            <th>Zend版本</th>
                            <td><?php echo Zend_Version();?></td>
                        </tr>
                        <tr>
                            <th>MYSQL版本</th>
                            <td>
                                <?php
                                $v = M()->query("select VERSION()");
                                echo $v[0]['VERSION()'];
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>PHP运行方式</th>
                            <td><?php echo php_sapi_name();?></td>
                        </tr>
                        <tr>
                            <th>上传附件</th>
                            <td><?php echo get_cfg_var("upload_max_filesize");?></td>
                        </tr>
                        <tr>
                            <th>最大执行时间</th>
                            <td><?php echo (get_cfg_var("max_execution_time")?:'0').'秒';?></td>
                        </tr>
                        <tr>
                            <th>FTP账号</th>
                            <td><span class="look_info" data-type="1">查看</span></td>
                        </tr>
                        <tr>
                            <th>FTP密码</th>
                            <td><span class="look_info" data-type="2">查看</span></td>
                        </tr>
                        <tr>
                            <th>MYSQL数据库名</th>
                            <td><?php echo C('DB_NAME');?></td>
                        </tr>
                        <tr>
                            <th>MYSQL用户名</th>
                            <td><?php echo C('DB_USER');?></td>
                        </tr>
                        <tr>
                            <th>MYSQL密码</th>
                            <td><span class="look_info" data-type="4">查看</span></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="quote">技术支持</div>
        </div>
    </div>
</div>
<script>
    $('.look_info').click(function () {
        layer.msg('暂无权限，请联系技术人员');
        return false;
        var _this = $(this);
        var id = $(this).attr('data-type');
        $.post('?m=Index&a=server_info',{id:id},function (msg) {
            console.log(msg);
            _this.parent('td').html(msg);
        })
    })
    function current(){
        var d=new Date(),str='';
        str +=d.getFullYear()+'-'; //获取当前年份
        if(d.getMonth()+1<=9) {
            str += ("0" + (d.getMonth()+1) + '-');
        }else{
            str +=d.getMonth()+1+'-';
        }
        if(d.getDate()<=9) {
            str += ("0" + d.getDate() + ' ');
        }else{
            str +=d.getDate()+' ';
        }
        if(d.getHours()<=9) {
            str += ("0" + d.getHours() + ':');
        }else{
            str +=d.getHours()+':';
        }
        if(d.getMinutes()<=9) {
            str += ("0" + d.getMinutes() + ':');
        }else{
            str +=d.getMinutes()+':';
        }
        if(d.getSeconds()<=9) {
            str += ("0" + d.getSeconds());
        }else{
            str +=d.getSeconds();
        }
        return str;
    }
    setInterval(function(){$(".nowTime").html(current)},1000);
</script>
<?php require APP_ROOT.'public/bottom.php';?>
<!--
<script type='text/javascript' src="./Admin/Tpl/js/tans.js"></script>
<a href="javascript:zh_tran('s');" class="zh_click" id="zh_click_s">简体</a>
<a href="javascript:zh_tran('t');" class="zh_click" id="zh_click_t" >繁體</a>
-->
