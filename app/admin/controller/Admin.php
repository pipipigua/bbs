<?php
namespace app\admin\controller;
use think\Controller;

class Admin extends Common{
	public function index()
	{	
		$where=$tempwhere=input('');
		if(empty($where['username'])){
            unset($where['username']);
        }
        if(!empty($where['cid'])){
        	$where['t1.cid']=$where['cid'];
            unset($where['cid']);
        }
        

        if(!empty($where['start'])){
            $where['t1.ctime'][]=[">=",strtotime($where['start'])];
            
        }

        if(!empty($where['end'])){
            $where['t1.ctime'][]=["<=",strtotime($where['end'])+86400];
           
        }
        unset($where['start']);
        unset($where['end']);
        unset($where['page']);

        
        if(isset($where['t1.ctime']) && count($where['t1.ctime'])==1){
            $where['t1.ctime'] = $where['t1.ctime'][0];
        }

		$admin=db('admin t1')->join('admin_role t2','t1.aid=t2.aid')->join('role t3','t2.roid=t3.roid')->field('t1.*,GROUP_CONCAT(t3.name) name')->group("t1.aid")->where($where)->paginate(5,null,['query'=>$tempwhere]);
		$res=db('admin')->count();


		return $this->fetch('',['res'=>$res,'admin'=>$admin,'where'=>$tempwhere]);
	}
	public function add()
	{	
		$data=db('role')->select();
		return $this->fetch('',['data'=>$data]);
	}

