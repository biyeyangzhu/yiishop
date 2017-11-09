<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>Apple(大中华区)-官方网站</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/user/index']],
        ['label'=>'商品管理','items'=>[
            ['label'=>'添加商品分类','url'=>['/goods-category/add']],
            ['label'=>'商品分类列表','url'=>['/goods-category/index']],
            ['label'=>'添加商品','url'=>['/goods/add']],
            ['label'=>'商品列表','url'=>['/goods/index']],
        ]],
        ['label'=>'用户管理','items'=>[
            ['label'=>'添加管理员','url'=>['/user/add']],
            ['label'=>'管理员列表','url'=>['/user/index']],
        ]],
        ['label'=>'RBAC','items'=>[
            ['label'=>'添加权限','url'=>['/auth/add-permission']],
            ['label'=>'权限列表','url'=>['/auth/index-permission']],
            ['label'=>'角色添加','url'=>['/auth/add-role']],
            ['label'=>'角色列表','url'=>['/auth/index-role']],
        ]]
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] =['label' => '登录', 'url' => ['/user/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/user/logout'], 'post')
            . Html::submitButton(
                '注销 (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
