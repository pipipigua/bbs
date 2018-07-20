<?php 

namespace app\index\validate;

use think\Validate;

class User extends Validate
{	

    protected $rule = [
        'nickname'  => 'require|length:2,10',
        'email' =>  'email|unique:user',
        'repassword'=>'require|confirm:password'
    ];
  	 protected	$message = [
    'nickname.require'=> '名称必须',
    'nicknamename.length' => '名称2到10个字符',
    'email.email'=> '邮箱格式错误',
    'email.unique' => '邮箱已注册',
    'repassword'=> '两次密码不一致',
    ];
    protected $scene = [
        'edit'  =>  ['repassword'],
    ];

}
/**
* 
*/
 ?>