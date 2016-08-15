<?php defined('InShopNC') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<div class="breadcrumb"><span class="icon-home"></span><span><a href="<?php echo SHOP_SITE_URL;?>">首页</a></span> <span class="arrow">></span> <span>合伙人服务站申请</span> </div>
<div class="main">
  <div class="sidebar">
    <div class="title">
      <h3>村村通合伙人服务站申请</h3>
    </div>
    <div class="content">
      <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
      <?php foreach($output['list'] as $key => $val){ ?>
      <dl show_id="<?php echo $val['type_id'];?>">
        <dt onclick="show_list('<?php echo $val['type_id'];?>');" style="cursor: pointer;"> <i class="hide"></i><?php echo $val['type_name'];?></dt>
        <dd style="display:none;">
          <ul>
            <li class="<?php echo $output['step'] == '1' ? 'current' : '';?>"><i></i>合伙人服务站信息提交</li>
            <li class="<?php echo $output['step'] == '2' ? 'current' : '';?>"><i></i>平台审核资质</li>
          </ul>
        </dd>
      </dl>
      <?php } ?>
      <?php } ?>
      <dl show_id="0">
        <dt onclick="show_list('0');" style="cursor: pointer;"> <i class="show"></i>提交申请</dt>
        <dd>
          <ul>
            <li class="<?php echo $output['step'] == '1' ? 'current' : '';?>"><i></i>合伙人服务站信息</li>
          </ul>
        </dd>
      </dl>
    </div>
    <div class="title">
      <h3>平台联系方式</h3>
    </div>
    <div class="content">
      <ul>
        <?php
			if(is_array($output['phone_array']) && !empty($output['phone_array'])) {
				foreach($output['phone_array'] as $key => $val) {
			?>
        <li><?php echo '电话'.($key+1).$lang['nc_colon'];?><?php echo $val;?></li>
        <?php
				}
			}
			 ?>
        <li><?php echo '邮箱'.$lang['nc_colon'];?><?php echo C('site_email');?></li>
      </ul>
    </div>
  </div>
  <div class="right-layout">
    <div class="joinin-step" style="text-align: center">
      <ul>
        <li class="step1 <?php echo $output['step'] >= 0 ? 'current' : '';?>"><span>合伙人服务站信息提交</span></li>
        <li class="<?php echo $output['step'] >= 1 ? 'current' : '';?>"><span>合伙人服务站信息</span></li>
        <li class="step6"><span>平台审核资质</span></li>
      </ul>
    </div>
    <div class="joinin-concrete">
      <!-- 公司信息 -->

      <div id="apply_company_info" class="apply-company-info">
        <form id="form_company_info" action="index.php?act=store_joinin_partner&op=partner" method="post" enctype="multipart/form-data" >
          <table border="0" cellpadding="0" cellspacing="0" class="all">
            <thead>
            <tr>
              <th colspan="2" style="text-align: center;">村村通合伙人服务站申请表</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <th><i>*</i>姓名：</th>
              <td><input name="partner_name" type="text" class="w100"  value="<?php echo $output['partner_name'];?>"/>
                <span></span></td>
            </tr>
            <tr>
              <th><i>*</i>性别：</th>
              <td class="vatop rowform"><ul>
                  <label>
                    <input type="radio" value="0" name="partner_sex" checked>
                    <?php echo "男"?></label>

                  <label>
                    <input type="radio" value="1" name="partner_sex">
                    <?php echo "女"?></label>
                </ul></td>
            </tr>
            <tr>
              <th><i>*</i>年龄：</th>
              <td>
                <select name="partner_age" id="partner_age">
                  <option value="18-21"name="partner_age">18-21</option>
                  <option value="22-25"name="partner_age">22-25</option>
                  <option value="26-29"name="partner_age">26-29</option>
                  <option value="30-35"name="partner_age">30-35</option>
                  <option value="超过35"name="partner_age">超过35</option>
                </select>
                <span></span></td>
            </tr>
            <tr>
              <th><i>*</i>职业：</th>
              <td><input name="partner_job" type="text" class="w200"  value="<?php echo $output['partner_job'];?>"/>
                <span></span></td>
            </tr>
            <tr>
              <th><i>*</i>联系电话：</th>
              <td><input name="partner_phone" type="text" class="w100"  value="<?php echo $output['partner_phone'];?>" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"/>
                <span></span></td>
            </tr>
            <tr>
              <th><i>*</i>详细地址：</th>
              <td><input name="partner_address" type="text" class="w200"  value="<?php echo $output['partner_address'];?>">
                <span></span></td>
            </tr>
            <tr>
              <th><i>*</i>场地大小：</th>
              <td class="vatop rowform"><ul>
                  <label>
                    <input type="radio" value="10m²" name="partner_field" checked>
                    <?php echo "10m²"?></label>
                  <label>
                    <input type="radio" value="11-20m²" name="partner_field">
                    <?php echo "11-20m²"?></label>
                  <label>
                    <input type="radio" value="20m²以上" name="partner_field">
                    <?php echo "20m²以上"?></label>
                </ul></td>
            </tr>
            <tr>
              <th><i>*</i>所处位置：</th>
              <td class="vatop rowform"><ul>
                  <label>
                    <input type="radio" value="0" name="partner_position" checked>
                    <?php echo "主干道"?></label>
                  <label>
                    <input type="radio" value="1" name="partner_position">
                    <?php echo "村委"?></label>
                  <label>
                    <input type="radio" value="2" name="partner_position">
                    <?php echo "村中心"?></label>
                  <label>
                    <input type="radio" value="3" name="partner_position">
                    <?php echo "村口"?></label>
                  <label>
                    <input type="radio" value="4" name="partner_position">
                    <?php echo "操场"?></label>
                  <label>
                    <input type="radio" value="5" name="partner_position">
                    <?php echo "其他（）"?></label>
                </ul></td>
            </tr>
            <tr>
              <th><i>*</i>人际关系：</th>
              <td class="vatop rowform"><ul>
                  <label>
                    <input type="radio" value="0" name="partner_relation" checked>
                    <?php echo "人缘好 "?></label>
                  <label>
                    <input type="radio" value="1" name="partner_relation">
                    <?php echo "活动力强"?></label>
                  <label>
                    <input type="radio" value="2" name="partner_relation">
                    <?php echo "其他（）"?></label>
                </ul></td>
            </tr>
            <tr>
              <th><i>*</i>村落户数：</th>
              <td class="vatop rowform"><ul>
                  <label>
                    <input type="radio" value="150户以内" name="partner_village" checked>
                    <?php echo "150户以内"?></label>
                  <label>
                    <input type="radio" value="150-300户" name="partner_village">
                    <?php echo "150-300户"?></label>
                  <label>
                    <input type="radio" value="300-500户" name="partner_village">
                    <?php echo "300-500户"?></label>
                  <label>
                    <input type="radio" value="500户以上" name="partner_village">
                    <?php echo "500户以上"?></label>
                </ul></td>
            </tr>
            <tr>
              <th><i>*</i>网购经验：</th>
              <td class="vatop rowform"><ul>
                  <label>
                    <input type="radio" value="0" name="partner_experience" checked>
                    <?php echo "无"?></label>
                  <label>
                    <input type="radio" value="1" name="partner_experience">
                    <?php echo "半年"?></label>
                  <label>
                    <input type="radio" value="2" name="partner_experience">
                    <?php echo "一年"?></label>
                  <label>
                    <input type="radio" value="3" name="partner_experience">
                    <?php echo "一年以上"?></label>
                </ul></td>
            </tr>
            <tr>
              <th><i>*</i>是否经营商铺或网店：</th>
              <td class="vatop rowform"><ul>
                  <label>
                    <input type="radio" value="0" name="partner_boss" checked>
                    <?php echo "无"?></label>
                  <label>
                    <input type="radio" value="1" name="partner_boss">
                    <?php echo "实体店"?></label>
                  <label>
                    <input type="radio" value="2" name="partner_boss">
                    <?php echo "网店"?></label>
                </ul></td>
              <span></span></td>
            </tr>
            <tr>
              <th><i>*</i>每天能付出多少时间：</th>
              <td class="vatop rowform"><ul>
                  <label>
                    <input type="radio" value="2小时以内" name="partner_time" checked>
                    <?php echo "2小时以内"?></label>
                  <label>
                    <input type="radio" value="2-5小时" name="partner_time">
                    <?php echo "2-5小时"?></label>
                  <label>
                    <input type="radio" value="半天" name="partner_time">
                    <?php echo "半天"?></label>
                  <label>
                    <input type="radio" value="全天" name="partner_time">
                    <?php echo "全天"?></label>
                </ul></td>
              <span></span></td>
            </tr>
            <tr>
              <th>对本公司的意见或建议（选填）：</th>
              <td><textarea name="partner_opinion" rows="20"cols="80" class="tarea" id="partner_opinion" value="<?php echo $output['partner_opinion'];?>"></textarea>
                <span></span></td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
              <td colspan="20">&nbsp;</td>
            </tr>
            </tfoot>
          </table>
        </form>
        <div class="bottom"><a id="btn_apply_company_next" href="javascript:;" class="btn">下一步，提交合伙人服务站信息</a></div>
      </div>

    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $('#form_company_info').validate({
      errorPlacement: function(error, element){
        element.nextAll('span').first().after(error);
      },
      rules : {
        partner_name: {
          required: true,
          maxlength: 5
        },
        partner_job: {
          required: true,
          maxlength: 20
        },
        partner_phone: {
          required: true,
          maxlength: 20
        },
        partner_address: {
          required: true,
          maxlength: 50
        }
      },
      messages : {
        partner_name: {
          required: '请输入姓名',
          maxlength: jQuery.validator.format("最多{0}个字")
        },
        partner_sex: {
          required: '请选择性别',
          maxlength: jQuery.validator.format("最多{0}个字")
        },
        partner_job: {
          required: '请输入职业',
          maxlength: jQuery.validator.format("最多{0}个字")
        },
        partner_phone: {
          required: '请输入联系电话',
          maxlength: jQuery.validator.format("最多{0}个字")
        },
        partner_address: {
          required: '请输入详细地址',
          maxlength: jQuery.validator.format("最多{0}个字")
        }
      }
    });

    $('#btn_apply_company_next').on('click', function() {
      if($('#form_company_info').valid()) {
        $('#form_company_info').submit();
      }
    });
  });
</script>