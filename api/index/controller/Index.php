<?php
namespace app\index\controller;
use \think\Controller;
class Index extends Controller
{   
    public $obj;
    public function index()
    {	
        $this->log($_GET);
    	if (isset($_GET['echostr']))
        {
            if ($this->checkSing()) {
                echo $_GET['echostr'];
            }
        }else{

            if(!$this->checkSing()){
                return false;
            }

            $this->request();
        }
        // echo $_GET['echostr'];
        // file_put_contents('./wx.txt', json_encode($_GET));
    }

    
    public function request ()
    {
         $data = $GLOBALS['HTTP_RAW_POST_DATA'];
         // $this->log(['data'=>$data]);
        $this->obj = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);


        switch ($this->obj->MsgType) {
            case 'text':
                $this->responText();
                break;
            case 'image':
                $this->responImage();
                break;
            default:
                # code...
                break;
        }


    }
    public function log($data)
    {
        $data['data'] = json_encode($data);
        $data['ctime'] = time();
        db('log')->insert($data);
    }
     public function logshow()
    {
        
        $data = db('log')->order('ctime desc')->select();

        var_dump($data);
    }
    public function responText()
    {
        $str = "<xml>
        <ToUserName><![CDATA[".$this->obj->FromUserName."]]></ToUserName>
        <FromUserName><![CDATA[".$this->obj->ToUserName."]]></FromUserName>
        <CreateTime>".time()."</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA["."你好"."]]></Content>
        </xml>";

        echo $str;


    }
    public function responImage()
    {
       $str = "<xml>
        <ToUserName><![CDATA[".$this->obj->FromUserName."]]></ToUserName>
        <FromUserName><![CDATA[".$this->obj->ToUserName."]]></FromUserName>
        <CreateTime>".time()."</CreateTime>
        <MsgType><![CDATA[image]]></MsgType>
        <Image><MediaId><![CDATA[".$this->obj->MediaId."]]></MediaId></Image>
        </xml>";

        echo $str;


    }
    public function checkSing()
    {
        $signature = $_GET['signature'];

        $nonce = $_GET['nonce'];

        $timestamp = $_GET['timestamp'];

        $token = "yang";

        $tmpArr = array($token,$timestamp, $nonce);//时间戳与字符串组成数组 

        sort($tmpArr, SORT_STRING); //数组排序

        $tmpStr = implode( $tmpArr ); //.数组组成字符串

        $sign = sha1( $tmpStr ); //哈希加密

        // file_put_contents("./wx.txt",$sign."---".$signature);
        if ($signature==$sign) {
            return true;
            }else{
                return flase;
            }
        }
}
