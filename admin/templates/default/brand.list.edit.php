<?php defined('InShopNC') or exit('Access Invalid!');?>

<table class="table tb-type2" id="brand_table">
    <?php if(is_array($output['brand_list']) && !empty($output['brand_list'])) {?>
        <tbody>
        <input type="hidden" value="" name="brand[form_submit]" nc_type="submit_value" />
        <?php foreach ($output['brand_list'] as $k=>$val){?>
            <tr class="hover edit">
                <td><h6 class="clear" id="brand_h6_<?php echo $k;?>"><?php echo $val['name'];?></h6>
                    <ul class="nofloat">
                        <?php if($val['brand']) {?>
                            <?php foreach ($val['brand'] as $bval){?>
                                <li class="left w33pre h36">
                                    <input type="checkbox" name="brand_id[]" class="brand_change_default_submit_value" value="<?php echo $bval['brand_id']?>" <?php if(in_array($bval['brand_id'], $output['brang_related'])){ echo 'checked="checked"';}?> id="brand_<?php echo $bval['brand_id'];?>" />
                                    <label for="brand_<?php echo $bval['brand_id'];?>"><?php echo $bval['brand_name']?></label>
                                </li>
                            <?php }?>
                        <?php }?>
                    </ul></td>
            </tr>
        <?php }?>
        </tbody>
    <?php }else{?>
        <tbody>
        <tr>
            <td class="tips" colspan="15"><?php echo $lang['type_add_brand_null_one'];?><a href="JavaScript:void(0);" onclick="window.parent.openItem('brand,brand,goods')"><?php echo $lang['nc_brand_manage'];?></a><?php echo $lang['type_add_brand_null_two']?></td>
        </tr>
        </tbody>
    <?php }?>
</table>