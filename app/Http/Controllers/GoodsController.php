<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Goods;

class GoodsController extends Controller
{
    //
    public function goods($id)
    {
        $goods=Goods::where(['goods_id'=>$id])->first();
        $timestamp = time();
        $nonceStr = Str::random(10);
        $jsapi_ticket = getJsapiTicket();
        $current_url = $_SERVER['REQUEST_SCHEME'].'://' . $_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI'];
        $string1  =   "jsapi_ticket=$jsapi_ticket&noncestr=$nonceStr&timestamp=$timestamp&url=$current_url";

        $sign = sha1($string1);
        $js_config = [
            'appId' => env('WX_APPID'),        //公众号APPID
            'timestamp' => $timestamp,
            'nonceStr' => $nonceStr,   //随机字符串
            'signature' => $sign,                      //签名
        ];
        return view('/goods/goods',['goods'=>$goods,'js_config'=>$js_config]);
    }
}
