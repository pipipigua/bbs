<?php
namespace app\index\controller;
use think\Controller;
use weixin\Weixin;

class Common extends Controller
{
    public function _initialize()
    {

        $auth_rule = config('auth_rule');

        $rule = strtolower(request()->module()).'/'.strtolower(request()->controller()).'/'.strtolower(request()->action());

        if(in_array($rule, $auth_rule)){

        	if(!session('uid')){

        		if(request()->isAjax()){
        			exit(json_encode(['error'=>1,'info'=>'请先登录']));
        		}else{
        			$this->error('请先登录',url('index/login/index'));
        		}
        	}
        }
    }
}
