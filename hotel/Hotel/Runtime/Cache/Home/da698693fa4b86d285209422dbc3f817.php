<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>广州地区酒店行业协会</title>
<meta name="description" content="广州地区酒店行业协会,联系我们">
<meta name="keywords" content="广州地区酒店行业协会,联系我们">
<link href="/jinghan_project/hotel/Public/Css/style.css" rel="stylesheet">
<link href="/jinghan_project/hotel/Public/Css/main.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="/jinghan_project/hotel/Public/Js/jquery-2.0.3.min.js" ></script>
</head>
<body>
<div id="war">
    <div id="header">
        <div id="logo">
	<div id="lgpic"></div>
</div>
<div id="div5"></div>
<div id="div2"></div>
<div id="hbtm"><!-- 头部导航 -->
<ul id="hnav">
	<li style="margin-left:122px;"><a href="<?php echo U('Index/index');?>">首页<span class="nav_b">|</span></a></li>
	<li id="next">
		<span><a href="<?php echo U('News/index?type=news');?>">最新资讯及刊物<span class="nav_b">|</span></a></span>
		<!-- <ul>
			<li><a href="javascript:;">行业资讯</a></li>
			<li><a href="javascript:;">协会活动</a></li>
			<li><a href="javascript:;">协会刊物</a></li>
			<li><a href="javascript:;">每月简报</a></li>
		</ul> -->
	</li>
	<li><a href="<?php echo U('Intelligence/index?type=monthly');?>">经营情报<span class="nav_b">|</span></a></li>
	<li><a href="<?php echo U('Promotion/index');?>">酒店推广<span class="nav_b">|</span></a></li>
	<li><a href="<?php echo U('Purchase/index');?>">酒店用品采购<span class="nav_b">|</span></a></li>
	<li><a href="<?php echo U('Goals/index?type=mission');?>">使命及目标<span class="nav_b">|</span></a></li>
	<li><a href="<?php echo U('Contact/index');?>">联络我们</a></li>
</ul></div>
<div id="div27"></div>
    </div>

    <div id="content">
        <div id="all_con">
            <div id="c_nav" style="margin-left:-24px;"><b>最新资讯及刊物</b></div>
            <div id="c_left">
                <div id="next_nav">    
                    <ul class="dropdown">
  <li class="vtitle">
      <a >行业资讯</a>
      <ul <?php if($tap != news): ?>style="display:none;"<?php endif; ?>>
          <li <?php if($cursor == news): ?>class="checkedli"<?php endif; ?>><a href="<?php echo U('News/index?type=news');?>">行业资讯</a></li>
          <li <?php if($cursor == activity): ?>class="checkedli"<?php endif; ?>><a href="<?php echo U('News/index?type=activity');?>">协会活动</a></li>
          <li <?php if($cursor == notice): ?>class="checkedli"<?php endif; ?>><a href="<?php echo U('News/index?type=notice');?>">最新通知</a></li>
          <li <?php if($cursor == meeting): ?>class="checkedli"<?php endif; ?>><a href="<?php echo U('News/index?type=meeting');?>">会展会议</a></li>
      </ul>
  </li>

  <li class="vtitle">
      <a >协会刊物</a>
      <ul <?php if($tap != publication): ?>style="display:none;"<?php endif; ?>>
          <li <?php if($cursor == publication): ?>class="checkedli"<?php endif; ?>><a href="<?php echo U('News/book?type=publication');?>">协会刊物</a></li>
          <li <?php if($cursor == brief): ?>class="checkedli"<?php endif; ?>><a href="<?php echo U('News/book?type=brief');?>">每月简报</a></li>
      </ul>
  </li>
</ul>
<!-- <script src="/jinghan_project/hotel/Public/Js/tendina.js"></script> -->
<script>
  // $('.dropdown').tendina({
  //   animate: true,
  //   speed: 500,
  //   openCallback: function($clickedEl) {
  //     console.log($clickedEl);
  //   },
  //   closeCallback: function($clickedEl) {
  //     console.log($clickedEl);
  //   }
  // })
