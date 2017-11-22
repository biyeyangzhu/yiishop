<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/6
 * Time: 11:22
 */

namespace backend\controllers;


use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class GoodsController extends CommonController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        //搜索
        $name = '';
        $sn = '';
        $price1 = '';
        $price2 = '';
        $condition2 = [];
        //判断表单是否提交了数据
        if (isset($_GET['name'])) {
            $name = $_GET['name'];
            $sn = $_GET['sn'];
            $price1 = $_GET['price1'];
            $price2 = $_GET['price2'];
            //对最大价格这个条件进行处理
            if (($_GET['price2'])) {
                $condition2 = ['<=', 'shop_price', $_GET['price2']];
            }
        }
        //根据条件查询
        $query = Goods::find()->where(['>', 'status', '0'])->andWhere(['like', 'name', $name])->andWhere(['like', 'sn', "%$sn", false])->andWhere($condition2)->andWhere(['>', 'shop_price', $price1]);
        //使用分页工具
        $page = new Pagination();
        $page->totalCount = $query->count();
        //设置每页显示条数
        $page->pageSize = 2;
        //查询数据limit
        $goods = $query->limit($page->limit)->offset($page->offset)->all();
        //显示页面
        return $this->render("index", ['model' => $goods, 'page' => $page, 'condition' => [$name, $sn, $price1, $price2]]);
    }


    public function actionAdd()
    {
        $model = new Goods();
        $request = new Request();
        $intro = new GoodsIntro();
        $dayCount = new GoodsDayCount();
        $model->goods_category_id = 0;
        $brand = Brand::find()->where(['>=', 'status', '0'])->all();
        $brand = ArrayHelper::map($brand, 'id', 'name');
        if ($request->isPost) {
            $model->load($request->post());
            $intro->load($request->post());
            //验证数据
//             var_dump($model);die;
            if ($model->validate() && $intro->validate()) {

                //保存到数据库中
                $model->create_time = time();

                //处理时间
                $date_sn = date('Ymd', time());
                //用于保存到day_count表
                $date = date('Y-m-d', time());
                //查询day_count表中有没有今天日期
                $result = GoodsDayCount::findOne(['day' => $date]);
                if ($result) {
                    //今天日期有就在count字段加1;
                    $result->count = sprintf('%05s', $result->count + 1);
                    $result->save();
                    $model->sn = $date_sn . $result->count;
                } else {
                    $dayCount->day = $date;
                    $dayCount->count = '00001';
                    $dayCount->save();
                    $model->sn = $date_sn . $dayCount->count;
                }
                //状态值默认为1
                $model->status = 1;
                //goods表的保存
                $model->save();
                //详情介绍表的保存
                $intro->save();
                //跳转到首页
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect('index');
            } else {
                //打印错误信息
                var_dump($model->getErrors());
            }
        }
        return $this->render('add', ['model' => $model, 'brand' => $brand, 'intro' => $intro]);
    }

    /**
     * Ueditor
     * @return array
     */
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://admin.yii2shop.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}" ,//上传保存路径
                "imageRoot" => \Yii::getAlias("@webroot"),
            ],
        ]
    ];
}

    public function actionDelete($id)
    {
        //删除将status状态改为0表名为删除
        $result = Goods::updateAll(['status' => 0], ['id' => $id]);
        if ($result) {
            //修改成功 返回数据
            echo 1;
        } else {
            echo "删除失败";
        }
    }

    public function actionEdit($id)
    {
        $model = Goods::findOne(['id' => $id]);
        $request = new Request();
        $intro = GoodsIntro::findOne(['goods_id' => $id]);
        $brand = Brand::find()->where(['>=', 'status', '0'])->all();
        $brand = ArrayHelper::map($brand, 'id', 'name');
        if ($request->isPost) {
            $model->load($request->post());
            $intro->load($request->post());
            //验证数据
            if ($model->validate() && $intro->validate()) {

                //goods表的保存
                $model->save();
                //详情介绍表的保存
                $intro->save();
                //跳转到首页
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect('index');
            } else {
                //打印错误信息
                var_dump($model->getErrors());
            }
        }
        return $this->render('add', ['model' => $model, 'brand' => $brand, 'intro' => $intro]);
    }

    /**
     *相册的显示
     */
    public function actionGalleryIndex()
    {
        $model = GoodsGallery::find()->all();
        return $this->render('gallery-index', ['model' => $model]);
    }

    /**
     * 相册的删除
     */
    public function actionGalleryDelete($id)
    {
        $model = GoodsGallery::findOne(['id' => $id]);
        if ($model->delete()) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * 相册的添加
     */
    public function actionGalleryAdd()
    {
        $model = new GoodsGallery();
        $request = new Request();
        if ($request->isPost) {
            $path = $_POST['path'];
            $goods_id = $_POST['goods_id'];
            $model->path = $path;
            $model->goods_id = $goods_id;
            if ($model->save()) {
                echo $model->getOldAttribute('id');
            }
        }
    }

    /**
     * 商品详情
     */
    public function actionView($id){
        $model = GoodsIntro::findOne(['goods_id'=>$id]);
        return $this->render("view",['model'=>$model]);
    }


}