
<div class=" member_left">
  <h3>会员中心</h3>
  <ul>
    <li <?php if(MODULE_NAME=='member' && ACTION_NAME=='mydata'){?> class="now"<?php }?>><a href="index.php?m=member&a=mydata">我的资料<span class="fa fa-angle-right"></span></a></li>
    <li <?php if(MODULE_NAME=='order' && ACTION_NAME=='index'){?> class="now"<?php }?>><a href="index.php?m=order&a=index">我的订单<span class="fa fa-angle-right"></span></a></li>    
    <li <?php if(MODULE_NAME=='member' && ACTION_NAME=='myaddress'){?> class="now"<?php }?>><a href="index.php?m=member&a=myaddress">我的收货地址<span class="fa fa-angle-right"></span></a></li>
    <li <?php if(MODULE_NAME=='member' && ACTION_NAME=='balance'){?> class="now"<?php }?>><a href="index.php?m=member&a=balance">我的余额<span class="fa fa-angle-right"></span></a></li>
    <li <?php if(MODULE_NAME=='member' && ACTION_NAME=='changepwd'){?> class="now"<?php }?>><a href="index.php?m=member&a=changepwd">修改密码<span class="fa fa-angle-right"></span></a></li>
  </ul>
</div>
