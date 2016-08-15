<?php
/**
 * 测试类
 *
 *
 * 诸暨村淘 zjcuntao
 *
 */
defined('InShopNC') or exit('Access Invalid!');

class getitemControl extends BaseHomeControl
{
    public function goodsOp(){
        header("Content-Type: text/html;charset=UTF-8");
        //$url=BASE_SITE_URL.DS.'tbksdk'.DS.'tbk.php?type=&key=苹果';
        $url=BASE_SITE_URL.DS.'tbksdk'.DS.'tbk.php?type=info&id='.$_GET['id'];
        import('function.ftp');
        $content = dfsockopen($url);
        //$content = json_decode($content,true);
        $tbk = json_decode($content);

        if(empty($tbk)){
            redirect(SHOP_SITE_URL);
            exit;
        }

        $mode_goods = Model('goods');
        $item_id ='';
        foreach($tbk as $t){
            $title          = ($t->{'title'});
            $num_iid        = ($t->{'num_iid'});
            $pict_url       = ($t->{'pict_url'});
            $reserve_price  = ($t->{'reserve_price'});
            $zk_final_price = ($t->{'zk_final_price'});
            $item_url       = ($t->{'item_url'});
            $small_images   = ($t->{'small_images'}->{'string'});

            $condition = array();
            $condition['goods_barcode'] = $t->{'num_iid'};

            $result = $mode_goods->getGoodsList($condition);
            if(empty($result)) {
                $item_id = $this->add_tbk_goods($num_iid, $title, $pict_url, $item_url, $reserve_price, $zk_final_price, $small_images);
            }else{
                $item_id = $result[0]['goods_id'];
            }
        }

        redirect(SHOP_SITE_URL.DS.'index.php?act=goods&op=index&goods_id='.$item_id);
    }


