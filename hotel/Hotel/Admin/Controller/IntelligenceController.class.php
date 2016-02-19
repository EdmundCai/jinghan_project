<?php
namespace Admin\Controller;
use Think\Controller;
class IntelligenceController extends Controller {

    private $intelligence;
    private $static;
    public function __construct(){
        parent::__construct();
        $this->intelligence=D('Intelligence');
        $this->static=D('Static');
        // $this->activityList = M('ActivityList');
    }
    
/**
 * [intelligence 各种经营情报 只有文字]
 * @author Ahao 
 */
	public function index($type = ''){
        if($type == ''){
            $this->error("获取情报类型失败！");
        }
        $where['type'] = $type;
        $result = $this->intelligence->getPageInfo($where);
        // print_r($result);exit;
		if($result){
            switch($type){
                case 0:
                    $title = "月度经营情报";
                    $name = "月度经营情报";break;
                case 1:
                    $title = "年度经营情报";
                    $name = "年度经营情报";break;
                case 2:
                    $title = "广交会经营情报";
                    $name = "广交会经营情报";break;
            
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
	//添加经营情报
	public function addInt(){
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
            $result = $this->intelligence->addN($data);
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
	//编辑经营情报
	public function editInt($id=''){
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
            $result = $this->intelligence->editN($id,$data);
            if($result){
                $this->ajaxReturn(array('status'=>1, 'message'=>'修改成功！')); 
            }else{
                $this->ajaxReturn(array('status'=>0, 'message'=>'修改失败！')); 
            }
        }else{//
            $result = $this->intelligence->showOneData($id);
            $this->assign('result',$result);
            $this->display();
        }
	}

	//删除经营情报
    public function del(){
        $id = I('id');
        if (!$id) {
            $this->error('id错误！');
        }
        $info = $this->intelligence->delete($id);
        if ($info == '0') {
            $this->success('不存在该数据！');
        }else if($info == false){
            $this->error('SQL出错！');
        }else{
            $this->success('删除成功！');
        }
    }

    //经营情报上传页面
    public function uplPage($id=''){
        $id=1;
        $where['id'] = $id; 
        if(IS_POST){
            $data = I('');
            if($data['details'] == ''){
                $this->ajaxReturn(array('status'=>0, 'message'=>'请输入页面人内容！')); 
            }
            $result = $this->static->where($where)->save($data);
            if($result){
                $this->ajaxReturn(array('status'=>1, 'message'=>'修改成功！')); 
            }else{
                $this->ajaxReturn(array('status'=>0, 'message'=>'修改失败！')); 
            }
        }else{
            $result = $this->static->where($where)->find();
            $this->assign("result",$result);
            $this->display();
        }
        
    }

}