	public function addadmin()
	{
		$data=input('post.');
		// var_dump($data);
		$validate = validate('Username');
		if(!$validate->check($data)){
			exit(json_encode(['error'=>1,'info'=>$validate->getError()]));
			}
		$data['ctime']=time();
		$data['password'] =md5($data['password']);

		
		$res=db('admin')->field('username,phone,password,ctime')->insert($data);
 
		$reg=db('admin')->where('username',$data['username'])->field('aid')->find();


	
		foreach ($data['roid'] as $v) {

			$reg['roid']=$v;

			
			$role=db('admin_role')->insert($reg);
						
		}
		if($res){
				
			exit(json_encode(['error'=>0,'info'=>"添加成功"]));
					
		}else{
			exit(json_encode(['error'=>1,'info'=>"添加失败"]));}

	}
	public function stop()
		{
			$data=input('post.');
			
			$res=db('admin')->where('aid',$data['aid'])->find();

			
			if($res['is_stop']==0){
				$res=db('admin')->where('aid',$data['aid'])->setField('is_stop','1');
				if($res){
				
					exit(json_encode(['error'=>0,'info'=>"停用成功"]));
					}else{
						exit(json_encode(['error'=>1,'info'=>"停用失败
						"]));
				}
			}else{
				$res=db('admin')->where('aid',$data['aid'])->setField('is_stop','0');
				if($res){
				
					exit(json_encode(['error'=>0,'info'=>"启用成功"]));
					}else{
						exit(json_encode(['error'=>1,'info'=>"启用失败"]));				
			}
		}
}

	public function cate()
	{	


		$cate=db('rule_cate')->paginate(5);
		$res=db('rule_cate')->count();
		return $this->fetch('',['res'=>$res,'cate'=>$cate]);
	}
	public function rule()
	{	

		

		$path = "../app/admin/controller/*.php";
		$reg=glob($path);
		foreach ($reg as $v) {
			$con[] =basename($v,".php");

		}
		$way=get_class_methods('app\admin\controller\Admin');
		$res=db('rule')->count();
		$rule=db('rule')->paginate(5);
		$cate=db('rule_cate')->select();
		return $this->fetch('',['res'=>$res,'con'=>$con,'cate'=>$cate,'rule'=>$rule,'way'=>$way]);
	}

	public function ruledel()
	{
		$data=input('post.');
    		
    		$res=db('rule')->where('rid',$data['rid'])->delete();
    		if($res){
    			exit(json_encode(['error'=>0,'info'=>'删除成功']));
    		}else{
    			exit(json_encode(['error'=>1,'info'=>'删除失败']));
    		}
	}
	 public function delallrule()
    {
        $data=input('');

        $res=db('rule')->delete($data['rid']);

        
        if($res){
    
            exit(json_encode(['error'=>0,'info'=>"删除成功"]));

        }else{

            exit(json_encode(['error'=>1,'info'=>"删除失败"]));
        }
        
    }
	public function getaction()
	{
		$c = input('c');

		$class = "app\admin\controller\\".$c;

		$way=get_class_methods($class);

		exit(json_encode(['error'=>0,'data'=>$way]));


	}

	public function ruleadd()
	{
		$data=input('post.');
		// var_dump($data);exit;
		$data['rule']="admin/".$data['contrller']."/".$data['action'];
		$res=db('rule')->insert($data);
		if($res){
    			exit(json_encode(['error'=>0,'info'=>'添加成功']));
    		}else{
    			exit(json_encode(['error'=>1,'info'=>'添加失败']));
    		}
		// var_dump($res);
	}
	public function del()
    	{
    		$data=input('post.');
    		
    		$res=db('admin')->where('aid',$data['aid'])->delete();
    		if($res){
    			exit(json_encode(['error'=>0,'info'=>'删除成功']));
    		}else{
    			exit(json_encode(['error'=>1,'info'=>'删除失败']));
    		}
    	}
    	  public function delall()
    {
        $data=input('');

        $res=db('admin')->delete($data['aid']);

        
        if($res){
    
            exit(json_encode(['error'=>0,'info'=>"删除成功"]));

        }else{

            exit(json_encode(['error'=>1,'info'=>"删除失败"]));
        }
        
    }
    public function add_cate()
    {
    	$data=input('post.');

    	// var_dump($data);

    	$res=db('rule_cate')->insert($data);


        if($res){
    
            exit(json_encode(['error'=>0,'info'=>"添加成功"]));

        }else{

            exit(json_encode(['error'=>1,'info'=>"添加失败"]));
        }

    }
    	public function delcate()
    	{
    		$data=input('post.');
    		// var_dump($data);exit;
    		
    		$res=db('rule_cate')->where('cid',$data['cid'])->delete();
    		if($res){
    			exit(json_encode(['error'=>0,'info'=>'删除成功']));
    		}else{
    			exit(json_encode(['error'=>1,'info'=>'删除失败']));
    		}
    	}
    public function delallcate()
    {
    	$data=input('');

        $res=db('rule_cate')->delete($data['cid']);

        
        if($res){
    
            exit(json_encode(['error'=>0,'info'=>"删除成功"]));

        }else{

            exit(json_encode(['error'=>1,'info'=>"删除失败"]));
        }
    	}
   	public function role()
	{	

	
		$role= db('role')->select();

		foreach ($role as $k => $v) {

			 $rules = db('rule')->where('rid','in',$v['rule'])->column('name');

			 $role[$k]['rules'] = implode(',', $rules);
		}

		$pag=db('role')->paginate(5);

		return $this->fetch('',['role'=>$role,'pag'=>$pag]);
	}
	public function role_rev()
	{	
		$data = input('roid');
		// var_dump($data);
		$rule=db('rule')->select();
		$role=db('role')->where('roid',$data)->find();
		return $this->fetch('',['rule'=>$rule,'role'=>$role
			]);
	}

	public function role_revs()
	{
		$data=input('post.');
		// var_dump($data);

		$get['rule']=implode(",",$data["rid"]);
		// var_dump($get);exit;
		
		$res=db('role')->where('roid',$data['roid'])->update(['rule' => $get['rule']]);


		if($res){
				
			exit(json_encode(['error'=>0,'info'=>"修改成功"]));
					
		}else{
			exit(json_encode(['error'=>1,'info'=>"修改失败"]));}

	}
	public function delrole()
    	{
    		$data=input('post.');
    		// var_dump($data);exit;
    		
    		$res=db('role')->where('roid',$data['roid'])->delete();
    		if($res){
    			exit(json_encode(['error'=>0,'info'=>'删除成功']));
    		}else{
    			exit(json_encode(['error'=>1,'info'=>'删除失败']));
    		}
    	}

}