    private function add_tbk_goods($num_iid,$title,$pict_url,$item_url,$reserve_price,$zk_final_price,$small_images){
        // 生成商品二维码
        require_once(BASE_RESOURCE_PATH.DS.'phpqrcode'.DS.'index.php');
        $PhpQRCode = new PhpQRCode();
        $PhpQRCode->set('pngTempDir',BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'1'.DS);

        $store_id       = 344;
        $store_name     = '外部商品';
        $goods_body     = '';

        $goods_image    =$store_id.'_'.basename($pict_url);//[^/]*$

        $model_store_goods	= Model('goods');

        $param	= array();
        $param['goods_name']			= $title;
        $param['gc_id']					= 0;
        $param['gc_id_1']				= 0;
        $param['gc_id_2']				= 0;
        $param['gc_id_3']				= 0;
        $param['gc_name']				= '';
        $param['spec_name']				= 'N;';
        $param['spec_value']		    = 'N;';
        $param['store_name']	        = $store_name;
        $param['store_id']				= $store_id;
        $param['type_id']				= '0';
        $param['goods_image']			= $goods_image;
        $param['goods_marketprice']		= $reserve_price;
        $param['goods_price']           = $zk_final_price;
        //$param['goods_show']			= '1';
        $param['goods_commend']			= 0;
        $param['goods_addtime']		    = time();
        $param['goods_body']			= $goods_body;
        $param['goods_state']			= '1';
        $param['goods_verify']			= '1';
        $param['areaid_1']				= 0;
        $param['areaid_2']			    = 0;
        //$param['goods_stcids']          = ',' . implode(',', array_unique($_POST['sgcate_id'])) . ',';
        $param['goods_serial']	        = $num_iid;
        $param['goods_barcode']	        = $num_iid;
        //$param['goods_jingle']	    = $num_iid;
        $goods_id	                    = $model_store_goods->addGoodsCommon($param);

        //添加库存
        $param	= array();
        $param['goods_commonid']        = $goods_id;
        $param['goods_name']			= $title;
        $param['gc_id']					= 0;
        $param['store_id']				= $store_id;
        $param['goods_image']			= $goods_image;
        $param['goods_marketprice']		= $reserve_price;
        $param['goods_price']           = $zk_final_price;
        //$param['goods_show']			= '1';
        $param['goods_commend']			= 0;
        $param['goods_addtime']		    = time();
        $param['goods_state']			= '1';
        $param['goods_verify']			= '1';
        $param['areaid_1']				= 0;
        $param['areaid_2']			    = 0;
//        $param['goods_stcids']          = ',' . implode(',', array_unique($_POST['sgcate_id'])) . ',';
        $param['goods_storage']	        = 9999;
        $param['goods_serial']	        = $num_iid;
        $param['goods_barcode']	        = $num_iid;
        //$param['goods_jingle']	    = $num_iid;
        $param['gc_id_1']				= 0;
        $param['gc_id_2']				= 0;
        $param['gc_id_3']				= 0;
        $param['goods_promotion_price']	= $zk_final_price;
        $param['goods_spec']			= 'N;';
        $param['store_name']	        = $store_name;

        $goods_id1                      = $model_store_goods->addGoods($param);

        //导入后直接生成二维码
        $PhpQRCode->set('date',WAP_SITE_URL . '/tmpl/product_detail.html?goods_id='.$goods_id1);
        $PhpQRCode->set('pngTempName', $goods_id1 . '.png');
        $PhpQRCode->init();

        if($goods_id){
            /**
             * 商品多图的添加
             */
            $pic_array = array();

            $path = BASE_UPLOAD_PATH.DS.'shop/store/goods'.DS.$store_id;
            if(!file_exists($path)){mkdir($path,0777);}//如果目录不存在，则创建
            //添加主图


            $saved_name = $path.DS.$goods_image;
            if(copy($pict_url,$saved_name)){//存储主图
                preg_match('/(?P<name>.*)\./', $goods_image, $m);

                //将主图分成4个大小存储
                $fz60	= $path.DS.$m['name'].'_60.jpg';
                $fz240	= $path.DS.$m['name'].'_240.jpg';
                $fz360  = $path.DS.$m['name'].'_360.jpg';
                $fz1280 = $path.DS.$m['name'].'_1280.jpg';

                if(copy($saved_name,$fz60)){
                    //更改图片大小
                    $this->resizeimage($fz60,60,60, $fz60);
                }
                if(copy($saved_name,$fz240)){
                    //更改图片大小
                    $this->resizeimage($fz240,240,240, $fz240);
                }
                if(copy($saved_name,$fz360)){
                    //更改图片大小
                    $this->resizeimage($fz360,360,360, $fz360);
                }
                if(copy($saved_name,$fz1280)){
                    //更改图片大小
                    $this->resizeimage($fz1280,1280,1280, $fz1280);
                }

            }

            foreach($small_images as $img){

                $saved_name =$path.DS.$store_id.'_'.basename($img);
                if(copy($img,$saved_name)){
                    $pic_array_image = $store_id.'_'.basename($img);

                    preg_match('/(?P<name>.*)\./', $pic_array_image, $m);

                    $fz60	= $path.DS.$m['name'].'_60.jpg';
                    $fz240	= $path.DS.$m['name'].'_240.jpg';
                    $fz360  = $path.DS.$m['name'].'_360.jpg';
                    $fz1280 = $path.DS.$m['name'].'_1280.jpg';

                    if(copy($saved_name,$fz60)){
                        //更改图片大小
                        $this->resizeimage($fz60,60,60, $fz60);
                    }
                    if(copy($saved_name,$fz240)){
                        //更改图片大小
                        $this->resizeimage($fz240,240,240, $fz240);
                    }
                    if(copy($saved_name,$fz360)){
                        //更改图片大小
                        $this->resizeimage($fz360,360,360, $fz360);
                    }
                    if(copy($saved_name,$fz1280)){
                        //更改图片大小
                        $this->resizeimage($fz1280,1280,1280, $fz1280);
                    }



                    $pic_array[] = $pic_array_image;
                }
            }

            if(!empty($pic_array) && is_array($pic_array)){
                $insert_array = array();
                foreach ($pic_array as $pic){
                    if($pic	== '')continue;
                    $param	= array();
                    $param['goods_image']	    = $pic;
                    $param['store_id']	        = $store_id;
                    $param['goods_commonid']	= $goods_id;
                    $insert_array[] = $param;
                }
                $model_store_goods->addGoodsImagesAll($insert_array);
            }
        }

        return $goods_id1;
    }
    /*
    * 图片缩略图
    */
    private function resizeimage($srcfile,$ratew='',$rateh='', $filename = "" ){
        $size=getimagesize($srcfile);
        switch($size[2]){
            case 1:
                $img=imagecreatefromgif($srcfile);
                break;
            case 2:
                $img=imagecreatefromjpeg($srcfile);//从源文件建立一个新图片
                break;
            case 3:
                $img=imagecreatefrompng($srcfile);
                break;
            default:
                exit;
        }
        //源图片的宽度和高度
        $srcw=imagesx($img);
        echo '源文件的宽度'.$srcw.'<br />';
        $srch=imagesy($img);
        echo '源文件的高度'.$srch.'<br />';
        //目的图片的宽度和高度
        $dstw=$ratew;
        $dsth=$rateh;
        //新建一个真彩色图像
        echo '新图片的宽度'.$dstw.'高度'.$dsth.'<br />';
        $im=imagecreatetruecolor($dstw,$dsth);
        $black=imagecolorallocate($im,255,255,255);
        imagefilledrectangle($im,0,0,$dstw,$dsth,$black);
        imagecopyresized($im,$img,0,0,0,0,$dstw,$dsth,$srcw,$srch);
        // 以 JPEG 格式将图像输出到浏览器或文件
        if( $filename ) {
            //图片保存输出
            imagejpeg($im, $filename ,100);
        }
        //释放图片
        imagedestroy($im);
        imagedestroy($img);
    }
}