<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

// OneThink常量定义
const ONETHINK_VERSION    = '1.0.131218';
const ONETHINK_ADDON_PATH = './Addons/';

/**
 * 系统公共库文件
 * 主要定义系统公共函数库
 */

/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function is_login(){
    $user = session('user_auth');
    if (empty($user)) {
        return 0;
    } else {
        return session('user_auth_sign') == data_auth_sign($user) ? $user['uid'] : 0;
    }
}

/**
 * 检测当前用户是否为管理员
 * @return boolean true-管理员，false-非管理员
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function is_administrator($uid = null){
    $uid = is_null($uid) ? is_login() : $uid;
    return $uid && (intval($uid) === C('USER_ADMINISTRATOR'));
}

/**
 * 字符串转换为数组，主要用于把分隔符调整到第二个参数
 * @param  string $str  要分割的字符串
 * @param  string $glue 分割符
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function str2arr($str, $glue = ','){
    return explode($glue, $str);
}

/**
 * 数组转换为字符串，主要用于把分隔符调整到第二个参数
 * @param  array  $arr  要连接的数组
 * @param  string $glue 分割符
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function arr2str($arr, $glue = ','){
    return implode($glue, $arr);
}

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
        if(false === $slice) {
            $slice = '';
        }
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice.'...' : $slice;
}
/**
 * 字符串截取，支持中文和utf-8编码
 * 改进：
 *     1.去掉两次判断（需要确保服务器支持 mb_substr 函数、并且都是 utf-8 编码）
 *     2.超出长度才追加省略号
 * @static
 * @access public
 * @param string $text 需要转换的字符串
 * @param string $length 截取长度
 * @return string
 */
function subtext($text, $length)
{
    if(mb_strlen($text, 'utf8') > $length) 
    return mb_substr($text, 0, $length, 'utf8').'...';
    return $text;
}

/**
 * 系统加密方法
 * @param string $data 要加密的字符串
 * @param string $key  加密密钥
 * @param int $expire  过期时间 单位 秒
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function think_encrypt($data, $key = '', $expire = 0) {
    $key  = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
    $data = base64_encode($data);
    $x    = 0;
    $len  = strlen($data);
    $l    = strlen($key);
    $char = '';

    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }

    $str = sprintf('%010d', $expire ? $expire + time():0);

    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)))%256);
    }
    return str_replace(array('+','/','='),array('-','_',''),base64_encode($str));
}

/**
 * 系统解密方法
 * @param  string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
 * @param  string $key  加密密钥
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function think_decrypt($data, $key = ''){
    $key    = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
    $data   = str_replace(array('-','_'),array('+','/'),$data);
    $mod4   = strlen($data) % 4;
    if ($mod4) {
       $data .= substr('====', $mod4);
    }
    $data   = base64_decode($data);
    $expire = substr($data,0,10);
    $data   = substr($data,10);

    if($expire > 0 && $expire < time()) {
        return '';
    }
    $x      = 0;
    $len    = strlen($data);
    $l      = strlen($key);
    $char   = $str = '';

    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }

    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1))<ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        }else{
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return base64_decode($str);
}

/**
 * 数据签名认证
 * @param  array  $data 被认证的数据
 * @return string       签名
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function data_auth_sign($data) {
    //数据类型检测
    if(!is_array($data)){
        $data = (array)$data;
    }
    ksort($data); //排序
    $code = http_build_query($data); //url编码并生成query字符串
    $sign = sha1($code); //生成签名
    return $sign;
}

/**
* 对查询结果集进行排序
* @access public
* @param array $list 查询结果
* @param string $field 排序的字段名
* @param array $sortby 排序类型
* asc正向排序 desc逆向排序 nat自然排序
* @return array
*/
function list_sort_by($list,$field, $sortby='asc') {
   if(is_array($list)){
       $refer = $resultSet = array();
       foreach ($list as $i => $data)
           $refer[$i] = &$data[$field];
       switch ($sortby) {
           case 'asc': // 正向排序
                asort($refer);
                break;
           case 'desc':// 逆向排序
                arsort($refer);
                break;
           case 'nat': // 自然排序
                natcasesort($refer);
                break;
       }
       foreach ( $refer as $key=> $val)
           $resultSet[] = &$list[$key];
       return $resultSet;
   }
   return false;
}

/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId =  $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
 * 将list_to_tree的树还原成列表
 * @param  array $tree  原来的树
 * @param  string $child 孩子节点的键
 * @param  string $order 排序显示的键，一般是主键 升序排列
 * @param  array  $list  过渡用的中间数组，
 * @return array        返回排过序的列表数组
 * @author yangweijie <yangweijiester@gmail.com>
 */
function tree_to_list($tree, $child = '_child', $order='id', &$list = array()){
    if(is_array($tree)) {
        $refer = array();
        foreach ($tree as $key => $value) {
            $reffer = $value;
            if(isset($reffer[$child])){
                unset($reffer[$child]);
                tree_to_list($value[$child], $child, $order, $list);
            }
            $list[] = $reffer;
        }
        $list = list_sort_by($list, $order, $sortby='asc');
    }
    return $list;
}

/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function format_bytes($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 设置跳转页面URL
 * 使用函数再次封装，方便以后选择不同的存储方式（目前使用cookie存储）
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function set_redirect_url($url){
    cookie('redirect_url', $url);
}

/**
 * 获取跳转页面URL
 * @return string 跳转页URL
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_redirect_url(){
    $url = cookie('redirect_url');
    return empty($url) ? __APP__ : $url;
}

/**
 * 处理插件钩子
 * @param string $hook   钩子名称
 * @param mixed $params 传入参数
 * @return void
 */
function hook($hook,$params=array()){
    \Think\Hook::listen($hook,$params);
}

/**
 * 获取插件类的类名
 * @param strng $name 插件名
 */
function get_addon_class($name){
    $class = "Addons\\{$name}\\{$name}Addon";
    return $class;
}

/**
 * 获取插件类的配置文件数组
 * @param string $name 插件名
 */
function get_addon_config($name){
    $class = get_addon_class($name);
    if(class_exists($class)) {
        $addon = new $class();
        return $addon->getConfig();
    }else {
        return array();
    }
}

/**
 * 插件显示内容里生成访问插件的url
 * @param string $url url
 * @param array $param 参数
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function addons_url($url, $param = array()){
    $url        = parse_url($url);
    $case       = C('URL_CASE_INSENSITIVE');
    $addons     = $case ? parse_name($url['scheme']) : $url['scheme'];
    $controller = $case ? parse_name($url['host']) : $url['host'];
    $action     = trim($case ? strtolower($url['path']) : $url['path'], '/');

    /* 解析URL带的参数 */
    if(isset($url['query'])){
        parse_str($url['query'], $query);
        $param = array_merge($query, $param);
    }

    /* 基础参数 */
    $params = array(
        '_addons'     => $addons,
        '_controller' => $controller,
        '_action'     => $action,
    );
    $params = array_merge($params, $param); //添加额外参数

    return U('Addons/execute', $params);
}

