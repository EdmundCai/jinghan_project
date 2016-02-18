<?php
namespace Home\Controller;
use Think\Controller;
class NewsController extends Controller {
    public function index(){
    	switch(I('type')){
    		case 'news':
    		$this->assign('cursor','news');
    		$this->assign("data","这里是行业资讯");
    		break;

    		case 'activity':
    		$this->assign("data","这里是协会活动");
    		$this->assign('cursor','activity');
    		break;

    		case 'notice':
    		$this->assign("data","这里是最新通知");
    		$this->assign('cursor','notice');
    		break;

    		case 'meeting':
    		$this->assign("data","这里是会展会议");
    		$this->assign('cursor','meeting');
    		break;

    		default:break;
    	}
        $this->assign('tap','news');
    	$this->display();
    }

    public function book(){
        switch(I('type')){
            case 'publication':
            // $this->assign("data","这里是协会刊物");
                if(I('choose') == 'special'){
                    $this->assign('choose','special');
                    $this->assign("data","现在是专业会刊页面");
                }else if(I('choose') == 'download'){
                    $this->assign('choose','download');
                    $this->assign("data","现在是会刊下载页面");
                }else{
                    $this->assign('choose','article');
                    $this->assign("data","现在是精选文摘页面");
                }
            $this->assign('cursor','publication');
            break;

            case 'brief':
                $this->assign("data","这里是每月简报");
                $this->assign('cursor','brief');
            break;

            default:break;
        }

        //如果是专业会看
        $this->assign('tap','publication');
        $this->display();
    }
}