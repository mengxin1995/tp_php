<?php defined('InShopNC') or exit('Access Invalid!');?>
<!--v3-v12-->

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo '设置';?></h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span>规则列表</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th style="text-align: center">属性</th>
            <th style="text-align: center">条件</th>
            <th style="text-align: center">预警临界值(% / 度)</th>
            <th style="text-align: center">报警临界值(% / 度)</th>
            <th style="text-align: center">邮件(开/关)</th>
            <th style="text-align: center">短信(开/关)</th>
            <th class="align-center">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr>
                    <td style="text-align: center"><?php if($v['shuxing'] == 1) echo "内存占用量";
                            else if($v['shuxing'] == 2) echo "CPU占用量";
                            else if($v['shuxing'] == 3) echo "CPU温度大小";
                            ;?></td>
                    <td style="text-align: center"><?php if($v['state'] == 1) echo "大于"; else echo "小于";?></td>
                    <td style="text-align: center"><?php echo $v['yujing'];?></td>
                    <td style="text-align: center"><?php echo $v['baojing'];?></td>
                    <td style="text-align: center"><?php if($v['is_mail'] == 1) echo "开启"; else echo "关闭";?></td>
                    <td style="text-align: center"><?php if($v['is_duanxin'] == 1) echo "开启"; else echo "关闭"; ?></td>
                    <td class="align-center w200">
                        <a href="index.php?act=store&op=bianjiyujing&id=<?php echo $v['id']?>">编辑</a>&nbsp;&nbsp;
                        <a href="index.php?act=store&op=del_guize&id=<?php echo $v['id']?>" onclick="return confirm('您确认要删除此计算机吗？');">删除</a>
                    </td>
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