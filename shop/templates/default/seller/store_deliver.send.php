<?php defined('InShopNC') or exit('Access Invalid!');?>
<style type="text/css">
  .sticky .tabmenu { padding: 0;  position: relative; }
  .express {  width:94px;  height:20px;  font:15px/20px Georgia, "Times New Roman", Times, serif; text-decoration:none;  }
  .popwindow{    z-index: 10000;  display: none;  position: fixed;  top: 39%;  font-size: 14px;  left: 45%;  border-radius: 3px;  padding: 40px 30px 30px;  background-color: #FFF;  border: 1px solid rgb(189, 189, 189);  }

  input[type="submit"] {}
  .subbox{  -moz-border-bottom-colors: none;  -moz-border-left-colors: none;  -moz-border-right-colors: none;  -moz-border-top-colors: none;  background-color: #f5f5f5;  border-color: #dcdcdc #dcdcdc #b3b3b3;     display: block;  left: 50%;    position: absolute;    top: 40%;  width: 1px; height:0px; opacity: 0;  }
  #sub{  position:relative;  font-size: 12px;  font-weight: normal;  height: 29px;  padding: 0 15px 2px;  line-height: 20px;  left:-60px;  top:91px;  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.1);  width: 54px;  color: #777;  background-color: #F5F5F5;  text-align: center;  display: inline-block;  border-width: 1px;  border-style: solid;  -moz-border-top-colors: none;  -moz-border-right-colors: none;  -moz-border-bottom-colors: none;  -moz-border-left-colors: none;  border-image: none;  border-color: #DCDCDC #DCDCDC #B3B3B3;  cursor: pointer;  }
</style>

<!--<span class="fr mr5"> <a id="selectExpress" href="<?php /*echo urlShop('store_deliver', 'waybill_print_old', array('order_id' => $output['order_info']['order_id']));*/?>" class="ncsc-btn-mini" target="_blank" title="打印运单"/><i class="icon-print"></i>打印运单</a></span>-->
<span class="fr mr5"> <a id="selectExpress" href="javascript:void(0);" class="ncsc-btn-mini" target="_blank" title="打印运单"/><i class="icon-print"></i>打印运单</a></span>
<!--选择快递公司 -->
<div class="popwindow" id="popwindow" >
  <span id="temp"></span>
</div>

<div class="wrap" >
  <div class="step-title" ><em ><?php echo $lang['store_deliver_first_step'];?></em><?php echo $lang['store_deliver_confirm_trade'];?></div>
  <form name="deliver_form" method="POST" id="deliver_form" action="index.php?act=store_deliver&op=send&order_id=<?php echo $_GET['order_id'];?>" onsubmit="ajaxpost('deliver_form', '', '', 'onerror');return false;">
    <input type="hidden" value="<?php echo getReferer();?>" name="ref_url">
    <input type="hidden" value="ok" name="form_submit">
    <input type="hidden" id="shipping_express_id" value="<?php echo $output['order_info']['shipping_express_id'];?>" name="shipping_express_id">
    <input type="hidden" value="<?php echo $output['order_info']['extend_order_common']['reciver_name'];?>" name="reciver_name" id="reciver_name">
    <input type="hidden" value="<?php echo $output['order_info']['extend_order_common']['reciver_info']['area'];?>" name="reciver_area" id="reciver_area">
    <input type="hidden" value="<?php echo $output['order_info']['extend_order_common']['reciver_info']['street'];?>" name="reciver_street" id="reciver_street">
    <input type="hidden" value="<?php echo $output['order_info']['extend_order_common']['reciver_info']['mob_phone'];?>" name="reciver_mob_phone" id="reciver_mob_phone">
    <input type="hidden" value="<?php echo $output['order_info']['extend_order_common']['reciver_info']['tel_phone'];?>" name="reciver_tel_phone" id="reciver_tel_phone">
    <input type="hidden" value="<?php echo $output['order_info']['extend_order_common']['reciver_info']['dlyp'];?>" name="reciver_dlyp" id="reciver_dlyp">
    <input type="hidden" value="<?php echo $output['order_info']['extend_order_common']['reciver_info']['station_name'];?>" name="station_name" id="station_name">
    <table class="ncsc-default-table order deliver">

      <tbody>
      <?php if (is_array($output['order_info']) and !empty($output['order_info'])) { ?>
        <tr>
          <td colspan="20" class="sep-row"></td>
        </tr>
        <tr>
          <th colspan="20"><a href="index.php?act=store_order_print&order_id=<?php echo $output['order_info']['order_id'];?>" target="_blank" class="fr" title="<?php echo $lang['store_show_order_printorder'];?>"/><i class="print-order"></i></a><span class="fr mr30"></span><span class="ml10"><?php echo $lang['store_order_order_sn'].$lang['nc_colon'];?><?php echo $output['order_info']['order_sn']; ?></span><span class="ml20"><?php echo $lang['store_order_add_time'].$lang['nc_colon'];?><em class="goods-time"><?php echo date("Y-m-d H:i:s",$output['order_info']['add_time']); ?></em></span></th>
        </tr>
        <?php foreach($output['order_info']['extend_order_goods'] as $k => $goods_info) { ?>
          <tr>
            <td class="bdl w10"></td>
            <td class="w50"><div class="pic-thumb"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$goods_info['goods_id']));?>" target="_blank"><img src="<?php echo cthumb($goods_info['goods_image'],'60',$$output['order_info']['store_id']); ?>" /></a></div></td>
            <td class="tl"><dl class="goods-name">
                <dt><a target="_blank" href="<?php echo urlShop('goods','index',array('goods_id'=>$goods_info['goods_id']));?>"><?php echo $goods_info['goods_name']; ?></a></dt>
                <dd><strong>￥<?php echo $goods_info['goods_price']; ?></strong>&nbsp;x&nbsp;<em><?php echo $goods_info['goods_num'];?></em>件</dd>
              </dl></td>
            <?php if ((count($output['order_info']['extend_order_goods']) > 1 && $k ==0) || (count($output['order_info']['extend_order_goods']) == 1)){?>
              <td class="bdl bdr order-info w500" rowspan="<?php echo count($output['order_info']['extend_order_goods']);?>"><dl>
                  <dt><?php echo $lang['store_deliver_shipping_amount'].$lang['nc_colon'];?></dt>
                  <dd>
                    <?php if (!empty($output['order_info']['shipping_fee']) && $output['order_info']['shipping_fee'] != '0.00'){?>
                      <?php echo $output['order_info']['shipping_fee'];?>
                    <?php }else{?>
                      <?php echo $lang['nc_common_shipping_free'];?>
                    <?php }?>
                  </dd>
                </dl>
                <dl>
                  <dt><?php echo $lang['store_deliver_forget'].$lang['nc_colon'];?></dt>
                  <dd>
                    <textarea name="deliver_explain" cols="100" rows="2" class="w400 tip-t" title="<?php echo $lang['store_deliver_forget_tips'];?>"><?php echo $output['order_info']['extend_order_common']['deliver_explain'];?></textarea>
                  </dd>
                </dl></td>
            <?php }?>
          </tr>
        <?php }?>
        <tr>
          <td colspan="20" class="tl bdl bdr" style="padding:8px" id="address"><strong class="fl"><?php echo $lang['store_deliver_buyer_adress'].$lang['nc_colon'];?></strong><span id="buyer_address_span"><?php echo $output['order_info']['extend_order_common']['reciver_name'];?>&nbsp;<?php echo $output['order_info']['extend_order_common']['reciver_info']['phone'];?>&nbsp;<?php echo $output['order_info']['extend_order_common']['reciver_info']['address'];?>[自提服务站]</span>&nbsp;站长姓名：<?php echo $output['order_info']['extend_order_common']['reciver_info']['station_name'];?></span>,&nbsp;站长电话：<?php echo $output['order_info']['extend_order_common']['reciver_info']['mob_phone']==''?$output['order_info']['extend_order_common']['reciver_info']['tel_phone']:$output['order_info']['extend_order_common']['reciver_info']['mob_phone'];?></span>
            <!--          <a href="javascript:void(0)" nc_type="dialog" dialog_title="--><?php //echo $lang['store_deliver_buyer_adress'];?><!--" dialog_id="edit_buyer_address" uri="index.php?act=store_deliver&op=buyer_address_edit&order_id=--><?php //echo $output['order_info']['order_id'];?><!--" dialog_width="550" class="ncsc-btn-mini fr"><i class="icon-edit"></i>--><?php //echo $lang['nc_edit'];?><!--</a>-->
          </td>
        </tr>
      <?php } else { ?>
        <tr>
          <td colspan="20" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
    <div class="step-title mt30"><em><?php echo $lang['store_deliver_second_step'];?></em><?php echo $lang['store_deliver_confirm_daddress'];?></div>
    <div class="deliver-sell-info"><strong class="fl"><?php echo $lang['store_deliver_my_daddress'].$lang['nc_colon'];?></strong>
      <a href="javascript:void(0);" onclick="ajax_form('modfiy_daddress', '<?php echo $lang['store_deliver_select_daddress'];?>', 'index.php?act=store_deliver&op=send_address_select&order_id=<?php echo $output['order_info']['order_id'];?>', 640,0);" class="ncsc-btn-mini fr"><i class="icon-edit"></i><?php echo $lang['nc_edit'];?></a> <span id="seller_address_span">
      <?php if (empty($output['daddress_info'])) {?>
        <?php echo $lang['store_deliver_none_set'];?>
      <?php } else { ?>
        <?php echo $output['daddress_info']['seller_name'];?>&nbsp;<?php echo $output['daddress_info']['telphone'];?>&nbsp;<?php echo $output['daddress_info']['area_info'];?>&nbsp;<?php echo $output['daddress_info']['address'];?>
      <?php } ?>
      </span>
    </div>
    <input type="hidden" name="daddress_id" id="daddress_id" value="<?php echo $output['daddress_info']['address_id'];?>">
    <div class="step-title mt30"><em><?php echo $lang['store_deliver_third_step'];?></em><?php echo $lang['store_deliver_express_select'];?></div>
    <div class="alert alert-success"><?php echo $lang['store_deliver_express_note'];?></div>
    <div class="tabmenu">
      <ul class="tab pngFix">
        <li id="eli1" class="active"><a href="javascript:void(0);" onclick="etab(1)"><?php echo $lang['store_deliver_express_zx'];?></a></li>
        <?php if (!isset($output['order_info']['extend_order_common']['reciver_info']['dlyp'])) {?>
          <li id="eli2" class="normal"><a href="javascript:void(0);" onclick="etab(2)"><?php echo $lang['store_deliver_express_wx'];?></a></li>
        <?php } ?>
      </ul>
    </div>
    <!--选择物流服务 -->
    <table class="ncsc-default-table order" id="texpress1">
      <tbody>
      <tr>
        <td class="bdl w150"><?php echo $lang['store_deliver_company_name'];?></td>
        <td class="w250"><?php echo $lang['store_deliver_shipping_code'];?></td>
        <td class="tc"><?php echo $lang['store_deliver_bforget'];?></td>
        <td class="bdr w90 tc"><?php echo $lang['nc_common_button_operate'];?></td>
      </tr>
      <?php if (is_array($output['my_express_list']) && !empty($output['my_express_list'])){?>
        <?php foreach ($output['my_express_list'] as $k=>$v){?>
          <?php if (!isset($output['express_list'][$v])) continue;?>
          <tr>
            <td class="bdl" name="myexpress"><?php echo $output['express_list'][$v]['e_name'];?></td>
            <td class="bdl"><input name="shipping_code" type="text" class="text w200 tip-r" title="<?php echo $lang['store_deliver_shipping_code_tips'];?>" maxlength="20" nc_type='eb' nc_value="<?php echo $v;?>" value="<?php echo 'ZJ'.$output['order_info']['order_sn'];?>" readonly /></td>
            <td class="bdl gray" nc_value="<?php echo $v;?>"></td>
            <td class="bdl bdr tc"><a nc_type='eb' nc_value="<?php echo $v;?>" href="javascript:void(0);" class="ncsc-btn"><?php echo $lang['nc_common_button_confirm'];?></a></td>
          </tr>
        <?php }?>
      <?php }?>
      </tbody>
    </table>
    <table class="ncsc-default-table order" id="texpress2" style="display:none">
      <tbody>
      <tr>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td class="bdl tr"><?php echo $lang['store_deliver_no_deliver_tips'];?></td>
        <td class="bdr tl w400">&emsp;<a nc_type='eb' nc_value="e1000" href="javascript:void(0);" class="ncsc-btn"><?php echo $lang['nc_common_button_confirm'];?></a></td>
      </tr>
      <tr>
        <td colspan="2"></td>
      </tr>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<!--选择物流公司 -->
<script type="text/javascript">
  //单击打印运单时，生成下拉框
  $('#selectExpress').click(
      function(e){
        e.preventDefault();
        var $temp=$('#temp');
        $selectObj=$("<select id='expressVal' name='expresstype'></select>");

        //防止产生多个下拉框
        if($('#temp select').length==0){
          $temp.append($selectObj);
        }
        var $myexpress=$('td[name=myexpress]');
        //如果一家默认快递都没有选择，则展示一个提示框
        if($myexpress.size()==0)
        {
          showDialog('请至少选择一家默认快递公司','','','','','','','','',2,'');
          return;
        }
        //如果只有一家默认的快递，直接进入打印
        if($myexpress.size()==1)
        {
          var $form=$("<form id='selectform'  method='get'>"
              +"<input type='hidden' name='act' value='store_deliver'>"
              +"<input type='hidden' name='op' value='waybill_print_old'>"
              +"<input type='hidden' name='order_id' value=" +"<?php echo $output['order_info']['order_id'] ?>"+">"
              +"<input type='hidden' name='expresstype' value="+$('td[name=myexpress]:eq(0)').text()+">"
              +"<input type='submit' id='sub' value='确定'>"
              +"</form>");
          $('#popwindow').append($form);
          $('#selectform').submit();
          return;
        }
        if($myexpress.size()>=2)
        {
          //防止产生多个下拉框
          if($('#temp select option').length==0){
            //循环开始
            $myexpress.each(function(i,val){
              var $new_obj1=$("<option  value="+$(val).text()+"><span class='express'>"+$(val).text()+"</span></option>");
              $('#expressVal').append($new_obj1);

            });
            //循环结束
          }

          showDialog('请选择快递公司&nbsp&nbsp&nbsp&nbsp&nbsp'
              +"<form id='selectform'  method='get'>"
              +"<input type='hidden' name='act' value='store_deliver'>"
              +"<input type='hidden' name='op' value='waybill_print_old'>"
              +"<input type='hidden' name='order_id' value=" +"<?php echo $output['order_info']['order_id'] ?>"+">"
              +$temp.html()
              +"<div class='subbox'>"
              +"<input type='submit' id='sub'   value='确定'>"
              +"</div>"
              +"</form>",'confirm','','','','','','','','','');
        }
      }
  );
</script>

<script type="text/javascript">
  function etab(t){
    if (t==1){
      $('#eli1').removeClass('normal').addClass('active');
      $('#eli2').removeClass('active').addClass('normal');
      $('#texpress1').css('display','');
      $('#texpress2').css('display','none');
    }else{
      $('#eli1').removeClass('active').addClass('normal');
      $('#eli2').removeClass('normal').addClass('active');
      $('#texpress1').css('display','none');
      $('#texpress2').css('display','');
    }
  }
  $(function(){
    //表单提示
    $('.tip-t').poshytip({
      className: 'tip-yellowsimple',
      showOn: 'focus',
      alignTo: 'target',
      alignX: 'center',
      alignY: 'top',
      offsetX: 0,
      offsetY: 2,
      allowTipHover: false
    });
    $('.tip-r').poshytip({
      className: 'tip-yellowsimple',
      showOn: 'focus',
      alignTo: 'target',
      alignX: 'right',
      alignY: 'center',
      offsetX: -50,
      offsetY: 0,
      allowTipHover: false
    });
    $('a[nc_type="eb"]').on('click',function(){
      if ($('input[nc_value="'+$(this).attr('nc_value')+'"]').val() == ''){
        showDialog('<?php echo $lang['store_deliver_shipping_code_pl'];?>', 'error','','','','','','','','',2);return false;
      }
      $('input[nc_type="eb"]').attr('disabled',true);
      $('input[nc_value="'+$(this).attr('nc_value')+'"]').attr('disabled',false);
      $('#shipping_express_id').val($(this).attr('nc_value'));
      $('#deliver_form').submit();
    });

    $('#add_time_from').datepicker({dateFormat: 'yy-mm-dd'});
    $('#add_time_to').datepicker({dateFormat: 'yy-mm-dd'});
    $('.checkall_s').click(function(){
      var if_check = $(this).attr('checked');
      $('.checkitem').each(function(){
        if(!this.disabled)
        {
          $(this).attr('checked', if_check);
        }
      });
      $('.checkall_s').attr('checked', if_check);
    });
    <?php if ($output['order_info']['shipping_code'] != ''){?>
    $('input[nc_value="<?php echo $output['order_info']['extend_order_common']['shipping_express_id'];?>"]').val('<?php echo $output['order_info']['shipping_code'];?>');
    $('td[nc_value="<?php echo $output['order_info']['extend_order_common']['shipping_express_id'];?>"]').html('<?php echo $output['order_info']['extend_order_common']['deliver_explain'];?>');
    <?php } ?>

    $('#my_address_add').click(function(){
      ajax_form('my_address_add', '<?php echo $lang['store_deliver_add_daddress'];?>' , 'index.php?act=store_deliver&op=send_address_edit', 550,0);
    });
  });
</script>



