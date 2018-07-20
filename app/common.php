<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function getRootPath(){

	return rtrim(dirname(rtrim(str_replace($_SERVER['HTTP_HOST'],'',explode('.php',$_SERVER['PHP_SELF'])[0].'.php'),'/')),'/');
}

function Ftime($time)
{
	$diff = time()-$time;

	if($diff<60){
		return "刚刚";
	}elseif($diff<3600){
		return ceil($diff/60)."分钟之前";
	}elseif($diff<86400){
		return ceil($diff/3600)."小时之前";
	}elseif($diff<(86400*7)){
		return ceil($diff/86400)."天之前";
	}else{
		return date('Y-m-d H:i:s',$time);
	}
}

function sendmail(){


	Vendor('phpmailer.PHPMailerAutoload', '.php');
	// require_once '../vendor/phpmailer/PHPMailerAutoload.php';
	$mail = new \PHPMailer(true); 
	var_dump($mail);
}
function get_client_ip($type = 0,$adv=false) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($adv){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

