<?php 
namespace app\common\validate;
use think\Validate;
class Username extends Validate
{
    protected $rule = [
       
    	'username' =>  'unique:admin',
    ];
    protected   $msg = [
     	
    	'username.unique' => '用户已被注册',
    ];
}