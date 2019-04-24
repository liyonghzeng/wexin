<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Goods;

class GoodsController extends Controller
{
    //
    public function goods($id)
    {
        $goods=Goods::where(['goods_id'=>$id])->first();
        view('goods/goods',['goods'=>$goods]);
    }
}
