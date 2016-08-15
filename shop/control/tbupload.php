<?php
/**
 * Created by PhpStorm.
 * User: wupeng
 * Date: 16/3/17
 * Time: 下午4:50
 * 淘宝数据导入组件之存储图片.
 */
defined('InShopNC') or exit('Access Invalid!');
class tbuploadControl extends BaseSellerControl
{
    public function indexOp()
    {
        if (isset($_FILES["Filedata"])
            || !is_uploaded_file($_FILES["Filedata"]["tmp_name"])
            || $_FILES["Filedata"]["error"] != 0) {

            $store_id = $_SESSION['store_id'];
            // 判断图片数量是否超限
            $model_album = Model('album');
            $album_limit = $this->store_grade['sg_album_limit'];
            if ($album_limit > 0) {
                $album_count = $model_album->getCount(array('store_id' => $store_id));
                if ($album_count >= $album_limit) {
                    $error = L('store_goods_album_climit');
                    if (strtoupper(CHARSET) == 'GBK') {
                        $error = Language::getUTF8($error);
                    }
                    exit(json_encode(array('error' => $error)));
                }
            }
            $class_info = $model_album->getOne(array('store_id' => $store_id, 'is_default' => 1), 'album_class');

            // 上传图片
            $upload = new UploadFile();
            $upload->set('default_dir', ATTACH_GOODS . DS . $store_id . DS . $upload->getSysSetPath());
            $upload->set('max_size', C('image_max_filesize'));

            $upload->set('thumb_width', GOODS_IMAGES_WIDTH);
            $upload->set('thumb_height', GOODS_IMAGES_HEIGHT);
            $upload->set('thumb_ext', GOODS_IMAGES_EXT);
            $upload->set('fprefix', $store_id);
            $upload->set('new_ext', 'jpg');

            if(C('upload_service_enabled') == 1) {
                $upload->set('isremote', true);
                $upload->set('ifremove', true);
            }
            $upload->set('allow_type', array('tbi'));
            $result = $upload->upfile("Filedata");
            if (!$result) {
                if (strtoupper(CHARSET) == 'GBK') {
                    $upload->error = Language::getUTF8($upload->error);
                }
                $output = array();
                $output['error'] = $upload->error;
                $output = json_encode($output);
                exit($output);
            }

            $img_path = $upload->getSysSetPath() . $upload->file_name;

            if(C('upload_service_enabled') == 1){
                $uri = C('upload_service_domain'). DS . DIR_UPLOAD . DS . ATTACH_GOODS . DS . $store_id . DS . $img_path .'@info';
                $json = file_get_contents($uri);

                $json_data = json_decode($json, true);

                $width = $json_data['width'];
                $height = $json_data['height'];
                //$size = $json_data['size'];

            }else {

                // 取得图像大小
                list($width, $height, $type, $attr) = getimagesize(BASE_UPLOAD_PATH . DS . ATTACH_GOODS . DS . $store_id . DS . $img_path);
            }

            // 存入相册
            $image = explode('.', $_FILES["Filedata"]["name"]);
            $insert_array = array();
            $insert_array['apic_name'] = $image['0'];
            $insert_array['apic_tag'] = '';
            $insert_array['aclass_id'] = $class_info['aclass_id'];
            $insert_array['apic_cover'] = $img_path;
            $insert_array['apic_size'] = intval($_FILES["Filedata"]['size']);
            $insert_array['apic_spec'] = $width . 'x' . $height;
            $insert_array['upload_time'] = TIMESTAMP;
            $insert_array['store_id'] = $store_id;

            if(C('upload_service_enabled') == 1){

                $insert_array['type'] = 1;//1 = aliyun
            }
            $model_album->addPic($insert_array);


            //更新相关数据表,goods,goods_common,goods_images

            $old_file_name = $image[0];//'******.tbi'的****
            $new_file_name = $img_path;

            $goods_model = Model('goods');
            $update_data = array();
            $update_data['goods_image'] = $new_file_name;
            $condition = array();
            $condition['goods_image'] = $old_file_name;
            $goods_model->editGoods($update_data,$condition);
            $goods_model->editGoodsCommon($update_data,$condition);
            $goods_model->editGoodsImages($update_data,$condition);

        }
    }
}