<?php
include 'phpqrcode.php'; 
$value = '1'; //��ά������   
$errorCorrectionLevel = 'H';//�ݴ���   
$matrixPointSize = 4;//����ͼƬ��С   
//���ɶ�ά��ͼƬ

if (isset($_REQUEST['data'])&&trim($_REQUEST['data']) != '') { 
    $value=$_REQUEST['data'];
} 
QRcode::png($value, false,$errorCorrectionLevel, $matrixPointSize, 1);   
?>