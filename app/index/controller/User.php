<?php
namespace app\index\controller;
use think\Controller;


class User extends Common{
	public function index()
	{
		$uid=input('uid');
		$user =db('user')->find($uid);

		$ques = db('question')->where(['uid'=>$uid,'is_del'=>0])->select();

		$answer=db('answer l1')->field('l1.*,l2.title,l2.cid,l3.cid,l3.name')->join('question l2','l1.qid=l2.qid')->join('cate l3','l3.cid=l2.cid')->where('l1.uid',$uid)->limit(5)->select();


		return $this->fetch('',['title'=>session('nickname')."的个人主页",'ques'=>$ques,'user'=>$user,'answer'=>$answer]);

	}
	public function del()
	{
		$data=input('post.');
		// var_dump($data);
		$res=db('question')->where('qid',$data['qid'])->setField('is_del','1');
		if ($res) {
			exit(json_encode(['error'=>0,'info'=>"删除成功"]));
		}else{
			exit(json_encode(['error'=>1,'info'=>"删除失败"]));
		}
	}

}