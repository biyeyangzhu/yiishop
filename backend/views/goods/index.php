<?php
?>
<div class="container">
    <form class="form-inline" method="get">
        <div class="form-group">
            <input type="text" class="form-control" name="name" placeholder="输入商品名称" value="<?=$condition[0]?>">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="sn" placeholder="请输入货号"value="<?=$condition[1]?>">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="price1" placeholder="请输入价格"value="<?=$condition[2]?>">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="price2" placeholder="请输入价格"value="<?=$condition[3]?>">
        </div>
        <button type="submit" class="btn btn-default" name="button"><span class="glyphicon glyphicon-search"></span>搜索</button>
    </form>
</div>
<div class="container">
    <table class="table table-border">
        <tr>
            <th>id</th>
            <th>商品名称</th>
            <th>货号</th>
            <th>商品价格</th>
            <th>库存</th>
            <th>logo</th>
            <th>操作</th>
        </tr>
        <?php foreach ($model as $v):?>
            <tr>
                <td><?=$v->id?></td>
                <td><?=$v->name?></td>
                <td><?=$v->sn?></td>
                <td><?=$v->shop_price?></td>
                <td><?=$v->stock?></td>
                <td><?=\yii\bootstrap\Html::img($v->logo,['width'=>"100px"])?></td>
                <td width="350px"><a class="btn btn-success" href="gallery-index?id=<?=$v->id?>"><span class="glyphicon glyphicon-picture"></span>相册</a>&nbsp;
                    <a class="btn btn-warning" href="edit?id=<?=$v->id?>" ><span class="glyphicon glyphicon-edit"></span>修改</a>&nbsp;
                    <a class="btn btn-danger" id="<?=$v->id?>" name="del" ><span class="glyphicon glyphicon-trash"></span>删除</a>&nbsp;
                    <a class="btn btn-info" ><span class="glyphicon glyphicon-log-in"></span>预览</a>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
</div>
    <script type="text/javascript">
        <?php $this->beginblock("js"); ?>
        $("table").on("click","[name=del]",function () {
            if(confirm("确认删除")){
                var id = $(this).attr('id');
                var tr = $(this).closest('tr');
                $.getJSON('delete',{id:id},function (data) {
                    if(data){
                        tr.remove();
                    }else if(data=='删除失败'){
                        alert(data);
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

