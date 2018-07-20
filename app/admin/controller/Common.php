<?php
namespace app\admin\controller;
use think\Controller;
use auth\Auth; 
class Common extends Controller
{	
	public function _initialize()
	{
		// $aid=session('aid');
		// $rule = strtolower(request()->module()).'/'.strtolower(request()->controller()).'/'.strtolower(request()->action());

		// $auth = new Auth();
		
		// $res= $auth->check($aid,$rule);//有权限返回TRUE
		// // var_dump($res);
		// 	if(!$res){
  //       		if(request()->isAjax()){
  //       			exit(json_encode(['error'=>1,'info'=>'权限不够，如需访问请通知超级管理员']));
  //       		}else{
  //       			echo "权限不够，如需访问请通知超级管理员";exit;
  //       		}
  //       	}


	}




}