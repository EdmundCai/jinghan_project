<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!--[if lt IE 9]>
<script type="text/javascript" src="/hotel/Public/Admin/lib/html5.js"></script>
<script type="text/javascript" src="/hotel/Public/Admin/lib/respond.min.js"></script>
<script type="text/javascript" src="/hotel/Public/Admin/lib/PIE_IE678.js"></script>
<![endif]-->
<link href="/hotel/Public/Admin/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/hotel/Public/Admin/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link href="/hotel/Public/Admin/lib/font-awesome/font-awesome.min.css" rel="stylesheet" type="text/css" />
<!--[if IE 7]>
<link href="/hotel/Public/Admin/lib/font-awesome/font-awesome-ie7.min.css" rel="stylesheet" type="text/css" />
<![endif]-->
<link href="/hotel/Public/Admin/lib/iconfont/iconfont.css" rel="stylesheet" type="text/css" />
<!--[if IE 6]>
<script type="text/javascript" src="/hotel/Public/Admin/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>我的桌面</title>
</head>
<body>
<div class="pd-20" style="padding-top:20px;">
  <p class="f-20 text-success">欢迎使用后台管理系统！</p>
  <p>登录次数：<?php echo ($adminInfo['login_times']); ?> </p>
  <p>上次登录IP：<?php echo ($adminInfo['last_ip']); ?>  上次登录时间：<?php echo ($adminInfo['last_time']); ?></p>
  
  <table class="table table-border table-bordered table-bg mt-20">
    <thead>
      <tr>
        <th colspan="2" scope="col">服务器信息</th>
      </tr>
    </thead>
    <tbody>
      <?php if(is_array($serverInfo)): $i = 0; $__LIST__ = $serverInfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr> 
            <td class="text"><?php echo ($key); ?>：</td>
            <td class="input"><?php echo ($v); ?></td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
  </table>
</div>
<footer class="footer">
  <p>感谢jQuery、layer、laypage、Validform、UEditor、My97DatePicker、iconfont、Font Awesome、Datatables、jquery.fileupload、Lightbox插件<br>Copyright &copy;2015 H-ui.admin v2.2 All Rights Reserved.<br>
    本后台系统由<a href="http://www.h-ui.net/" target="_blank" title="H-ui前端框架">H-ui前端框架</a>提供前端技术支持</p>
</footer>
<script type="text/javascript" src="/hotel/Public/Admin/lib/jquery.min.js"></script>
<script type="text/javascript" src="/hotel/Public/Admin/js/H-ui.js"></script>
<script type="text/javascript" src="/hotel/Public/Admin/js/H-ui.admin.doc.js"></script>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?080836300300be57b7f34f4b3e97d911";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F080836300300be57b7f34f4b3e97d911' type='text/javascript'%3E%3C/script%3E"));
</script>
</body>
</html>