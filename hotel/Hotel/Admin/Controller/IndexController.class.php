<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        if (I('session.user_id',0)) {// 获取$_session['user_id'] 如果不存在则默认为0
            //已登录，跳转到首页
            $Admin = M('Admin');
            $adminInfo = $Admin->find(I('session.user_id'));

            $this->assign('adminInfo',$adminInfo);
            $this->display();
        } else {
            //未登录，跳转到登录页
            $this->display('login');
        }
        
    }

    public function welcome(){
        //服务器信息
        $serverInfo = array(
            '操作系统'=>PHP_OS,
            '运行环境'=>$_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式'=>php_sapi_name(),
            'ThinkPHP版本'=>THINK_VERSION.' [ <a href="http://thinkphp.cn" target="_blank">查看最新版本</a> ]',
            '上传附件限制'=>ini_get('upload_max_filesize'),
            '执行时间限制'=>ini_get('max_execution_time').'秒',
            '服务器时间'=>date("Y年n月j日 H:i:s"),
            '北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
            '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            '剩余空间'=>round((disk_free_space(".")/(1024*1024)),2).'M',
            'register_globals'=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
            'magic_quotes_gpc'=>(1===get_magic_quotes_gpc())?'YES':'NO',
            'magic_quotes_runtime'=>(1===get_magic_quotes_runtime())?'YES':'NO',
        );

        $Admin = M('Admin');
        $adminInfo = $Admin->find(I('session.user_id'));
        
        $this->assign('serverInfo', $serverInfo);
        $this->assign('adminInfo',$adminInfo);
        $this->display();
    }

    public function login(){
        if (IS_POST) {
            //判断登录
            $data = I();
            if('' == $data['name'] || '' == $data['pwd']){
                die('账户或密码不能为空！');
            }
            $map = array(
                'name' => $data['name'],
            );
            $Admin = M('Admin');
            $info = $Admin->where($map)->find();
            
            if ($info) {//如果账户存在
                if ($info['pwd'] == $data['pwd']) {
                    //登录成功
                    session('user_id', $info['id']);
                    $Admin->where('id='.$info['id'])->setField('last_ip', get_client_ip());//TODO:欢迎页实际读取的是这里的信息
                    $Admin->where('id='.$info['id'])->setField('last_time', date("Y-m-d H:i:s"));
                    $Admin->where('id='.$info['id'])->setInc('login_times');
                    $this->success('登录成功！', U('index'));
                } else {
                    $this->error('密码错误！');// 默认返回前一页
                }
                
            } else {
                $this->error('账户不存在！');
            }

        } else {
            $this->display();
        }
        
    }

    public function unlogin(){
        session('user_id', null);
        $this->success('注销成功，请重新登录。', U('login'));
    }

    public function getVerifyCode(){
        $Verify = new \Think\Verify();
        $Verify->entry();
    }



}