<?php
$this->registerCssFile('@web/DataTables/jquery.dataTables.css');
$this->registerJsFile('@web/DataTables/jquery.dataTables.js',[
        'depends'=>\yii\web\JqueryAsset::className(),//依赖jQuery；',
]);
?>
    <table class="table table-bordered" id="table">
        <thead>
        <tr>
            <th>名称</th>
            <th>描述</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($model as $value):?>
            <tr>
                <td><?=$value->name?></td>
                <td><?=$value->description?></td>
                <td><?= \yii\bootstrap\Html::a('修改',['edit-permission','name'=>$value->name],['class'=>'btn btn-warning glyphicon glyphicon-pencil'])?><?= \yii\bootstrap\Html::button(' 删除',['class'=>'btn btn-danger  glyphicon glyphicon-trash','name'=>'del','id'=>$value->name])?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>

<script type="text/javascript">
    $(document).ready(function () {
        $("table").on("click","[name=del]",function () {
            if(confirm("确认删除")){
                var id = $(this).attr('id');
                var tr = $(this).closest('tr');
                $.getJSON('delete-permission',{name:id},function (data) {
                    if(data){
                        tr.remove();
                    }else if(data=='删除失败'){
                        alert(data);
                    }
                })
            }
        });

        $('#table').DataTable();
    })

</script>
