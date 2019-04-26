<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
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
        return view('/goods/xgoods',['goods'=>$goods,'js_config'=>$js_config]);
    }
    public function index()
    {
        $data=Goods::get();
        $goosranking = 'goods:zz:';
        $is= Redis::zRevRange($goosranking,0,100,true);
        if($is!=null){
            $ii= Array_keys($is);
            $choun =array_chunk($ii,3);
            $vv=array_shift($choun);
            $ly=[];
            foreach ($vv as $k=>$v) {
                $ly[]=Goods::where(['goods_id'=>$v])->first();
            }
        }else{
            $ly='';
        }
        return view('goods/goods',["data"=>$data,"ly"=>$ly]);
    }
    public function Browse($id)
    {
//       $res = Goods::where(['goods_id'=>$id])->first();
//        $data=$res->browse+1;
//        Goods::where(['goods_id'=>$id])->update(['browse'=>$data]);
//       return view('goods/browse',["res"=>$res, 'data'=>$data]);
//       $data=$res->browse+1;
//        Goods::where(['goods_id'=>$id])->update(['browse'=>$data]);
        $goods_browse='goods:browse'.$id;
        $res = Goods::where(['goods_id'=>$id])->first();
        $data =  Redis::incr($goods_browse);
        Goods::where(['goods_id'=>$id])->update(['browse'=>$data]);
//        dump($res);
        //浏览排名
        $goosranking = 'goods:zz:';      //定义key
        Redis::zadd($goosranking,$data,$id);
        $ii=Redis::zRangeByscore ($goosranking,0,100,['withscores'=>true] ); //倒叙
//       print_r($ii);
        $is= Redis::zRevRange($goosranking,0,100,true);//正序
//        print_r($is);
//        $iii= Redis::zRange($goosranking,0,100);
//       print_r($iii);
        return view('goods/browse',["res"=>$res, 'data'=>$data]);


    }
}
