<?php
namespace Admin\Controller;
use Think\Controller;
class AdminController extends Controller {
    // 管理员管理首页
    public function index(){
        //管理员管理首页
        $Admin = M('Admin');
        $data_admin = $Admin->select();
          // var_dump($data_admin); exit(); 
        $role = M('Role');
        $data_role = $role->select();
        // var_dump($data_admin); exit(); 
        // echo session('user_id');exit();
        $this->assign('data_role', $data_role);
        $this->assign('data_admin', $data_admin);
        $this->display();
    }
    //角色列表
    public function adminrole(){
        $model = D("Role");
        // $data = $model->getPageInfo();
        $data = M('Role')->select();
        $this->assign("data", $data);
        // var_dump($data); exit();
        $this->display();
    }
    //添加角色
    public function adminroleadd(){
        // $this->auth("rolelist");
        $model = D("Role");
        // $authModel = D("Auth");
        if(IS_POST){
            $role_auth_ids = I("post.role_auth_ids", 0);
            if(!empty($role_auth_ids)){
                //获得权限的id
                $ids = implode(',', $_POST['role_auth_ids']);
            }
            if($model->create()){
                //普通管理员pid为1
                $model->pid = 1;
                // $model->auth_ids = $ids;
                $model->auth_ac = $ids;
                if($model->add()){
                    $this->success("添加成功！");exit;
                }else{
                    $this->error("添加失败！");
                }
            }else{
                $this->error($model->getError());
            }
        }
        $this->display();
    }

    public function add(){
        $response = array();
        $Admin = D('Admin');
        if ($Admin->create()) {
            $result = $Admin->add();//若主键是自增，则返回该主键的值。若不是自增，则返回插入数据的个数。若返回false则表示写入出错。
            if ($result) {
                $response['status'] = 1;
                $response['data'] = '添加成功！';
            } else {
                $response['status'] = 0;
                $response['data'] = '添加失败！';
            }
            
        } else {
            $response['status'] = 0;
            $response['data'] = $Admin->getError();
        }

        $this->ajaxReturn($response);
    }

    public function delete(){
        $data = I();
        if (!$data['id']) {
            $this->error('id错误！');
        }
        if ($data['id'] == '1') {
            $this->error('您不能删除该账号！');
        }
        $Admin = M('Admin');
        $info = $Admin->delete($data['id']);
        if ($info == '0') {
            $this->success('不存在该数据！');
        }else if($info == false){
            $this->error('SQL出错！');
        }else{
            $this->success('删除成功！');
        }
    }

    public function edit($id=''){
        if (!$id) {
            $this->error('id错误！');
        }
        if (IS_POST) {
            //TODO:处理提交的数据
        } else {
            $Admin = M('Admin');
            $map = array(
                'id'=>$id,
                );
            $admin = $Admin->where($map)->find();
            $this->assign('admin', $admin);
            $this->display();
        }
        
    }

    public function select(){
        //
    }

    public function changeStatus(){
        $data = I();
        if (!$data['id']) {
            $this->error('id错误！');
        }
        $Admin = M('Admin');
        $map = array(
            'id' => $data['id'], 
            );
        $info = $Admin->where($map)->setField('status', $data['status']);
        //echo $Admin->getLastSql();exit();
        if ($info) {
            $this->success('状态已更新。');
        }else{
            $this->error('更新出错！');
        }
    }



}