/**
 * 时间戳格式化
 * @param int $time
 * @return string 完整的时间显示
 * @author huajie <banhuajie@163.com>
 */
function time_format($time = NULL,$format='Y-m-d H:i'){
    $time = $time === NULL ? NOW_TIME : intval($time);
    return date($format, $time);
}

/**
 * 根据用户ID获取用户名
 * @param  integer $uid 用户ID
 * @return string       用户名
 */
function get_username($uid = 0){
    static $list;
    if(!($uid && is_numeric($uid))){ //获取当前登录用户名
        return session('user_auth.username');
    }

    /* 获取缓存数据 */
    if(empty($list)){
        $list = S('sys_active_user_list');
    }

    /* 查找用户信息 */
    $key = "u{$uid}";
    if(isset($list[$key])){ //已缓存，直接使用
        $name = $list[$key];
    } else { //调用接口获取用户信息
        $User = new User\Api\UserApi();
        $info = $User->info($uid);
        if($info && isset($info[1])){
            $name = $list[$key] = $info[1];
            /* 缓存用户 */
            $count = count($list);
            $max   = C('USER_MAX_CACHE');
            while ($count-- > $max) {
                array_shift($list);
            }
            S('sys_active_user_list', $list);
        } else {
            $name = '';
        }
    }
    return $name;
}

/**
 * 根据用户ID获取用户昵称
 * @param  integer $uid 用户ID
 * @return string       用户昵称
 */
function get_nickname($uid = 0){
    static $list;
    if(!($uid && is_numeric($uid))){ //获取当前登录用户名
        return session('user_auth.username');
    }

    /* 获取缓存数据 */
    if(empty($list)){
        $list = S('sys_user_nickname_list');
    }

    /* 查找用户信息 */
    $key = "u{$uid}";
    if(isset($list[$key])){ //已缓存，直接使用
        $name = $list[$key];
    } else { //调用接口获取用户信息
        $info = M('Member')->field('nickname')->find($uid);
        if($info !== false && $info['nickname'] ){
            $nickname = $info['nickname'];
            $name = $list[$key] = $nickname;
            /* 缓存用户 */
            $count = count($list);
            $max   = C('USER_MAX_CACHE');
            while ($count-- > $max) {
                array_shift($list);
            }
            S('sys_user_nickname_list', $list);
        } else {
            $name = '';
        }
    }
    return $name;
}

/**
 * 获取分类信息并缓存分类
 * @param  integer $id    分类ID
 * @param  string  $field 要获取的字段名
 * @return string         分类信息
 */
function get_category($id, $field = null){
    static $list;

    /* 非法分类ID */
    if(empty($id) || !is_numeric($id)){
        return '';
    }

    /* 读取缓存数据 */
    if(empty($list)){
        $list = S('sys_category_list');
    }

    /* 获取分类名称 */
    if(!isset($list[$id])){
        $cate = M('Category')->find($id);
        if(!$cate || 1 != $cate['status']){ //不存在分类，或分类被禁用
            return '';
        }
        $list[$id] = $cate;
        S('sys_category_list', $list); //更新缓存
    }
    return is_null($field) ? $list[$id] : $list[$id][$field];
}

/* 根据ID获取分类标识 */
function get_category_name($id){
    return get_category($id, 'name');
}

/* 根据ID获取分类名称 */
function get_category_title($id){
    return get_category($id, 'title');
}

/**
 * 获取文档模型信息
 * @param  integer $id    模型ID
 * @param  string  $field 模型字段
 * @return array
 */
function get_document_model($id = null, $field = null){
    static $list;

    /* 非法分类ID */
    if(!(is_numeric($id) || is_null($id))){
        return '';
    }

    /* 读取缓存数据 */
    if(empty($list)){
        $list = S('DOCUMENT_MODEL_LIST');
    }

    /* 获取模型名称 */
    if(empty($list)){
        $map   = array('status' => 1, 'extend' => 1);
        $model = M('Model')->where($map)->field(true)->select();
        foreach ($model as $value) {
            $list[$value['id']] = $value;
        }
        S('DOCUMENT_MODEL_LIST', $list); //更新缓存
    }

    /* 根据条件返回数据 */
    if(is_null($id)){
        return $list;
    } elseif(is_null($field)){
        return $list[$id];
    } else {
        return $list[$id][$field];
    }
}

/**
 * 解析UBB数据
 * @param string $data UBB字符串
 * @return string 解析为HTML的数据
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function ubb($data){
    //TODO: 待完善，目前返回原始数据
    return $data;
}

/**
 * 记录行为日志，并执行该行为的规则
 * @param string $action 行为标识
 * @param string $model 触发行为的模型名
 * @param int $record_id 触发行为的记录id
 * @param int $user_id 执行行为的用户id
 * @return boolean
 * @author huajie <banhuajie@163.com>
 */
