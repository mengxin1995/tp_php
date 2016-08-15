<?php
/**
 * 同步农业局网站每日一助
 *
 *
 * 诸暨村淘 zjcuntao
 *
 */
defined('InShopNC') or exit('Access Invalid!');

class meiriyizhuControl extends BaseCronControl
{

    public function indexOp(){
        $url='http://www.zjnyj.gov.cn/archives/category/mryz';
        header("Content-type: text/html; charset=utf-8");
        import('libraries.simple_html_dom');

        $html = file_get_html($url);

        $eles = $html->find('div.main_list_C li');

        $eles = array_reverse($eles);

        foreach($eles as $elements) {

            echo $elements->find('a',0)->href .$elements->find('em',0)->plaintext. '<br/>';
            echo $elements->find('a',0)->title . '<br/>';

            $href = $elements->find('a',0)->href;
            $title = $elements->find('a',0)->title;
            $em = $elements->find('em',0)->plaintext;

            $regex_id = '/archives\/(?P<id>\d+$)/';
            $regex_date = '/\[(?P<date>.*)\]/';

            $matches_id = array();
            if(preg_match($regex_id,$href,$matches_id)){
                $id = $matches_id['id'];

                echo $id . '<br/>';
            }
            $matches_date = array();
            if(preg_match($regex_date,$em,$matches_date)){
                $date = $matches_date['date'];

                echo $date . '<br/>';
            }


            $model_article = Model('cms_article');

            $condition = array();
            $condition['article_origin_address'] = $href;
            if($model_article->isExist($condition)){
                continue;
            }


            //插入文章
            $param = array();
            $param['article_title'] = '每日一助'.$em;
            $param['article_title_short'] = mb_substr($_POST['article_title'], 0, 12, CHARSET);

            $param['article_class_id'] = 1;//每日一助的类别id
            $param['article_origin'] = '诸暨市农业局';
            $param['article_origin_address'] = $href;
            $param['article_author'] = '诸暨农业';///////////////////////////////////
            $param['article_abstract'] = $title;
            $param['article_content'] = $title;
            $param['article_link'] = '';
            $param['article_keyword'] = '每日一助';

            $param['article_publisher_name'] = '诸暨农业';///////////////////////////////
            $param['article_publisher_id'] = 1989;////////////////////////////////////////
            $param['article_type'] = 2;
            $param['article_attachment_path'] = 1989;
            $param['article_sort'] = 255;

            $param['article_commend_flag'] = 0;
            $param['article_tag'] = '每日一助';



            //发布时间
            $param['article_publish_time'] = strtotime($date);

            $param['article_modify_time'] = time();

            $param['article_state'] = 3;//已发布

            $model_tag_relation = Model('cms_tag_relation');




            $article_id = $model_article->save($param);


            //插入文章标签

            $tag_list = explode(',', '每日一助');
            $param = array();
            $param['relation_type'] = 1;
            $param['relation_object_id'] = $article_id;
            $params = array();
            foreach ($tag_list as $value) {
                $param['relation_tag_id'] = $value;
                $params[] = $param;
            }
            $model_tag_relation->saveAll($params);
        }

        $html->clear();
    }
}