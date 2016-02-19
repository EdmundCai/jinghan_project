<?php
namespace Admin\Controller;
use Think\Controller;
class NewsListController extends Controller {

    private $newsList;
    public function __construct(){
        parent::__construct();
        $this->newsList=D('NewsList');
        // $this->activityList = M('ActivityList');
    }
    
/**
 * [newsList 行业资讯列表 只有文字]
 * @author Ahao 
 * 
 */
	public function newsList($type = ''){
        if($type == ''){
            $this->error("获取资讯类型失败！");
        }
        $where['type'] = $type;
        $result = $this->newsList->getPageInfo($where);
        // print_r($result);exit;
		if($result){
            switch($type){
                case 0:
                    $title = "行业资讯";
                    $name = "资讯";break;
                case 1:
                    $title = "最新通知";
                    $name = "通知";break;
                case 2:
                    $title = "会展会议";
                    $name = "会议";break;
                case 3:
                    $title = "精选文摘";
                    $name = "文摘";break;
                case 4:
                    $title = "每月简报";
                    $name = "简报";break;
                default:
                    $title = "新闻";
                    $name = "新闻";break;
                    break;
            }
            // 模板title和name
            $this->assign("title",$title);
            $this->assign("name",$name);
            $this->assign('result',$result);
        }else{
            $this->assign('empty',"<tr><td colspan='7' style='text-align:center;'>暂时没有数据！</td></tr>");
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
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写详情！'));
            }
            if($data['date']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写日期！'));
            }
            $result = $this->newsList->addN($data);
            // print_r($result);
            if($result){
                $this->ajaxReturn(array('status'=>1, 'message'=>'添加成功！')); 
            }else{
                $this->ajaxReturn(array('status'=>0, 'message'=>'添加失败！')); 
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
                $this->ajaxReturn(array('status'=>0,'message'=>'获取id失败！'));
            }
            if($data['title']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写标题！'));
            }
            if($data['details']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写详情！'));
            }
            if($data['date']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写日期！'));
            }
            $result = $this->newsList->editN($id,$data);
            if($result){
                $this->ajaxReturn(array('status'=>1, 'message'=>'修改成功！')); 
            }else{
                $this->ajaxReturn(array('status'=>0, 'message'=>'修改失败！')); 
            }
        }else{//
            $result = $this->newsList->showOneNews($id);
            $this->assign('result',$result);
            $this->display();
        }
	}

	//删除行业资讯
    public function del(){
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
 * [imgList 有封面图列表 包括协会活动、专业会刊]
 * @author Ahao
 */
    
    public function uppic(){
        $upload = new \Think\Upload();
        $upload->autoSub = true;
        $upload->rootPath = 'Public/Uploads/imgList/';
        $upload->savePath = '';
        $info = $upload->upload();
        if($info){
            if(isset($info['image'])){
                //输出图片新路径
                echo $upload->rootPath.$info['image']['savepath'].$info['image']['savename'];exit;
            }
        }else{
            $result = $upload->getError();
            if(!empty($result)){
                //输出错误信息
                echo 'error';exit;
            }
        }
    }

    public function uppdf(){
        $upload = new \Think\Upload();
        $upload->autoSub = true;
        $upload->rootPath = 'Public/Uploads/fileList/';
        $upload->savePath = '';
        $info = $upload->upload();
        if($info){
            if(isset($info['file'])){
                //输出文件新路径
                echo $upload->rootPath.$info['file']['savepath'].$info['file']['savename'];exit;
            }
        }else{
            $result = $upload->getError();
            if(!empty($result)){
                //输出错误信息
                echo 'error';exit;
            }
        }
    }

	public function imgList($type) {
		if($type == ''){
            $this->error("获取类型失败！",U('Index/index.html'),2);
        }
        $where['type'] = $type;
        $result = $this->newsList->getPageInfo($where);
        if($result){
            switch($type){
                case 5:
                    $title = "协会活动";
                    $name = "活动";break;
                case 6:
                    $title = "专业会刊";
                    $name = "专业会刊";break;
                default:break;
            }
            // 模板title和name
            $this->assign("title",$title);
            $this->assign("name",$name);
            $this->assign('result',$result);
        }else{
            $this->assign('empty',"<tr><td colspan='7' style='text-align:center;'>暂时没有数据！</td></tr>");
        }
        $this->display();
	}
	//添加带封面的资讯列表
    public function addImg(){
        if(IS_POST){
            $data=I();
            if($data['title']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写标题！'));
            }
            if($data['details']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写详情！'));
            }
            if($data['date']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写日期！'));
            }
            if($data['img_path']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请上传封面！'));
            }
            $result = $this->newsList->addN($data);
            // print_r($result);
            if($result){
                $this->ajaxReturn(array('status'=>1, 'message'=>'添加成功！')); 
            }else{
                $this->ajaxReturn(array('status'=>0, 'message'=>'添加失败！')); 
            }
        }else{
            $this->display();
        }
    }
    //编辑带封面的资讯列表
    public function editImg($id=''){
        if (IS_POST) {//获取信息
            $data = I();
            if($id == ''){
                $this->ajaxReturn(array('status'=>0,'message'=>'获取id失败！'));
            }
            if($data['title']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写标题！'));
            }
            if($data['details']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写详情！'));
            }
            if($data['date']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请填写日期！'));
            }
            if($data['img_path']==''){
                $this->ajaxReturn(array('status'=>0,'message'=>'请上传封面！'));
            }
            $result = $this->newsList->editN($id,$data);
            if($result){
                $this->ajaxReturn(array('status'=>1, 'message'=>'修改成功！')); 
            }else{
                $this->ajaxReturn(array('status'=>0, 'message'=>'修改失败！')); 
            }
        }else{//
            $result = $this->newsList->showOneNews($id);
            $this->assign('result',$result);
            $this->display();
        }
    }

    //协会活动  设置第一个标题
    public function setFirst(){
        $where['id'] = I('id');
        $set['is_first'] = 1;
        $clear['is_first'] = 0;
        if($where['id'] == ''){
            $this->ajaxReturn(array('status'=>0,'message'=>'获取id失败！'));
        }
        // 清空is_first
        $clear = $this->newsList->where($set)->save($clear);
        // 设置指定id的is_first为1
        $result = $this->newsList->where($where)->save($set);
        if($result){
            $this->ajaxReturn(array('status'=>1, 'message'=>'设置成功！')); 
        }else{
            $this->ajaxReturn(array('status'=>0, 'message'=>'设置失败！')); 
        }
    }

    
}