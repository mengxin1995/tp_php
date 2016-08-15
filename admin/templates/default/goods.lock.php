<?php defined('InShopNC') or exit('Access Invalid!');?>
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
<link rel="stylesheet" href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>商品</h3>
            <ul class="tab-base">
                <li><a href="<?php echo urlAdmin('goods', 'goods');?>" ><span><?php echo $lang['goods_index_all_goods'];?></span></a></li>
                <li><a href="<?php echo urlAdmin('goods', 'goods', array('type' => 'lockup'));?>" ><span><?php echo $lang['goods_index_lock_goods'];?></span></a></li>
                <li><a href="<?php echo urlAdmin('goods', 'goods', array('type' => 'waitverify'));?>"><span>等待审核</span></a></li>
                <li><a href="<?php echo urlAdmin('goods', 'goods_set');?>"><span><?php echo $lang['nc_goods_set'];?></span></a></li>
                <li><a class="current" href="JavaScript:void(0);"><span>商品锁定</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="goodsLock" name="goodsLock" method="get">
        <input type="hidden" name="act" value="goods">
        <input type="hidden" name="op" value="goods_lock_save">
        <input type="hidden" name="goods_id" value="<?php echo $output['goods_id'] ?>">
        <table class="table tb-type2">
            <tbody>

            <tr class="noborder" style="background: rgb(255, 255, 255) none repeat scroll 0% 0%;">
                <td class="required" colspan="2">
                    <label>全部锁定:</label>
                </td>
            </tr>
            <tr class="noborder" style="background: rgb(255, 255, 255) none repeat scroll 0% 0%;">
                <td class="vatop rowform onoff">
                    <label class="cb-enable <?php if($output['lock_type'] == '1'){ ?>selected<?php } ?>" title="开启" for="all_lock_1">
                        <span>开启</span>
                    </label>
                    <label class="cb-disable <?php if($output['lock_type'] != '1'){ ?>selected<?php } ?>" title="关闭" for="all_lock_0">
                        <span>关闭</span>
                    </label>
                    <input id="all_lock_1" type="radio"  value="1" <?php if($output['lock_type'] == '1'){ ?>checked="checked"<?php } ?> name="all_lock">
                    <input id="all_lock_0" type="radio" value="0" <?php if($output['lock_type'] != '1'){ ?>checked="checked"<?php } ?>" name="all_lock">
                </td>
            </tr>

            <tr class="noborder" style="background: rgb(255, 255, 255) none repeat scroll 0% 0%;">
                <td class="required" colspan="2">
                    <label>价格锁定:</label>
                </td>
            </tr>
            <tr class="noborder" style="background: rgb(255, 255, 255) none repeat scroll 0% 0%;">
                <td class="vatop rowform onoff">
                    <label class="cb-enable <?php if($output['lock_type'] == '2'||$output['lock_type'] == '4'){ ?>selected<?php } ?>" title="开启" for="price_lock_1">
                        <span>开启</span>
                    </label>
                    <label class="cb-disable <?php if($output['lock_type'] == '1'||$output['lock_type'] == '0'||$output['lock_type'] == '3'){ ?>selected<?php } ?>" title="关闭" for="price_lock_0">
                        <span>关闭</span>
                    </label>
                    <input id="price_lock_1" type="radio"  value="1" <?php if($output['lock_type'] == '2'||$output['lock_type'] == '4'){ ?>checked="checked"<?php } ?> name="price_lock">
                    <input id="price_lock_0" type="radio" value="0" <?php if($output['lock_type'] == '1'||$output['lock_type'] == '0'||$output['lock_type'] == '3'){ ?>checked="checked"<?php } ?> name="price_lock">
                </td>
            </tr>

            <tr style="background: rgb(255, 255, 255) none repeat scroll 0% 0%;">
                <td class="required" colspan="2">库存锁定: </td>
            </tr>
            <tr class="noborder" style="background: rgb(255, 255, 255) none repeat scroll 0% 0%;">
                <td class="vatop rowform onoff">
                    <label class="cb-enable <?php if($output['lock_type'] == '3'||$output['lock_type'] == '4'){ ?>selected<?php } ?>" title="开启" for="stock_lock_1">
                        <span>开启</span>
                    </label>
                    <label class="cb-disable <?php if($output['lock_type'] == '1'||$output['lock_type'] == '0'||$output['lock_type'] == '2'){ ?>selected<?php } ?>" title="关闭" for="stock_lock_0">
                        <span>关闭</span>
                    </label>
                    <input id="stock_lock_1" type="radio" value="1" <?php if($output['lock_type'] == '3'||$output['lock_type'] == '4'){ ?>checked="checked"<?php } ?> name="stock_lock">
                    <input id="stock_lock_0" type="radio" value="0" <?php if($output['lock_type'] == '1'||$output['lock_type'] == '0'||$output['lock_type'] == '2'){ ?>checked="checked"<?php } ?> name="stock_lock">
                </td>
            </tr>

            </tbody>
            <tfoot>
            <tr class="tfoot" style="background: rgb(255, 255, 255) none repeat scroll 0% 0%;">
                <td colspan="2">
                    <a id="submitBtn" class="btn" href="JavaScript:void(0);">
                        <span>提交</span>
                    </a>
                </td>
            </tr>
            </tfoot>
        </table>
        </form>

</div>
<script>
    //表单提交
    $('#submitBtn').click(function(){
    $('#goodsLock').submit();
    });

</script>
