<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Cart;
use App\Goods;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //商品添加的购物侧数据库
    public function index($id)
    {
        $user =Auth::id();
        if($user){//判断是否登录
            $cart_i= Cart::where(['goods_id'=>$id,'u_name'=>$user])->first();//查询商品是否存在
            if(!$cart_i){
                $data= Goods::where(['goods_id'=>$id])->first();//查询商品是否存在
                if($data){//判断是否存在
                    //添加条件
                    $where=[
                        'goods_id'    =>   $data->goods_id,
                        'goods_name'   =>   $data->goods_name,
                        'cart_number'  =>   1,
                        'goods_price'   =>   $data->goods_price,
                        'u_name'       =>   $user
                    ];
                    //                   dump($where);die;
                    //进行添加
                    $res= Cart::insert($where);

                    if($res){
                        header('refresh:3;url=/cart/list');
                        echo '添加成功';
                    }else{
                        header('refresh:3;url=/goods');
                        echo '添加购物车出现错误，请谅解 - - !!!!';
                    }
                }else{
                    header('refresh:3;url=/goods');
                    echo '请正常途径添加商品';
                }
            }else{
                $cc = $cart_i->cart_number+1;
                $res= Cart::where(['goods_id'=>$cart_i->goods_id,'u_name'=>$user])->update(['cart_number'=>$cc]);
                if($res){
                    header('refresh:3;url=/cart/list');
                    echo '添加成功';
                }else{
                    header('refresh:3;url=/goods');
                    echo '添加购物车出现错误，请谅解 - - !!!!';
                }
            }
        }else{
            header('refresh:3;url=/login');
            echo '未登录，请返回登录';
        }
    }
    //购物车展示列表
    public function list()
    {
        //查询所有购物车
        $data=Cart::get();
        $res = 0;
        //求购物总价
        foreach ($data as $k=>$v)
        {
            $res +=$v->goods_price*$v->cart_number;
        }

        return view('/goods',['data'=>$data,'res'=>$res]);
    }
}
