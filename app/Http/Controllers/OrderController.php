<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Goods;
use App\Cart;
use App\Order;
use App\Oderd;
class OrderController extends Controller
{
    //
    public function index($id)
    {

        $user = Auth::id();
        if($user){
            if($id!='i'){
                $goods=Goods::where(['goods_id'=>$id])->first();
                //查看是否存在
                $Cart=Cart::where(['goods_id'=>$id,'u_name'=>$user])->first();
                if($Cart){
                    $where= [
                        'order_id' => Order::dj(),
                        'goods_id' => $goods->goods_id,
                        'goods_price' => $goods->goods_price,
                        'u_name' => $user,
                        'cart_number'=> $Cart->cart_number,
                        'add_cart_time'=>time()
                    ];
                    $goods_s=Order::insert($where);
                }else{
                    header('refresh:3;url=/goods');
                    echo '提交订单失败，请稍后进行添加 - - !!!!';
                }
//                print_r($goods);die;
                $oderdWhere = [
                    'goods_id' => $id,
                    'goods_name' =>$goods->goods_name,
                    'goods_price' => $goods->goods_price,
                    "u_name" =>  $user,
                    'cart_number' => $Cart->cart_number,
                    'oderd_addtime'=>time()
                ];
                $ii  =Oderd::insert($oderdWhere);
                if($ii&&$goods_s){
                    $Cart=Cart::where(['goods_id'=>$id,'u_name'=>$user])->delete();
                    header('refresh:1;url=/order/list');
                    echo '操做成功';
                }
            }else{
                $order_id=Order::dj();
                $CartAll=Cart::where(['u_name'=>$user])->get();
//                dump($CartAll);die;
                foreach ($CartAll as $k=>$v){
                    $where= [
                        'order_id' => $order_id,
                        'goods_id' => $v->goods_id,
                        'goods_price' => $v->goods_price,
                        'u_name' => $user,
                        'cart_number'=> $v->cart_number,
                        'add_cart_time'=>time()
                    ];
                    $OrderInfo=Order::insert($where);

                    $oderdWhere = [
                        'goods_id' =>$v->goods_id,
                        'goods_name' =>$v->goods_name,
                        'goods_price' => $v->goods_price,
                        "u_name" =>  $user,
                        'cart_number' => $v->cart_number,
                        'oderd_addtime'=>time()
                    ];
                    $ii  =Oderd::insert($oderdWhere);

                }
                if($OrderInfo && $ii){
                    $Cart=Cart::where(['cart_id'=>$v->cart_id])->delete();
                    if($Cart){
                        header('refresh:1;url=/order/list');
                        echo '操作订单系列成功';
                    }else{
                        header('refresh:3;url=/goods');
                        echo '提交订单失败，请稍后进行添加 - - !!!!';
                    }
                }
            }
        }
    }

    public function list()
    {
        $data = Order::all();
        return    view('/order/order',['data'=>$data]);
    }

    public function payStatus()
    {
        $oid = intval($_GET['oid']);
        $info = Order::where(['order_id'=>$oid])->first();
        $response = [];
        if($info){
            if($info->pay_time>0){      //已支付
                $response = [
                    'status'    => 0,       // 0 已支付
                    'msg'       => 'ok'
                ];
            }
            //echo '<pre>';print_r($info->toArray());echo '</pre>';
        }else{
            die("订单不存在");
        }
        die(json_encode($response));
    }
}
