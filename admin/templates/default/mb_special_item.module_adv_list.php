<?php defined('InShopNC') or exit('Access Invalid!');?>
<?php if($item_edit_flag) { ?>
<table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12" class="nobg"> <div class="title nomargin">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span> </div>
        </th>
      </tr>
      <tr>
        <td><ul>
            <li>点击添加新的广告条按钮可以添加新的广告条</li>
            <li>鼠标移动到已有的广告条上点击出现的删除按钮可以删除对应的广告条</li>
            <li>操作完成后点击保存编辑按钮进行保存</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <?php } ?>
<div class="index_block adv_list">
    <?php if($item_edit_flag) { ?>
  <h3>广告条版块</h3>
  <?php } ?>
  <div nctype="item_content" class="content" id="item_list_adv">
    <?php if($item_edit_flag) { ?>
    <h5>内容：</h5>
    <?php } ?>
    <?php if(!empty($item_data['item']) && is_array($item_data['item'])) {?>
    <?php foreach($item_data['item'] as $item_key => $item_value) {?>
    <div nctype="item_image" class="item"> <img nctype="image" src="<?php echo getMbSpecialImageUrl($item_value['image']);?>" alt="">
      <?php if($item_edit_flag) { ?>
      <input nctype="image_name" name="item_data[item][<?php echo $item_key;?>][image]" type="hidden" value="<?php echo $item_value['image'];?>">
      <input nctype="image_type" name="item_data[item][<?php echo $item_key;?>][type]" type="hidden" value="<?php echo $item_value['type'];?>">
      <input nctype="image_data" name="item_data[item][<?php echo $item_key;?>][data]" type="hidden" value="<?php echo $item_value['data'];?>">
      <div class="handle">
      <a nctype="btn_edit_item_image" href="javascript:;"><i class="icon-edit"></i>修改</a>
      <a nctype="btn_del_item_image" href="javascript:;"><i class="icon-trash"></i>删除</a>
      <a nctype="btn_move_up" href="javascript:;"><i class="icon-arrow-up"></i>上移</a>
      <a nctype="btn_move_down" href="javascript:;"><i class="icon-arrow-down"></i>下移</a>
      </div>
      <?php } ?>
    </div>
    <?php } ?>
    <?php } ?>
  </div>
  <?php if($item_edit_flag) { ?>
  <a nctype="btn_add_item_image" class="btn-add" data-desc="640*240" href="javascript:;">添加新的广告条</a>
  <?php } ?>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        //上移
        $('#item_list_adv').on('click', '[nctype="btn_move_up"]', function() {
            var $current = $(this).parents('[nctype="item_image"]');
            $prev = $current.prev('[nctype="item_image"]');
            if($prev.length > 0) {
                $prev.before($current);
            } else {
                showError('已经是第一个了');
            }
        });

        //下移
        $('#item_list_adv').on('click', '[nctype="btn_move_down"]', function() {
            var $current = $(this).parents('[nctype="item_image"]');
            $next = $current.next('[nctype="item_image"]');
            if($next.length > 0) {
                $next.after($current);
            } else {
                showError('已经是最后一个了');
            }
        });
    });
</script>
