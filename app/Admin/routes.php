<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/goods/list', GoodsController::class);

    $router->get('/', 'HomeController@index');
    $router->get('weixin/list', 'WeixinController@index');


});