function action_log($action = null, $model = null, $record_id = null, $user_id = null){

    //参数检查
    if(empty($action) || empty($model) || empty($record_id)){
        return '参数不能为空';
    }
    if(empty($user_id)){
        $user_id = is_login();
    }

    //查询行为,判断是否执行
    $action_info = M('Action')->getByName($action);
    if($action_info['status'] != 1){
        return '该行为被禁用或删除';
    }

    //插入行为日志
    $data['action_id']      =   $action_info['id'];
    $data['user_id']        =   $user_id;
    $data['action_ip']      =   ip2long(get_client_ip());
    $data['model']          =   $model;
    $data['record_id']      =   $record_id;
    $data['create_time']    =   NOW_TIME;

    //解析日志规则,生成日志备注
    if(!empty($action_info['log'])){
        if(preg_match_all('/\[(\S+?)\]/', $action_info['log'], $match)){
            $log['user']    =   $user_id;
            $log['record']  =   $record_id;
            $log['model']   =   $model;
            $log['time']    =   NOW_TIME;
            $log['data']    =   array('user'=>$user_id,'model'=>$model,'record'=>$record_id,'time'=>NOW_TIME);
            foreach ($match[1] as $value){
                $param = explode('|', $value);
                if(isset($param[1])){
                    $replace[] = call_user_func($param[1],$log[$param[0]]);
                }else{
                    $replace[] = $log[$param[0]];
                }
            }
            $data['remark'] =   str_replace($match[0], $replace, $action_info['log']);
        }else{
            $data['remark'] =   $action_info['log'];
        }
    }else{
        //未定义日志规则，记录操作url
        $data['remark']     =   '操作url：'.$_SERVER['REQUEST_URI'];
    }

    M('ActionLog')->add($data);

    if(!empty($action_info['rule'])){
        //解析行为
        $rules = parse_action($action, $user_id);

        //执行行为
        $res = execute_action($rules, $action_info['id'], $user_id);
    }
}

/**
 * 解析行为规则
 * 规则定义  table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
 * 规则字段解释：table->要操作的数据表，不需要加表前缀；
 *              field->要操作的字段；
 *              condition->操作的条件，目前支持字符串，默认变量{$self}为执行行为的用户
 *              rule->对字段进行的具体操作，目前支持四则混合运算，如：1+score*2/2-3
 *              cycle->执行周期，单位（小时），表示$cycle小时内最多执行$max次
 *              max->单个周期内的最大执行次数（$cycle和$max必须同时定义，否则无效）
 * 单个行为后可加 ； 连接其他规则
 * @param string $action 行为id或者name
 * @param int $self 替换规则里的变量为执行用户的id
 * @return boolean|array: false解析出错 ， 成功返回规则数组
 * @author huajie <banhuajie@163.com>
 */
function parse_action($action = null, $self){
    if(empty($action)){
        return false;
    }

    //参数支持id或者name
    if(is_numeric($action)){
        $map = array('id'=>$action);
    }else{
        $map = array('name'=>$action);
    }

    //查询行为信息
    $info = M('Action')->where($map)->find();
    if(!$info || $info['status'] != 1){
        return false;
    }

    //解析规则:table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
    $rules = $info['rule'];
    $rules = str_replace('{$self}', $self, $rules);
    $rules = explode(';', $rules);
    $return = array();
    foreach ($rules as $key=>&$rule){
        $rule = explode('|', $rule);
        foreach ($rule as $k=>$fields){
            $field = empty($fields) ? array() : explode(':', $fields);
            if(!empty($field)){
                $return[$key][$field[0]] = $field[1];
            }
        }
        //cycle(检查周期)和max(周期内最大执行次数)必须同时存在，否则去掉这两个条件
        if(!array_key_exists('cycle', $return[$key]) || !array_key_exists('max', $return[$key])){
            unset($return[$key]['cycle'],$return[$key]['max']);
        }
    }

    return $return;
}

/**
 * 执行行为
 * @param array $rules 解析后的规则数组
 * @param int $action_id 行为id
 * @param array $user_id 执行的用户id
 * @return boolean false 失败 ， true 成功
 * @author huajie <banhuajie@163.com>
 */
function execute_action($rules = false, $action_id = null, $user_id = null){
    if(!$rules || empty($action_id) || empty($user_id)){
        return false;
    }

    $return = true;
    foreach ($rules as $rule){

        //检查执行周期
        $map = array('action_id'=>$action_id, 'user_id'=>$user_id);
        $map['create_time'] = array('gt', NOW_TIME - intval($rule['cycle']) * 3600);
        $exec_count = M('ActionLog')->where($map)->count();
        if($exec_count > $rule['max']){
            continue;
        }

        //执行数据库操作
        $Model = M(ucfirst($rule['table']));
        $field = $rule['field'];
        $res = $Model->where($rule['condition'])->setField($field, array('exp', $rule['rule']));

        if(!$res){
            $return = false;
        }
    }
    return $return;
}

//基于数组创建目录和文件
function create_dir_or_files($files){
    foreach ($files as $key => $value) {
        if(substr($value, -1) == '/'){
            mkdir($value);
        }else{
            @file_put_contents($value, '');
        }
    }
}

if(!function_exists('array_column')){
    function array_column(array $input, $columnKey, $indexKey = null) {
        $result = array();
        if (null === $indexKey) {
            if (null === $columnKey) {
                $result = array_values($input);
            } else {
                foreach ($input as $row) {
                    $result[] = $row[$columnKey];
                }
            }
        } else {
            if (null === $columnKey) {
                foreach ($input as $row) {
                    $result[$row[$indexKey]] = $row;
                }
            } else {
                foreach ($input as $row) {
                    $result[$row[$indexKey]] = $row[$columnKey];
                }
            }
        }
        return $result;
    }
}

/**
 * 获取表名（不含表前缀）
 * @param string $model_id
 * @return string 表名
 * @author huajie <banhuajie@163.com>
 */
function get_table_name($model_id = null){
    if(empty($model_id)){
        return false;
    }
    $Model = M('Model');
    $name = '';
    $info = $Model->getById($model_id);
    if($info['extend'] != 0){
        $name = $Model->getFieldById($info['extend'], 'name').'_';
    }
    $name .= $info['name'];
    return $name;
}

/**
 * 获取属性信息并缓存
 * @param  integer $id    属性ID
 * @param  string  $field 要获取的字段名
 * @return string         属性信息
 */
