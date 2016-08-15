<?php
include "TopSdk.php";
header("Content-Type: text/html;charset=UTF-8");
header('Content-type:text/json');
date_default_timezone_set('Asia/Shanghai');
$appkey = '23253574';
$secret = 'fee781d9438d8dc9df3d70b9cd2ebbd8';

$c = new TopClient;
$c->appkey = $appkey;
$c->secretKey = $secret;
$c->format = 'json';

$type = $_GET['type'];
$resp = null;
if($type=='info'){
	/*
	 * taobao.tbk.item.info.get (淘宝客商品详情（简版）)
	 * http://open.taobao.com/doc2/apiDetail.htm?apiId=24518&scopeId=11655
	 */
	$req = new TbkItemInfoGetRequest;
	$req->setFields("title,small_images,num_iid,pict_url,reserve_price,zk_final_price,user_type,item_url");
	$req->setNumIids($_GET['id']);
	$resp = $c->execute($req);
}
else{
	/*
     * taobao.tbk.item.get (淘宝客商品查询)
     * http://open.taobao.com/doc2/apiDetail.htm?apiId=24515
     */
	$req = new TbkItemGetRequest;
	$req->setFields("title,small_images,num_iid,pict_url,reserve_price,zk_final_price,user_type,item_url");
	$req->setQ($_GET['key']);
	$req->setSort("tk_rate");
	$req->setIsTmall("true");
	$req->setEndTkRate(1000);
	$req->setPageSize(60);
	$resp = $c->execute($req);
}
$tbk = $resp->{'results'}->{'n_tbk_item'};

echo (json_encode($tbk));