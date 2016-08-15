$(function (){
	var headTitle = document.title;
	var tmpl = '<div class="header-wrap">'
	        		+'<a href="javascript:history.back();" class="header-back"><span>返回</span></a>'
						+'<h2>'+headTitle+'</h2>'
						+'<a href="javascript:void(0)" id="btn-opera" class="i-main-opera">'
					 	+'<span></span>'
				 	+'</a>'
    			+'</div>'
		    	+'<div class="main-opera-pannel">'
		    		+'<div class="main-op-table main-op-warp">'
		    			+'<a href="'+WapSiteUrl+'/index.html" class="quarter">'
		    				+'<span class="i-home"></span>'
		    				+'<p>首页</p>'
		    			+'</a>'
		    			+'<a href="'+WapSiteUrl+'/tmpl/product_first_categroy.html" class="quarter">'
		    				+'<span class="i-categroy"></span>'
		    				+'<p>分类</p>'
		    			+'</a>'
		    			+'<a href="'+WapSiteUrl+'/tmpl/cart_list.html" class="quarter">'
		    				+'<span class="i-cart"></span>'
		    				+'<p>购物车</p>'
		    			+'</a>'
		    			+'<a href="'+WapSiteUrl+'/tmpl/member/member.html?act=member" class="quarter">'
		    				+'<span class="i-mine"></span>'
		    				+'<p>个人中心</p>'
		    			+'</a>'
		    		+'</div>'
		    	+'</div>';
  	//登录界面
	//var tmpl_login='<div class="header-wrap">'
	//		+'<a href="javascript:history.back();" class="header-back"><span>返回</span></a>'
	//		+'<h2>'+headTitle+'</h2>'
	//		+'<a class="btn mr5 reg" href="http://localhost/wap/tmpl/member/register.html">注册</a>'
	//			//+'<a href="javascript:void(0)" id="btn-opera" class="i-main-opera">'
	//			//+'<span></span>'
	//		+'</a>'
	//		+'</div>'
	////商品分类界面
	//var tmpl_catagroy='<div class="header-wrap">'
	//		+'<a href="javascript:history.back();" class="header-back"><span>返回</span></a>'
	//		+'<div class="htsearch-wrap with-home-logo">'
	//		+'<input type="text" class="htsearch-input clr-999" value="" id="keyword" />'
	//		+'<a href="javascript:void(0);" class="search-button"><span></span></a>'
	//		+'</div>'
	//		+'</a>'
	//		+'</div>'

	//渲染页面
	//if(headTitle == "登录")
	//{
	//	var html = template.compile(tmpl_login);
	//	$("#header").html(html);
	//}
	//else if(headTitle == "商品分类")
	//{
	//	var html = template.compile(tmpl_catagroy);
	//	$("#header").html(html);
	//}
	//else
	//{
		var html = template.compile(tmpl);
		$("#header").html(html);
	//}


	$("#btn-opera").click(function (){
		$(".main-opera-pannel").toggle();
	});
	//当前页面
	if(headTitle == "商品分类"){
		$(".i-categroy").parent().addClass("current");
	}else if(headTitle == "购物车"){
		$(".i-cart").parent().addClass("current");
	}else if(headTitle == "个人中心"){
		$(".i-mine").parent().addClass("current");
	}
});