<?php
namespace Admin\Controller;
use Think\Controller;
class NewsController extends Controller {

/**
 * [newsList 行业资讯列表]
 * @return [type] [description]
 */
	public function newsList(){
		if( showNews() ){
            $this->assign('result',$result);
        }else{
            $this->assign('empty',"<tr><td colspan='7' style='text-align:center;'>暂时没有新闻！</td></tr>");
        }
		$this->display();
	}    
	//添加行业资讯
	public function addNews(){//添加公告
        if(IS_POST){
            $data=I();
            $data['type']=1;//type:1-通知公告
            $data['author']=I('author');
            $data['title']=I('title');
            $data['intro']=I('intro');
            $data['content']=I('content');
            $data['addtime'] =time();
            $data['updatetime'] =time();
            if($data['title']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写标题！'));
            }
            if($data['intro']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写通知公告简介！'));
            }
            if($data['content']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写通知公告内容！'));
            }
            if($notice->create() && $data['intro']!='' && $data['title']!=''){
                $notice->add($data);
                $this->ajaxReturn(array('status'=>1, 'message'=>'通知公告添加成功！')); 
            }else{
                $this->ajaxReturn(array('status'=>0, 'message'=>'通知公告添加失败！')); 
            }
        }else{
            $this->display();
        }
	}
	//编辑行业资讯
	public function editNews($id=''){//编辑通知公告
		if (IS_POST) {//获取信息
            $data=I();
            $data['type']=1;//type:1-通知公告
            $data['author']=I('author');
            $data['title']=I('title');
            $data['intro']=I('intro');
            $data['content']=I('content');
            $data['updatetime'] =strtotime(I('updatetime'));
            //$data['updatetime'] =time();
            if($data['title']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写标题！'));
            }
            if($data['intro']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写通知公告简介！'));
            }
            if($data['content']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写通知公告内容！'));
            }
            if($notice->create() && $data['intro']!='' && $data['title']!=''){
                $notice->save($data);
                $this->ajaxReturn(array('status'=>1, 'message'=>'通知公告修改成功！')); 
            }else{
                $this->ajaxReturn(array('status'=>0, 'message'=>'通知公告修改失败！')); 
            }
        }else{//
            $map = array('id' => $id);
            $notice=M('Xx_article');
            $result = $notice->where($map)->find();
            $result['updatetime']=date("Y-m-d",$result['updatetime']);
            $this->assign('affiche',$result);
            $this->display();
        }
	}

	//删除行业资讯
    public function delNews(){
        $data = I();
        if (!$data['id']) {
            $this->error('id错误！');
        }
        $where['id'] = I('id');
        $affiche=M('Xx_article');;
        $info = $affiche->delete($data['id']);
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