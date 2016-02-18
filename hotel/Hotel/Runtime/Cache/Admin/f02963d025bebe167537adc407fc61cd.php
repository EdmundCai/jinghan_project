<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="/favicon.ico" >
<LINK rel="Shortcut Icon" href="/favicon.ico" />
<!--[if lt IE 9]>
<script type="text/javascript" src="/hotel/Public/Admin/lib/html5.js"></script>
<script type="text/javascript" src="/hotel/Public/Admin/lib/respond.min.js"></script>
<script type="text/javascript" src="/hotel/Public/Admin/lib/PIE_IE678.js"></script>
<![endif]-->
<link href="/hotel/Public/Admin/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/hotel/Public/Admin/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link href="/hotel/Public/Admin/css/style.css" rel="stylesheet" type="text/css" />
<link href="/hotel/Public/Admin/lib/iconfont/iconfont.css" rel="stylesheet" type="text/css" />
<link href="/hotel/Public/Admin/lib/font-awesome/font-awesome.min.css" rel="stylesheet" type="text/css" />
<!--[if IE 7]>
<link href="/hotel/Public/Admin/lib/font-awesome/font-awesome-ie7.min.css" rel="stylesheet" type="text/css" />
<![endif]-->
<!--[if IE 6]>
<script type="text/javascript" src="/hotel/Public/Admin/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>网站后台管理系统</title>
<meta name="keywords" content="网站后台模版,后台模版下载,后台管理系统模版,HTML后台模版下载">
<meta name="description" content="国产、轻量级、扁平化、完全免费开源的网站后台管理系统模版，适合中小型CMS后台系统。">
</head>
<body>
<header class="Hui-header cl"> <a class="Hui-logo l" title="H-ui.admin v2.2" href="">网站后台管理系统</a> <a class="Hui-logo-m l" href="" title="H-ui.admin">移动版</a> <span class="Hui-subtitle l">V2.2</span> <span class="Hui-userbox"><span class="c-white">超级管理员：<?php echo ($adminInfo['name']); ?></span> <a class="btn btn-danger radius ml-10" href="<?php echo U('unlogin');?>" title="退出"><i class="icon-off"></i> 退出</a></span> <a aria-hidden="false" class="Hui-nav-toggle" href="#"></a> </header>
<aside class="Hui-aside">
  <input runat="server" id="divScrollValue" type="hidden" value="" />
  <div class="menu_dropdown bk_2">

    <dl id="menu-picture">
      <dt><i class="icon-book"></i> 行业资讯栏目<i class="iconfont menu_dropdown-arrow">&#xf02a9;</i></dt>
      <dd>
        <ul>
          <li><a _href="<?php echo U('News/intro');?>" href="javascript:void(0)">行业资讯</a></li>
          <li><a _href="<?php echo U('News/organization');?>" href="javascript:void(0)">协会活动</a></li>
          <li><a _href="<?php echo U('News/organization');?>" href="javascript:void(0)">最新通知</a></li>
          <li><a _href="<?php echo U('News/organization');?>" href="javascript:void(0)">会展会议</a></li>
        </ul>
      </dd>
    </dl>
    <dl id="menu-picture">
      <dt><i class="icon-book"></i> 协会刊物栏目<i class="iconfont menu_dropdown-arrow">&#xf02a9;</i></dt>
      <dd>
        <ul>
          <li><a _href="<?php echo U('News/publiccation');?>" href="javascript:void(0)">协会刊物</a></li>
          <li><a _href="<?php echo U('News/brief');?>" href="javascript:void(0)">每月简报</a></li>
        </ul>
      </dd>
    </dl>
    <dl id="menu-picture">
      <dt><i class="icon-book"></i> 经营情报<i class="iconfont menu_dropdown-arrow">&#xf02a9;</i></dt>
      <dd>
        <ul>
          <li><a _href="<?php echo U('Intelligence/monthly');?>" href="javascript:void(0)">月度经营情报</a></li>
          <li><a _href="<?php echo U('Intelligence/yearly');?>" href="javascript:void(0)">年度经营情报</a></li>
          <li><a _href="<?php echo U('Intelligence/gjh');?>" href="javascript:void(0)">广交会经营情报</a></li>
          <li><a _href="<?php echo U('Intelligence/upload');?>" href="javascript:void(0)">经营情报上传</a></li>
        </ul>
      </dd>
    </dl>

    
    <!-- <dl id="menu-multimedia">
      <dt><i class="icon-edit"></i> 多媒体管理<i class="iconfont menu_dropdown-arrow">&#xf02a9;</i></dt>
      <dd>
        <ul>
            <li><a _href="<?php echo U('multimedia/bgmusic');?>" href="javascript:void(0)">背景音乐管理</a></li>
            <li><a _href="<?php echo U('multimedia/video');?>" href="javascript:void(0)">视频管理</a></li>
        </ul>
      </dd>
    </dl>
    <dl id="menu-product">
      <dt><i class="icon-beaker"></i> 产品库<i class="iconfont menu_dropdown-arrow">&#xf02a9;</i></dt>
      <dd>
        <ul>
          <li><a _href="<?php echo U('Category/index');?>" href="javascript:void(0)">分类管理</a></li>
          <li><a _href="<?php echo U('Product/index');?>" href="javascript:void(0)">产品管理</a></li>
        </ul>
      </dd>
    </dl> -->
   <?php if(($adminInfo["role_id"] == 1)): ?><dl id="menu-admin">
      <dt><i class="icon-key"></i> 管理员管理<i class="iconfont menu_dropdown-arrow">&#xf02a9;</i></dt>
      <dd>
        <ul>
          <li><a _href="<?php echo U('Role/adminrole');?>" href="javascript:void(0)">角色管理</a></li>
          <!-- <li><a _href="admin-permission.html" href="javascript:void(0)">权限管理</a></li> -->
          <li><a _href="<?php echo U('Admin/index');?>" href="javascript:void(0)">管理员列表</a></li>
        </ul>
      </dd>
    </dl><?php endif; ?>
    <!--<dl id="menu-system">
      <dt><i class="icon-cogs"></i> 系统管理<i class="iconfont menu_dropdown-arrow">&#xf02a9;</i></dt>
      <dd>
        <ul>
          <li><a _href="system-base.html" href="javascript:void(0)">基本设置</a></li>
          <li><a _href="system-lanmu.html" href="javascript:void(0)">栏目设置</a></li>
          <li><a _href="system-data.html" href="javascript:void(0)">数据字典</a></li>
          <li><a _href="system-shielding.html" href="javascript:void(0)">屏蔽词</a></li>
          <li><a _href="system-log.html" href="javascript:void(0)">系统日志</a></li>
        </ul>
      </dd>
    </dl>-->
  </div>
