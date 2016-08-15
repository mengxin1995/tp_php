<?php defined('InShopNC') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">
  $(document).ready(function(){
    $('a[nctype="nyroModal"]').nyroModal();

    $('#btn_fail').on('click', function() {
      if($('#partner_message').val() == '') {
        $('#validation_message').text('请输入审核意见');
        $('#validation_message').show();
        return false;
      } else {
        $('#validation_message').hide();
      }
      if(confirm('确认拒绝申请？')) {
        $('#verify_type').val('fail');
        $('#form_partner_verify').submit();
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
          $('#form_partner_verify').submit();
        }
      }
    });
  });
</script>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo "服务站申请";?></h3>
      <ul class="tab-base">
        <li><a href="<?php echo urlAdmin('partner', 'partner');?>" ><span>所有服务站</span></a></li>
        <li><a href="<?php echo urlAdmin('partner', 'partner', array('type' => 'lockup'));?>"><span>已审核服务站</span></a></li>
        <li><a href="<?php echo urlAdmin('partner', 'partner', array('type' => 'waitverify'));?>"><span>等待审核</span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>服务站审核</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="form_partner_verify" action="index.php?act=partner&op=partner_verify" method="post">
    <input id="verify_type" name="verify_type" type="hidden" />
    <input name="partner_id" type="hidden" value="<?php echo $output['info']['partner_id'];?>" />
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <thead>
      <tr>
        <th colspan="20" style="text-align: center">合伙人服务站申请表</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <th class="w150">姓名：</th>
        <td><?php echo $output['info']['partner_name'];?></td>
      </tr>
      <tr>
        <th class="w150">性别：</th>
        <td><?php echo  str_replace(array('0','1'),array('男','女'),$output['info']['partner_sex']); ?></td>
      </tr>
      <tr>
        <th>年龄：</th>
        <td><?php echo $output['info']['partner_age']; ?></td>
      </tr>
      <tr>
        <th class="w150">职业：</th>
        <td><?php echo $output['info']['partner_job']; ?></td>
      </tr>
      <tr>
        <th>联系电话：</th>
        <td><?php echo $output['info']['partner_phone']; ?></td>
      </tr>
      <tr>
        <th class="w150">详细住址：</th>
        <td><?php echo $output['info']['partner_address']; ?></td>
      </tr>
      <tr>
        <th class="w150">场地大小：</th>
        <td><?php echo $output['info']['partner_field']; ?></td>
      </tr>
      <tr>
        <th class="w150">所处位置：</th>
        <td><?php echo str_replace(array('0','1','2','3','4','5'),array('主干道','村委','村中心','村口','操场','其他（）'),$output['info']['partner_position']); ?></td>
      </tr>
      <tr>
        <th class="w150">人际关系：</th>
        <td><?php echo str_replace(array('0','1','2'),array('人缘好','活动力强','其他（）'),$output['info']['partner_relation']); ?></td>
      </tr>
      <tr>
        <th class="w150">村落户数：</th>
        <td><?php echo $output['info']['partner_village']; ?></td>
      </tr>
      <tr>
        <th class="w150">网购经验：</th>
        <td><?php echo str_replace(array('0','1','2','3'),array('无','半年','一年','一年以上'),$output['info']['partner_experience']); ?></td>
      </tr>
      <tr>
        <th class="w150">是否经营商铺或网店：</th>
        <td><?php echo str_replace(array('0','1','2'),array('无','实体店','网店'),$output['info']['partner_boss']); ?></td>
      </tr>
      <tr>
        <th class="w150">每天能付出多少时间：</th>
        <td><?php echo $output['info']['partner_time']; ?></td>
      </tr>
      <tr>
        <th class="w150">对本公司的意见或建议(选填)：</th>
        <td><?php echo $output['info']['partner_opinion']; ?></td>
      </tr>
      <tr>
        <th class="w150">申请时间：</th>
        <td><?php echo @date('Y-m-d H:i:s',$output['info']['pdr_add_time']); ?></td>
      </tr>
        <tr>
          <th>审核意见：</th>
          <td colspan="2"><textarea id="partner_message" name="partner_message"></textarea></td>
        </tr>
      <?php if($output['info']['paystate_search']!=0){ ?>
        <tr>
          <th>审核状态：</th>
          <td><?php echo str_replace(array('1','2'),array('已批准','已拒绝'),$output['info']['paystate_search']);?></td>
        </tr>
      <?php } ?>
      <?php if($output['info']['paystate_search']!=0){ ?>
        <tr>
          <th>审核意见：</th>
          <td><?php echo $output['info']['partner_message']; ?></td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
      <div id="validation_message" style="color:red;display:none;"></div>
      <div><a id="btn_fail" class="btn" href="JavaScript:void(0);"><span>拒绝</span></a> <a id="btn_pass" class="btn" href="JavaScript:void(0);"><span>通过</span></a></div>
  </form>
</div>
