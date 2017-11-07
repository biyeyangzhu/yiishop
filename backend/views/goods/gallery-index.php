<?php
$id = $_GET['id'];
$gallerys = \backend\models\GoodsGallery::find()->where(['goods_id'=>$id])->all();
?>
    <div id="uploader-demo">
        <!--用来存放item-->
        <div id="fileList" class="uploader-list"></div>
        <div id="filePicker">添加图片</div>
    </div>
<table class="table">
    <?php foreach ($gallerys as $gallery):?>
    <tr>
        <td><img src=<?=$gallery->path?>></td>
        <td><a class="btn btn-warning" id="<?=$gallery->id?>" name="del">删除</a></td>
    </tr>
    <?php endforeach;?>
</table>

    <script type="text/javascript">

    </script>
<?php
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js');
//使用brand中的ajax上传
$url = \yii\helpers\Url::to(['brand/upload']);
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
 
  //将值赋值保存到数据库
    $.post('gallery-add',{path:response.url,goods_id:$id},function(date) {
        //回显图片 追加到table里面
        $("<tr><td><img src="+response.url+"></td><td><a class='btn btn-warning' name='del' id="+date+">删除</td></tr>").appendTo("table")
    })
})
    $("table").on('click','[name=del]',function() {
        if(confirm("确认删除")){
            var tr = $(this).closest("tr");
            var id = $(this).attr('id');
            $.getJSON('gallery-delete',{id:id},function(data) {
              if(data){
                  tr.remove();
              }else{
                  alert("删除失败");
              }
            })
        }
    });
JS

);