function get_model_attribute($model_id, $group = true){
    static $list;

    /* 非法ID */
    if(empty($model_id) || !is_numeric($model_id)){
        return '';
    }

    /* 读取缓存数据 */
    if(empty($list)){
        $list = S('attribute_list');
    }

    /* 获取属性 */
    if(!isset($list[$model_id])){
        $map = array('model_id'=>$model_id);
        $extend = M('Model')->getFieldById($model_id,'extend');

        if($extend){
            $map = array('model_id'=> array("in", array($model_id, $extend)));
        }
        $info = M('Attribute')->where($map)->select();
        $list[$model_id] = $info;
        //S('attribute_list', $list); //更新缓存
    }

    $attr = array();
    foreach ($list[$model_id] as $value) {
        $attr[$value['id']] = $value;
    }

    if($group){
        $sort  = M('Model')->getFieldById($model_id,'field_sort');

        if(empty($sort)){	//未排序
            $group = array(1=>array_merge($attr));
        }else{
            $group = json_decode($sort, true);

            $keys  = array_keys($group);
            foreach ($group as &$value) {
                foreach ($value as $key => $val) {
                    $value[$key] = $attr[$val];
                    unset($attr[$val]);
                }
            }

            if(!empty($attr)){
                $group[$keys[0]] = array_merge($group[$keys[0]], $attr);
            }
        }
        $attr = $group;
    }
    return $attr;
}

/**
 * 调用系统的API接口方法（静态方法）
 * api('User/getName','id=5'); 调用公共模块的User接口的getName方法
 * api('Admin/User/getName','id=5');  调用Admin模块的User接口
 * @param  string  $name 格式 [模块名]/接口名/方法名
 * @param  array|string  $vars 参数
 */
function api($name,$vars=array()){
    $array     = explode('/',$name);
    $method    = array_pop($array);
    $classname = array_pop($array);
    $module    = $array? array_pop($array) : 'Common';
    $callback  = $module.'\\Api\\'.$classname.'Api::'.$method;
    if(is_string($vars)) {
        parse_str($vars,$vars);
    }
    return call_user_func_array($callback,$vars);
}

/**
 * 根据条件字段获取指定表的数据
 * @param mixed $value 条件，可用常量或者数组
 * @param string $condition 条件字段
 * @param string $field 需要返回的字段，不传则返回整个数据
 * @param string $table 需要查询的表
 * @author huajie <banhuajie@163.com>
 */
function get_table_field($value = null, $condition = 'id', $field = null, $table = null){
    if(empty($value) || empty($table)){
        return false;
    }

    //拼接参数
    $map[$condition] = $value;
    $info = M(ucfirst($table))->where($map);
    if(empty($field)){
        $info = $info->field(true)->find();
    }else{
        $info = $info->getField($field);
    }
    return $info;
}

/**
 * 获取链接信息
 * @param int $link_id
 * @param string $field
 * @return 完整的链接信息或者某一字段
 * @author huajie <banhuajie@163.com>
 */
function get_link($link_id = null, $field = 'url'){
    $link = '';
    if(empty($link_id)){
        return $link;
    }
    $link = M('Url')->getById($link_id);
    if(empty($field)){
        return $link;
    }else{
        return $link[$field];
    }
}

/**
 * 获取文档封面图片
 * @param int $cover_id
 * @param string $field
 * @return 完整的数据  或者  指定的$field字段值
 * @author huajie <banhuajie@163.com>
 */
function get_cover($cover_id, $field = null){
    if(empty($cover_id)){
        return false;
    }
    $picture = M('Picture')->where(array('status'=>1))->getById($cover_id);
    return empty($field) ? $picture : $picture[$field];
}

/**
 * 检查$pos(推荐位的值)是否包含指定推荐位$contain
 * @param number $pos 推荐位的值
 * @param number $contain 指定推荐位
 * @return boolean true 包含 ， false 不包含
 * @author huajie <banhuajie@163.com>
 */
function check_document_position($pos = 0, $contain = 0){
    if(empty($pos) || empty($contain)){
        return false;
    }

    //将两个参数进行按位与运算，不为0则表示$contain属于$pos
    $res = $pos & $contain;
    if($res !== 0){
        return true;
    }else{
        return false;
    }
}

/**
 * 获取数据的所有子孙数据的id值
 * @author 朱亚杰 <xcoolcc@gmail.com>
 */

function get_stemma($pids,Model &$model, $field='id'){
    $collection = array();

    //非空判断
    if(empty($pids)){
        return $collection;
    }

    if( is_array($pids) ){
        $pids = trim(implode(',',$pids),',');
    }
    $result     = $model->field($field)->where(array('pid'=>array('IN',(string)$pids)))->select();
    $child_ids  = array_column ((array)$result,'id');

    while( !empty($child_ids) ){
        $collection = array_merge($collection,$result);
        $result     = $model->field($field)->where( array( 'pid'=>array( 'IN', $child_ids ) ) )->select();
        $child_ids  = array_column((array)$result,'id');
    }
    return $collection;
}

/**
 * 导出excel表格
 * @param string $file_name          文件名
 * @param string $title              标题
 * @param string $table_header       表头
 * @param string $table_content      表内容
 * @throws PHPExcel_Exception
 * @throws PHPExcel_Reader_Exception
 * @author Yu-Bin Zhuang
 */
