<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {
    /**
     * @return [type]
     * @param [string] $cursor 用于左侧栏标识当前选中的页面
     * @param [string] $tap 用于左侧栏标识当前展开的一级菜单
     */
    public function __construct(){
        parent::__construct();
        // todo...打算声明一些model  减少冗余代码 推荐命名格式为驼峰型
    }
}