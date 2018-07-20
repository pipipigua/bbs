<?php
namespace app\admin\controller;
use think\Controller;

class Jie extends Common{
	public function index()
	{	

		$where=$tempwhere=input('');
		if(empty($where['nickname'])){
            unset($where['nickname']);
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

		$ques = db('question t1')->join('user t2','t1.uid=t2.uid')->join('cate t3','t1.cid=t3.cid')->field('t1.*,t2.nickname,t2.face,t3.name cname')->where($where)->paginate(5,null,['query'=>$tempwhere]);
		$res=db('question')->count();
		$cates = db('cate')->select();

		return $this->fetch('',['res'=>$res,'cates'=>$cates,'ques'=>$ques,'where'=>$tempwhere]);
	}
	public function top()
	{
		$data=input('post.');
		$ques = db('question')->find($data['qid']);
		if ($ques['is_top']) {
			$data['is_top']=0;

		}else{
			$data['is_top']=1;
		}
		$res=db('question')->update($data);
		if($res){
	
		exit(json_encode(['error'=>0,'info'=>"操作成功"]));
		}else{
			exit(json_encode(['error'=>1,'info'=>"操作失败"]));
		}


	}
		public function jing()
	{
		$data=input('post.');
		$ques = db('question')->find($data['qid']);
		if ($ques['is_jing']) {
			$data['is_jing']=0;

		}else{
			$data['is_jing']=1;
		}
		$res=db('question')->update($data);
		if($res){
	
		exit(json_encode(['error'=>0,'info'=>"操作成功"]));
		}else{
			exit(json_encode(['error'=>1,'info'=>"操作失败"]));
		}


	}
	public function del()
    	{
    		$data=input('post.');
    		
    		$res=db('question')->where('qid',$data['qid'])->delete();
    		if($res){
    			exit(json_encode(['error'=>0,'info'=>'删除成功']));
    		}else{
    			exit(json_encode(['error'=>1,'info'=>'删除失败']));
    		}
    	}
	public function delall()
	{
		$data=input('');

		$res=db('question')->delete($data['qid']);

		
		if($res){
	
			exit(json_encode(['error'=>0,'info'=>"删除成功"]));

		}else{

			exit(json_encode(['error'=>1,'info'=>"删除失败"]));
		}
		
	}

 }
