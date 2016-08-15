<?php defined('InShopNC') or exit('Access Invalid!');?>
<!--v3-v12-->
<script type="text/javascript">
    window.onload = function(){
        var t = document.getElementById('time');
        window.setInterval("fun()", t.value * 60 * 1000);
    }
    function fun(){
        history.go(0);
    }
</script>

<div>
    <input id="time" style="display: none" value=<?php echo $_SESSION['time'];?> />
</div>

<h1 align="center">进程查看</h1>

<div class="page">
    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th style="text-align: center">映像名称</th>
            <th style="text-align: center">PID</th>
            <th style="text-align: center">会话名</th>
            <th style="text-align: center">会话#</th>
            <th style="text-align: center">内存使用</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr>
                    <td style="text-align: center"><?php echo $v['process_ying'];?></td>
                    <td style="text-align: center"><?php echo $v['process_pid'];?></td>
                    <td style="text-align: center"><?php echo $v['process_ming'];?></td>
                    <td style="text-align: center"><?php echo $v['process_jing'];?></td>
                    <td style="text-align: center"><?php echo $v['process_nei'];?></td>
                </tr>
            <?php } ?>
        <?php }else { ?>
            <tr class="no_data">
                <td colspan="15"><?php echo $lang['nc_no_record'];?></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <tr class="tfoot">
            <td></td>
            <td colspan="16">
                <div class="pagination"><?php echo $output['page'];?></div></td>
        </tr>
        </tfoot>
    </table>
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>