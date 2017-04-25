<?php
namespace magazine\home\controller;
use magazine\home\controller\Base;
use magazine\model\Magazine;
use magazine\model\MagazineArticle;
use magazine\model\MagazineArticleStats;
/**
 * @desc   期刊前台页面
 * @author ming123jew
 *
 */
class Index extends Base{

    public function __construct($request){
        parent::__construct($request);
        $this->shareData = $this->_getShareJSTicket();
    }


    /**
     * @desc   首页
     * @return multitype:string multitype:
     */
    public function Index(){
        $tid = $this->request->param('tid');
        $tid = intval($tid);
        if($tid==0){
            $tid = 3;
        }

        $model_Magazine = new Magazine();
        $where_Magazine = [
            'category_id'	=>	$tid
        ];
        $array_Magazine = $model_Magazine->cache(true,10)->where($where_Magazine)->where('status=1')->limit(0,1)->select()->toArray();

        $this->shareData['share_thumb'] = 'http://pznst.wmting.com/magazinev1.0/images/share.jpg';
        $this->shareData['share_url'] = 'http://pznst.wmting.com/magazine/?tid='.$tid;
        if( $array_Magazine ){
            $this->shareData['share_desc'] = $array_Magazine[0]['desc'] ?  $array_Magazine[0]['desc'] : '平证牛事通' ;
            $this->shareData['share_title'] = $array_Magazine[0]['title'] ? $array_Magazine[0]['title'] : '平证牛事通' ;
        }
        //print_r($this->shareData);
        $template_data = array_merge(
            ['signPackage'=>$this->shareData],
            ['tid'=>$tid],
            ['list'=>$array_Magazine]
        );
        return [MAGAZINE_HOME_VIEW_PATH.'magazinev1.0/index.html',$template_data];
    }

    public function Index2(){
        $tid = $this->request->param('tid');
        $tid = intval($tid);
        if($tid==0){
            $tid = 3;
        }

        $model_Magazine = new Magazine();
        $where_Magazine = [
            'category_id'	=>	$tid
        ];
        $array_Magazine = $model_Magazine->cache(true,10)->where($where_Magazine)->where('status=1')->limit(0,1)->select()->toArray();

        $this->shareData['share_thumb'] = 'http://pznst.wmting.com/magazinev1.0/images/share.jpg';
        $this->shareData['share_url'] = 'http://pznst.wmting.com/magazine/?tid='.$tid;
        $this->shareData['share_desc'] = $array_Magazine[0]['desc'];
        $this->shareData['share_title'] = $array_Magazine[0]['title'];
        //print_r($this->shareData);
        $template_data = array_merge(
            ['signPackage'=>$this->shareData],
            ['tid'=>$tid],
            ['list'=>$array_Magazine]
        );
        return [MAGAZINE_HOME_VIEW_PATH.'magazinev1.0/index2.html',$template_data];
    }

    public function Index3(){
        $template_data = array_merge(['signPackage'=>$this->shareData]);
        return [MAGAZINE_HOME_VIEW_PATH.'magazinev1.0/index3.html',$template_data];
    }

    /**
     * @desc   根据category_id 获取期刊列表
     * @return multitype:number string unknown |multitype:string multitype:
     */
    public function Lists($id=0){
        $tid = $this->request->param('tid');//分类id
        $tid = intval($tid);
        if($id){
            $tid = $id;
        }
        $p = $this->request->param('p');//分页参数
        $p = intval($p);
        $json =  $this->request->param('j');//返回 json 标识
        if($p==0){
            $start = 0;
            $end =1;
        }else{
            $end = 2;
            $start = ($p-1)*$end + 1;
        }
        $model_Magazine = new Magazine();
        $where_Magazine = [
            'category_id'	=>	$tid
        ];

        //如是获取json数据则直接返回json格式
        if($json){
            $json_Magazine = $model_Magazine->cache(true,10)->where($where_Magazine)->where('status=1')->limit($start,$end)->select()->toJson();
            return ['code'=>1,'msg'=>'json data.','page'=>$p,'list'=>$json_Magazine];exit(0);
        }else{
            $array_Magazine = $model_Magazine->cache(true,10)->where($where_Magazine)->limit($start,$end)->select()->toArray();
        }

        $template_data = array_merge(
            $this->data,
            ['list'=>$array_Magazine],
            ['sysdata'=>$this->data],
            ['page'=>$p]
        );
        return [MAGAZINE_HOME_VIEW_PATH.'pinganniutong/index/lists.php',$template_data];exit(0);
    }

