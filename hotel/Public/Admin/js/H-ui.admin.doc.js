/*H-ui.admin.doc.js date:15:42 2015-04-29 by:guojunhui*/
/*----------用户管理------------------*/
/*用户-添加*/
function user_add(w,h,title,url){
	layer_show(w,h,title,url);
}
/*用户-查看*/
function user_show(id,w,h,title,url){
	layer_show(w,h,title,url);
}
/*用户-密码-修改*/
function user_password_edit(id,w,h,title,url){
	layer_show(w,h,title,url);
}

/*用户-编辑*/
function user_edit(id,w,h,title,url){
	layer_show(w,h,title,url);
}
/*用户-编辑-保存*/
function user_edit_save(obj,id){
	var i = parent.layer.getFrameIndex();
	parent.layer.close(i);
}

/*用户-停用*/
function user_stop(obj,id){
	layer.confirm('确认要停用吗？',function(index){
		$(obj).parents("tr").find(".user-manage").prepend('<a style="text-decoration:none" onClick="user_start(this,\'10001\')" href="javascript:;" title="启用"><i class="icon-hand-up"></i></a>');
		$(obj).parents("tr").find(".user-status").html('<span class="label radius">已停用</span>');
		$(obj).remove();
		layer.msg('已停用!',1);
	});
}
/*用户-启用*/
function user_start(obj,id){
	layer.confirm('确认要启用吗？',function(index){
		$(obj).parents("tr").find(".user-manage").prepend('<a style="text-decoration:none" onClick="user_stop(this,\'10001\')" href="javascript:;" title="停用"><i class="icon-hand-down"></i></a>');
		$(obj).parents("tr").find(".user-status").html('<span class="label label-success radius">已启用</span>');
		$(obj).remove();
		layer.msg('已启用!',1);
	});
}
/*用户-删除*/
function user_del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
		$(obj).parents("tr").remove();
		layer.msg('已删除!',1);
	});
}
/*------------资讯管理----------------*/
/*获取分类值*/
function SetSubID(obj) {
	$("#hid_ccid").val($(obj).val());
}
/*资讯-分类-添加*/
function article_class_add(obj){
	var v = $("#article-class-val").val();
	if(v==""||v==null){
		layer.msg('分类名称不能为空！', 1);
		return false;
	}else{
		//ajax请求 添加分类
		$.ajax({
	        url: $('#form_category_add').attr('action'),
	        type: 'POST',
	        dataType: 'json',
	        data: $('#form_category_add').serialize(),
	    })
	    .done(function(response) {
	        console.log("success");
	        if (response.status == '1') {
	            alert(response.data);
	            location.replace(location.href);
	        }else{
	            alert('操作失败：'+response.data);
	        }
	    })
	    .fail(function(jqXHR, textStatus) {
	        console.log("error:" + textStatus);
	    })
	    .always(function() {
	        console.log("complete");
	    });
	}
}
function my_article_class_add(w,h,title,url){
    layer_show(w,h,title,url);
}

