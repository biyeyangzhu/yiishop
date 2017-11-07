<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>商品种类</th>
        <th>操作</th>
    </tr>
    <?php foreach ($category as $v):?>
    <tr>
        <td><?=$v->id?></td>
        <td><?= $v->name = str_repeat('&emsp;',($v->depth)*2).$v->name;?></td>
        <td><?=\yii\bootstrap\Html::button("删除",['class'=>'btn btn-warning','id'=>$v->id])?><?=\yii\bootstrap\Html::a("修改",['edit','id'=>$v->id],['class'=>'btn btn-warning'])?></td>
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
                }else {
                    alert("删除失败,该节点下还有子节点")
                }
            })
        }
    })
    <?php $this->endblock();?>
</script>
<?php
$this->registerJs($this->blocks['js']);
