<?php 
namespace app\common\validate;

use think\Validate;

class Jie extends Validate
{
    protected $rule = [
        'cid'  =>  'require',
        'title'=>  'require|length:2,60',
        'content' =>  'require|min:10',
        'kiss'=>'require'
    ];

    protected $message = [
	    'cid.require' => '分类必须',
        'title.require' => '标题必须',
        'content.require' => '内容必须',
        'kiss.require' => '飞吻必须',
	    'title.length'  => '标题不能超过2-60个字符',
	    'content.min'  => '最少10'
	];

}
 ?>