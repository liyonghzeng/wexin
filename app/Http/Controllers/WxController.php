<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Wx;
use App\Qd;
use App\Ss;
use App\Tmp;
use App\Wximg;
use App\Goods;
use App\Shouquan;
use App\Wxvoice;
use App\Wxcontent;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class WxController extends Controller
{
    //
    /**
     * 处理首次接入GET请求
     */
    public function ix()
    {
      
    }
    public function valid()
    {
        echo $_GET['echostr'];
    }

    public function atoken()
    {
       echo getAccessToken();
    }
    //通过redis 定时计划
    public function swx()
    {
        $jf=Redis::get("goods_name_ss");
        echo $jf;
        $data= Ss::insert($jf);
    }
    /**
     * 接收微信事件推送 POST
     */
    public function wxEvent()
    {
        //接收微信服务器推送
    //    echo 111;die;
        $content = file_get_contents("php://input");
        // dump($content);
        $time = date('Y-m-d H:i:s');
        $str = $time . $content . "\n";
        
        file_put_contents("logs/wx_event.log",$str,FILE_APPEND);
    
        //转换obj格式
        $data = simplexml_load_string($content);
        // dump($data);die;
//        echo 'ToUserName: '. $data->ToUserName;echo '</br>';        // 公众号ID
//        echo 'FromUserName: '. $data->FromUserName;echo '</br>';    // 用户OpenID
//        echo 'CreateTime: '. $data->CreateTime;echo '</br>';        // 时间戳
//        echo 'MsgType: '. $data->MsgType;echo '</br>';              // 消息类型
//        echo 'Event: '. $data->Event;echo '</br>';                  // 事件类型
//        echo 'EventKey: '. $data->EventKey;echo '</br>';  
        $client = new Client;
        $wx_id = $data->ToUserName;             // 公众号ID
        $openid = $data->FromUserName;          //用户OpenID
        $event = $data->Event;  
        $msg_type = $data->MsgType;
        // dump($msg_type);die;
        // echo $event;die;        //事件类型
        if($event=='subscribe'){            //扫码关注事件
//             if($msg_type=='event'){
//                     $u = $this->getUserInfo($openid);
//                     $whss=[
//                         'openid'=>$u['openid']
//                     ];
//                     $iss=Tmp::where($whss)->first();
//                     if($iss){
//                             $name=Goods::where(['goods_id'=>1])->first();
//                             $goods_name=$name->goods_name;
//                             $sr = "欢迎回来.请继续观看商品";
//                             $url = "http://1809liyongzheng.comcto.com/goods/$name->goods_id";
//                             $nr='<xml>
//                                  <ToUserName><![CDATA['.$openid.']]></ToUserName>
//                               <FromUserName><![CDATA['.$wx_id .']]></FromUserName>
//                             <CreateTime>'.time().'</CreateTime>
//                              <MsgType><![CDATA[news]]></MsgType>
//                              <ArticleCount>1</ArticleCount>
//                              <Articles>
//                                <item>
//                                     <Title><![CDATA['.$sr.']]></Title>
//                                  <Description><![CDATA['.$goods_name.']]></Description>
//                                  <PicUrl><![CDATA[https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=633401611,1187473375&fm=26&gp=0.jpg]]></PicUrl>
//                                  <Url><![CDATA['.$url.']]></Url>
//                                </item>
//                              </Articles>
//                            </xml>';
//                             echo $nr;
//
//                    }else{
//                         $where=[
//                             'openid'=>$u['openid'],
//                             'CreateTime'=>time(),
//                             "EventKey"=>$u['qr_scene']
//                         ];
//
//                         $res=Tmp::insert($where);
//                         if($res){
//                             $name=Goods::where(['goods_id'=>1])->first();
//                             $goods_name=$name->goods_name;
//                             $sr = "你可能喜欢的";
//                             $url = "http://1809liyongzheng.comcto.com/goods/$name->goods_id";
//                             $nr='<xml>
//                                  <ToUserName><![CDATA['.$openid.']]></ToUserName>
//                               <FromUserName><![CDATA['.$wx_id .']]></FromUserName>
//                             <CreateTime>'.time().'</CreateTime>
//                              <MsgType><![CDATA[news]]></MsgType>
//                              <ArticleCount>1</ArticleCount>
//                              <Articles>
//                                <item>
//                                     <Title><![CDATA['.$sr.']]></Title>
//                                  <Description><![CDATA['.$goods_name.']]></Description>
//                                  <PicUrl><![CDATA[https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=633401611,1187473375&fm=26&gp=0.jpg]]></PicUrl>
//                                  <Url><![CDATA['.$url.']]></Url>
//                                </item>
//                              </Articles>
//                            </xml>';
//                             echo $nr;
//                         }
//                     }
////                     if($event=='CLICK'){
////                         $xml='<xml>
////                            <ToUserName><![CDATA[.$openid.]]></ToUserName>
////                            <FromUserName><![CDATA[.$wx_id .]]></FromUserName>
////                            <CreateTime>'.time().'</CreateTime>
////                            <MsgType><![CDATA[event]]></MsgType>
////                            <Event><![CDATA[VIEW]]></Event>
////                            <EventKey><![CDATA[/i]]></EventKey>
////                            <MenuId>MENUID</MenuId>
////                         </xml>';
////                     }
//                }else{

                 $local_user = Wx::where(['openid'=>$openid])->first();
                 //    dump($local_user);die;
                 if($local_user){
                     // echo 11;
                     // echo $local_user['nickname'];die;
                     echo '<xml>
                        <ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName>
                        <![CDATA['.$wx_id.']]></FromUserName><CreateTime>'.time().'
                        </CreateTime><MsgType><![CDATA[text]]></MsgType><Content>
                        <![CDATA['. '欢迎回来 '. $local_user['nickname'] .']]></Content>
                    </xml>';
                 }else{
                     // echo 111;die;
                     $u = $this->getUserInfo($openid);
                     $u_info = [
                         'openid' => $u['openid'],
                         'nickname'  => $u['nickname'],
                         'sex'  => $u['sex'],
                         'headimgurl'  => $u['headimgurl'],
                     ];
                     $id = Wx::insertGetId($u_info);
                     echo '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName>
                           <FromUserName><![CDATA['.$wx_id.']]></FromUserName>
                           <CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]>
                           </MsgType><Content><![CDATA['. '请输入商品名字字样 '. $u['nickname'] .']]></Content>
                      </xml>';
                 }


        }else if($msg_type=='image'){//图片
           
           
            // echo 11;die;
            $media_id = $data->MediaId;
            // echo 11;die;
            $url2 = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.getAccessToken().'&media_id='.$media_id;
            $response =$client->get(new Uri($url2));

            $headers = $response->getHeaders();//获取 相应头 信息
            $file_info  = $headers['Content-disposition'][0];

            $file_name = rtrim(substr($file_info,-20),'"'); //获取文件名字
            
            // $img = file_get_contents($data->PicUrl);
            // $file_name = time().mt_rand(11111,99999).'.jpg';
            // $rs=file_put_contents('wx/images/'.$file_name,$img); //下载

            $new_file_name ='weixin/'.substr(md5(time().mt_rand()),10,8).'_'.$file_name;
            
         
            $re = Storage::put($new_file_name,$response->getBody());
            $u = $this->getUserInfo($openid);
            // dump($u);
            $u_info = [
                'openid' => $u['openid'],
                'img_adress'  => $new_file_name,
            ];
            $res = Wximg::insertGetId($u_info);
            if($res){
                echo 'success';
            }else{
                echo '失败';
            }
            // var_dump($rs);
        }else if($msg_type=='voice'){
            $u = $this->getUserInfo($openid);
            $media_id = $data->MediaId;
            $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.getAccessToken().'&media_id='.$media_id;
            $amr = file_get_contents($url);
            $file_name = time().mt_rand(11111,99999).'.mp3';
            $rs = file_put_contents('wx/voice/'.$file_name,$amr);
            // dump($media_id);
           $u_info = [
                'openid' => $u['openid'],
                'voice_adress'  => 'wx/voice/'.$file_name,
            ];
            $res = Wxvoice::insertGetId($u_info);
            if($res){
                echo 'success';
            }else{
                echo '失败';
            }
        }else if($msg_type=='text'){
            $u = $this->getUserInfo($openid);
            $t_Content = $data->Content;  
            $u_info = [
                'openid' => $u['openid'],
                'wx_text'  => $t_Content,
            ];
//            dump($u_info);die;
            $res = Wxcontent::insertGetId($u_info);
            if(strpos($t_Content,'+天气')){
                // echo 111;
                $city =explode('+',$t_Content)[0];
                
                $url = 'https://free-api.heweather.net/s6/weather/now?key=HE1904161046411448&location='.$city;
                //返回xml 格式转换 转化成数组
                $arrx=json_decode(file_get_contents($url),true);
                // dd($arrx);
                //天气状况
                if($arrx['HeWeather6'][0]['status']=='ok'){
                    $cond_txt=$arrx['HeWeather6'][0]['now']['cond_txt'];//摄氏度
                    $tmp=$arrx['HeWeather6'][0]['now']['tmp']; //风向
                    $wind_dir=$arrx['HeWeather6'][0]['now']['wind_dir'];//风力
                    $hum=$arrx['HeWeather6'][0]['now']['hum']; //温度
                    $sr = '天气'.$cond_txt ."\n"."风向:".$wind_dir."\n"."温度:".$hum."\n";
                    // echo '<pre>';print_r($arrx);echo'</pre>';
                    $nr = '<xml>
                            <ToUserName><![CDATA['.$openid.']]></ToUserName>
                            <FromUserName><![CDATA['.$wx_id .']]></FromUserName>
                            <CreateTime>'.time().'</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA['.$sr.']]></Content>
                        </xml>';

                 echo $nr;
                }else{
                    $nr = '<xml>
                    <ToUserName><![CDATA['.$openid.']]></ToUserName>
                    <FromUserName><![CDATA['.$wx_id .']]></FromUserName>
                    <CreateTime>'.time().'</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[城市名称有误,请输入正确的地区名称！！！]]></Content>
                  </xml>';
                echo $nr;
                }

        }else if($t_Content=='最新商品'){
                $name=Goods::where(['goods_id'=>1])->first();
                $goods_name=$name->goods_name;
                $sr = "最新商品";
                $url = "http://1809liyongzheng.comcto.com/goods/$name->goods_id";
                    $nr='<xml>
                              <ToUserName><![CDATA['.$openid.']]></ToUserName>
                           <FromUserName><![CDATA['.$wx_id .']]></FromUserName>
                         <CreateTime>'.time().'</CreateTime>
                          <MsgType><![CDATA[news]]></MsgType>
                          <ArticleCount>1</ArticleCount>
                          <Articles>
                            <item>
                                 <Title><![CDATA['.$sr.']]></Title>
                              <Description><![CDATA['.$goods_name.']]></Description>
                              <PicUrl><![CDATA[https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=633401611,1187473375&fm=26&gp=0.jpg]]></PicUrl>
                              <Url><![CDATA['.$url.']]></Url>
                            </item>
                          </Articles>
                        </xml>';
                    echo $nr;

            }else{
//                $ccc=rand(1,7);
//                $name=Goods::where(['goods_id'=>$ccc])->first();
//                $goods_name=$name->goods_name;
//                $sr = "随机商品";
//                $url = "http://1809liyongzheng.comcto.com/goods/$name->goods_id";
//                $nr='<xml>
//                              <ToUserName><![CDATA['.$openid.']]></ToUserName>
//                           <FromUserName><![CDATA['.$wx_id .']]></FromUserName>
//                         <CreateTime>'.time().'</CreateTime>
//                          <MsgType><![CDATA[news]]></MsgType>
//                          <ArticleCount>1</ArticleCount>
//                          <Articles>
//                            <item>
//                                 <Title><![CDATA['.$sr.']]></Title>
//                              <Description><![CDATA['.$goods_name.']]></Description>
//                              <PicUrl><![CDATA[https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=633401611,1187473375&fm=26&gp=0.jpg]]></PicUrl>
//                              <Url><![CDATA['.$url.']]></Url>
//                            </item>
//                          </Articles>
//                        </xml>';
//                echo $nr;
                $ksc = 'goods_name_ss';
                Redis::set($ksc,$t_Content);
                $where=[
                     'goods_name'=>$t_Content
                ];
               $jg= Goods::where($where)->first();
                if($jg){
                $sr = "商品";
                $url = "http://1809liyongzheng.comcto.com/goods/$jg->goods_id";
                $goods_name=$jg->goods_name;
                    $nr='<xml>
                              <ToUserName><![CDATA['.$openid.']]></ToUserName>
                           <FromUserName><![CDATA['.$wx_id .']]></FromUserName>
                         <CreateTime>'.time().'</CreateTime>
                          <MsgType><![CDATA[news]]></MsgType>
                          <ArticleCount>1</ArticleCount>
                          <Articles>
                            <item>
                                 <Title><![CDATA['.$sr.']]></Title>
                              <Description><![CDATA['.$goods_name.']]></Description>
                              <PicUrl><![CDATA[https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=633401611,1187473375&fm=26&gp=0.jpg]]></PicUrl>
                              <Url><![CDATA['.$url.']]></Url>
                            </item>
                          </Articles>
                        </xml>';
                echo $nr;
                }else{
                    $nr = '<xml>
                    <ToUserName><![CDATA['.$openid.']]></ToUserName>
                    <FromUserName><![CDATA['.$wx_id .']]></FromUserName>
                    <CreateTime>'.time().'</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[稍后再来]]></Content>
                  </xml>';
                    echo $nr;
                }
            }
        }
    }


   
    // /** 
    //  * 获取微信 AccessToken
    //  */
    // public function getAccessToken()
    // {
    //     //是否有缓存
    //     $key = 'wx_access_token';
    //     $token = Redis::get($key);
    //     if($token){
    //     }else{
    //         $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WX_APPID').'&secret='.env('WX_APPSECRET');
    //              // https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=APPSECRET
    //         $response = file_get_contents($url);
    //         $arr = json_decode($response,true);
    //         //缓存 access_token
    //         Redis::set($key,$arr['access_token']);
    //         Redis::expire($key,3600);       //缓存时间 1小时
    //         $token = $arr['access_token'];
    //     }
    //     return $token;
    // }
    public function test()
    {
        $access_token = getAccessToken();
        echo 'token : '. $access_token;echo '</br>';
    }
    /**
     * 获取微信用户信息
     * @param $openid
     */
    public function getUserInfo($openid)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.getAccessToken().'&openid='.$openid.'&lang=zh_CN';
        
        $data = file_get_contents($url);
  
        $u = json_decode($data,true);
