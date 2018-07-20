<?php
namespace app\admin\controller;
use think\Controller;
use think\captcha\Captcha;

class Login extends Controller
{
    public function index()
    {

    	return $this->fetch('');
    }

    public function checkdata()
    {

        $data = input('post.');

        $captcha = new Captcha();

       if(!$captcha->check($data['captcha'],'')){
            exit(json_encode(['error'=>1,'info'=>"验证码错误"]));
       }

        $user = db('admin')->where(['username'=>$data['username'],'password'=>md5($data['password'])])->find();

        if($user){

            session('aid',$user['aid']);

            session('username',$user['username']);
            $ip = get_client_ip();
            $date= date("Y-m-d h:i:s");
            $reg=db('admin')->where('aid',session('aid'))->update(['lip' => $ip]);
            $reg=db('admin')->where('aid',session('aid'))->update(['ltime' => $date]);

            exit(json_encode(['error'=>0,'info'=>"登录成功"]));
        }else{
            exit(json_encode(['error'=>1,'info'=>"邮箱或者密码错误"]));
        }
    }
    public function loginout()
    {
       session(null);
        $this->error('退出成功','admin/login/index');
    }

    public function code()
    {
       $config =    [   
            // 验证码位数
            'length'      =>    3,
            'imageH'      =>  50 
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }


}