/*资讯-分类-删除*/
function article_class_del(obj,id){
	layer.confirm('确认要删除吗？（包括该分类下的所有子类）',function(index){
		$.ajax({
			url: $(obj).attr('data-href'),
			type: 'POST',
			dataType: 'json',
			data: {id: id},
		})
	    .done(function(response) {
	        console.log("success");
	        if (response.status == '1') {
				layer.msg(response.info, 1, function(){
					history.go(0);
				});
	        }else{
	            layer.msg(response.info,1);
	        }
	    })
	    .fail(function(jqXHR, textStatus) {
	        console.log("error:" + textStatus);
	    })
	    .always(function() {
	        console.log("complete");
	    });
	});
}
/*资讯-分类-编辑*/
function article_class_edit(id,w,h,title,url){
	layer_show(w,h,title,url);
}
/*资讯-分类-编辑保存*/
function class_save(obj,id){
	$.ajax({
		url: $('#form-article-class').attr('action'),
		type: 'POST',
		dataType: 'json',
		data: $('#form-article-class').serialize(),
	})
    .done(function(response) {
        console.log("success");
        if (response.status == '1') {
			parent.history.go(0);
			//当你在iframe页面关闭自身时
			var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
			parent.layer.close(index); //再执行关闭
        }else{
            alert(response.data);
        }
    })
    .fail(function(jqXHR, textStatus) {
        console.log("error:" + textStatus);
    })
    .always(function() {
        console.log("complete");
    });
}
/*资讯-添加*/
function article_add(w,h,title,url){
	layer_show(w,h,title,url);
}
/*事件*/
function event_add(w,h,title,url){
	layer_show(w,h,title,url);
}
/*资讯-编辑*/
function article_edit(id,w,h,title,url){
	layer_show(w,h,title,url);
}
/*资讯-下架*/
function article_xiajia(obj,id){
	$(obj).parents("tr").find(".article-manage").prepend('<a style="text-decoration:none" onClick="article_fabu(this,\'10001\')" href="javascript:;" title="发布"><i class="icon-hand-up"></i></a>');
	$(obj).parents("tr").find(".article-status").html('<span class="label radius">已下架</span>');
	$(obj).remove();
}
/*资讯-发布*/
function article_fabu(obj,id){
	$(obj).parents("tr").find(".article-manage").prepend('<a style="text-decoration:none" onClick="article_xiajia(this,\'10001\')" href="javascript:;" title="下架"><i class="icon-hand-down"></i></a>');
	$(obj).parents("tr").find(".article-status").html('<span class="label label-success radius">已发布</span>');
	$(obj).remove();
}
/*管理员-删除*/
function article_del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
		$(obj).parents("tr").remove();
		layer.msg('已删除!',1);
	});
}
/*------------图片库--------------*/
/*图片库-分类-添加*/
function picture_class_add(obj){
	var v = $("#picture-class-val").val();
	if(v==""||v==null){
		return false;
	}else{
		//ajax请求 添加分类
	}
}

/*图片库-分类-编辑*/
function picture_class_edit(id,w,h,title,url){
	layer_show(w,h,title,url);
}
/*图片库-添加*/
function picture_add(w,h,title,url){
	layer_show(w,h,title,url);
}
/*图片库-编辑*/
function picture_edit(id,w,h,title,url){
	layer_show(w,h,title,url);
}
/*图片库-下架*/
function picture_xiajia(obj,id){
	$(obj).parents("tr").find(".picture-manage").prepend('<a style="text-decoration:none" onClick="picture_fabu(this,\'10001\')" href="javascript:;" title="发布"><i class="icon-hand-up"></i></a>');
	$(obj).parents("tr").find(".picture-status").html('<span class="label radius">已下架</span>');
	$(obj).remove();
}
/*图片库-发布*/
function picture_fabu(obj,id){
	$(obj).parents("tr").find(".picture-manage").prepend('<a style="text-decoration:none" onClick="picture_xiajia(this,\'10001\')" href="javascript:;" title="下架"><i class="icon-hand-down"></i></a>');
	$(obj).parents("tr").find(".picture-status").html('<span class="label label-success radius">已发布</span>');
	$(obj).remove();
}
/*管理员-删除*/
function picture_del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
		$(obj).parents("tr").remove();
		layer.msg('已删除!',1);
	});
}
/*------------产品库------------------*/
/*产品-品牌-编辑*/
function product_brand_edit(id,w,h,title,url){
	layer_show(w,h,title,url);
}
/*产品-管理-发布&下架*/
function change_product_status(obj,id){
	var status = 1;
	if ($(obj).find('.label-success').length) {
		status = 0;
	};
	$.ajax({
		url: $(obj).attr('data-href'),
		data: {
			id: id,
			status: status,
		},
	})
	.done(function(response){
		if (response.status == '1') {
			history.go(0);
		};
	});
}
/*产品-管理-编辑*/
function product_edit(id,w,h,title,url){
	layer_show(w,h,title,url);
}
/*产品-管理-编辑保存*/
function product_save(){
	$.ajax({
		url: $('#form-product').attr('action'),
		type: 'POST',
		dataType: 'json',
		data: $('#form-product').serialize(),
	})
    .done(function(response) {
        console.log("success");
        if (response.status == '1') {
			parent.history.go(0);
			//当你在iframe页面关闭自身时
			var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
			parent.layer.close(index); //再执行关闭
        }else{
            alert(response.data);
        }
    })
    .fail(function(jqXHR, textStatus) {
        console.log("error:" + textStatus);
    })
    .always(function() {
        console.log("complete");
    });
}
/*产品-管理-新增*/
function product_add(){
	$.ajax({
		url: $('#form-product').attr('action'),
		type: 'POST',
		dataType: 'json',
		data: $('#form-product').serialize(),
	})
    .done(function(response) {
        console.log("success");
        if (response.status == '1') {
			parent.history.go(0);
			//当你在iframe页面关闭自身时
			var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
			parent.layer.close(index); //再执行关闭
        }else{
            alert(response.data);
        }
    })
    .fail(function(jqXHR, textStatus) {
        console.log("error:" + textStatus);
    })
    .always(function() {
        console.log("complete");
    });
}
/*产品-管理-删除*/
function product_del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
		$.ajax({
			url: $(obj).attr('data-href'),
			type: 'POST',
			dataType: 'json',
			data: {id: id},
		})
	    .done(function(response) {
	        console.log("success");
	        if (response.status == '1') {
				layer.msg(response.info,1);
				history.go(0);
	        }else{
	            layer.msg(response.info,1);
	        }
	    })
	    .fail(function(jqXHR, textStatus) {
	        console.log("error:" + textStatus);
	    })
	    .always(function() {
	        console.log("complete");
	    });
	});
}

