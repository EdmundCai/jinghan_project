<?php
namespace Home\Controller;
use Think\Controller;
class GoalsController extends BaseController {
    /**
     * @return [type]
     * @param [string] $cursor 用于左侧栏标识当前选中的页面
     * @param [string] $tap 用于左侧栏标识当前展开的一级菜单
     */
    public function index(){
    	switch(I('type')){
    		case 'mission':
    		
    		$this->assign("data","这里是协会使命");
            $this->assign('cursor','mission');
    		break;

    		case 'rules':
    		$this->assign("data","这里是协会章程");
    		$this->assign('cursor','rules');
    		break;

    		case 'joinUs':
    		$this->assign("data","这里是加入我们");
    		$this->assign('cursor','joinUs');
    		break;

            // 以下是协会会员列表的三级内容
            case 'chairman':
            $this->assign("data","这里是会长单位");
            $this->assign('cursor','chairman');
            $this->assign('tap','member');
            break;

            case 'vice_chairman':
            $this->assign("data","这里是副会长单位");
            $this->assign('cursor','vice_chairman');
            $this->assign('tap','member');
            break;
            
            case 'st_council':
            $this->assign("data","这里是常务理事单位");
            $this->assign('cursor','st_council');
            $this->assign('tap','member');
            break;

            case 'council':
            $this->assign("data","这里是理事单位");
            $this->assign('cursor','council');
            $this->assign('tap','member');
            break;

            case 'member':
            $this->assign("data","这里是会员单位");
            $this->assign('cursor','member');
            $this->assign('tap','member');
            break;

    		default:break;
    	}
    	
    	$this->display();
    }
}