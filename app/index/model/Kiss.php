<?php 
namespace app\index\model;

use think\Model;

class Kiss extends Model
{

	public function checkkiss($uid,$kiss)
	{
		$res = db('user')->field('kiss')->find($uid);

		if($res['kiss']<$kiss){
			exit(json_encode(['error'=>1,'info'=>'飞吻不够，请先赚飞吻']));
		}
	}
}

 ?>