<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>填写核对订单信息</title>
    <link rel="stylesheet" href="/style/base.css" type="text/css">
    <link rel="stylesheet" href="/style/global.css" type="text/css">
    <link rel="stylesheet" href="/style/header.css" type="text/css">
    <link rel="stylesheet" href="/style/fillin.css" type="text/css">
    <link rel="stylesheet" href="/style/footer.css" type="text/css">

    <script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/js/cart2.js"></script>

</head>
<body>
<!-- 顶部导航 start -->
<div class="topnav">
    <div class="topnav_bd w990 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li>您好，欢迎来到金叫唤！[<a href="/login.html">登录</a>] [<a href="/register.html">免费注册</a>] </li>
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
        <h2 class="fl"><a href="/index.html"><img src="/images/logo.jpg" width="300px" alt="金叫唤商城"></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php foreach ($address as $add):?>
                <p>
                    <input type="radio" name="address_id" <?=$add['default']?'checked':''?> value=<?=$add['id']?> /><?=$add['name'].'&nbsp;'.$add['tel'].'&nbsp;'.$add['province'].'&nbsp;'.$add['city'].'&nbsp;'.$add['area'].'&nbsp;'.$add['address']?></p>
                <?php endforeach;?>
            </div>


        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody id="body">
                    <tr class="cur">
                        <td>
                            <input type="radio" name="delivery" checked="checked" value="1" data="10"/><sapn>普通快递送货上门</sapn>

                        </td>
                        <td>￥10.00</td>
                        <td>每张订单不满499.00元,运费15.00元, 订单4...</td>
                    </tr>
                    <tr>

                        <td><input type="radio" name="delivery" value="2" data="40"/><span>特快专递</span></td>
                        <td>￥40.00</td>
                        <td>每张订单不满499.00元,运费40.00元, 订单4...</td>
                    </tr>
                    <tr>

                        <td><input type="radio" name="delivery" value="3" data="40"/><span>加急快递送货上门</span></td>
                        <td>￥40.00</td>
                        <td>每张订单不满499.00元,运费40.00元, 订单4...</td>
                    </tr>
                    <tr>

                        <td><input type="radio" name="delivery" value="4" data="10"/><span>平邮</span></td>
                        <td>￥10.00</td>
                        <td>每张订单不满499.00元,运费15.00元, 订单4...</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <tr class="cur">
                        <td class="col1"><input type="radio" name="pay" checked="checked" value="1"/><span>货到付款</span></td>
                        <td class="col2">送货上门后再收款，支持现金、POS机刷卡、支票支付</td>
                    </tr>
                    <tr>
                        <td class="col1"><input type="radio" name="pay" value="2"/><span>在线支付</span></td>
                        <td class="col2">即时到帐，支持绝大数银行借记卡及部分银行信用卡</td>
                    </tr>
                    <tr>
                        <td class="col1"><input type="radio" name="pay" value="3"/><span>上门自提</span></td>
                        <td class="col2">自提时付款，支持现金、POS刷卡、支票支付</td>
                    </tr>
                    <tr>
                        <td class="col1"><input type="radio" name="pay" value="4"/><span>邮局汇款</span></td>
                        <td class="col2">通过快钱平台收款 汇款后1-3个工作日到账</td>
                    </tr>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
        <div class="receipt none">
            <h3>发票信息 </h3>


            <div class="receipt_select ">
                <form action="">
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>

            </div>
        </div>
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php $count =0;$number=0;?>
                <?php foreach ($model as $cart):?>
                <tr>
                    <td class="col1"><a href="/"><img src=<?=$cart->logo?> alt="" /></a>  <strong><a href="/"><?=$cart->name?></a></strong></td>
                    <td class="col3">￥<?=$cart->shop_price?>.00</td>
                    <td class="col4"> <?=$carts[$cart->id]?></td>
                    <td class="col5"><span>￥<?=$cart->shop_price*$carts[$cart->id]?></span></td>
                </tr>
                    <?php $count+=$cart->shop_price*$carts[$cart->id] ;$number +=$carts[$cart->id] ?>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span><?=$number?> 件商品，总商品金额：</span>
                                <em>￥<?=$count?>.00</em>
                            </li>
                            <li>
                                <span>返现：</span>
                                <em>-￥0.00</em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em id="fare">￥10.00</em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em name="total"><?=$count+10?>.00</em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">
        <a id="submit" href="javascript:;"><span>提交订单</span></a>
        <p>应付总额：<strong name="total">￥<?=$count+10?>.00元</strong></p>

    </div>
</div>
<!-- 主体部分 end -->

<div style="clear:both;"></div>
<!-- 底部版权 start -->
<div class="footer w1210 bc mt15">
    <p class="links">
        <a href="/">关于我们</a> |
        <a href="/">联系我们</a> |
        <a href="/">人才招聘</a> |
        <a href="/">商家入驻</a> |
        <a href="/">千寻网</a> |
        <a href="/">奢侈品网</a> |
        <a href="/">广告服务</a> |
        <a href="/">移动终端</a> |
        <a href="/">友情链接</a> |
        <a href="/">销售联盟</a> |
        <a href="/">金叫唤论坛</a>
    </p>
    <p class="copyright">
        © 2005-2013 金叫唤网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
    </p>
    <p class="auth">
        <a href="/"><img src="/images/xin.png" alt="" /></a>
        <a href="/"><img src="/images/kexin.jpg" alt="" /></a>
        <a href="/"><img src="/images/police.jpg" alt="" /></a>
        <a href="/"><img src="/images/beian.gif" alt="" /></a>
    </p>
</div>
<?php $url = \yii\helpers\Url::to(['cart/cart'])?>
<script type="text/javascript">
$('[name=delivery]').click(function () {
    if($(this).val()==1||$(this).val()==4){
        $("#fare").html(10);
        $("[name=total]").html(10+<?=$count?>)
    }else {
        $("#fare").html(40);
        $("[name=total]").html(40+<?=$count?>)
    }

})
    $("#submit").click(function () {
        if($('[name=delivery]').val()==1||$('[name=delivery]').val()==4){
            var total=10+<?=$count?>
        }else {
            var total=40+<?=$count?>
        }
        var delivery = $('[name=delivery]').val();
       var pay = $('[name=pay]').val();
       var delivery_name  =$('[name=delivery]').parent().find('span').html();
       var delivery_price = $('[name=delivery]').attr('data');
       var payment_name =  $('[name=pay]').parent().find('span').html();
       var address = $('[name=address_id]:checked').val();
        $.post('add',{total:total,payment_id:pay,address_id:address,delivery_id:delivery,delivery_name:delivery_name,payment_name:payment_name,delivery_price:delivery_price},function (data) {
            if (data == 1){
                location.href="<?=\yii\helpers\Url::to('success')?>"
            }else{
                alert(data);
                location.href="<?=$url?>"
            }

        })
    })
</script>
<!-- 底部版权 end -->
</body>
</html>
