<?php defined('InShopNC') or exit('Access Invalid!');?>

<form action="index.php?act=store&op=sendmsg_show&store_id=<?php echo $output['store_id']?>" method="post">
    <br />
    发送的消息内容：
    <tr>
        <td class="w10"></td>
        <td class="tl" colspan="2">
            <textarea  name="store_message[<?php echo $output['store_id'];?>]" class="ncc-msg-textarea" placeholder="给商家发送消息" title="给商家发送消息"  maxlength="150" style="width:400px;height:100px;margin-top:3px;"></textarea></td>
        <td class="tl" colspan="10">
            <div class="ncc-form-default">
            </div>
        </td>
    </tr>
    <input type="submit" value="发送" style="margin-left:400px;margin-top:10px;height:25px;width:70px"/>
</form>
