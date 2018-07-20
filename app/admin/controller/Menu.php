<?php
namespace app\admin\controller;
use think\Controller;

class Menu extends Common{

	public function run()
	{
		$start = strtotime(date("Y-m-d",strtotime("-1 days")));
		$end = strtotime(date("Y-m-d"));



	}

	public function menu1()
	{
		$data=db('report')->order('dateline desc')->limit(7)->select();
		$dataDate='';
		$dataNum='';
        foreach ( $data as $key => $row) {
            
            $dataDate .= "'".$row['dateline']."',";
            $dataNum .= $row['reg_num'].",";
        }

        $dataDate = rtrim($dataDate,',');

        $dataNum = rtrim($dataNum,',');
    	

        return $this->fetch('',['dataDate'=>$dataDate,'dataNum'=>$dataNum]);
    }
	
	    public function userajax()
    {
        $data = db('report')->order('dateline desc')->limit(7)->select();
        // var_dump($data);

        exit(json_encode(['error'=>0,'data'=>$data]));
    }
}