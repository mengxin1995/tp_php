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

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo '计算机';?></h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span>组别A</span></a></li>
                <li><a href="index.php?act=store&op=com_b" ><span>组别B</span></a></li>
                <li><a href="index.php?act=store&op=com_c" ><span>组别C</span></a></li>
                <li><a href="index.php?act=store&op=com_d" ><span>组别D</span></a></li>
                <li><a href="index.php?act=store&op=com_e" ><span>组别E</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <tr>
        <td class="required">当前采集周期：</td>
        <td colspan="2"><?php if($output['t']['time']) {echo $output['t']['time'];} else {echo '0';}?><span style="color:red; font-size: 15px; margin-left: 2px;">分</span></td>
    </tr>
    <form method="get" name="formSearch" id="formSearch" action="index.php?act=store&op=com_a">
        <input type="hidden" value="store" name="act">
        <input type="hidden" value="com_a" name="op">
        <table class="tb-type1 noborder search">
            <h3>按条件查询：</h3>
            <tbody>
            <tr>
                <td><label for="owner_and_name">IP</label></td>
                <td><input type="text" value="<?php echo $output['com_ip'];?>" name="com_ip" id="com_ip" class="txt"></td>
                <td class="vatop tips">(查找IP中带有相应数字的计算机)</td>
            </tr>
            <tr>
                <td><label for="owner_and_name">用户名</label></td>
                <td><input type="text" value="<?php echo $output['username'];?>" name="username" id="username" class="txt"></td>
                <td class="vatop tips">(查找对应用户名的计算机)</td>
            </tr>
            <tr>
                <td><label for="owner_and_name">计算机名</label></td>
                <td><input type="text" value="<?php echo $output['com_name'];?>" name="com_name" id="com_name" class="txt"></td>
                <td class="vatop tips">(查找计算机名)</td>
            </tr>
            <tr>
                <td><label for="owner_and_name">状态</label></td>
                <td>
                    <select name="com_state">
                        <option value="3" <?php if($output['com_state'] == 3){ echo 'selected = "selected"';}?>>请选择</option>
                        <option value="0" <?php if($output['com_state'] == 0){ echo 'selected = "selected"';}?>>正常</option>
                        <option value="1" <?php if($output['com_state'] == 1){ echo 'selected = "selected"';}?>>预警</option>
                        <option value="2" <?php if($output['com_state'] == 2){ echo 'selected = "selected"';}?>>报警</option>
                    </select>
                </td>
                <td class="vatop tips">(按照计算机状态查找)</td>
            </tr>
            </tbody>
            <tfoot>
            <td colspan="2">
                <input type="submit" value="查找" style="margin-top:10px;height:25px;width:70px; cursor: pointer;"/>
            </td>
            </tfoot>
        </table>
    </form>
    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th style="text-align: center">IP</th>
            <th style="text-align: center">用户名</th>
            <th style="text-align: center">计算机名</th>
            <th style="text-align: center">CPU利用率</th>
            <th style="text-align: center">CPU温度</th>
            <th style="text-align: center">内存利用率</th>
            <th style="text-align: center">计算机状态</th>
            <th class="align-center">状态显示</th>
            <th class="align-center">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr>
                    <td style="text-align: center"><?php echo $v['com_ip'];?></td>
                    <td style="text-align: center"><?php echo $v['username'];?></td>
                    <td style="text-align: center"><?php echo $v['com_name'];?></td>
                    <td style="text-align: center"><?php if($output['t']['cpu_isuse'] == 1)echo $v['cpu_sum_use']; else echo "您没有开启监测CPU"?></td>
                    <td style="text-align: center"><?php if($output['t']['cpu_isuse'] == 1)echo $v['cpu_temp']; else echo "您没有开启监测CPU"?></td>
                    <td style="text-align: center"><?php if($output['t']['mem_isuse'] == 1)echo $v['mem_shiyong']; else echo "您没有开启对内存的监控"?></td>
                    <td style="text-align: center"><?php if($v['is_warning'] == 0){echo "正常";}
                        else if ($v['is_warning'] == 1){echo "预警";}
                        else if ($v['is_warning'] == 2){echo "报警";}
                        ?></td>
                    <td style="text-align: center"><?php if($v['is_warning'] == 0){?><img src="./templates/default/images/op_memo_0.png"><?php }?>
                        <?php if($v['is_warning'] == 1){?><img src="./templates/default/images/op_memo_1.png"><?php }?>
                        <?php if($v['is_warning'] == 2){?><img src="./templates/default/images/op_memo_2.png"><?php }?>
                    </td>
                    <td class="align-center w200">
                        <a href="index.php?act=store&op=store_look&com_ip=<?php echo $v['com_ip'];?>">查看</a>&nbsp;&nbsp;
                        <a href="index.php?act=store&op=store_edit&com_ip=<?php echo $v['com_ip']?>">编辑</a>&nbsp;&nbsp;
                        <a href="index.php?act=store&op=lishi&com_ip=<?php echo $v['com_ip'];?>&ok=<?php echo $output['t'];?>">历史</a>&nbsp;&nbsp;
                        <a href="index.php?act=store&op=store_jincheng&com_ip=<?php echo $v['com_ip'];?>&ok=<?php echo $output['t']['fuwu'];?>">进程</a>&nbsp;&nbsp;
                        <a href="index.php?act=store&op=del&com_ip=<?php echo $v['com_ip']?>" onclick="return confirm('您确认要删除此计算机吗？');">删除</a>
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