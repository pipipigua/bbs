<?php 
namespace app\common\validate;

use think\Validate;

class User extends Validate
{	

    protected $rule = [
        'nickname'  => 'require|length:3,12',
        'email' =>  'email|unique:user',
        'repassword'=>'require|confirm:password'
    ];
  	 protected	$msg = [
        'nickname.require'=> '名称必须',
        'nicknamename.length' => '名称3到12个字符',
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