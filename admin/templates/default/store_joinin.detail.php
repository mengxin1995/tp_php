<?php defined('InShopNC') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">
    $(document).ready(function(){
        $('a[nctype="nyroModal"]').nyroModal();

        $('#btn_fail').on('click', function() {
            if($('#joinin_message').val() == '') {
                $('#validation_message').text('请输入审核意见');
                $('#validation_message').show();
                return false;
            } else {
                $('#validation_message').hide();
            }
            if(confirm('确认拒绝申请？')) {
                $('#verify_type').val('fail');
                $('#form_store_verify').submit();
            }
        });
        $('#btn_pass').on('click', function() {
            var valid = true;
            $('[nctype="commis_rate"]').each(function(commis_rate) {
                rate = $(this).val();
                if(rate == '') {
                    valid = false;
                    return false;
                }

                var rate = Number($(this).val());
                if(isNaN(rate) || rate < 0 || rate >= 100) {
                    valid = false;
                    return false;
                }
            });
            if(valid) {
                $('#validation_message').hide();
                if(confirm('确认通过申请？')) {
                    $('#verify_type').val('pass');
                    $('#form_store_verify').submit();
                }
            } else {
                $('#validation_message').text('请正确填写分佣比例');
                $('#validation_message').show();
            }
        });
    });
</script>

<div class="fixed-bar">
    <div class="item-title">
        <h3><?php echo '计算机';?></h3>
        <ul class="tab-base">
            <li><a href="index.php?act=store&op=store"><span><?php echo $lang['manage'];?></span></a></li>
            <li><a href="JavaScript:void(0);" class="current"><span><?php echo '查看';?></span></a></li>
        </ul>
    </div>
</div>
<div class="fixed-empty"></div>

<?php if ( empty($output['joinin_detail']['company_registered_capital'])) { ?>
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
        <tr>
            <th colspan="6">计算机信息</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th>用户名：</th>
            <td><?php echo $output['com']['username'];?></td>
            <th>计算机名：</th>
            <td><?php echo $output['com']['com_name'];?></td>
            <th>计算机域名：</th>
            <td colspan="3"><?php echo $output['com']['com_yu'];?></td>
        </tr>
        <tr>
            <th>本地ip地址：</th>
            <td colspan="2"><?php echo $output['com']['com_ip'];?></td>
            <th>本地主机名：</th>
            <td colspan="2"><?php echo $output['com']['loc_com_name'];?></td>
        </tr>
        </tbody>
    </table>

    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
        <tr>
            <th colspan="6">操作系统版本</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th>操作系统的名称：</th>
            <td><?php echo $output['com']['win_name'];?></td>
            <th>操作系统的构架：</th>
            <td><?php echo $output['com']['win_goujia'];?></td>
            <th>操作系统的版本：</th>
            <td colspan="3"><?php echo $output['com']['win_banben'];?></td>
        </tr>
        <tr>
            <th>操作系统</th>
            <td><?php echo $output['com']['win'];?></td>
            <th>操作系统CpuEndian()：</th>
            <td><?php echo $output['com']['win_cpu_end'];?></td>
            <th>操作系统的描述：</th>
            <td colspan="3"><?php echo $output['com']['win_dis'];?></td>
        </tr>
        <tr>
            <th>操作系统的卖主：</th>
            <td colspan="2"><?php echo $output['com']['win_seller'];?></td>
            <th>操作系统卖主类型：</th>
            <td colspan="2"><?php echo $output['com']['win_seller_type'];?></td>
        </tr>
        </tbody>
    </table>

    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
        <tr>
            <th colspan="6">内存信息：</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th>内存总量：</th>
            <td><?php echo $output['com']['mem_sum'];?></td>
            <th>交换区总量：</th>
            <td><?php echo $output['com']['change_sum'];?></td>
        </tr>
        </tbody>
    </table>
<?php } ?>

</div>
