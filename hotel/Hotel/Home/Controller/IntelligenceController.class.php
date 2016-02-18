<?php
namespace Home\Controller;
use Think\Controller;
class IntelligenceController extends Controller {
    public function index(){
    	switch(I('type')){
    		case 'monthly':
    		
    		$this->assign("data","这里是月度经营情报");
            $this->assign('cursor','monthly');
    		break;

    		case 'yearly':
    		$this->assign("data","这里是年度经营情报");
    		$this->assign('cursor','yearly');
    		break;

    		case 'gjh':
    		$this->assign("data","这里是广交会经营情报");
    		$this->assign('cursor','gjh');
    		break;

    		case 'upload':
    		$this->assign("data","这里是经营情报上传");
    		$this->assign('cursor','upload');
    		break;

    		default:break;
    	}
    	
    	$this->display();
    }
}