function excel($file_name = "order", $title = "订单信息表", $table_header = '', $table_content = '' ,$object_name ,$style=''){
    import('Org.Util.PHPExcel');
    $objPHPExcel = new \PHPExcel();
    //设置excel属性
    $objPHPExcel->getProperties()->setCreator("JAMES")
        ->setLastModifiedBy("JAMES")
        ->setTitle("zltrans")
        ->setSubject("Dorder")
        ->setDescription("Dorder List")
        ->setKeywords("Dorder")
        ->setCategory("Test result file");
    //设置标题
    $objPHPExcel->getActiveSheet()->setTitle($title);
    $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);//设置单元格宽度
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);

    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'GITF 广州国际旅游展览会');
    $objPHPExcel->getActiveSheet()->setCellValue('A2', $object_name);    

    $len=count($table_header);
    // print_r(chr(65+$len));exit();
    //合并
    $objPHPExcel->getActiveSheet()->mergeCells('A1:'.chr(65+$len-1).'1');
    $objPHPExcel->getActiveSheet()->mergeCells('A2:'.chr(65+$len-1).'2');
    //设置表头行高
    $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(34);
    $objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(34);
    //设置字体样式
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('微软雅黑');
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(11);
    // $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    // $objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getFont()->setBold(true);
        
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setName('微软雅黑');
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(11);
    $objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(65+$len-1).'3')->getFont()->setSize(10);
    
    $objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(65+$len-1).'3')->getFont()->setName('微软雅黑');

    //设置居中
    $objPHPExcel->getActiveSheet()->getStyle('A1:'.chr(65+$len-1).'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A2:'.chr(65+$len-1).'2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //所有垂直居中
    $objPHPExcel->getActiveSheet()->getStyle('A1:'.chr(65+$len-1).'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A2:'.chr(65+$len-1).'2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    //设置单元格边框
    $objPHPExcel->getActiveSheet()->getStyle('A1:'.chr(65+$len-1).'1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('A2:'.chr(65+$len-1).'2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    //设置自动换行
    $objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(65+$len-1).'3')->getAlignment()->setWrapText(true);

    $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);//设置单元格宽度

    if($style='app'){
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    }
    /**
     * 设置表头
     * $table_header=array('订单', '姓名', '电话')
     */
    
    if(is_array($table_header)){
        for($i = 0; $i < count($table_header); $i++){
            $objPHPExcel->getActiveSheet()->setCellValue(chr(65+$i).'3', $table_header[$i]);
        }
        //设置行高
        $objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(40);
        //设置边框
        $objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(65+$len-1).'3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        //设置居中
        $objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(65+$len-1).'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //所有垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(65+$len-1).'3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置自动换行
        $objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(65+$len-1).'3')->getAlignment()->setWrapText(true);
        //加粗
        $objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(65+$len-1).'3')->getFont()->setBold(true);
    }
    /**
     * 设置表内容
     * array(array('id'=>1, 'name'='小明'), array('id'=>2, 'name'='小红'));
     */
    $start=4;
    if(is_array($table_content)){
        for($i = 0; $i < count($table_content); $i++){
            $ascii = 65;
            foreach($table_content[$i] as $val){
                $objPHPExcel->getActiveSheet()->setCellValue(chr($ascii).($i+4), $val);
                $ascii++;
            }
            //设置边框
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$start).':'.chr(65+$len-1).($i+$start))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            //设置行高
            $objPHPExcel->getActiveSheet()->getRowDimension($i+$start)->setRowHeight(60);
            //设置居中
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$start).':'.chr(65+$len-1).($i+$start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //所有垂直居中
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$start).':'.chr(65+$len-1).($i+$start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //设置自动换行
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$start).':'.chr(65+$len-1).($i+$start))->getAlignment()->setWrapText(true);
            //设置字体
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$start).':'.chr(65+$len-1).($i+$start))->getFont()->setName('微软雅黑');
        }
    }
    ob_end_clean();//清除缓冲区,避免乱码
    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
    header('Content-Disposition: attachment;filename="' . $file_name . '.xls"');
    header('Cache-Control: max-age=0');
    //保存
    import("Org.Utrl.PHPExcel.IOFactory");
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
}



/**
 * 导出excel表格
 * @param string $file_name          文件名
 * @param string $title              标题
 * @param string $table_header       表头
 * @param string $table_content      表内容
 * @throws PHPExcel_Exception
 * @throws PHPExcel_Reader_Exception
 * @author Yu-Bin Zhuang
 */
