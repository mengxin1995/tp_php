$(function() {
    $.ajax({
        url: ApiUrl + "/index.php?act=index",
        type: 'get',
        dataType: 'json',
        success: function(result) {
            $.ajax({//每日一品
                type: 'post',
                url: ApiUrl + '/index.php?act=zjcuntao&op=getMeiRiYiPin',
                dataType: 'json',
                success: function (result) {
                    var result = result.datas.list;
                    var html = '';
                    $.each(result, function (k, v) {
                        html +='<a href="tmpl/product_detail.html?goods_id=' + v.goods_id + '" xmlns="http://www.w3.org/1999/html">'
                            +'<img src="../data/upload/shop/groupbuy/'+ v.store_id+'/'+v.groupbuy_image+'" style="width:100%"/>'
                            +'<div class="saled">'
                            +'<span class="goods_number">'+v.buy_quantity+'</span>件</br><span style="font-size: 12px;">已售出</span>'
                            +'</div>'
                            +'<span></span><span class="introduce">'+v.groupbuy_name+'</span>'
                            +'<div class="bottom"><span class="price">'
                            +'<span class="price_now">￥'+v.groupbuy_price+'</span>'
                            +'<span class="price_ago">￥'+v.goods_price+'</span></span>'
                            +'<p class="l-btn-sale" id="start_salebtn">'+v.button_text+' </p>'
                            +'</a>'
                        $('.home6 .time-remain').attr('count_down', v.count_down);
                        $('.everyday_goods').html(html);
                    });
                }
            });
            var data = result.datas;
            var html = '';
            $.each(data, function(k, v) {
                $.each(v, function(kk, vv) {
                    switch (kk) {
                        case 'adv_list':
                        case 'home3':
                        case 'home5':
                            $.each(vv.item, function(k3, v3) {
                                vv.item[k3].url = buildUrl(v3.type, v3.data);
                            });
                            break;

                        case 'home1':
                        case 'home6':
                            vv.url = buildUrl(vv.type, vv.data);
                            break;

                        case 'home2':
                        case 'home4':
                            vv.square_url = buildUrl(vv.square_type, vv.square_data);
                            vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data);
                            vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data);
                            break;
                    }
                    vv.WapSiteUrl = WapSiteUrl;
                    vv.WapTplUrl = WapTplUrl;
                    html += template.render(kk, vv);
                    return false;
                });
            });

            $("#main-container").html(html);

            $('.adv_list').each(function() {
                if ($(this).find('.item').length < 2) {
                    return;
                }

                Swipe(this, {
                    startSlide: 2,
                    speed: 400,
                    auto: 3000,
                    continuous: true,
                    disableScroll: false,
                    stopPropagation: false,
                    callback: function(index, elem) {},
                    transitionEnd: function(index, elem) {}
                });
            });

        }
    });

    $("#search_text").focus(function(){
        location.href = WapSiteUrl+'/search.html';
    });

    $('.search-btn').click(function(){
        var keyword = encodeURIComponent($('#keyword').val());
        var key = $("#choose").val();
        if(key == '商品') {
            location.href = WapSiteUrl+'/tmpl/product_list.html?keyword='+keyword;
        } else {
            location.href = WapSiteUrl+'/tmpl/shop.html?keyword='+keyword;
        }
    });

});
//v3-b12
var uid = window.location.href.split("#V3");
var  fragment = uid[1];
if(fragment){
	if (fragment.indexOf("V3") == 0) {document.cookie='uid=0';}
else {document.cookie='uid='+uid[1];}
	}
