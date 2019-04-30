<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    phpinfo();
});

Route::get('/test/urlencode', function () {
    echo urlencode($_GET['url']);
});
//商品展示
Route::get('/goods', 'GoodsController@index');

//详情
Route::get('/addBrowse/{id}', 'GoodsController@Browse');

//购物车
Route::get('cart/index/{id}', 'CartController@index');


Route::get('cart/list', 'CartController@list');

//订单生成
//Route::get('/order/paystatus', 'OrderController@payStatus');//

Route::get('order/index/{id}', 'OrderController@index');

Route::get('order/list', 'OrderController@list');

Route::get('/text/{id}', 'weixin\WxPayController@text');//微信支付

Route::post('weixin/pay/notify', 'weixin\WxPayController@notify');//微信支付回调

Route::get('/order/paystatus', 'OrderController@payStatus');//


Route::get('/i','WxController@shouquan');//授权
Route::get('/sq','WxController@sq');//授权

//搜索
Route::get('/ss','WxController@swx');//授权


//签到
Route::get('/xa','WxController@xa');

Route::get('/aa','WxController@aa');


Route::get('weixin/ui','WxController@valid');

//接收微信服务器推送事件
Route::post('weixin/ui','WxController@wxEvent');

Route::get('/tmp','WxController@tmp');


Route::get('/weixin/create_menu','WxController@createMenu');     //创建公众号菜单
Route::get('/weixin/get_access_token','WxController@getAccessToken');
Route::get('/weixin/test','WxController@test');
//atoken 测试
Route::get('/weixin/atoken','WxController@atoken');


//群发
Route::get('/weixin/wxgroups','WxController@wxgroups');

//测试
Route::get('/ix','WxController@ix');

Route::get('/goods/{id}','GoodsController@goods');//商品详情
//微信支付
Route::get('/text','WxPayController@text');




Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
