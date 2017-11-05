<?php
/**
 * @var $this yii\web\view
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,"name")->textInput();
echo $form->field($model,"intro")->textarea();
echo $form->field($model,"sort")->textInput();
echo $form->field($model,"status",['inline'=>1])->radioList([1=>'正常',0=>'隐藏']);
echo $form->field($model,"logo")->hiddenInput();

?>
    <div id="uploader-demo">
        <!--用来存放item-->
        <div id="fileList" class="uploader-list"></div>
        <div id="filePicker">选择图片</div>
    </div>
    <div><img id="img"></div>
<?php
echo "<input type='submit' class='btn btn-info'>";
\yii\bootstrap\ActiveForm::end();

$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[
    'depends'=>\yii\web\JqueryAsset::className(),//依赖jQuery；
]);
$url = \yii\helpers\Url::to(['upload']);
$this->registerJs(
    <<<JS
// 初始化Web Uploader
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径 暂时没用到
    // swf: BASE_URL + '/js/Uploader.swf',

    // 文件接收服务端。
    server: '{$url}',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',

    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/jpg,image/jpeg,image/png,image/bmp'
    }
});
    //文件上传成功回显图片
uploader.on('uploadSuccess',function(file,response) {
  $("#img").attr('src',response.url);
  //将值赋值给隐藏框用于保存到数据库
  $("#brand-logo").val(response.url)
})
JS

);
