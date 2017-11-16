<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>购物车页面</title>
    <link rel="stylesheet" href="/style/base.css" type="text/css">
    <link rel="stylesheet" href="/style/global.css" type="text/css">
    <link rel="stylesheet" href="/style/header.css" type="text/css">
    <link rel="stylesheet" href="/style/cart.css" type="text/css">
    <link rel="stylesheet" href="/style/footer.css" type="text/css">

    <script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/js/cart1.js"></script>

</head>
<body>
<!-- 顶部导航 start -->
<div class="topnav">
    <div class="topnav_bd w990 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <?php if (Yii::$app->user->isGuest): ?>
                    <li>您好，欢迎来到金叫唤！[<a href=<?= \yii\helpers\Url::to(['member/login']) ?>>登录</a>] [<a
                                href="register.html">免费注册</a>]
                    </li>
                <?php else: ?>
                    <li>您好,<?= Yii::$app->user->identity->username ?> 欢迎来到金叫唤！[<a
                                href=<?= \yii\helpers\Url::to(['member/logout']) ?>>注销</a>]
                    </li>
                <?php endif; ?>
                <li class="line">|</li>
                <li>我的订单</li>
                <li class="line">|</li>
                <li>客户服务</li>

            </ul>
        </div>
    </div>
</div>
<!-- 顶部导航 end -->

<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><img src="/images/logo.jpg" alt="京西商城" width="300px"></a></h2>
        <div class="flow fr">
            <ul>
                <li class="cur">1.我的购物车</li>
                <li>2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="mycart w990 mt10 bc">
    <h2><span>我的购物车</span></h2>
    <table id="table">
        <thead>
        <tr>
            <th class="col1">商品名称</th>
            <th class="col3">单价</th>
            <th class="col4">数量</th>
            <th class="col5">小计</th>
            <th class="col6">操作</th>
        </tr>
        </thead>
            <?=\frontend\models\Cart::GoodsCart($model)?>
        <tfoot>
        <tr>
            <td colspan="6">购物金额总计： <strong>￥ <span id="total"></span></strong></td>
        </tr>
        </tfoot>
    </table>
    <div class="cart_btn w990 bc mt10">
        <a href=<?=\yii\helpers\Url::to(['shop/index'])?> class="continue">继续购物</a>
        <a href="" class="checkout">结 算</a>
    </div>
</div>
<!-- 主体部分 end -->

<div style="clear:both;"></div>
<!-- 底部版权 start -->
<div class="footer w1210 bc mt15">
    <p class="links">
        <a href="">关于我们</a> |
        <a href="">联系我们</a> |
        <a href="">人才招聘</a> |
        <a href="">商家入驻</a> |
        <a href="">千寻网</a> |
        <a href="">奢侈品网</a> |
        <a href="">广告服务</a> |
        <a href="">移动终端</a> |
        <a href="">友情链接</a> |
        <a href="">销售联盟</a> |
        <a href="">京西论坛</a>
    </p>
    <p class="copyright">
        © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
    </p>
    <p class="auth">
        <a href=""><img src="/images/xin.png" alt="" /></a>
        <a href=""><img src="/images/kexin.jpg" alt="" /></a>
        <a href=""><img src="/images/police.jpg" alt="" /></a>
        <a href=""><img src="/images/beian.gif" alt="" /></a>
    </p>
</div>
<!-- 底部版权 end -->
<script type="text/javascript">
    $(function(){
        //计算总计
        var total = 0;
        $(".col5 span").each(function(){
            total += parseFloat($(this).text());
        });

        $("#total").text(total.toFixed(2));
        //删除购物车
        $("#table").on('click','[name=del]',function () {
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
        //点击减少
        $("#table").on('click',".reduce_num",function(){
            var amount = $(this).parent().find(".amount").val();
            var id = $(this).parent().find(".amount").attr('id');
            $.getJSON('reduce',{amount:amount,id:id})
        });
        //点击增加
        $(".add_num").click(function(){
            var amount = $(this).parent().find(".amount").val();
            var id = $(this).parent().find(".amount").attr('id');
            $.getJSON('reduce',{amount:amount,id:id})
        })
        //直接输入数量的处理
        $(".amount").blur(function(){
            var amount =$(this).val();
            var id = $(this).attr('id');
            $.getJSON('reduce',{amount:amount,id:id})
            })
    })

</script>
</body>
</html>

