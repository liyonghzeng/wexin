<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\Redis;
use \GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use App\Xxc;
use Illuminate\Support\Str;

class XcController extends Controller
{
    public function  index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body(view('/xc/xc'));
    }
    public function  imgCun(Content $content)
    {

//            dump($extension);die;
            if (request()->hasFile('img') && request()->file('img')->isValid()) {

                $extension = request()->img->extension();
                $photo = request()->file('img');
                //文件名称s
                $path = time().'test'.Str::random(15).'.'.$extension;
                $store_result = $photo->storeAs('img',$path);
                $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.getAccessToken().'&type=image';
                $client = new \GuzzleHttp\Client();

                $response = $client->request('post',$url,[
                    'multipart' => [
                        [
                            'name' => 'media',
                            'contents' => fopen('../storage/app/img/'.$path, 'r'),
                        ]
                    ]
                ]);

                $json =  json_decode($response->getBody(),true);
                if($json){
                    $where=[
                          "type"=>$json['type'],
                          "media_id"=>$json['media_id'],
                        "created_at"=>$json['created_at']
                    ];
                    $res=xxc::insert($where);
                    if($res){
//                        $urll="https://api.weixin.qq.com/cgi-bin/media/get?access_token=".getAccessToken()."&media_id=".$json['media_id'];
//                        $response =$client->get(new Uri($urll));
//                        $headers = $response->getHeaders();//获取 相应头 信息
//                        $file_info  = $headers['Content-disposition'][0];
//                        $file_name = rtrim(substr($file_info,-69),'"');
//                        echo $file_name;

                        header('refresh:3;url=/admin/xxc');
                        echo '上传成功';
                    }
                }
            }


    }
 
}
