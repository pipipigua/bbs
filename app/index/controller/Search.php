<?php
namespace app\index\controller;
use \think\Controller;
use \app\index\model\Vercode;
class Search extends Common
{
    public function index()
    {	
    	$p = input('q');

		if($p){

		vendor('pscws4.pscws4','.class.php');

		// 建立分词类对像, 参数为字符集, 默认为 gbk, 可在后面调用 set_charset 改变
		$pscws = new \PSCWS4('utf8');

		//
		// 接下来, 设定一些分词参数或选项, set_dict 是必须的, 若想智能识别人名等需要 set_rule 
		//
		// 包括: set_charset, set_dict, set_rule, set_ignore, set_multi, set_debug, set_duality ... 等方法
		// 
		$pscws->set_dict('./static/dict.utf8.xdb');
		// $pscws->set_rule('/path/to/etc/rules.ini');

		// 分词调用 send_text() 将待分词的字符串传入, 紧接着循环调用 get_result() 方法取回一系列分好的词
		// 直到 get_result() 返回 false 为止
		// 返回的词是一个关联数组, 包含: word 词本身, idf 逆词率(重), off 在text中的偏移, len 长度, attr 词性
		//

		$pscws->send_text($p);
		// while ($some = $pscws->get_result())
		// {
		//    foreach ($some as $word)
		//    {
		//       var_dump($word);
		//    }
		// }

		// 在 send_text 之后可以调用 get_tops() 返回分词结果的词语按权重统计的前 N 个词
		// 常用于提取关键词, 参数用法参见下面的详细介绍.
		// 返回的数组元素是一个词, 它又包含: word 词本身, weight 词重, times 次数, attr 词性
		$tops = $pscws->get_tops(3, 'n,v');

		$where = [];

		if($tops){

			if(count($tops)>1){
				foreach ($tops as $row) {
					$where['title'][] = ['like',"%".$row['word']."%"];
				}
			}else{
				$where['title'] = ['like',"%".$tops[0]['word']."%"];
			}

		}else{
			$where['title'] = ['like',"%".$p."%"];
		}

		// var_dump($where);

			$Arr = db('question t1')->join('user t2','t1.uid=t2.uid')->join('cate t3','t1.cid=t3.cid')->field('t1.*,t2.nickname,t2.face,t3.name cname')->where($where)->select();


			// var_dump($Arr);


			foreach ($Arr as $k=>$v) {
				$reh = db('user')->where('uid',$v['uid'])->find();
				$Arr[$k]['nickname']=$reh['nickname'];
			}
			$cates = db('cate')->select();
			return $this->fetch('',['Arr'=>$Arr,'p'=>$p,'title'=>'搜索'.$p,'cates'=>$cates]);
		}else{
			$cates = db('cate')->select();
		 	return $this->fetch('',['Arr'=>[],'p'=>$p,'title'=>'搜索','cates'=>$cates]);
		}
    }
}