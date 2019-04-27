<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
//    $router->get('/goods/list', GoodsController::class);
    Route::resource('/goods/list', GoodsController::class);
    Route::resource('/weixin/user', WxController::class);
    Route::resource('/weixin/img', WximgController::class);

    //添加
    $router->get('/xc', 'XcController@index');

    $router->post('/imgCun', 'XcController@imgCun');

    $router->get('/xxc', 'XxcController@index');

    //群发
    $router->get('/qunfa', 'QunfaController@index');
    $router->post('/dispose', 'QunfaController@dispose');


    $router->get('/', 'HomeController@index');

    $router->get('weixin/list', 'WeixinController@index');


});
