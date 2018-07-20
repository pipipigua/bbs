<?php
namespace app\index\controller;
use \think\Controller;
use \app\index\model\Vercode;
use think\Model;
class Login extends Common
{
    public function index()
    {       
        if (session('uid')) {
           $this->error('请不要重复登录','index/index/index');
        }
    	$question = model('vercode')->getRandOne();

        return $this->fetch('',['question'=>$question,'title'=>'登录']);
    }

    public function checkdata()
    { 
    	$data = input('post.');

        model('vercode')->checkcode($data['vercode']);

        $res = db('user')->where(['email'=>$data['email'],'password'=>md5($data['password'])])->find();
        // var_dump($res);

        if ($res) {
            session('uid',$res['uid']);
            session('face',$res['face']);
            session('nickname',$res['nickname']);
            session('email',$res['email']);
            $ip = get_client_ip();
            $date= date("Y-m-d h:i:s");
            $reg=db('user')->where('uid',session('uid'))->update(['ip' => $ip]);
            $reg=db('user')->where('uid',session('uid'))->update(['ltime' => $date]);


            exit(json_encode(['error'=>0,'info'=>"登录成功"]));
        }else{

            exit(json_encode(['error'=>1,'info'=>"账号密码错误"]));
        }
    }

    public function loginout()
    {
       session(null);
        $this->error('退出成功','index/index/index');
    }
     public function qqlogin()
    {

        // $this->redirect('http://www.baidu.com');

        vendor('qqconnect.API.qqConnectAPI', '.php');//引用第三方类库

        $qc = new \QC();

        $url = $qc->qq_login();

        $this->redirect($url);
    }

    public function qqreturn()
    {
        vendor('qqconnect.API.qqConnectAPI', '.php');

        $qc = new \QC();

        $qc->qq_callback();

        $openid = $qc->get_openid();

        $res = db('user')->where('openid',$openid)->find();

        if($res){

            session('uid',$res['uid']);

            session('face',$res['face']);

            session('nickname',$res['nickname']);

            $this->success('登录成功',url('index/index/index'));

        }else{

            
            $qc = new \QC();
            $arr = $qc->get_user_info();


            $data['nickname'] = $arr['nickname'];

            $data['sex'] = $arr['gender'];

            $data['city'] = $arr['city'];

            $data['openid'] = $openid;

            $data['face'] = $this->getqqface($arr['figureurl_2']);

            db('user')->insert($data);

            $uid = db('user')->getLastInsID();

            session('uid',$uid);

            session('face',$data['face']);

            session('nickname',$data['nickname']);

            $this->success('登录成功',url('index/index/index'));

        }

    }

    public function getqqface($face)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$face);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HEADER, 0);

        $output = curl_exec($ch);

        curl_close($ch);

        $name = uniqid();

        $path = './face/'.$name.'.png';

        file_put_contents($path,$output);

        return $path;
    }
}
