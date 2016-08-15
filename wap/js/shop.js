/**
 * all shop v3
 */
$(function() {
    $("input[name=keyword]").val(escape(GetQueryString('keyword')));
    $.ajax({
        url:ApiUrl+"/index.php?act=shop&keyword="+$("input[name=keyword]").val(),
        type:'get',
        jsonp:'callback',
        dataType:'jsonp',
        success:function(result){
            var data = result.datas;
            data.WapSiteUrl = WapSiteUrl;
            var html = template.render('category-one', data);
            $("#categroy-cnt").html(html);
        }
    });
});