</aside>
<div class="dislpayArrow"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
<section class="Hui-article-box">
  <div id="Hui-tabNav" class="Hui-tabNav">
    <div class="Hui-tabNav-wp">
      <ul id="min_title_list" class="acrossTab cl">
        <li class="active"><span title="我的桌面" data-href="welcome.html">我的桌面</span><em></em></li>
      </ul>
    </div>
    <div class="Hui-tabNav-more btn-group"><a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i class="icon-step-backward"></i></a><a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i class="icon-step-forward"></i></a></div>
  </div>
  <div id="iframe_box" class="Hui-article">
    <div class="show_iframe">
      <div style="display:none" class="loading"></div>
      <iframe scrolling="yes" frameborder="0" src="<?php echo U('welcome');?>"></iframe>
    </div>
  </div>
</section>
<script type="text/javascript" src="/hotel/Public/Admin/lib/jquery.min.js"></script> 
<script type="text/javascript" src="/hotel/Public/Admin/lib/Validform_v5.3.2.js"></script> 
<script type="text/javascript" src="/hotel/Public/Admin/lib/layer1.8/layer.min.js"></script> 
<script type="text/javascript" src="/hotel/Public/Admin/js/H-ui.js"></script> 
<script type="text/javascript" src="/hotel/Public/Admin/js/H-ui.admin.js"></script> 
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