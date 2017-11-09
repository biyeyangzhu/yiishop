<?php
?>
<table class="table table-bordered">
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $value):?>
        <tr>
            <td><?=$value->name?></td>
            <td><?=$value->description?></td>
            <td><?= \yii\bootstrap\Html::a('修改',['edit-role','name'=>$value->name],['class'=>'btn btn-warning glyphicon glyphicon-pencil'])?><?= \yii\bootstrap\Html::button(' 删除',['class'=>'btn btn-danger  glyphicon glyphicon-trash','name'=>'del','id'=>$value->name])?></td>
        </tr>
    <?php endforeach;?>
</table>

<script type="text/javascript">
    $("table").on("click","[name=del]",function () {
        if(confirm("确认删除")){
            var name = $(this).attr('id');
            var tr = $(this).closest('tr');
            $.getJSON('delete-role',{name:name},function (data) {
                if(data){
                    tr.remove();
                }else if(data=='删除失败'){
                    alert(data);
                }
            })
        }
    })
</script>
