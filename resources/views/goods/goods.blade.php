<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>商品详情</title>
    <script src="http://res2.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
    <script src="/js/jquery.js"></script>
</head>
<body>
    商品名称 ----  商品价格 ---- 商品数量
    {{$goods->goods_name}}----{{$goods->goods_price}}----{{$goods->goods_number}}
</body>
</html>
<script>

    wx.config({
        //debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: "{{$js_config['appId']}}", // 必填，公众号的唯一标识
        timestamp: "{{$js_config['timestamp']}}", // 必填，生成签名的时间戳
        nonceStr: "{{$js_config['nonceStr']}}", // 必填，生成签名的随机串
        signature: "{{$js_config['signature']}}",// 必填，签名
        jsApiList: ['chooseImage','uploadImage'] // 必填，需要使用的JS接口列表
    });

</script>