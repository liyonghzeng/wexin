<?php


namespace App\Http\Controllers\weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\WXBizDataCryptController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Order;

class WxPayController extends Controller
{
    //
    //类的属性
    public $weixin_unifiedorder_url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';        // 统一下单接口
    public $notify_url = 'http://1809liyongzheng.comcto.com/weixin/pay/notify';     // 支付回调

    /*
     *微信支付测试
     */
    public function text($id)
    {
//        echo $id;
        $u_name=Auth::id();
        $data=Order::where(['order_id'=>$id,'u_name'=>$u_name])->get();
        if($data){ //判断订单号是否存在
            //计算订单总价
            $number = 0;
            foreach($data as $k=>$v){
                $number +=  $v->cart_number * $v->goods_price;
            }

        }else{

            header('refresh:1;url=/order/list');
            die;
        }

        $total_fee = $number; //用户支付金额
//        $order_id = time() . mt_rand(111111, 999999);
        $order_info = [
            'appid' => env('WEIXIN_APPID_0'),      //微信支付绑定的服务号的APPID
            'mch_id' => env('WEIXIN_MCH_ID'),       // 商户ID
            'nonce_str' => Str::random(16),             // 随机字符串
            'sign_type' => 'MD5',
            'body' => '测试订单-' .$id,
            'out_trade_no' => $id,                       //本地订单号
            'total_fee' => $total_fee,
            'spbill_create_ip' => $_SERVER['REMOTE_ADDR'],     //客户端IP
            'notify_url' => $this->notify_url,        //通知回调地址
            'trade_type' => 'NATIVE'                         // 交易类型
        ];
        //定义类的属性
        $this->values = [];
        $this->values = $order_info;
        $this->SetSign();
//        print_r($this->values);
        $xml = $this->ToXml();      //将数组转换为XML
//        echo $xml;die;
//        echo print_r($xml);die;
        $rs = $this->postXmlCurl($xml, $this->weixin_unifiedorder_url, $useCert = false, $second = 30);
        $data = simplexml_load_string($rs);
//              echo 'return_code: '.$data->return_code;echo '<br>';
//        		echo 'return_msg: '.$data->return_msg;echo '<br>';
//        		echo 'appid: '.$data->appid;echo '<br>';
//        		echo 'mch_id: '.$data->mch_id;echo '<br>';
//        		echo 'nonce_str: '.$data->nonce_str;echo '<br>';
//        		echo 'sign: '.$data->sign;echo '<br>';
//        		echo 'result_code: '.$data->result_code;echo '<br>';
//        		echo 'prepay_id: '.$data->prepay_id;echo '<br>';
//        		echo 'trade_type: '.$data->trade_type;echo '<br>';
                echo 'code_url: '.$data->code_url;echo '<br>';
        $data = [
            'code_url' => $data->code_url,
            'o_id'       => $id,
        ];

        return view('wx/wxzf', $data);
    }

    private function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//		if($useCert == true){
//			//设置证书
//			//使用证书：cert 与 key 分别属于两个.pem文件
//			curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
//			curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
//			curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
//			curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
//		}
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            die("curl出错，错误码:$error");
        }

    }

    public function ToXml()
    {
        if (!is_array($this->values) || count($this->values) <= 0) {
            die("数组数据异常！");
        }
        $xml = "<xml>";
        foreach ($this->values as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    public function SetSign()
    {
        $sign = $this->MakeSign();
        $this->values['sign'] = $sign;
        return $sign;
    }

    //进行拼接 key 加密
    public function MakeSign()
    {
        ksort($this->values);
        //转化成字符串
        $string = $this->ToUrlParams();
        //进行拼接 签名步骤二：在string后加入KEY
        $string = $string . "&key=" . env('WEIXIN_MCH_KEY');
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    //转换成字符串
    public function ToUrlParams()
    {
        $buff = '';
        foreach ($this->values as $k => $v) {
            if ($k != 'sign' && $v != '' && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    public function notify()
    {
//        echo 111;
        $data = file_get_contents("php://input");

        //记录日志
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_pay_notice.log', $log_str, FILE_APPEND);

        $xml = simplexml_load_string($data);
//        dump($xml->result_code);die;
        if ($xml->result_code == 'SUCCESS' && $xml->return_code == 'SUCCESS') {      //微信支付成功回调
            //验证签名
            $sign = true;
            if ($sign) {       //签名验证成功
                //TODO 逻辑处理  订单状态更新
                $pay_time = strtotime($xml->time_end);
                echo  $pay_time;
                Order::where(['order_id'=>$xml->out_trade_no])->update(['pay_time'=>1]);
//                echo 222;
            } else {
                //TODO 验签失败
                echo '验签失败，IP: ' . $_SERVER['REMOTE_ADDR'];
                // TODO 记录日志
            }
        }
//        dump($sign);die;
        $response = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        echo $response;
    }

    public function paySuccess()
    {
        $oid = $_GET['oid'];
        echo 'OID: '.$oid . "支付成功";
    }

}
