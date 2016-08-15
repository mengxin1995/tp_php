<?php
/**
 * 菜单 v3-b12
 *
 * by 33hao 好商城V3  www.33hao.com 开发
 */
defined('InShopNC') or exit('Access Invalid!');
/**
 * top 数组是顶部菜单 ，left数组是左侧菜单
 * left数组中'args'=>'welcome,dashboard,dashboard',三个分别为op,act,nav，权限依据act来判断
 */
$arr = array(
    'top' => array(
        0 => array(
            'args' => 'dashboard',
            'text' => $lang['nc_console']),
        1 => array(
            'args' => 'store',
            'text' => '监控'),
        2 => array(
            'args' => 'setting',
            'text' => '设置'),
        3 => array(
            'args' => 'stat',
            'text' => $lang['nc_stat']),
    ),
    'left' => array(
        0 => array(
            'nav' => 'dashboard',
            'text' => $lang['nc_normal_handle'],
            'list' => array(
                array('args' => 'welcome,dashboard,dashboard', 'text' => $lang['nc_welcome_page']),
                array('args' => 'aboutus,dashboard,dashboard', 'text' => $lang['nc_aboutus']),
            )
        ),
        1 => array(
            'nav' => 'store',
            'text' => '监控',
            'list' => array(
                array('args' => 'store,store,store', 'text' => '节点监控'),
                array('args' => 'newcom_add,store,store', 'text' => '新建节点'),
                array('args' => 'store_fenzu,store,store', 'text' => '分组管理'),
                array('args' => 'com_state,store,store', 'text' => '状态变化记录'),
            )
        ),
        2 => array(
            'nav' => 'setting',
            'text' => '设置',
            'list' => array(
                array('args' => 'cms_manage,cms_manage,setting', 'text' => "节点采集设置"),
                array('args' => 'xianshiguize,store,setting', 'text' => '异常报警规则'),
                array('args' => 'store_yujing,store,setting', 'text' => '新建规则'),
            )
        ),

        3 => array(
            'nav' => 'stat',
            'text' => $lang['nc_stat'],
            'list' => array(
                array('args' => 'newstore,stat_store,stat', 'text' => '异常统计'),
            )
        ),
    ),
);

if (C('flea_isuse') == 1) {
    $arr['top'][] = array(
        'args' => 'flea',
        'text' => 用户管理);
    $arr['left'][] = array(
        'nav' => 'flea',
        'text' => 用户管理,
        'list' => array(
            0 => array('args' => 'admin,admin,flea', 'text' => $lang['nc_limit_manage']),
        )
    );
}
if (C('cms_isuse') !== null) {
    $arr['top'][] = array(
        'args' => 'cms',
        'text' => 日志);
    $arr['left'][] = array(
        'nav' => 'cms',
        'text' => 日志,
        'list' => array(
            0 => array('args' => 'list,admin_log,cms', 'text' => $lang['nc_admin_log']),
            1 => array('args' => 'db,db,cms', 'text' => '数据备份'),
            )
    );
}

return $arr;
?>
