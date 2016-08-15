function imgsize(){
	var w = $(".goods-item-pic").width();//容器宽度
	var h = $(".goods-item-pic").height();
	$(".goods-item-pic img").each(function() {
		var img_w = $(this).width();//图片宽度
		var img_h = $(this).height();//图片高度
		if (img_w > w) {
			var height = (w * img_h) / img_w; //高度等比缩放
			$(this).css({"width": w-2, "height": height});//设置缩放后的宽度和高度
		}
		else if(img_h > 210){
			var width = (210 * img_w) / img_h;
			$(this).css({"width": width, "height": 210});
		}
	})
}

function GetQueryString(name){
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	if (r!=null) return unescape(r[2]); return null;
}

function addcookie(name,value,expireHours){
	var cookieString=name+"="+escape(value)+"; path=/";
	//判断是否设置过期时间
	if(expireHours>0){
		var date=new Date();
		date.setTime(date.getTime+expireHours*3600*1000);
		cookieString=cookieString+"; expire="+date.toGMTString();
	}
	document.cookie=cookieString;
}

function getcookie(name){
	var strcookie=document.cookie;
	var arrcookie=strcookie.split("; ");
	for(var i=0;i<arrcookie.length;i++){
	var arr=arrcookie[i].split("=");
	if(arr[0]==name)return arr[1];
	}
	return "";
}

function delCookie(name){//删除cookie
	var exp = new Date();
	exp.setTime(exp.getTime() - 1);
	var cval=getcookie(name);
	if(cval!=null) document.cookie= name + "="+cval+"; path=/;expires="+exp.toGMTString();
}

function checklogin(state){
	if(state == 0){
		location.href = WapSiteUrl+'/tmpl/member/login.html';
		return false;
	}else {
		return true;
	}
}

function contains(arr, str) {
    var i = arr.length;
    while (i--) {
           if (arr[i] === str) {
           return true;
           }
    }
    return false;
}

function buildUrl(type, data) {
    switch (type) {
        case 'keyword':
            return WapSiteUrl + '/tmpl/product_list.html?keyword=' + encodeURIComponent(data);
        case 'special':
            return WapSiteUrl + '/special.html?special_id=' + data;
        case 'goods':
            return WapSiteUrl + '/tmpl/product_detail.html?goods_id=' + data;
        case 'url':
            return data;
    }
    return WapSiteUrl;
}

/*设置购物车
 * v3-b12
*/
function setcart() {
	var key = getcookie('key');
	if(key != ''){
		$.ajax({
			url: ApiUrl + "/index.php?act=member_cart&op=cart_info",
			type:"post",
			dataType:"json",
			data:{key:key},
			success: function(result) {
				$(".m_cart_num").html(result.datas.num);
				if(result.datas.num>0){
					$(".m_cart_num").show();
				}else{
					$(".m_cart_num").hide();
				}

			}
		});
	}
}
/*
*判断当前浏览器是否是微信内置浏览器
 */
function is_weixn(){
	var ua = navigator.userAgent.toLowerCase();
	if(ua.match(/MicroMessenger/i)=="micromessenger") {
		return true;
	} else {
		return false;
	}
}