/*------------管理员管理--------------*/
/*管理员-角色-添加*/
function admin_role_add(w,h,title,url){
	layer_show(w,h,title,url);
}
/*管理员-角色-编辑*/
function admin_role_edit(id,w,h,title,url){
	layer_show(w,h,title,url);
}
/*管理员-角色-删除*/
function admin_role_del(obj,id){
	layer.confirm('角色删除须谨慎，确认要删除吗？',function(index){
		$(obj).parents("tr").remove();
		layer.msg('已删除!',1);
	});
}

/*管理员-权限-添加*/
function admin_permission_add(){
	
}
/*管理员-权限-编辑*/
function admin_permission_edit(id,w,h,title,url){
	layer_show(w,h,title,url);
}

/*管理员-权限-删除*/
function admin_permission_del(obj,id){
	layer.confirm('角色删除须谨慎，确认要删除吗？',function(index){
		$(obj).parents("tr").remove();
		layer.msg('已删除!',1);
	});
}

/*管理员-编辑-保存*/
function admin_edit_save(obj,id){
	var i = parent.layer.getFrameIndex();
	parent.layer.close(i);
}
/*管理员-新增*/

function admin_add(){
    $.ajax({
        url: $('#form_admin_add').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $('#form_admin_add').serialize(),
    })
    .done(function(response) {
        console.log("success");
        if (response.status == '1') {
            alert(response.data);
            location.replace(location.href);
        }else{
            alert(response.data)
        }
    })
    .fail(function(jqXHR, textStatus) {
        console.log("error:" + textStatus);
    })
    .always(function() {
        console.log("complete");
    });
    
}
/*管理员-删除*/
function admin_del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
		$.ajax({
			url: $(obj).attr('data-href'),
			type: 'POST',
			dataType: 'json',
			data: {id: id},
		})
	    .done(function(response) {
	        console.log("success");
	        if (response.status == '1') {
				layer.msg(response.info,1);
	        }else{
	            layer.msg(response.info,1);
	        }
	    })
	    .fail(function(jqXHR, textStatus) {
	        console.log("error:" + textStatus);
	    })
	    .always(function() {
	        console.log("complete");
	    });
	});
}
/*管理员-编辑*/
function admin_edit(id,w,h,title,url){
	layer_show(w,h,title,url);
}
/*管理员-停用*/
function change_status(obj,id){
	var status = 1;
	if ($(obj).find('.label-success').length) {
		status = 0;
	};
	$.ajax({
		url: $(obj).attr('data-href'),
		data: {
			id: id,
			status: status,
		},
	})
	.done(function(response){
		if (response.status == '1') {
			history.go(0);
		};
	});
}
/*管理员-启用*/
function admin_start(obj,id){
	$(obj).parents("tr").find(".admin-manage").prepend('<a style="text-decoration:none" onClick="admin_stop(this,\'10001\')" href="javascript:;" title="停用"><i class="icon-hand-down"></i></a>');
	$(obj).parents("tr").find(".admin-status").html('<span class="label label-success radius">已启用</span>');
	$(obj).remove();
}
/*------------系统管理--------------*/
/*系统管理-日志-删除*/
function system_log_del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
		$(obj).parents("tr").remove();
		layer.msg('已删除!',1);
	});
}
$(function(){
	/*返回顶部调用*/
	$(window).on("scroll",$backToTopFun);
	$backToTopFun();
});