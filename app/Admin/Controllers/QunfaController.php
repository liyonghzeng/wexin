<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use GuzzleHttp\Client;
use App\Wx;

class QunfaController extends Controller
{
    public function index(Content $content)
    {
        //获取所有用户信息
        $data=Wx::get();
        return $content
            ->header('Index')
            ->description('description')
            ->body(view("qunfa/qunfa",['data'=>$data]));
    }
    public function dispose()
    {
       $c= request()->all();
       $id = $c['penid'];
       $name=explode(',',$id);
       $openid=array_pop($name);
        $res=[
            "touser"=>$name,
            "msgtype"=> "text",
            "text"=>[
                "content"=>  $c['nr'],
            ]
        ];
//        $ddd=json_encode($res);
        $data=json_encode($res,JSON_UNESCAPED_UNICODE);
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.getAccessToken();
        $client = new Client();
           $response= $client->request("post",$url,[
                "body"=>$data
            ]);
        if ($response){
           return json_encode('ok');
        }
    }
}