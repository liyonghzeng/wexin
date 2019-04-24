<?php

use Illuminate\Support\Facades\Redis;


    /**
     * 获取微信 AccessToken
     */
        function getAccessToken()
        {
            //是否有缓存
            $key = 'wx_access_token';
            $token = Redis::get($key);
            if(!$token){
                $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WX_APPID').'&secret='.env('WX_APPSECRET');
                    // https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=APPSECRET
                $response = file_get_contents($url);
                $arr = json_decode($response,true);
                //缓存 access_token
                Redis::set($key,$arr['access_token']);
                Redis::expire($key,3600);       //缓存时间 1小时
                $token = $arr['access_token'];

            }
            return $token;
        }
        