function Three_Tables_Excel($file_name = "My Service List", $title = "订单信息表", $company , $date1 , $table_header1 = '', $table_content1 = '',$date2 , $table_header2 = '', $table_content2 = '' ,$date3 , $table_header3 = '', $table_content3 = ''){
    import('Org.Util.PHPExcel');
    $objPHPExcel = new \PHPExcel();
    //设置excel属性
    $objPHPExcel->getProperties()->setCreator("JAMES")
        ->setLastModifiedBy("JAMES")
        ->setTitle("zltrans")
        ->setSubject("Dorder")
        ->setDescription("Dorder List")
        ->setKeywords("Dorder")
        ->setCategory("Test result file");
    //设置标题
    $objPHPExcel->getActiveSheet()->setTitle($title);
    $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);//设置单元格宽度
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);

    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'GITF 广州国际旅游展览会');
    $objPHPExcel->getActiveSheet()->setCellValue('A2', $company);    

    $len=count($table_header1);
    // print_r(chr(65+$len));exit();
    //合并
    $objPHPExcel->getActiveSheet()->mergeCells('A1:'.chr(65+$len-1).'1');
    $objPHPExcel->getActiveSheet()->mergeCells('A2:'.chr(65+$len-1).'2');
    //设置表头行高
    $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(34);
    $objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(34);
    $objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(34);
    //设置字体样式
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('微软雅黑');
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(11);
    // $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    // $objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getFont()->setBold(true);
        
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setName('微软雅黑');
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(11);
    $objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(65+$len-1).'3')->getFont()->setSize(11);
    
    $objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(65+$len-1).'3')->getFont()->setName('微软雅黑');
    $objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(65+$len-1).'3')->getFont()->setBold(true);
    //设置居中
    $objPHPExcel->getActiveSheet()->getStyle('A1:'.chr(65+$len-1).'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A2:'.chr(65+$len-1).'2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(65+$len-1).'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //所有垂直居中
    $objPHPExcel->getActiveSheet()->getStyle('A1:'.chr(65+$len-1).'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A2:'.chr(65+$len-1).'2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    //设置单元格边框
    $objPHPExcel->getActiveSheet()->getStyle('A1:'.chr(65+$len-1).'1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('A2:'.chr(65+$len-1).'2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    //设置自动换行
    $objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(65+$len-1).'3')->getAlignment()->setWrapText(true);

    $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);//设置单元格宽度

    //展具申请
    $objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
    $objPHPExcel->getActiveSheet()->mergeCells('D3:E3');
    //平行、垂直居中、边框
    $objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(65+$len-1).'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(65+$len-1).'3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(65+$len-1).'3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->setCellValue('A3', '展具申请列表');
    $objPHPExcel->getActiveSheet()->setCellValue('D3', $date1);    
    /**
     * 设置表头
     * $table_header=array('订单', '姓名', '电话')
     */
    
    if(is_array($table_header1)){
        for($i = 0; $i < count($table_header1); $i++){
            $objPHPExcel->getActiveSheet()->setCellValue(chr(65+$i).'4', $table_header1[$i]);
        }
        //设置行高
        $objPHPExcel->getActiveSheet()->getRowDimension(4)->setRowHeight(40);
        //设置边框
        $objPHPExcel->getActiveSheet()->getStyle('A4:'.chr(65+$len-1).'4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        //设置居中
        $objPHPExcel->getActiveSheet()->getStyle('A4:'.chr(65+$len-1).'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //所有垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A4:'.chr(65+$len-1).'4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置自动换行
        $objPHPExcel->getActiveSheet()->getStyle('A4:'.chr(65+$len-1).'4')->getAlignment()->setWrapText(true);
        //加粗
        $objPHPExcel->getActiveSheet()->getStyle('A4:'.chr(65+$len-1).'4')->getFont()->setBold(true);
    }
    /**
     * 设置表内容
     * array(array('id'=>1, 'name'='小明'), array('id'=>2, 'name'='小红'));
     */
    $start=5;
    if(is_array($table_content1)){
        for($i = 0; $i < count($table_content1); $i++){
            $ascii = 65;
            foreach($table_content1[$i] as $val){
                $objPHPExcel->getActiveSheet()->setCellValue(chr($ascii).($i+$start), $val);
                $ascii++;
            }
            //设置边框
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$start).':'.chr(65+$len-1).($i+$start))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            //设置行高
            $objPHPExcel->getActiveSheet()->getRowDimension($i+$start)->setRowHeight(60);
            //设置居中
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$start).':'.chr(65+$len-1).($i+$start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //所有垂直居中
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$start).':'.chr(65+$len-1).($i+$start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //设置自动换行
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$start).':'.chr(65+$len-1).($i+$start))->getAlignment()->setWrapText(true);
            //设置字体
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$start).':'.chr(65+$len-1).($i+$start))->getFont()->setName('微软雅黑');
        }
        $objPHPExcel->getActiveSheet()->getRowDimension($start+count($table_content1))->setRowHeight(60);
    }


    $start2 = $start+count($table_content1)+2;
    $len2 = count($table_header2);
    //设备
    $objPHPExcel->getActiveSheet()->mergeCells('A'.($start2-1).':E'.($start2-1));
    $objPHPExcel->getActiveSheet()->mergeCells('F'.($start2-1).':I'.($start2-1));
    $objPHPExcel->getActiveSheet()->getRowDimension(($start2-1))->setRowHeight(34);
    $objPHPExcel->getActiveSheet()->getStyle('A'.($start2-1).':'.chr(65+$len2-1).($start2-1))->getFont()->setName('微软雅黑');
    $objPHPExcel->getActiveSheet()->getStyle('A'.($start2-1).':'.chr(65+$len2-1).($start2-1))->getFont()->setBold(true);
    //平行、垂直居中、边框
    $objPHPExcel->getActiveSheet()->getStyle('A'.($start2-1).':'.chr(65+$len2-1).($start2-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A'.($start2-1).':'.chr(65+$len2-1).($start2-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A'.($start2-1).':'.chr(65+$len2-1).($start2-1))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.($start2-1), '设备申请列表');
    $objPHPExcel->getActiveSheet()->setCellValue('F'.($start2-1), $date2);    
    if(is_array($table_header2)){
        for($i = 0; $i < count($table_header2); $i++){
            $objPHPExcel->getActiveSheet()->setCellValue(chr(65+$i).$start2, $table_header2[$i]);
        }
        //设置行高
        $objPHPExcel->getActiveSheet()->getRowDimension($start2)->setRowHeight(40);
        //设置边框
        $objPHPExcel->getActiveSheet()->getStyle('A'.$start2.':'.chr(65+$len2-1).$start2)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        //设置居中
        $objPHPExcel->getActiveSheet()->getStyle('A'.$start2.':'.chr(65+$len2-1).$start2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //所有垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A'.$start2.':'.chr(65+$len2-1).$start2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置自动换行
        $objPHPExcel->getActiveSheet()->getStyle('A'.$start2.':'.chr(65+$len2-1).$start2)->getAlignment()->setWrapText(true);
        //加粗
        $objPHPExcel->getActiveSheet()->getStyle('A'.$start2.':'.chr(65+$len2-1).$start2)->getFont()->setBold(true);
    }
    /**
     * 设置表内容
     * array(array('id'=>1, 'name'='小明'), array('id'=>2, 'name'='小红'));
     */

    $end2= $start2+1;
    if(is_array($table_content2)){
        for($i = 0; $i < count($table_content2); $i++){
            $ascii = 65;
            foreach($table_content2[$i] as $val){
                $objPHPExcel->getActiveSheet()->setCellValue(chr($ascii).($i+$end2), $val);
                $ascii++;
            }
            //设置边框
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$end2).':'.chr(65+$len2-1).($i+$end2))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            //设置行高
            $objPHPExcel->getActiveSheet()->getRowDimension($i+$end2)->setRowHeight(60);
            //设置居中
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$end2).':'.chr(65+$len2-1).($i+$end2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //所有垂直居中
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$end2).':'.chr(65+$len2-1).($i+$end2))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //设置自动换行
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$end2).':'.chr(65+$len2-1).($i+$end2))->getAlignment()->setWrapText(true);
            //设置字体
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$end2).':'.chr(65+$len2-1).($i+$end2))->getFont()->setName('微软雅黑');
        }

        $objPHPExcel->getActiveSheet()->getRowDimension($end2+count($table_content2))->setRowHeight(60);
    }



    $start3 = $end2+count($table_content3)+3;
    $len3 = count($table_header3);
    //广告
    $objPHPExcel->getActiveSheet()->mergeCells('A'.($start3-1).':C'.($start3-1));
    $objPHPExcel->getActiveSheet()->mergeCells('D'.($start3-1).':F'.($start3-1));
    $objPHPExcel->getActiveSheet()->getRowDimension(($start3-1))->setRowHeight(34);
    $objPHPExcel->getActiveSheet()->getStyle('A'.($start3-1).':'.chr(65+$len3-1).($start3-1))->getFont()->setName('微软雅黑');
    $objPHPExcel->getActiveSheet()->getStyle('A'.($start3-1).':'.chr(65+$len3-1).($start3-1))->getFont()->setBold(true);
    //平行、垂直居中、边框
    $objPHPExcel->getActiveSheet()->getStyle('A'.($start3-1).':'.chr(65+$len3-1).($start3-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A'.($start3-1).':'.chr(65+$len3-1).($start3-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A'.($start3-1).':'.chr(65+$len3-1).($start3-1))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.($start3-1), '设备申请列表');
    $objPHPExcel->getActiveSheet()->setCellValue('D'.($start3-1), $date3);    
    if(is_array($table_header3)){
        for($i = 0; $i < count($table_header3); $i++){
            $objPHPExcel->getActiveSheet()->setCellValue(chr(65+$i).$start3, $table_header3[$i]);
        }
        //设置行高
        $objPHPExcel->getActiveSheet()->getRowDimension($start3)->setRowHeight(40);
        //设置边框
        $objPHPExcel->getActiveSheet()->getStyle('A'.$start3.':'.chr(65+$len3-1).$start3)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        //设置居中
        $objPHPExcel->getActiveSheet()->getStyle('A'.$start3.':'.chr(65+$len3-1).$start3)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //所有垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A'.$start3.':'.chr(65+$len3-1).$start3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置自动换行
        $objPHPExcel->getActiveSheet()->getStyle('A'.$start3.':'.chr(65+$len2-1).$start3)->getAlignment()->setWrapText(true);
        //加粗
        $objPHPExcel->getActiveSheet()->getStyle('A'.$start3.':'.chr(65+$len3-1).$start3)->getFont()->setBold(true);
    }
    /**
     * 设置表内容
     * array(array('id'=>1, 'name'='小明'), array('id'=>2, 'name'='小红'));
     */


    $end3= $start3+1;
    if(is_array($table_content3)){
        for($i = 0; $i < count($table_content3); $i++){
            $ascii = 65;
            foreach($table_content3[$i] as $val){
                $objPHPExcel->getActiveSheet()->setCellValue(chr($ascii).($i+$end3), $val);
                $ascii++;
            }
            //设置边框
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$end3).':'.chr(65+$len3-1).($i+$end3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            //设置行高
            $objPHPExcel->getActiveSheet()->getRowDimension($i+$end3)->setRowHeight(60);
            //设置居中
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$end3).':'.chr(65+$len3-1).($i+$end3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //所有垂直居中
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$end3).':'.chr(65+$len3-1).($i+$end3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //设置自动换行
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$end3).':'.chr(65+$len3-1).($i+$end3))->getAlignment()->setWrapText(true);
            //设置字体
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$end3).':'.chr(65+$len3-1).($i+$end3))->getFont()->setName('微软雅黑');
        }
    }
    ob_end_clean();//清除缓冲区,避免乱码
    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
    header('Content-Disposition: attachment;filename="' . $file_name . '.xls"');
    header('Cache-Control: max-age=0');
    //保存
    import("Org.Utrl.PHPExcel.IOFactory");
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
}



