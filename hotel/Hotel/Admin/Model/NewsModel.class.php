<?php
namespace Admin\Model;
use Think\Model;
class NewsModel extends Model {
	// 定义自动验证
	protected $newsList = M('NewsList');
	protected $activityList = M('ActivityList');
	public function __construct(){

	}
    protected $_validate = array(
    	array('title','require','请输入标题'),
        array('details','require','新闻详情必须填写！'),
    );
    //获取新闻列表 
    public function showNews(){
    	$result = $newsList->where()->order('updatetime desc')->select();
    	if(count($result) < 0){
    		return false;
    	}else{
    		return $result;
    	}
    }
}