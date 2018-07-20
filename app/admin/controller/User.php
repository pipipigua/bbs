<?php
namespace app\admin\controller;
use think\Controller;
class User extends Common{
	public function index()
	{	

        $where=$tempwhere=input('');
        if(empty($where['nickname'])){
            unset($where['nickname']);
        }
        // if(!empty($where['cid'])){
        //     $where['t1.cid']=$where['cid'];
        //     unset($where['cid']);
        // }
        

        if(!empty($where['start'])){
            $where['ctime'][]=[">=",strtotime($where['start'])];
            
        }

        if(!empty($where['end'])){
            $where['ctime'][]=["<=",strtotime($where['end'])+86400];
           
        }
        unset($where['start']);
        unset($where['end']);
        unset($where['page']);

        
        if(isset($where['ctime']) && count($where['ctime'])==1){
            $where['ctime'] = $where['ctime'][0];
        }

		$user=db('user')->where($where)->paginate(5);
        $res=db('user')->count();

		return $this->fetch('',['res'=>$res,'user'=>$user,'where'=>$tempwhere]);
	}
	    public function revise()
    {
    	$data = input('uid');
    	

    	$res= db('user')->where('uid',$data)->find();

		return $this->fetch('',['res'=>$res]);
    }

   public function alter()
    {	

    	$data = input('post.');

    	$validate = validate('user');

    	

		if(!$validate->check($data)){
			exit(json_encode(['error'=>1,'info'=>$validate->getError()]));
		}else{

			unset($data['repassword']);
			// var_dump($data);
			$data['password']=md5($data['password']);

			$res=db('user')->where('uid',$data['uid'])->update($data);
			
			exit(json_encode(['error'=>0,'info'=>'修改成功']));
		}
	}
    	public function del()
    	{
    		$data=input('post.');

    		$res=db('user')->where('uid',$data['uid'])->delete();
    		if($res){
    			exit(json_encode(['error'=>0,'info'=>'删除成功']));
    		}else{
    			exit(json_encode(['error'=>1,'info'=>'删除失败']));
    		}
    	}
    public function add()
    {   
        $data=db('user')->select();
        return $this->fetch('',['data'=>$data]);
    }
    public function adduser()
    {
        $data=input('post.');
        // var_dump($data);
        $validate = validate('User');
        if(!$validate->check($data)){
            exit(json_encode(['error'=>1,'info'=>$validate->getError()]));
            }
        $data['ctime']=time();
        $data['password'] =md5($data['password']);

        unset($data['repassword']);
        $res=db('user')->insert($data);


        if($res){
                
            exit(json_encode(['error'=>0,'info'=>"添加成功"]));
                    
        }else{
            exit(json_encode(['error'=>1,'info'=>"添加失败"]));}

    }
    public function delall()
    {
        $data=input('');

        $res=db('user')->delete($data['uid']);

        
        if($res){
    
            exit(json_encode(['error'=>0,'info'=>"删除成功"]));

        }else{

            exit(json_encode(['error'=>1,'info'=>"删除失败"]));
        }
        
    }
}