//         dump($u);die;
        return $u;
    }
    /**
     * 创建公众号菜单
     */
    public function createMenu()
    {
        // url
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.getAccessToken();
        $server=$_SERVER['REQUEST_SCHEME'].'://1809liyongzheng.comcto.com/sq';
        $servers=$_SERVER['REQUEST_SCHEME'].'://1809liyongzheng.comcto.com/aa';

//        echo $server;die;
        // 接口数据
        $post_arr = [               //注意菜单层级关系
            'button'    => [
                [
                    'type'  => 'view',
                    'name'  => '最新福利',
                    'key'   => 'key_menu_001',
                    'url'=>$server
//                    https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx4ed0983f28f62b51&redirect_uri=http%3A%2F%2F1809.com%2Fi&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect
                ],
                [
                    'type'  => 'view',
                    'name'  => '签到',
                    'key'   => 'key_menu_002',
                     'url'=>$servers
                ],
            ]
        ];
        $json_str = json_encode($post_arr,JSON_UNESCAPED_UNICODE);  //处理中文编码
        // 发送请求
        $clinet = new Client();
        $response = $clinet->request('POST',$url,[      //发送 json字符串
            'body'  => $json_str
        ]);
        //处理响应
        $res_str = $response->getBody();
        $arr = json_decode($res_str,true);
        //判断错误信息
        if($arr['errcode']>0){
            //TODO 错误处理
            echo "创建菜单失败";
        }else{
            // TODO 正常逻辑
            echo "创建菜单成功";
        }
    }
     //群发
     public function wxgroups($openid_arr,$content){

      
        // echo $groups;
        // echo '<hr />';
     
        // echo $json_str;



       $msg = [
            "touser"=>$openid_arr,
             "msgtype"=>"text",
             "text"=>[
                 'content' => $content
             ]
       ];
       $json_str = json_encode($msg,JSON_UNESCAPED_UNICODE); 
       $groups='https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.getAccessToken();
       $Client =new Client();
       $response=$Client->request('POST',$groups,[
           "body"=>$json_str
       ]);
       echo $response->getBody();
    }
    public function send(){
      $user_list = Wx::get()->toArray();
      $openid=  array_column($user_list,'openid');
      $content = '【保时捷】，【奔驰】，【宝马】，【法拉利】，【兰博基尼】....';
      $sss=$this->wxgroups($openid,$content);
        echo $sss;
    }
    //签到
    public function xa()
    {
        header("refresh:3;url=https://open.weixin.qq.com/connect/oauth2/authorize?appid=".env("WX_APPID")."&redirect_uri=http%3A%2F%2F1809liyongzheng.comcto.com%2Faa&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect");
        echo '正在前往签到活动中心';
    }
    public function aa()
    {
        $code = $_GET['code'];
        $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env("WX_APPID")."&secret=".env("WX_APPSECRET")."&code=".$code."&grant_type=authorization_code";
        $data=json_decode(file_get_contents($url),true);
        $size ="https://api.weixin.qq.com/sns/userinfo?access_token=".$data['access_token']."&openid=".$data['openid']."&lang=zh_CN";
        $user_y=json_decode(file_get_contents($size),true);
        $res=Qd::where(['openid'=>$user_y['openid']])->first();
        $qdkey ='qd_'.$user_y['openid'];
        if($res){
            $p=$res->nickname.time();
            Redis::lpush($qdkey,$p);
            echo Redis::lrange($qdkey);
        }else{
            $where=[
                "openid"=>$user_y['openid'],
                'addtime'=>time(),
                "nickname"=>$user_y['nickname'],
            ];
            QB::insert($where);
            $q = $user_y['nickname'].time();
            echo Redis::lrange($qdkey,$q);
        }

    }
    //最新活动
    public function sq()
    {
        header("refresh:3;url=https://open.weixin.qq.com/connect/oauth2/authorize?appid=".env("WX_APPID")."&redirect_uri=http%3A%2F%2F1809liyongzheng.comcto.com%2Fi&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect");
            echo '正在前往最新活动现场';
    }
    //授权
    public function shouquan(){
//        echo $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".env("WX_APPID")."&redirect_uri=http%3A%2F%2F1809liyongzheng.comcto.com%2Fi&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
       $code = $_GET['code'];
       $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env("WX_APPID")."&secret=".env("WX_APPSECRET")."&code=".$code."&grant_type=authorization_code";
        $data=json_decode(file_get_contents($url),true);
       $size ="https://api.weixin.qq.com/sns/userinfo?access_token=".$data['access_token']."&openid=".$data['openid']."&lang=zh_CN";
        $user_y=json_decode(file_get_contents($size),true);
        $res=Shouquan::where(['openid'=>$user_y['openid']])->first();
//        dump($res->nickname);die;
        if($res){
            echo "亲爱的".$res->nickname."欢迎回来";
        }else{
            $where=[
                "nickname"=>$user_y['nickname'],
                "sex"=>$user_y['sex'],
                "province"=>$user_y['province'],
                "city"=>$user_y['city'],
                "country"=>$user_y['country'],
                "openid"=>$user_y['openid'],
            ];
            Shouquan::insert($where);
        }
        $server=$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/goods';
        header("refresh:3;url=".$server);
        echo '正在前往最新活动现场';
    }
    public function tmp()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".getAccessToken();
//        echo $url;die;
        $msg =[
                "expire_seconds"=>604800,
                "action_name"=>'QR_SCENE',
                "action_info"=>[
                    'scene'=>['scene_id'=>888],
                ]

        ];
        $data=json_encode($msg,JSON_UNESCAPED_UNICODE);
        $client = new Client;
        $response= $client->request("POST",$url,[
            "body"=>$data
        ]);

        $obj= $response->getBody();
        $arr = json_decode($obj,true);

        $mm='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$arr['ticket'];
       header("refresh:1;url=$mm");

    }
}