$(function(){
  $('.vtitle').click(function(){
    // $(".vtitle").find('ul').slideUp();
    // $(this).find('ul').toggle(
    //   function(){ 
    //       $(this).find('ul').hide(); 
    //   },function(){  
    //       $(this).find('ul').slide(); 
    //   });
    $(this).find('ul').slideToggle(300);
  });
});
</script>

                </div>
                <div id="divh"></div>
            </div>
            <div id="c_con">
                <div id="news">
                    <!-- 如果不是专业刊物页面的话展示新闻列表形式 -->
                    <?php if($cursor == publication): ?><div id="top">
                            <li><a href="<?php echo U('News/book?type=publication');?>" <?php if($choose == article): ?>class="top_check"<?php endif; ?>>精选文摘</a></li>
                            <li><a href="<?php echo U('News/book?type=publication&choose=special');?>" <?php if($choose == special): ?>class="top_check"<?php endif; ?>>专业会刊</a></li>   
                            <li><a href="<?php echo U('News/book?type=publication&choose=download');?>" <?php if($choose == download): ?>class="top_check"<?php endif; ?>>会刊下载</a></li> 
                        </div><?php endif; ?>
                    <?php if($choose != special): ?><ul id="news_list">
                            <li><?php echo ($data); ?><span class="date">2016-2-1</span></li>
                            <li><a href="">品尝台湾美食 领略宝岛魅力 <span class="date">2016-2-1</span></a></li>
                            <li><a href="">品尝台湾美食 领略宝岛魅力 <span class="date">2016-2-1</span></a></li>
                            <li><a href="">品尝台湾美食 领略宝岛魅力 <span class="date">2016-2-1</span></a></li>
                            <li><a href="">品尝台湾美食 领略宝岛魅力 <span class="date">2016-2-1</span></a></li>
                            <li><a href="">品尝台湾美食 领略宝岛魅力 <span class="date">2016-2-1</span></a></li>
                            <li><a href="">品尝台湾美食 领略宝岛魅力 <span class="date">2016-2-1</span></a></li>
                            <li><a href="">品尝台湾美食 领略宝岛魅力 <span class="date">2016-2-1</span></a></li>
                            <li><a href="">品尝台湾美食 领略宝岛魅力 <span class="date">2016-2-1</span></a></li>
                            <li><a href="">品尝台湾美食 领略宝岛魅力 <span class="date">2016-2-1</span></a></li>
                            <li><a href="">品尝台湾美食 领略宝岛魅力 <span class="date">2016-2-1</span></a></li>
                            <li><a href="">品尝台湾美食 领略宝岛魅力 <span class="date">2016-2-1</span></a></li>
                            <li><a href="">品尝台湾美食 领略宝岛魅力 <span class="date">2016-2-1</span></a></li>
                        </ul>        
                    <?php else: ?>
                    <!--  否则展示精选文摘/专业会刊  -->
                        <ul id="book_list">
                            <!-- <?php echo ($data); ?> -->
                            <li>
                                <a href="">
                                    <img src="/jinghan_project/hotel/Public/Images/test_image/special1.jpg">
                                    <span>《广州地区酒店》（2013）第1期总第89期</span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <img src="/jinghan_project/hotel/Public/Images/test_image/special1.jpg">
                                    <span>《广州地区酒店》（2013）第1期总第89期</span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <img src="/jinghan_project/hotel/Public/Images/test_image/special1.jpg">
                                    <span>《广州地区酒店》（2013）第1期总第89期</span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <img src="/jinghan_project/hotel/Public/Images/test_image/special1.jpg">
                                    <span>《广州地区酒店》（2013）第1期总第89期</span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <img src="/jinghan_project/hotel/Public/Images/test_image/special1.jpg">
                                    <span>《广州地区酒店》（2013）第1期总第89期</span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <img src="/jinghan_project/hotel/Public/Images/test_image/special1.jpg">
                                    <span>《广州地区酒店》（2013）第1期总第89期</span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <img src="/jinghan_project/hotel/Public/Images/test_image/special1.jpg">
                                    <span>《广州地区酒店》（2013）第1期总第89期</span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <img src="/jinghan_project/hotel/Public/Images/test_image/special1.jpg">
                                    <span>《广州地区酒店》（2013）第1期总第89期</span>
                                </a>
                            </li>
                        </ul><?php endif; ?>  
                </div>

                <div id="pages">
                    <a href="">上一页</a>
                    <a href="">1</a>
                    <a href="">2</a>
                    <a href="">3</a>
                    <a href="">4</a>
                    <a href="">下一页</a>
                </div>
            </div>
            <div id="c_right">
                <div id="ht_search">
	<!-- - - 这尼玛居然是2个排版  内页的话就用下面这个gif！ -->
	<?php if($current != index): ?><p style="margin-right:15px;">
			<img src="/jinghan_project/hotel/Public/Images/map.gif" style="width:100%;" alt="广州地区酒店">
		</p><?php endif; ?>
	
	<form action="<?php echo U('Index/index');?>" method="post">
		<p><b>区域：</b></p>
		<ul id="city">
			<li><input type="radio" name="city" value="1">白云区</li>
			<li><input type="radio" name="city" value="2">海珠区</li>
			<li><input type="radio" name="city" value="3">荔湾区</li>
			<li><input type="radio" name="city" value="4">越秀区</li>
			<li><input type="radio" name="city" value="5">天河区</li>
			<li><input type="radio" name="city" value="6">番禺区</li>
			<li><input type="radio" name="city" value="7">黄埔区</li>
			<li><input type="radio" name="city" value="8">萝岗区</li>
			<li><input type="radio" name="city" value="9">花都区</li>
			<li><input type="radio" name="city" value="10">增城市</li>
			<li><input type="radio" name="city" value="11">从化区</li>
			<li><input type="radio" name="city" value="12">南沙区</li>
		</ul>
		<div class="clear"></div>
		<p><b>星级：</b></p>
		<ul id="star">
			<li><input type="radio" name="star" value="1">一星</li>
			<li><input type="radio" name="star" value="2">二星</li>
			<li><input type="radio" name="star" value="3">三星</li>
			<li><input type="radio" name="star" value="4">四星</li>
			<li><input type="radio" name="star" value="5">五星</li>
		</ul>
		<div class="clear"></div>
		<p><input type="text" name="ht_name" id="ht_name" placeholder="搜索:酒店名称"></p>
		<p><input type="submit" value="搜索" id="submit"></p>
	</form>
	<script>
	$(function(){
		$("#city li input").click(function(){
			$('#city li').removeClass('radio');    						
			$('input:radio[name="city"]:checked').parent().addClass('radio');
		});
		$("#star li input").click(function(){
			$('#star li').removeClass('radio');    						
			$('input:radio[name="star"]:checked').parent().addClass('radio');
		});
	})
	</script>
</div>
                <div class="clear"></div>
                <div class="cnav_r">                    
                    <div id="cnav">
	<ul>
		<a href="javascript:;"><li class="li_yd">会刊在线阅读</li></a>
		<a href="javascript:;"><li class="li_dc">行业调查</li></a>
		<a href="javascript:;"><li class="li_jb">每月简报</li></a>
		<a href="javascript:;"><li class="li_xh">加入协会</li></a>
		<a href="javascript:;"><li class="li_wd">公众问答</li></a>
		<a href="mailto:chairman@gzha.com"><li class="li_yx" style="margin-right:0px;">会长信箱</li></a>
	</ul>
</div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <div id="footer">
    	<p style="padding-top:12px;">友情链接：中华人民共和国国家旅游局，饭店统计分析系统，广州旅游资源网</p>
<p style="border-bottom:1px #FFF dashed;height:1px;margin:0 auto;padding:0px;"></p>
<p>Copyright © Guangzhou Hotels Association 广州地区酒店行业协会版权所有</p>
    </div>
</div>
</body>
</html>