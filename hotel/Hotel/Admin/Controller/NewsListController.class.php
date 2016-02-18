<?php
namespace Admin\Controller;
use Think\Controller;
class NewsListController extends Controller {

    public $newsList;
    public $activityList;
    public function __construct(){
        parent::__construct();
        $this->newsList=D('NewsList');
        // $this->activityList = M('ActivityList');
    }
    
/**
 * [newsList 行业资讯列表]
 * @return [type] [description]
 */
	public function newsList($type = ''){
       if($type == ''){
            $this->error("获取id失败！");
       }
        $result = $this->newsList->showNews($type);
        // print_r($result);exit;
		if($result){
            $this->assign('result',$result);
        }else{
            $this->assign('empty',"<tr><td colspan='7' style='text-align:center;'>暂时没有新闻！</td></tr>");
        }
		$this->display();
	}    
	//添加行业资讯
	public function addNews(){
        if(IS_POST){
            $data=I();
            if($data['title']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写标题！'));
            }
            if($data['details']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写新闻详情！'));
            }
            // if($data['date']==''){
            //     $this->ajaxReturn(array('status'=>0,'message'=>'请填写新闻日期！'));
            // }
            $result = $this->newsList->addN($data);
            // print_r($result);
            if($result){
                $this->ajaxReturn(array('status'=>1, 'message'=>'通知公告添加成功！')); 
            }else{
                $this->ajaxReturn(array('status'=>0, 'message'=>'通知公告添加失败！')); 
            }
        }else{
            $this->display();
        }
	}
	//编辑行业资讯
	public function editNews($id=''){
		if (IS_POST) {//获取信息
            $data = I();
            if($id == ''){
                $this->ajaxReturn(array('status'=>0,'message'=>'获取新闻id失败！'));
            }
            if($data['title']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写标题！'));
            }
            if($data['details']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写新闻详情！'));
            }
            // if($data['date']==''){
            //     $this->ajaxReturn(array('status'=>0,'message'=>'请填写新闻日期！'));
            // }
            $result = $this->newsList->editN($id,$data);
            if($result){
                $this->ajaxReturn(array('status'=>1, 'message'=>'通知公告修改成功！')); 
            }else{
                $this->ajaxReturn(array('status'=>0, 'message'=>'通知公告修改失败！')); 
            }
        }else{//
            $result = $this->newsList->showOneNews($id);
            $this->assign('result',$result);
            $this->display();
        }
	}

	//删除行业资讯
    public function delNews(){
        $id = I('id');
        if (!$id) {
            $this->error('id错误！');
        }
        $info = $this->newsList->delete($id);
        if ($info == '0') {
            $this->success('不存在该数据！');
        }else if($info == false){
            $this->error('SQL出错！');
        }else{
            $this->success('删除成功！');
        }
    }

/**
 * [activityList description]
 * @return [type] [description]
 */
	public function activityList() {
		$this->display();
	}
	public function noticeList() {
		$this->display();
	}
	public function meetingList() {
		$this->display();
	}
}