/**
 * 导出excel表格  多个表
 * @param string $file_name          文件名
 * @param string $title              标题
 * @param string $table_header       表头
 * @param string $table_content      表内容
 * @throws PHPExcel_Exception
 * @throws PHPExcel_Reader_Exception
 * @author Yu-Bin Zhuang
 */
function Mulitexcel($file_name = "Schedule List", $title = "个人日程表", $username='', $company='', $table_header1 = '', $table_content1 = '', $user_type = 1){
    import('Org.Util.PHPExcel');
    $objPHPExcel = new \PHPExcel();
    //设置excel属性
    $objPHPExcel->getProperties()->setCreator("JAMES")
        ->setLastModifiedBy("JAMES")
        ->setTitle("zltrans")
        ->setSubject("Dorder")
        ->setDescription("Dorder List")
        ->setKeywords("Dorder")
        ->setCategory("Test result file");

/***
***基本设置开始
***/
    //设置标题
    $objPHPExcel->getActiveSheet()->setTitle($title);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'GITF 广州国际旅游展览会');
    $objPHPExcel->getActiveSheet()->setCellValue('A2', '  账号名：'.$username);    
    $objPHPExcel->getActiveSheet()->setCellValue('B2', '  '.$company);


    //设置表头行高
    $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(34);
    $objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(34);
    //设置字体样式
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('微软雅黑');
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(11);
    // $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    // $objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getFont()->setBold(true);
        
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setName('微软雅黑');
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(11);
    $objPHPExcel->getActiveSheet()->getStyle('A4:F4')->getFont()->setSize(10);
    
    $objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFont()->setName('微软雅黑');

    //设置居中
    $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    // $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //所有垂直居中
    $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    //设置单元格边框
    $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    //设置自动换行
    $objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setWrapText(true);

    $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);//设置单元格宽度
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
/**
***基本设置结束
****/

    if($user_type == 1){
        //如果是参展商的话
        $objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
        $objPHPExcel->getActiveSheet()->mergeCells('B2:J2');
        //第一行数据的行数
        $start = 5;

        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
        
        //设置边框   先边框再合并，包有边框！
        $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('B2:J2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        //联合第3行
        $objPHPExcel->getActiveSheet()->mergeCells('B2:J2');
        $objPHPExcel->getActiveSheet()->mergeCells('F3:J3');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:A4');
        $objPHPExcel->getActiveSheet()->mergeCells('B3:B4');
        $objPHPExcel->getActiveSheet()->mergeCells('C3:C4');
        $objPHPExcel->getActiveSheet()->mergeCells('D3:D4');
        $objPHPExcel->getActiveSheet()->mergeCells('E3:E4');
        //合并-》居中
        $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //所有垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //第四行评论高度
        $objPHPExcel->getActiveSheet()->getRowDimension(4)->setRowHeight(20);
        $objPHPExcel->getActiveSheet()->setCellValue('F4', 'No show');
        $objPHPExcel->getActiveSheet()->setCellValue('G4', 'Poor');
        $objPHPExcel->getActiveSheet()->setCellValue('H4', 'Average');
        $objPHPExcel->getActiveSheet()->setCellValue('I4', 'Good');
        $objPHPExcel->getActiveSheet()->setCellValue('J4', 'Expecting');
    }else{
        //如果是买家的话
        $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
        $objPHPExcel->getActiveSheet()->mergeCells('B2:F2');
        //第一行数据的行数
        $start = 4;
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    }
    
    /**
     * 设置表头
     * $table_header=array('订单', '姓名', '电话')
     */

    if(is_array($table_header1)){
        for($i = 0; $i < count($table_header1); $i++){
            $objPHPExcel->getActiveSheet()->setCellValue(chr(65+$i).'3', $table_header1[$i]);
        }
            //设置边框
            $objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            if($user_type == 1){
                //设置行高
                $objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(40);
                //加粗
                $objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->getFont()->setBold(true);
            }else{
                //设置行高
                $objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(60);
                //加粗
                $objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFont()->setBold(true);
            }

            //设置居中
            $objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //所有垂直居中
            $objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //设置自动换行
            $objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setWrapText(true);
    
    }
    if($user_type == 1){
        // $objPHPExcel->getActiveSheet()->setCellValue(chr(65+$i).'3', $table_header1[$i]);
    }else{
        $objPHPExcel->getActiveSheet()->setCellValue('F3', "展商签名  Exhibitor's Signature");
    }
    /**
     * 设置表内容
     * array(array('id'=>1, 'name'='小明'), array('id'=>2, 'name'='小红'));
     */
    // 
    if(is_array($table_content1)){
        for($i = 0; $i < count($table_content1); $i++){
            $ascii = 65;
            foreach($table_content1[$i] as $val){
                $objPHPExcel->getActiveSheet()->setCellValue(chr($ascii).($i+$start), $val);
                $ascii++;
            }
            if($user_type == 1){
                //设置边框
                $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$start).':J'.($i+$start))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            }else{
                //设置边框
                $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$start).':F'.($i+$start))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            }
            
            //设置行高
            $objPHPExcel->getActiveSheet()->getRowDimension($i+$start)->setRowHeight(60);
            //设置居中
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$start).':F'.($i+$start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //所有垂直居中
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$start).':F'.($i+$start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //设置自动换行
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$start).':F'.($i+$start))->getAlignment()->setWrapText(true);
            //设置字体
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+$start).':F'.($i+$start))->getFont()->setName('微软雅黑');
        }
        // print_r($i);exit();
    }


    ob_end_clean();//清除缓冲区,避免乱码
    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
    header('Content-Disposition: attachment;filename="' . $file_name . '.xls"');
    header('Cache-Control: max-age=0');
    //保存
    import("Org.Utrl.PHPExcel.IOFactory");
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
}

