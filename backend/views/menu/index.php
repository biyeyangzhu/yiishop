<table class="table table-bordered">
    <tr>
        <th>菜单名称</th>
        <th>操作</th>
    </tr>
    <?php foreach ($menu as $v):?>
    <tr>
        <td><?= $v->name = str_repeat('——',($v->deep)*2).$v->name;?></td>
        <td><?=\yii\bootstrap\Html::button("删除",['class'=>'btn btn-warning','id'=>$v->id])?><?=\yii\bootstrap\Html::a("修改",['edit','id'=>$v->id],['class'=>'btn btn-warning'])?></td>
    </tr>
    <?php endforeach;?>
</table>

<script type="text/javascript">
    $("table").on('click','button',function () {
        if(confirm("确认删除?")){
            var id = $(this).attr('id');
            var tr = $(this).closest('tr');
            $.getJSON('delete',{id:id},function (data) {
                if(data){
                    tr.remove();
                }else{
                    alert("删除失败该菜单下有子菜单");
                }
            })
        }
    })
</script>
