<?php
namespace app\index\controller;
use \think\Controller;
class Index extends Common
{
    public function index()
    {	
    	$cates = db('cate')->select();
    	$where=input('');
    	if (isset($where['cid'])) {
    		$where['t1.cid']=$where['cid'];
    		unset($where['cid']);
    	}
    	unset($where['page']);
    	if(!$where){
    		$top  = db('question t1')->field('t1.*,t2.nickname,t2.face,t3.name cname')->join('user t2','t1.uid=t2.uid')->join('cate t3','t1.cid=t3.cid')->where('is_top','1')->limit(5)->select();
    		// $where['is_top']= '0';

    		$ques = db('question t1')->field('t1.*,t2.nickname,t2.face,t3.name cname')->join('user t2','t1.uid=t2.uid')->join('cate t3','t1.cid=t3.cid')->where($where)->paginate(4);
    	}else{
    		$top = [];
    		$ques = db('question t1')->field('t1.*,t2.nickname,t2.face,t3.name cname')->join('user t2','t1.uid=t2.uid')->join('cate t3','t1.cid=t3.cid')->where($where)->order('is_top desc')->paginate(4);
    	}
        $ret=db('user')->order('ret desc')->limit(20)->select();
        $hot=db('question')->order('answer_num desc')->where(['ctime'=>['>',strtotime('-7 days')]])->limit(15)->select();

        $signin= db('signin')->where(['uid'=>session('uid'),'dateline'=>date("Y-m-d")])->find(); 
        $new=db('signin t1')->join('user t2','t1.uid=t2.uid')->field('t1.*,t2.nickname,t2.face')->where(['t1.dateline'=>date("Y-m-d")])->order('sid desc')->limit(10)->select();
        $asc=db('signin t1')->join('user t2','t1.uid=t2.uid')->field('t1.*,t2.nickname,t2.face')->where(['t1.dateline'=>date("Y-m-d")])->order('sid asc')->limit(10)->select();

        $day=0 ;
        $kiss=0;
        
        for ($i=0; $i <=30 ; $i++) { 
            $date = date("Y-m-d",strtotime('-'.$i.' days'));
             $sign= db('signin')->where(['uid'=>session('uid'),'dateline'=>$date])->find();
            if($sign['dateline']==$date){
                $day++;
            }else{
                break;
            }



            if ($day<5) {
                $kiss=5;
            } elseif($day>5&&$day<15) {
               
                $kiss=10;
             
            } else{
                $kiss=20;
            }
        }
       
        $sigin_time=date("Y-m-d");
        $res=db('user')->where(['uid'=>session('uid')])->update(['day' => $day,'sigin_time'=>$sigin_time]);
        
        $con=db('user')->where(['sigin_time'=>$sigin_time])->select();
        
        
    	return $this->fetch('',['ques'=>$ques,'top'=>$top,'cates'=>$cates,'title'=>'首页','ret'=>$ret,'hot'=>$hot, 'signin'=>$signin,'day'=>$day,'kiss'=>$kiss,'new'=>$new,'asc'=>$asc,'con'=>$con]);
    }
    public function signin()
    {  

        $signin; 
        $data['uid']=session('uid');
        $data['dateline']=date("Y-m-d");
        $data['time']=date("Y-m-d h:i:s");
        $signin['is_signin']=db('signin')->where(['uid'=>session('uid'),'dateline'=>$data['dateline']]);

        $reg=db('signin')->insert($data);
        if($reg){
                exit(json_encode(['error'=>0,'info'=>"签到成功",]));
            }else{
                exit(json_encode(['error'=>1,'info'=>"签到失败"]));       
        }

        $day=0;

        for ($i=0; $i <=30 ; $i++) { 
            $date = date("Y-m-d",strtotime('-'.$i.' days'));
             $sign= db('signin')->where(['uid'=>session('uid'),'dateline'=>$date])->find();
            if($sign['dateline']==$date){
                $day++;
            }else{
                break;
            }
        }

        $kiss = 0;

        if ($day<5) {
            $kiss=5;
        } elseif($day>5&&$day<15) {
           
            $kiss=10;
        
        } else{
            $kiss=20;
        }

        $kiss=db('user')->where(['uid'=>session('uid')])->setInc('kiss', $kiss);

    }


}
?>