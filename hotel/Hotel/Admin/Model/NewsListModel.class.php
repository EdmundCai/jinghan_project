<?php
namespace Admin\Model;
use Think\Model;
class NewsListModel extends Model {
    protected $tableName = 'news_list'; 
	// 定义自动验证
    protected $_validate = array(
    	array('title','require','请输入标题'),
        array('details','require','新闻详情必须填写！'),
    );

    //获取新闻列表 
    public function showNews($type){
        // type为0表示新闻列表
        $where['type']=$type;
    	$result = $this->where($where)->order('update_time desc')->select();
    	if(count($result) <= 0){
    		return false;
    	}else{
    		return $result;
    	}
    }

    //添加新闻 
    public function addN($data){
        if($data){
            $data['update_time'] =date("Y-m-d",time());
            $result = $this->add($data);
            // print_r($result);exit();
            if($result){
                return true;
            }else{
                return false;
            }
        } 
    }

    //编辑新闻 
    public function editN($id,$data){
        if($data){   
            $data['update_time'] =date("Y-m-d",time());
            $result = $this->where('id = '.$id)->save($data);
            if($result){
                return true;
            }else{
                return false;
            }
        } 
    }

    //显示某条新闻记录
    public function showOneNews($id){
        $where['id'] = $id; 
        $result = $this->where($where)->find();
        if(count($result) <= 0){
            return false;
        }else{
            return $result;
        }
    } 
}