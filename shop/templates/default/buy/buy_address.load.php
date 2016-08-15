<?php defined('InShopNC') or exit('Access Invalid!');?>

<ul>
    <?php foreach((array)$output['address_list'] as $k=>$val){ ?>
        <li class="receive_add address_item <?php echo $k == 0 ? 'ncc-selected-item' : null; ?>">
            <input address="<?php echo intval($val['dlyp_id']) ? '[自提服务站] ' : '';?><?php echo $val['area_info'].'&nbsp;'.$val['address']; ?>" true_name="<?php echo $val['true_name'];?>" id="addr_<?php echo $val['address_id']; ?>" nc_type="addr" type="radio" class="radio" city_id="<?php echo $val['city_id']?>" area_id=<?php echo $val['area_id'];?> name="addr" value="<?php echo $val['address_id']; ?>" phone="<?php echo $val['mob_phone'] ? $val['mob_phone'] : $val['tel_phone'];?>" <?php echo $val['is_default'] == '1' ? 'checked' : null; ?> />
            <label for="addr_<?php echo $val['address_id']; ?>"><span class="true-name"><?php echo $val['true_name'];?></span><span class="address"><?php echo intval($val['dlyp_id']) ? '[自提服务站] ' : '';?><?php echo $val['area_info']; ?>&nbsp;<?php echo $val['address']; ?></span><span class="phone"><i class="icon-mobile-phone"></i><?php echo $val['mob_phone'] ? $val['mob_phone'] : $val['tel_phone'];?></span></label>
            <a href="javascript:void(0);" onclick="delAddr(<?php echo $val['address_id']?>);" class="del">[ 删除 ]</a> </li>
    <?php } ?>
    <li class="receive_add addr_item">
        <input value="0" nc_type="addr" id="add_addr" type="radio" name="addr">
        <label for="add_addr">使用新的收货地址</label>
    </li>
    <div id="add_addr_box"><!-- 存放新增地址表单 --></div>
</ul>
<div class="hr16"> <a id="hide_addr_list" class="ncc-btn ncc-btn-red" href="javascript:void(0);"><?php echo $lang['cart_step1_addnewaddress_submit'];?></a></div>
<script type="text/javascript">
    function delAddr(id){
        $('#addr_list').load(SITEURL+'/index.php?act=buy&op=load_addr&id='+id);
    }
    $(function(){
        function addAddr() {
            $('#add_addr_box').load(SITEURL+'/index.php?act=buy&op=add_delivery');
        }
        $('input[nc_type="addr"]').on('click',function(){
            if ($(this).val() == '0') {
                $('.address_item').removeClass('ncc-selected-item');
                $('#add_addr_box').load(SITEURL+'/index.php?act=buy&op=add_delivery');
            } else {
                $('.address_item').removeClass('ncc-selected-item');
                $(this).parent().addClass('ncc-selected-item');
                $('#add_addr_box').html('');
            }
        });
        $('#hide_addr_list').on('click',function(){
            if ($('input[nc_type="addr"]:checked').val() == '0'){
                //submitAddAddr();
                submitAddDeliveryAddr();            
		} else {
                if ($('input[nc_type="addr"]:checked').size() == 0) {
                    alert('你还没有没有选择地址');
                    return false;
                }
                var city_id = $('input[name="addr"]:checked').attr('city_id');
                var area_id = $('input[name="addr"]:checked').attr('area_id');
                var addr_id = $('input[name="addr"]:checked').val();
                var true_name = $('input[name="addr"]:checked').attr('true_name');
                var address = $('input[name="addr"]:checked').attr('address');
                var phone = $('input[name="addr"]:checked').attr('phone');
                showShippingPrice(city_id,area_id);
                hideAddrList(addr_id,true_name,address,phone);
            }
            //将当前选择地址设置为默认地址,原来的默认地址清空.
            $.ajax({
                url: SITEURL + "/index.php?act=member_address&op=set_default_address",
                data: {addr_id: addr_id},
                type: "get",
                success: function() {
                    //重新加载页面..地址变化需要判断是否去增加运费商品,所以重载页面,by alphawu,2016.01.11
                    location.reload();
                }
            });
        });
        if ($('input[nc_type="addr"]').size() == 1){
            $('#add_addr').attr('checked',true);
            addAddr();
        }
    });
</script>