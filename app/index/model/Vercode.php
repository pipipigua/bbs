<?php 

namespace app\index\model;

use think\Model;

class Vercode extends Model
{

	public function getRandOne()
	{
		$sql="select * from vercode order by rand() limit 1";
		$data=$this->query($sql);
		$data=current($data);
		session('answer',$data['answer']);
		return $data['question'];
	}

    public function checkcode($code)
    {	
    	
    	if (session('answer')!=$code) {
    		exit(json_encode(['error'=>1,'info'=>'验证码错误']));
    	}
    	

    }

}

 ?>