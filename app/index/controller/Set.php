<?php
namespace app\index\controller;
use \think\Controller;
use think\Model;
use \app\index\model\Vercode;
class Set extends Common
{
    public function index()
    {	
    	$user=db('user')->find(session('uid'));
    	return $this->fetch('',['user'=>$user,'title'=>'基本设置']);
    }

    public function revise()
    {
    	$data = input('post.');
    	// var_dump($data);
    	$validate = validate('Email');
    	// var_dump($validate);
    	$data['uid']=session('uid');

	  	if(!$validate->check($data)){
			exit(json_encode(['error'=>1,'info'=>$validate->getError()]));
		}else{
			$res=db('user')->where('uid',session('uid'))->update($data);
			session('nickname',$data['nickname']);
			exit(json_encode(['error'=>0,'info'=>'修改成功']));
		}
    	// var_dump($res);
    }
    public function ps()
    {	$data = input('post.');

    	$validate = validate('user');

		$result = $validate->scene('edit')->check($data);

		if(!$validate->check($data)){
			exit(json_encode(['error'=>1,'info'=>$validate->getError()]));
		}else{

			unset($data['repassword']);
			// var_dump($data);
			$data['password']=md5($data['password']);

			$res=db('user')->where('uid',session('uid'))->update($data);
			
			exit(json_encode(['error'=>0,'info'=>'修改成功']));
		}
    	

    }
    public function upload()
    {
    	    // 获取表单上传文件 例如上传了001.jpg
    $file = request()->file('file');
    
    // 移动到框架应用根目录/public/uploads/ 目录下
    if($file){
        $info = $file->validate(['size'=>150000,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){ 
            $face="uploads/".date('Ymd').'/'.$info->getFilename();
            $data['face']=$face;
            $data['uid']= session('uid');
            $res=db('user')->update($data);
            
            if($res){
            	session('face',$face);
            	exit(json_encode(['code'=>0,'info'=>'上传成功']));
            }
            echo $info->getFilename(); 

        }else{
            exit(json_encode(['error'=>1,'info'=>'修改成功']));
            echo $file->getError();
        }
    }
    }
        public function xiaoxi()
    {   
        $user=db('user')->find(session('uid'));
        return $this->fetch('',['user'=>$user,'title'=>'我的消息']);
    }
        public function user()
    {   

        $ques = db('question')->where(['uid'=>session('uid'),'is_del'=>0])->select();

        $collect = db('collect t1')->join('question t2' ,'t1.qid=t2.qid')->where(['t1.uid'=>session('uid'),'is_del'=>0])->select();

        // var_dump($collect);


        return $this->fetch('',['ques'=>$ques,'collect'=>$collect,'title'=>'用户中心']);
    }
     public function test()
    { 

        $html = <<<xbs
<div><br></div><div><includetail><style>.mmsgLetter{width:580px;margin:0 auto;padding:10px;color:#333;background:#fff;border:0px solid#aaa;border:1px solid#aaa\9;border-radius:5px;-webkit-box-shadow:3px 3px 10px#999;-moz-box-shadow:3px 3px 10px#999;box-shadow:3px 3px 10px#999;font-family:Verdana,sans-serif}.mmsgLetter a:link,.mmsgLetter a:visited{color:#407700}.mmsgLetterContent{text-align:left;padding:30px;font-size:14px;line-height:1.5;background:url(http:.mmsgLetterContent h3{color:#000;font-size:20px;font-weight:bold;margin:20px 0 20px;border-top:2px solid#eee;padding:20px 0 0 0;font-family:"微软雅黑","黑体","Lucida Grande",Verdana,sans-serif}.mmsgLetterContent p{margin:20px 0;padding:0}.mmsgLetterContent.salutation{font-weight:bold}.mmsgLetterContent.mmsgMoreInfo{}.mmsgLetterContent a.mmsgButton{display:block;float:left;height:40px;text-decoration:none;text-align:center;cursor:pointer}.mmsgLetterContent a.mmsgButton span{display:block;float:left;padding:0 25px;height:40px;line-height:36px;font-size:14px;font-weight:bold;color:#fff;text-shadow:1px 0 0#235e00}.mmsgLetterContent a.mmsgButton:link,.mmsgLetterContent a.mmsgButton:visited{background:#338702 url(http:.mmsgLetterContent a.mmsgButton:link span,.mmsgLetterContent a.mmsgButton:visited span{background:url(http:.mmsgLetterContent a.mmsgButton:hover,.mmsgLetterContent a.mmsgButton:active{background:#338702 url(http:.mmsgLetterContent a.mmsgButton:hover span,.mmsgLetterContent a.mmsgButton:active span{background:url(http:.mmsgLetterInscribe{padding:40px 0 0}.mmsgLetterInscribe.mmsgAvatar{float:left}.mmsgLetterInscribe.mmsgName{margin:0 0 10px}.mmsgLetterInscribe.mmsgSender{margin:0 0 0 54px}.mmsgLetterInscribe.mmsgInfo{font-size:12px;margin:0;line-height:1.2}.mmsgLetterHeader{height:23px;background:url(http:.mmsgLetterFooter{margin:16px;text-align:center;font-size:12px;color:#888;text-shadow:1px 0px 0px#eee}.mmsgLetterClr{clear:both;overflow:hidden;height:1px}.mmsgLetterUser{padding:10px 0}.mmsgLetterUserItem{padding:0 0 20px 0}.mmsgLetterUserAvatar{height:40px;border:1px solid#ccc;padding:2px;display:block;float:left}.mmsgLetterUserAvatar img{width:40px;height:40px}.mmsgLetterInfo{margin-left:48px}.mmsgLetterName{display:block;color:#5fa207;font-weight:bold;margin-left:10px}.mmsgLetterDesc{font-size:12px;float:left;height:43px;background:url(http:.mmsgLetterDesc div{white-space:nowrap;float:left;height:43px;padding:0 20px;line-height:40px;background:url(http:.mmsgLetterUser{}.mmsgLetterAvatar{float:left}.mmsgLetterInfo{margin:0 0 0 60px}.mmsgLetterNickName{font-size:14px;font-weight:bold}.mmsgLetterUin{font-size:12px;color:#666}</style><div style="background-color:#d0d0d0;background-image:url(http://weixin.qq.com/zh_CN/htmledition/images/weixin/letter/mmsgletter_2_bg.png);text-align:center;padding:40px;"><div class="mmsgLetter"style="width:580px;margin:0 auto;padding:10px;color:#333;background-color:#fff;border:0px solid #aaa;border-radius:5px;-webkit-box-shadow:3px 3px 10px #999;-moz-box-shadow:3px 3px 10px #999;box-shadow:3px 3px 10px #999;font-family:Verdana, sans-serif; "><div class="mmsgLetterHeader"style="height:23px;background:url(http://weixin.qq.com/zh_CN/htmledition/images/weixin/letter/mmsgletter_2_bg_topline.png) repeat-x 0 0;"></div><div class="mmsgLetterContent"style="text-align:left;padding:30px;font-size:14px;line-height:1.5;background:url(http://weixin.qq.com/zh_CN/htmledition/images/weixin/letter/mmsgletter_2_bg_wmark.jpg) no-repeat top right;"><div><p class="salutation"style="font-weight:bold;">Hi,<span id="mailUserName">gh_55f932e8ba0c</span>：</p><p>忘记微信密码了吗？别着急，请点击以下链接，我们协助您找回密码：<br><a href="https://mp.weixin.qq.com/acct/resetpwd?action=set_pwd_page&amp;verify_key=mmverifycodebrokeremail_1_mp_acct_reset_pwd_email_6c595fce8736a81c3ed04d7fb7058257&amp;email=MTEzNjY0MDAwQHFxLmNvbQ%3D%3D"style="word-break:break-all;word-wrap:break-word;display:block">https:</div><div class="mmsgLetterInscribe"style="padding:40px 0 0;"><img class="mmsgAvatar"src="http://weixin.qq.com/zh_CN/htmledition/images/weixin/letter/mmsgletter_2_avatar_01.png"style="float:left;"><div class="mmsgSender"style="margin:0 0 0 54px;"><p class="mmsgName"style="margin:0 0 10px;">Claire Wang</p><p class="mmsgInfo"style="font-size:12px;margin:0;line-height:1.2;">微信产品经理<br><a href="mailto:claire1023@qq.com"style="color:#407700;">claire1023@qq.com</a></p></div></div></div><div class="mmsgLetterFooter"style="margin:16px;text-align:center;font-size:12px;color:#888;text-shadow:1px 0px 0px #eee;"></div></div></div></includetail></div>
xbs;


        $this->sendmail('6955044@qq.com','测试abc',$html);
    }


    public function sendmail($email,$title,$body)
    {

        vendor('phpmailer.PHPMailerAutoload', '.php');

        $mail = new \PHPMailer(true);
        // Passing `true` enables exceptions
            //Server settings
            // $mail->SMTPDebug = 2;   
        try {  
            $mail->CharSet = "GB2312";                                                 
        // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.qq.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'shilive@foxmail.com';                 // SMTP username
            $mail->Password = 'jnvlkohdurbvbdig';                           // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                                    // TCP port to connect to

            $mail->CharSet = "GB2312";

            //Recipients
            // $nickname = "=?UTF-8?B?".base64_encode('学并思')."?=";

            $mail->setFrom('shilive@foxmail.com',iconv("UTF-8","GB2312//IGNORE","学并思"));

            $mail->addAddress($email);

            $mail->isHTML(true);

            $mail->Subject = iconv("UTF-8","GB2312//IGNORE",$title);

            $mail->Body    = $body;

            $mail->Body    = iconv("UTF-8","GB2312//IGNORE",$body) ;

            $res = $mail->send();


        } catch (Exception $e) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
    }
    
}
