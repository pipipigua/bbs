<?php
namespace app\index\controller;
use think\Controller;

/**
* 
*/
class Jie extends Common
{
	
	public function index()
	{
		$qid = input('qid');

		db('question')->where('qid',$qid)->setInc('view_num',1);

		$question = db('question t1')->field('t1.*,t2.nickname,t2.face,t3.name cname')->join('user t2','t1.uid=t2.uid')->join('cate t3','t1.cid=t3.cid')->find($qid);
		$question['is_collect'] = db('collect')->where(['qid'=>$qid,'uid'=>session('uid')])->find();

		$answer=db('answer l1')->field('l1.*,l2.nickname,l2.face')->join('user l2','l1.uid=l2.uid')->where('qid',$qid)->select();

		foreach ($answer as $k => $v) {
			$answer[$k]['is_zan'] = db('zan')->where(['aid'=>$v['aid'],'uid'=>session('uid')])->find();
		}
		$cates = db('cate')->select();

		return $this->fetch('',['ques'=>$question,'aws'=>$answer,'title'=>$question['title'],'cates'=>$cates]);
	}
	 public function add()
	{	
		$cates = db('cate')->select();
		$question = model('vercode')->getRandOne();
		return $this->fetch('',['question'=>$question,'cates'=>$cates,'title'=>'发贴']);
	}
	public function upload()
	{
		 $file = request()->file('file');

	    // 移动到框架应用根目录/public/uploads/ 目录下
	    if($file){
	        $info = $file->validate(['size'=>1560000,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');

	        if($info){

	        	$face = getRootPath().'/uploads/'.date('Ymd').'/'. $info->getFilename();

	        	exit(json_encode(['code'=>0,'msg'=>"上传成功",'data'=>['src'=>$face]]));

	        }else{
	            // 上传失败获取错误信息
	            exit(json_encode(['code'=>1,'msg'=>$file->getError()]));
	        }
	    }

    }
	public function addjie()
	{
		$data=input('post.');

		model('vercode')->checkcode($data['vercode']);
		model('kiss')->checkkiss(session('uid'),$data['kiss']);	
		$validate = validate('Jie');
		if(!$validate->check($data)){
		    exit(json_encode(['error'=>1,'info'=>$validate->getError()]));
		}
		unset($data['file']);
		unset($data['vercode']);
		if($data['cid']==1){
			$data['exct'] = json_encode($data['exct']);
		}else{
			unset($data['exct']);
		}
		$data['uid'] = session('uid');

		$data['ctime'] = time();


		$res = db('question')->insert($data);

		$qid = db('question')->getLastInsID();


		if($res){

			db('user')->where('uid', session('uid'))->setDec('kiss', $data['kiss']);

			exit(json_encode(['error'=>0,'info'=>"发布成功",'url'=>url('index/jie/index',array('qid'=>$qid))]));
		}else{

			exit(json_encode(['error'=>1,'info'=>"发布失败"]));
		}
	}

	public function huifu()
	{	
		$data=input('post.');
		$data['uid'] = session('uid');
		$data['ctime'] = time();
		unset($data['file']);
		$res = db('answer')->insert($data);

		if($res){
			db('question')->where('qid',$data['qid'])->setInc('answer_num',1);
			$get=db('answer')->where(['uid'=>session('uid'),'ctime'=>['>',strtotime('-7 days')]])->count('aid');
			db('user')->update(['ret'=>$get,'uid'=>$data['uid']]);
			exit(json_encode(['error'=>0,'info'=>"回复成功",'url'=>url('index/jie/index')]));
		}else{

			exit(json_encode(['error'=>1,'info'=>"回复失败"]));
		}
	}
	public function collect()
	{
		$data['qid']=input('qid');
		$data['uid']=session('uid');
		$res=db('collect')->where($data)->find();
		if ($res) {
			$res=db('collect')->where($data)->delete();
			if($res){
				exit(json_encode(['error'=>0,'info'=>"取消收藏"]));
			}else{

				exit(json_encode(['error'=>1,'info'=>"取消收藏失败"]));
			}

		}else{
			$data['ctime'] = time();
			$res=db('collect')->insert($data);
			if($res){
				exit(json_encode(['error'=>0,'info'=>"收藏成功",]));
			}else{
				exit(json_encode(['error'=>1,'info'=>"收藏失败"]));
			}
		}

	}
	public function zan()
	{
		$data['aid']=input('aid');
		$data['uid']=session('uid');
		$res=db('zan')->where($data)->find();
		if ($res) {
			$res=db('zan')->where($data)->delete();
			db('answer')->where('aid', $data['aid'])->setDec('zan_num');
			if($res){
				exit(json_encode(['error'=>0,'info'=>"取消赞"]));
			}else{

				exit(json_encode(['error'=>1,'info'=>"取消赞失败"]));
			}

		}else{
			$data['ctime'] = time();
			$res = db('zan')->insert($data);
			db('answer')->where('aid', $data['aid'])->setInc('zan_num');
			if($res){
				exit(json_encode(['error'=>0,'info'=>"赞成功",]));
			}else{
				exit(json_encode(['error'=>1,'info'=>"赞失败"]));
			}
		}


	}
}
