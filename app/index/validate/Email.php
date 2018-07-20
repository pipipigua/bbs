<?php 

namespace app\index\validate;

use think\Validate;


class Email extends Validate
{
    protected $rule = [
       
    	'email' =>  'email|unique:user',
    ];
    protected   $message = [
     	'email.email'=> '邮箱格式错误',
    	'email.unique' => '邮箱已注册',
    ];
}

/**
* 
*/


 ?>