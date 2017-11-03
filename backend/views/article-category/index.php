<?php
header("Content-Type:text/html;charset=utf-8");
echo \yii\bootstrap\Html::a('添加',['add'],['class'=>'btn btn-info']);
?>
<table class="table table-bordered">
    <tr>
        <th>id</th>
        <th>类名</th>
        <th>简介</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($category as $v):?>
    <tr>
        <td><?=$v->id?></td>
        <td><?=$v->name?></td>
        <td><?=$v->intro?></td>
        <td><?=$v->status==1?'正常':"隐藏"?></td>
        <td><?=\yii\bootstrap\Html::button('删除',['class'=>'btn btn-info','id'=>$v->id])?><?=\yii\bootstrap\Html::a('修改',['edit','id'=>$v->id],['class'=>'btn btn-info'])?></td>
    </tr>
    <?php endforeach;?>
</table>
<script type="text/javascript">
    <?php $this->beginblock('js');?>
    $("table").on('click','button',function () {
        if(confirm("确认删除?")){
        var id = $(this).attr('id');
        var tr = $(this).closest('tr');
        $.getJSON('delete',{id:id},function (data) {
            if(data){
                tr.remove();
            }
        })
        }
    })
    <?php $this->endblock();?>
</script>
<?php
$this->registerJs($this->blocks['js']);
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
]);
