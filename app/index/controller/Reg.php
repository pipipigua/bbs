<?php
namespace app\index\controller;
use \think\Controller;
use \app\index\model\Vercode;
class Reg extends Common
{
    public function index()
    {	

    	if (session('uid')) {
           $this->error('请先退出登录','index/index/index');
        }
    	$question=model('vercode')->getRandOne();

      	return $this->fetch('',['question'=>$question,'title'=>'注册']);

    } 
    public function checkdata()
    {
    	$data =input('post.');
        
    	model('vercode')->checkcode($data['vercode']);
    	// var_dump($data);
    	$validate = validate('User');
    	// var_dump($validate);
	  	if(!$validate->check($data)){
			exit(json_encode(['error'=>1,'info'=>$validate->getError()]));
			}
		
		unset($data['repassword']);
		unset($data['vercode']);
		$data['ctime']=time();
		$data['password'] =md5($data['password']);
		$res = db('user')->insert($data);

		if ($res) {
			exit(json_encode(['error'=>0,'info'=>"注册成功"]));
		}else{
			exit(json_encode(['error'=>1,'info'=>"注册失败"]));
		}

    }
       public function getCity()
    {
        $ip = get_client_ip();

        // $ip =  "121.33.62.220";

        $url = "http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;

        $data = file_get_contents($url);


        $data = json_decode($data,true);


        if($data['code']==0){
            return $data['data']['city'];
        }
    }

}