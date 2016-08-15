<?php defined('InShopNC') or exit('Access Invalid!');?>

<!--商家备注-->
<div>
  来自后台的消息：
  <textarea  disabled="disabled" style="width:350px;height:40px;">
    <?php
    if ($output['msg']['store_msg'] != NULL)
      echo $output['msg']['store_msg'];
    ?>
  </textarea><br />
</div>