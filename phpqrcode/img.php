<?php
include 'phpqrcode.php'; 
$value = '1'; //二维码内容   
$errorCorrectionLevel = 'H';//容错级别   
$matrixPointSize = 4;//生成图片大小   
//生成二维码图片

if (isset($_REQUEST['data'])&&trim($_REQUEST['data']) != '') { 
    $value=$_REQUEST['data'];
} 
QRcode::png($value, false,$errorCorrectionLevel, $matrixPointSize, 1);   
?>