<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model {
    // 定义自动验证
    protected $_validate    =   array(
        array('name','require','账户必须'),
        );
    // 定义自动完成
    protected $_auto    =   array(
        array('create_time','time',1,'function'),
        array('last_ip','get_client_ip',3,'function'),
        array('last_time','time',3,'function'),
        );

}