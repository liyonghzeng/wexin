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
        jsApiList: ['chooseImage','uploadImage','updateAppMessageShareData'] // 必填，需要使用的JS接口列表
    });
    wx.ready(function () {   //需在用户可能点击分享按钮前就先调用
        wx.updateAppMessageShareData({
            title: '最新商品', // 分享标题
            desc: '{{$goods->goods_name}}', // 分享描述
            link: "http://1809liyongzheng.comcto.com/goods/"+"{{$goods->goods_id}}", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: 'http://image.baidu.com/search/detail?ct=503316480&z=undefined&tn=baiduimagedetail&ipn=d&word=%E4%BF%9D%E6%97%B6%E6%8D%B7&step_word=&ie=utf-8&in=&cl=2&lm=-1&st=undefined&hd=undefined&latest=undefined&copyright=undefined&cs=3660498484,1098792735&os=1399308224,4247077296&simid=4179537836,760861667&pn=2&rn=1&di=181475265840&ln=1901&fr=&fmq=1556082291656_R&fm=&ic=undefined&s=undefined&se=&sme=&tab=0&width=undefined&height=undefined&face=undefined&is=0,0&istype=0&ist=&jit=&bdtype=0&spn=0&pi=0&gsm=0&objurl=http%3A%2F%2Fphotocdn.sohu.com%2F20100305%2FImg270601722.jpg&rpstart=0&rpnum=0&adpicid=0&force=undefined', // 分享图标
            success: function (res) {
                console.log(res);
            }
        })
    });
</script>