/**
 * 上传excel表
 * @param $file_path
 * @return array|void
 * @author Yu-Bin Zhuang
 */
function upexcel($file_path){
    import('Org.Util.PHPExcel');
    import('Org.Util.PHPExcel.Writer.Excel5');//其他低版本excel
    import('Org.Util.PHPExcel.Writer.Excel2007');//2007版本的excel
    //默认用2007
    $PHPReader = new PHPExcel_Reader_Excel2007();
    if(!$PHPReader->canRead($file_path)){
        $PHPReader = new PHPExcel_Reader_Excel5();
        if(!$PHPReader->canRead($file_path)){
            echo '读取失败！';
            return;
        }
    }
    $PHPExcel = $PHPReader->load($file_path);
    $currentSheet = $PHPExcel->getSheet(0);//读取第一个工作表
    $allColumn = $currentSheet->getHighestColumn();//最大列号
    $allRow = $currentSheet->getHighestRow();//行号
    $arr = array();
    /*从第二行开始输出，因为excel表中第一行为列名*/
    for($currentRow = 2;$currentRow <= $allRow;$currentRow++){
        /**从第A列开始输出*/
        for($currentColumn= 'A';$currentColumn<= $allColumn; $currentColumn++){
            $val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65,$currentRow)->getValue();/**ord()将字符转为十进制数*/
            if($val!=''){
                $arr[$currentRow-2][] = $val;
            }
            /**如果输出汉字有乱码，则需将输出内容用iconv函数进行编码转换，如下将gb2312编码转为utf-8编码输出*/
            //echo iconv('utf-8','gb2312', $val)."\t";
        }
    }
    return $arr;

}

/**
 * 根据id获得菜单名
 * @param $id
 * @return bool
 * @author Yu-Bin Zhuang
 */
function getIdByCategoryName($id){
    if(empty($id)){
        return false;
    }
    $model = M("Category");
    $data = $model->field('title')->find($id);
    return $data['title'];
}

/**
 * 根据id获得菜单图片array
 * @param $id
 * @return bool
 * @author Yu-Bin Zhuang
 */
function getIdByMenuImg($id){
    if(empty($id)){
        return false;
    }
    $model = M("Background");
    $where = array(
        'pid' => $id,
        'status' => 1
    );
    $order = array(
        'sort desc'
    );
    $data = $model->field('cover_url')->where($where)->order($order)->select();
    return $data;
}

/**
 * 发送邮件
 * @param $address
 * @param $title
 * @param $content
 * @param $fromuser
 * @return bool
 * @author Yu-Bin Zhuang
 */
function sendMessage($address, $title, $content, $fromuser){
    vendor('PHPMailer.class#phpmailer');
    $mail             = new PHPMailer();
    /*服务器相关信息*/
    $mail->IsSMTP();                        //设置使用SMTP服务器发送
    $mail->SMTPAuth   = true;               //开启SMTP认证
    $mail->Host       = 'smtp.163.com';   	    //设置 SMTP 服务器,自己注册邮箱服务器地址
    $mail->Username   = '13760676791';  		//发信人的邮箱名称
    $mail->Password   = '93105zyb';          //发信人的邮箱密码
    /*内容信息*/
    $mail->IsHTML(true); 			         //指定邮件格式为：html
    $mail->CharSet    ="UTF-8";			     //编码
    $mail->From       = '13760676791@163.com';	 		 //发件人完整的邮箱名称
    $mail->FromName   = $fromuser;			 //发信人署名
    $mail->Subject    = $title;  			 //信的标题
    $mail->MsgHTML($content);  				 //发信内容
    //$mail->AddAttachment("15.jpg");	     //附件
    /*发送邮件*/
    $mail->AddAddress($address);  			 //收件人地址
    //使用send函数进行发送
    if($mail->Send()) {
        return true;
    } else {
        return  false;//$mail->ErrorInfo;
    }
}

/**
 * 获得分钟
 * @return array
 * @author Yu-Bin Zhuang
 */
function getBranch(){
    return array(
        0  => '00',
        5  => '05',
        10 => 10,
        15 => 15,
        20 => 20,
        25 => 25,
        30 => 30,
        35 => 35,
        40 => 40,
        45 => 45,
        50 => 50,
        55 => 55,
    );
}

