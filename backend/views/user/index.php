<?php
echo \yii\bootstrap\Html::a("修改密码",['update'],['class'=>'btn btn-warning'])
?>

    <table class="table table-border">
        <tr>
            <th>id</th>
            <th>用户名</th>
            <th>邮箱</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <?php foreach ($model as $v):?>
            <tr>
                <td><?=$v->id?></td>
                <td><?=$v->username?></td>
                <td><?=$v->email?></td>
                <td><?=$v->status==1?"正常":"禁用"?></td>
                <td><?=\yii\bootstrap\Html::button("删除",['class'=>'btn btn-warning','id'=>$v->id])?><?=\yii\bootstrap\Html::a("修改",['edit','id'=>$v->id],['class'=>'btn btn-warning'])?></td>
            </tr>
        <?php endforeach;?>
    </table>

    <script type="text/javascript">
        <?php $this->beginblock("js"); ?>
        $("table").on("click","button",function () {
            if(confirm("确认删除")){
                var id = $(this).attr('id');
                var tr = $(this).closest('tr');
                $.getJSON('delete',{id:id},function (data) {
                    if(data){
                        tr.remove();
                    }
                })
            }
        })
        <?php $this->endblock(); ?>
    </script>
<?php $this->registerJs($this->blocks['js']) ?>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
]);

