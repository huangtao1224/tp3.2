<div id="left" class="left_panel">
    <div class="navigation">
        <?php if($user['permissions']==3){?>
            <ul class="side-nav">
                <?php
                $list = M('admin_classify')->where(array('level_id'=>2))->order('classify_id asc')->select();foreach($list as $k=>$v){
                $where['user_id'] = $user['user_id'];
                $where['permission_name'] = 1;
                $where['admin_classify_permission'] = array('neq','');
                $where['admin_classify_id'] = $v['classify_id'];
                $permission = M('permission')->where($where)->find();
                $where2['user_id'] = $user['user_id'];
                $where2['permission_name'] = 1;
                $where2['classify_permission'] = array('neq','');
                $where2['c.version_id'] = $version_id;
                $permission2 = M('permission p')->join('index_classify c on p.classify_id=c.classify_id')->where($where2)->order('date asc')->select();
                if($permission||($permission2&&$v['classify_id']==3)){
                ?>
                <li class="side-nav-li <?php if($v['classify_id']==pg('admin_classify_id')){ echo 'open';}?>">
                    <a href="<?php if($v['classify_id'] == 3) { echo 'javascript:;';}else{ echo $v['classify_url'];?>&admin_classify_id=<?php echo $v['classify_id'];}?>" class="<?php if(($v['classify_id']==pg('admin_classify_id')&&(pg('recursive_classify_id')==''))||($v['classify_id']==3&&pg('type_id'))){echo 'active';}?> <?php if($v['classify_id']==pg('admin_classify_id')){ echo 'active2';}?>">
                        <i class="bi bi-<?php echo $v['classify_icon']?:'ui-checks-grid';?>"></i>
                        <div class="flex align-items side-nav-li-box" style="">
                            <span><?php echo $v['classify_name'];?></span>
                            <?php if($v['classify_id'] == 3){?>
                                <i class="bi bi-chevron-left"></i>
                            <?php }?>
                        </div>
                    </a>
                    <?php
                    if($v['classify_id'] == 3){
                        ?>
                        <ul class="li_menu" <?php if(($v['classify_id']==pg('admin_classify_id'))||(pg('type_id'))){ echo 'style="display:block"';}?>>
                            <?php foreach($permission2 as $li){ ?>
                                <li <?php if($li['classify_id']==pg('recursive_classify_id')){echo "class='active'";}?>>
                                    <a href="admin.php?m=classify&a=index&&admin_classify_id=<?php echo $v['classify_id'];?>&recursive_classify_id=<?php  echo $li['classify_id'];?>">
                                        <i class="bi bi-chevron-right"></i>
                                        <span><?php echo M('classify')->where('classify_id='.$li['classify_id'])->getField('classify_name');?></span>
                                    </a>
                                </li>
                            <?php }?>
                        </ul>
                    <?php }?>
                </li>
                <?php } }?>
            </ul>
        <?php }else{?>
        <ul class="side-nav">
            <?php
            $list = M('admin_classify')->where(array('level_id'=>2))->order('date asc')->select();foreach($list as $k=>$v){
                $user_classify = M('user_classify')->where(array('user_id'=>$user['user_id'],'classify_id'=>$v['classify_id']))->find();
                if(!empty($user_classify)){
                    ?>
                    <li class="side-nav-li <?php if(($v['classify_id']==pg('admin_classify_id'))||($v['classify_id']==3&&pg('type_id'))){ echo 'open';}?>">
                        <a href="<?php if($v['classify_id'] == 3) { echo 'javascript:;';}else{ echo $v['classify_url'];?>&admin_classify_id=<?php echo $v['classify_id'];}?>" class="<?php if(($v['classify_id']==pg('admin_classify_id')&&(pg('recursive_classify_id')==''))||($v['classify_id']==3&&pg('type_id'))){echo 'active';}?> <?php if($v['classify_id']==pg('admin_classify_id')){ echo 'active2';}?>">
                            <i class="bi bi-<?php echo $v['classify_icon']?:'ui-checks-grid';?>"></i>
                            <div class="flex align-items side-nav-li-box" style="">
                                <span><?php echo $v['classify_name'];?></span>
                                <?php if($v['classify_id'] == 3){?>
                                    <i class="bi bi-chevron-left"></i>
                                <?php }?>
                            </div>
                        </a>
                        <?php
                        if($v['classify_id'] == 3){
                            $version_classify_id=1;
                            if(session('version_id')>1){
                                for($i=1;$i<=session('version_id');$i++) {
                                    $version_classify_id=$version_classify_id*10;
                                }
                            }
                            $flist = M('classify')->where(['classify_pid'=>$version_classify_id,'version_id'=>session('version_id')])->order('date asc')->select();
                            ?>
                            <ul class="li_menu" <?php if(($v['classify_id']==pg('admin_classify_id'))||(pg('type_id'))){ echo 'style="display:block"';}?>>
                                <?php foreach($flist as $li){ ?>
                                    <li <?php if($li['classify_id']==pg('recursive_classify_id')){echo "class='active'";}?>>
                                        <a href="admin.php?m=classify&a=index&&admin_classify_id=<?php echo $v['classify_id'];?>&recursive_classify_id=<?php  echo $li['classify_id'];?>">
                                            <i class="bi bi-chevron-right"></i>
                                            <span><?php echo $li['classify_name'];?></span>
                                        </a>
                                    </li>
                                <?php }?>
                            </ul>
                        <?php }?>
                    </li>
                <?php }} ?>
        </ul>
        <?php }?>
    </div>
</div>
<script type="text/javascript">
    $('.side-nav').on('click','.side-nav-li',function(e) {
        $(this).siblings().children('a').removeClass('active')
        $(this).children('a').addClass('active');
        if(!$(this).parents('#left').hasClass('nav-mini')){
            if($(this).children('.li_menu').length){
                if($(this).hasClass('open')){
                    $(this).removeClass('open');
                    $(this).find('.bi-chevron-left').css({'transform': 'rotate( 0deg)'});
                    $(this).find('.li_menu').stop(true,true).slideUp();
                    console.log($(this).find('.li_menu'));
                }else{
                    $(this).addClass('open');
                    $(this).find('.bi-chevron-left').css({'transform': 'rotate( -90deg)'});
                    $(this).find('.li_menu').stop(true,true).slideDown();
                    console.log($(this).find('.li_menu'));
                }
            }
        }
    })
    $(".hide").click(function(){
        var ty=$(this).attr('ty');
        if(ty==1){
            $(this).attr('ty',2).html("+");
            $(".li_menu").slideUp();
        }else{
            $(this).attr('ty',1).html("-");
            $(".li_menu").slideDown();
        }
        return false;
    });
</script>



