<?php
/**
 * 队列
*
*
*
*
* by alphawu 村村兔  www.cuncuntu.com 开发
*/
defined('InShopNC') or exit('Access Invalid!');

class indexControl extends Wechat {

    const DEFAULT_WECHAT = 1;

    public function xOp(){
        var_dump(parent::EVENT_SUBSCRIBE);
        Log::record('xx',Log::ERR);
    }

    public function indexOp(){

        $id = self::DEFAULT_WECHAT;

        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }

        $model_wechat = Model('weixin_account_wechats');

        $wechat = $model_wechat->getWechatInfoByID($id);

        if(empty($wechat)){
            return ;
        }


        $options = array(
            'token'=>$wechat['token'],
            'encodingaeskey'=>$wechat['encodingaeskey'],
            'appid'=>$wechat['appid'],
            'appsecret'=>$wechat['secret']
        );

        $this->setup($options);
        $this->valid();

        $type = $this->getRev()->getRevType();
        switch($type) {

            case parent::MSGTYPE_TEXT:
                $this->text("hello, I'm CunCunTu")->reply();
                exit;
                break;
            case parent::MSGTYPE_EVENT: {
                $event_info = $this->getRevEvent();

                switch ($event_info['event']) {

                    case parent::EVENT_LOCATION:
                        //
                        $this->location();
                        exit;
                        break;
                    case parent::EVENT_SUBSCRIBE:
                        //
                        $this->subscribe();
                        exit;
                        break;
                    case parent::EVENT_UNSUBSCRIBE:
                        //
                        $this->unsubscribe();
                        exit;
                        break;
                }
            }
            default:
                $this->text("我目前理解不了你的信息:(")->reply();
        }
    }

    /**
     * 地理位置信息
     */
    private function location(){
        $openid = $this->getRevFrom();
        $geo_info = $this->getRevEventGeo();
        $model_fans_geo = Model('weixin_fans_geo');

        $data = array();

        $data['openid']             = $openid;
        $data['x']                  = $geo_info['x'];
        $data['y']                  = $geo_info['y'];
        $data['geo_precision']      = $geo_info['precision'];

        $model_fans_geo->addWeixinFansGeo($data);

    }

    /**
     * 关注
     */
    private function subscribe(){
        //在fans表写入基本信息

        $openid = $this->getRevFrom();
        $originid = $this->getRevTo();

        $model_wechat = Model('weixin_account_wechats');

        $wechat_info = $model_wechat->getWechatInfoByOriginID($originid);

        if(empty($wechat_info)){
            $acid = 0;
        }else{
            $acid = $wechat_info['acid'];
        }

        $model_fans = Model('weixin_fans');

        $fans_info = $model_fans->getFansInfoByOpenID($openid);

        if(empty($fans_info)) {

            $data = array();
            $data['acid'] = $acid;
            $data['openid'] = $openid;
            $data['followtime'] = $this->getRevCtime();

            $model_fans->addWeixinFans($data);
        }else{
            $update = array();
            $update['follow'] = 1;
            $update['followtime'] = $this->getRevCtime();
            $update['updatetime'] = $this->getRevCtime();

            $model_fans->editFansInfoByOpenID($update,$openid);
        }

    }

    /**
     * 取消关注
     */
    private function unsubscribe(){
        $openid = $this->getRevFrom();

        $model_fans = Model('weixin_fans');

        $data = array();

        $data['follow'] = 0;
        $data['unfollowtime'] = $this->getRevCtime();
        $data['updatetime'] = $this->getRevCtime();

        $model_fans->editFansInfoByOpenID($data,$openid);
    }

}