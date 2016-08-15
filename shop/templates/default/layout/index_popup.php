<!--[if lt IE 9]>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<!--js弹窗样式开始-->
<style>
    .popup {
        display: none;
        overflow: hidden;
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1040;
        -webkit-overflow-scrolling: touch;
        outline: 0;
    }

    .popup-dialog {
        position: relative;
        margin-left:auto; margin-right:auto;width:500px;
        background-color:white;
    }

    .popup-header {
        padding: 15px;
        border-bottom: 1px solid #e5e5e5;
        min-height: 16.42857143px;
        background-color: #8d0029;
        height: 70px;;
        background:url(../shop/templates/default/images/shop/goods_meta_bg.png) no-repeat;
    }
    .popup-body {
        padding: 20px;
        border-bottom: 1px solid #e5e5e5;
        min-height: 16.42857143px;
        color: white;

    }
    .area{font-size: 20px;padding:10px 85px; }
    .area:hover{ color:white; background-color:#8d0029;text-decoration: none;}
</style>
<!--js弹窗样式结束-->
<div class="popup " style="display: none; background:rgba(128,128,128,0.7)">
    <div class="popup-dialog" >
        <div class="popup-header">
            <img src="../shop/templates/default/images/shop/logo.png" style="height: 80px;width: 50px; margin-left: 50px;">
            <p  style="text-align: center;color:white;font-size: 24px;margin:-45px 50px;">请先选择您所在的地区</p>
        </div>
        <div class="popup-body">
            <div style="margin: 10px; 30px; font-size: 13px;">
                <a href="<?php echo SHOP_SITE_URL;?>/index.php?site_id=<?php echo $output['current_site']['site_id'];?>"class="area"><?php echo $output['current_site']['site_name'];?></a>
                <?php if(is_array($output['site_list'])){ ?>
                    <?php foreach($output['site_list'] as $k => $v){ ?>
                        <?php if($v['site_name']!="总站"&&$v['site_id']!=0) { ?><!--隐藏总站开始-->
                            <a href="<?php echo SHOP_SITE_URL;?>/index.php?site_id=<?php echo $v['site_id'];?>"class="area"><?php echo $v['site_name'];?></a>
                        <?php } ?><!--隐藏总站结束-->
                    <?php   }  ?>
                <?php } ?>
            </div>
        </div>

    </div>
</div>
<script>
    var once_per_session=1
    function setCookie (name,value,expires,path,theDomain,secure) {
        value = escape(value);
        var theCookie = name + "=" + value +
            ((expires) ? "; expires=" + expires.toGMTString() : "") +
            ((path) ? "; path=" + path : "") +
            ((theDomain) ? "; domain=" + theDomain : "") +
            ((secure) ? "; secure" : "");
        document.cookie = theCookie;
    }
    function getCookieVal(a){
        var b=document.cookie.indexOf(";",a);
        if(b==-1)b=document.cookie.length;
        return unescape(document.cookie.substring(a,b))
    }
    function getCookie(a){
        var v=a+"=";
        var i=0;
        while(i<document.cookie.length){
            var j=i+v.length;
            if(document.cookie.substring(i,j)==v)return getCookieVal(j);
            i=document.cookie.indexOf(" ",i)+1;
            if(i==0)break
        }
        return null
    }
    function alertornot(){
        if (getCookie('alerted')==null){
            setCookie('alerted','yes');
            loadalert();
        }
    }
    function loadalert(){
        var str=location.search;
        if(str==""){
            $(".popup").css({"display":"block"});
        }

        $('.close').on("click",function(e){
            $(".popup").css("display","none");
        })
        var h=window.innerHeight;
        $(".popup-dialog").css({"margin-top":h/3});
        console.log(str);
    }

    if (once_per_session==0)
    {
        loadalert();
    }
    else
    {
        alertornot();
    }
</script>