    /**
     * @desc   期刊主页面 (包含四个目录)
     * @return multitype:number string unknown |multitype:string multitype:
     */
    public function ListSub(){
        $magazine_id = $this->request->param('id');// 期刊id
        $magazine_id = intval($magazine_id);
        $catalogue_id = $this->request->param('cid');//目录id
        $catalogue_id = intval($catalogue_id);
        $get_article =  $this->request->param('b');// 判断是否获取内容
        $get_article = intval($get_article);

        //参数判断
        if($magazine_id<=0 || !in_array($catalogue_id, [1,2,3,4])){
            return ['code'=>0,'msg'=>'error.'];exit(0);
        }

        $json =  $this->request->param('j');
        $p = $this->request->param('p');
        $p = intval($p);

        if($p==0){
            $start = 0;
            $end =3;
        }else{
            $end = 3;
            $start =  $start = ($p-1)*$end;
        }
        $model_MagazineArticle  = new MagazineArticle();
        $where_MagazineArticle = [
            'magazine_id'	=>	$magazine_id,
            'catalogue_id'  =>	$catalogue_id

        ];
        //如是获取json数据则直接返回json格式
        if($json){

            //精选目录
            if($catalogue_id==2){
                $with_MagazineArticle = ['MagazineArticleStats'];
            }else{
                $with_MagazineArticle = '';
            }

            $json_Magazine = $model_MagazineArticle->cache(true,10)->with($with_MagazineArticle)->where($where_MagazineArticle)
                ->where('status=1')
                ->limit($start,$end)->select()->toArray();
            foreach ($json_Magazine as $key=>$value){
                $json_Magazine[$key]['body'] = htmlspecialchars_decode($value['body']);
            }

            if($catalogue_id!=2 && array_key_exists(0, $json_Magazine)){
                //更新文章点击   由于除开精选目录是列表，其他都不是，因此需要更新第一篇文章点击
                $this-> _ArticleClick($json_Magazine[0]['id']);
            }

            return ['code'=>1,'msg'=>'json data.','list'=> json_encode($json_Magazine)];exit(0);
        }else{

            $array_Magazine = $model_MagazineArticle->cache(true,10)->where($where_MagazineArticle)->limit($start,$end)->select()->toArray();
        }
        //print_r($array_Magazine);
        $template_data = array_merge(
            $this->data,
            ['list'=>$array_Magazine],
            ['sysdata'=>$this->data]
        );

        return [MAGAZINE_HOME_VIEW_PATH.'pinganniutong/index/listsub_'.$catalogue_id.'.php',$template_data];exit(0);
    }

    /**
     * @desc 读取期刊文章内容
     * @return
     */
    public function Article(){

        $magazine_article_id = $this->request->param('id');// 期刊id
        $magazine_article_id = intval($magazine_article_id);

        $model_MagazineArticle = new MagazineArticle();
        $where_MagazineArticle = [
            'id'=>$magazine_article_id

        ];
        $with_MagazineArticle = ['MagazineArticleStats'];
        $array_MagazineArticle = $model_MagazineArticle->cache(true,10)
            ->with($with_MagazineArticle)
            ->where($where_MagazineArticle)->where('status=1')
            ->find()->toArray();
        $array_MagazineArticle['body'] = htmlspecialchars_decode($array_MagazineArticle['body']);
        //print_r($array_MagazineArticle);

        $json =  $this->request->param('j');

        if($json){
            //更新文章点击
            $this-> _ArticleClick($magazine_article_id);

            return ['code'=>1,'msg'=>'json data.','list'=> json_encode($array_MagazineArticle)];exit(0);
        }


        $template_data = array_merge(
            $this->data,
            ['list'=>$array_MagazineArticle],
            ['sysdata'=>$this->data]
        );

        return [MAGAZINE_HOME_VIEW_PATH.'pinganniutong/index/article.php',$template_data];exit(0);
    }

    //内部更新文章点击
    private function _ArticleClick($id=0){
        $magazine_article_id = $this->request->param('id');// 期刊id
        $magazine_article_id = intval($magazine_article_id);
        if($id){
            $magazine_article_id = $id;
        }
        $model_MagazineArticleStats = new MagazineArticleStats();
        $where_MagazineArticleStats = [
            'magazine_article_id'=>$magazine_article_id
        ];
        $model_MagazineArticleStats->where($where_MagazineArticleStats)->setInc('click_num', 1);
    }
    //网页更新   文章点击
    public function ArticleClick($id=0){
        $magazine_article_id = $this->request->param('id');// 期刊id
        $magazine_article_id = intval($magazine_article_id);
        if($id){
            $magazine_article_id = $id;
        }
        $model_MagazineArticleStats = new MagazineArticleStats();
        $where_MagazineArticleStats = [
            'magazine_article_id'=>$magazine_article_id
        ];
        $model_MagazineArticleStats->where($where_MagazineArticleStats)->setInc('click_num', 1);exit(0);
    }

    private function _getShareJSTicket(){
        $url = 'http://pr2.wmting.com/__WX_TECH3_API__/api_huizhikeji.php';
        $post_data= array('url'=>urldecode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"));
        $js_json = curlPost($url,$post_data);
        $js_array = json_decode($js_json,true);
        return $js_array;

    }

    public function testshare(){
        $a = $this->_WxShareCode();
        print_r($a);exit;
    }

    public function testcurl(){
        $url = 'http://pr2.wmting.com/__WX_TECH3_API__/api_huizhikeji.php';
        $post_data= array('url'=>urldecode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"));
        $js_json = curlPost($url,$post_data);
        $js_array = json_decode($js_json,true);
        //print_r($js_array);
        return $js_array;
        exit(0);
    }

}



