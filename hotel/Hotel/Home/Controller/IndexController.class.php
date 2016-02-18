<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	if(IS_POST){
    		var_dump(I());
    	}else{	
    		$this->assign("current","index");
        	$this->display();
    	}
    }
}