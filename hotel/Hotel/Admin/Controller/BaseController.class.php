<?php
/**
 * Created by PhpStorm.
 * User: Yu-Bin Zhuang
 * Date: 2015/7/2
 * Time: 15:00
 */
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller {

      /**
     * 权限控制
     * @param $action
     * @return bool
     * @author Yu-Bin Zhuang
     */
    protected function auth($action){
        //获得登陆用户id
        // $login_admin = session('user_id');
        // //用户id
        // $adminModel = D("Admin");
        // $adminData = $adminModel->where('id='.$login_admin)->select();
        // //获得登陆用户所属的角色id
        // $role_id = $adminData['role_id'];
        // if($role_id == 1){
            //超级管理员
            return true;
        // }else{
        //     //普通管理员
        //     $auth_ac = session("auth_ac");
        //     $action = ','.$action.',';
        //     $auth_ac = ','.$auth_ac.',';
        //     if(strpos($auth_ac, $action) !== false){
        //         return true;
        //     }else{
        //         $this->error("没有权限访问该网页");
        //     }
        // }
    }

    /**
     * 空操作
     * @param $name
     * @author Yu-Bin Zhuang
     */
    public function _empty($name){
        $this->error("error 404!");
    }
}