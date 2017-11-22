<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/14
 * Time: 14:34
 */

namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use frontend\models\SphinxClient;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;

class ShopController extends Controller
{
    /**
     * 商城首页
     * @return string
     */
    public function actionIndex()
    {
        $request = \Yii::$app->request;
//        var_dump($request->get());die;
        $cl = new SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);//设置sphinx的searchd服务信息

        $cl->SetConnectTimeout ( 10 );//超时
        $cl->SetArrayResult ( true );//结果以数组形式返回
// $cl->SetMatchMode ( SPH_MATCH_ANY);
        $cl->SetMatchMode ( SPH_MATCH_EXTENDED2);//设置匹配模式
        $cl->SetLimits(0, 1000);//设置分页
        $info = $request->get('search');//查询关键字
        //进行查询   Query(查询关键字,使用的索引)
        $res = $cl->Query($info, 'goods');//shopstore_search
//print_r($cl);
        //print_r($res);
        if(isset($res['matches'])){
            //查询到结果
            $ids = ArrayHelper::map($res['matches'],'id','id');
            //var_dump($ids);
            $query = Goods::find()->where(['in','id',$ids]);
            //分页工具
            $pager = new Pagination();
            $pager->totalCount = $query->count();
            $pager->pageSize = 4;
            $models = $query->limit($pager->limit)->offset($pager->offset)->all();
            return $this->render('list', ['goods' => $models, 'page' => $pager]);

        }else{
            //没有查询到结果
            return $this->render('index');
        }
        return $this->render('index');
    }

    /**
     * sphinx 的全文检索测试
     * 中文分词搜索测试
     */
    public function actionSearch(){
        $cl = new SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);//设置sphinx的searchd服务信息

        $cl->SetConnectTimeout ( 10 );//超时
        $cl->SetArrayResult ( true );//结果以数组形式返回
// $cl->SetMatchMode ( SPH_MATCH_ANY);
        $cl->SetMatchMode ( SPH_MATCH_EXTENDED2);//设置匹配模式
        $cl->SetLimits(0, 1000);//设置分页
        $info = "";//查询关键字
        //进行查询   Query(查询关键字,使用的索引)
        $res = $cl->Query($info, 'goods');//shopstore_search
//print_r($cl);
        //print_r($res);
        if(isset($res['matches'])){
            //查询到结果
            $ids = ArrayHelper::map($res['matches'],'id','id');
            //var_dump($ids);

        }else{
            //没有查询到结果
        }


    }

    /**
     * 商品列表
     * @param $id
     * @return string
     */
    public function actionGoods($id)
    {
        //查询出传过来的商品分类 一级分类 二级分类 三级分类
        $category = GoodsCategory::findOne(['id' => $id]);
        //进行判断是一级分类还是几级分类
        if ($category->depth == 2) {
            //查询商品 使用查询器 后续用于分页处理
            //三级分类
            $query = Goods::find()->where(['goods_category_id' => $id]);
        } else {
            //一级分类二级分类
            //这里需要父分类下所有子分类id用于查询商品
            $ids = $category->children()->andWhere(['depth' => 2])->column();
            $query = Goods::find()->andWhere(['in', 'goods_category_id', $ids]);
        }


        //分页工具
        $pager = new Pagination();
        $pager->totalCount = $query->count();
        $pager->pageSize = 4;

        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('list', ['goods' => $models, 'page' => $pager]);
    }

    /**
     * 商品分类展示以及存入redis进行优化
     *      * @return bool|string
     */
    public static function Gategory_index()
    {
        //使用redis进行性能优化(后台改变商品分类[添加修改删除],需要清除redis缓存)
        //缓存使用 先读缓存,有就直接用,没有就重写生成
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $redis->delete('goods-category');
        $html = $redis->get('goods-category');
        if ($html == false) {
            $html = '<div class="cat_bd">';
            //遍历一级分类
            $categories = GoodsCategory::find()->where(['depth' => 0])->all();
            foreach ($categories as $k1 => $category) {
                //第一个一级分类需要加class = item1
                $html .= '<div class="cat ' . ($k1 == 0 ? 'item1' : '') . '">
                    <h3><a href=' . Url::to(['shop/goods', 'id' => $category->id]) . '>' . $category->name . '</a><b></b></h3>
                    <div class="cat_detail">';
                //遍历该一级分类的二级分类
                $categories2 = $category->children(1)->all();
                foreach ($categories2 as $k2 => $category2) {
                    $html .= '<dl ' . ($k2 == 0 ? 'class="dl_1st"' : '') . '>
                            <dt><a href=' . Url::to(['shop/goods', 'id' => $category2->id]) . '>' . $category2->name . '</a></dt>
                            <dd>';
                    //遍历该二级分类的三级分类
                    $categories3 = $category2->children(1)->all();
                    foreach ($categories3 as $category3) {
                        $html .= '<a href=' . Url::to(['shop/goods', 'id' => $category3->id]) . '>' . $category3->name . '</a>';
                    }
                    $html .= '</dd>
                        </dl>';
                }

                $html .= '</div>
                </div>';
            }
            $html .= '</div>';
            //保存到redis
            $redis->set('goods-category', $html, 24 * 3600);
        }

        return $html;
    }

    /**
     * 商品详情
     */
    public function actionContent($id){
        //商品基本信息
        $goods = Goods::findOne(['id'=>$id]);
        //相册
        $gallery=GoodsGallery::find()->where(['goods_id'=>$id])->all();
        //详情
        $content = GoodsIntro::findOne(['goods_id'=>$id]);
        return $this->render('content',['goods'=>$goods,'gallery'=>$gallery,'content'=>$content]);
    }


}