<?php defined('InShopNC') or exit('Access Invalid!');?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet"  href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/smoothness/bootstrap.css" />
    <style>
        .info_header{
            background: steelblue;
            margin-bottom:20px;
        }

        .info_header .info_header_title{
            color: #FFFFFF;
        }
        .info_form .input-group {
            margin-bottom: 10px;
        }

        footer{
            height:20px;
        }
    </style>
</head>

<body>

<header class="info_header">
    <div class="container">
        <h2 class="info_header_title text-center">村村通合伙人服务站申请表</h2>
    </div>
</header>
<form id="form_company_info" action="index.php?act=store_joinin_partner_wap&op=partner_wap" method="post" enctype="multipart/form-data" >
<div class="info_form">
    <div class="container" id="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="input-group">
							<span class="input-group-addon">
							<span style="color:red;">*</span>姓名
	      				</span>
                    <input type="text"  name="partner_name" class="form-control" value="<?php echo $output['partner_name'];?>" required>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="input-group">
							<span class="input-group-addon">
					<span style="color:red;">*</span>性别
	      			</span>


      				<span class="form-control">
						<input type="radio" value="0" name="partner_sex">
							男

						<input type="radio" value="1" name="partner_sex">
							女
					</span>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="input-group">
							<span class="input-group-addon">
					<span style="color:red;">*</span>年龄
	      			</span>
                    <select name="partner_age" id="1" class="form-control">
                        <option value="100" name="partner_age">请选择</option>
                        <option value="18-21" name="partner_age">18-21</option>
                        <option value="22-25" name="partner_age">22-25</option>
                        <option value="26-29" name="partner_age">26-29</option>
                        <option value="30-35" name="partner_age">30-35</option>
                        <option value="超过35" name="partner_age">超过35</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="input-group">
							<span class="input-group-addon">
							<span style="color:red;">*</span>职业
	      				</span>
                    <input type="text" name="partner_job" class="form-control" value="<?php echo $output['partner_job'];?>" required>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="input-group">
							<span class="input-group-addon">
							<span style="color:red;">*</span>联系电话
	      				</span>
                    <input type="text" name="partner_phone" class="form-control" value="<?php echo $output['partner_phone'];?>" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" required>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="input-group">
							<span class="input-group-addon">
							<span style="color:red;">*</span>详细地址
	      				</span>
                    <input type="text" name="partner_address" class="form-control" value="<?php echo $output['partner_address'];?>" required>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="input-group">
							<span class="input-group-addon">
							<span style="color:red;">*</span>场地大小
	      				</span>
                    <select name="partner_field" id="2" class="form-control">
                        <option value="100" name="partner_field">请选择</option>
                        <option value="10m²" name="partner_field">10m²</option>
                        <option value="11-20m²" name="partner_field">11-20m²</option>
                        <option value="20m²以上" name="partner_field">20m²以上</option>
                    </select>

                </div>
            </div>

            <div class="col-xs-12">
                <div class="input-group">
							<span class="input-group-addon">
							<span style="color:red;">*</span>所处位置
	      				</span>
                    <select name="partner_position" id="3" class="form-control">
                        <option value="100" name="partner_position">请选择</option>
                        <option value="0" name="partner_position">主干道</option>
                        <option value="1" name="partner_position">村委</option>
                        <option value="2" name="partner_position">村中心</option>
                        <option value="3" name="partner_position">村口</option>
                        <option value="4" name="partner_position">操场</option>
                        <option value="5" name="partner_position">其他</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="input-group">
							<span class="input-group-addon">
							<span style="color:red;">*</span>人际关系
	      				</span>
                    <select name="partner_relation" id="4" class="form-control">
                        <option value="100" name="partner_relation">请选择</option>
                        <option value="0" name="partner_position">人缘好</option>
                        <option value="1" name="partner_position">活动力强</option>
                        <option value="2" name="partner_position">其他()</option>
                    </select>
                </div>
            </div>


            <div class="col-xs-12">
                <div class="input-group">
							<span class="input-group-addon">
							<span style="color:red;">*</span>村落户数
	      				</span>
                    <select name="partner_village" id="5" class="form-control">
                        <option value="100" name="partner_village">请选择</option>
                        <option value="150户以内" name="partner_village">150户以内</option>
                        <option value="150-300户" name="partner_village">150-300户</option>
                        <option value="300-500户" name="partner_village">300-500户</option>
                        <option value="500户以上" name="partner_village">500户以上</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="input-group">
							<span class="input-group-addon">
							<span style="color:red;">*</span>网购经验
	      				</span>
                    <select name="partner_experience" id="6" class="form-control">
                        <option value="100" name="partner_experience">请选择</option>
                        <option value="0" name="partner_experience">无</option>
                        <option value="1" name="partner_experience">半年</option>
                        <option value="2" name="partner_experience">一年</option>
                        <option value="3" name="partner_experience">一年以上</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="input-group">
							<span class="input-group-addon">
							<span style="color:red;">*</span>是否经营商铺或网店
	      				</span>
                    <select name="partner_boss" id="7" class="form-control">
                        <option value="100" name="partner_boss">请选择</option>
                        <option value="1" name="partner_boss">无</option>
                        <option value="2" name="partner_boss">实体店</option>
                        <option value="3" name="partner_boss">网店</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="input-group">
							<span class="input-group-addon">
							<span style="color:red;">*</span>每天能付出多少时间
	      				</span>
                    <select name="partner_time" id="8" class="form-control">
                        <option value="100" name="partner_time">请选择</option>
                        <option value="2小时以内" name="partner_time">2小时以内</option>
                        <option value="2-5小时" name="partner_time">2-5小时</option>
                        <option value="半天" name="partner_time">半天</option>
                        <option value="全天" name="partner_time">全天</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="input-group">
							<span class="input-group-addon">
							对公司的意见和建议
	      				</span>
                    <textarea class="form-control" name="partner_opinion" id="partner_opinion" value="<?php echo $output['partner_opinion'];?>"></textarea>
                </div>
            </div>



        </div>
    </div>
    <div class="container">
        <div class="col-xs-12">
            <input type="submit" class="btn btn-primary btn-block" id="subBtn" value="提交">
        </div>
    </div>
</div>
</form>
<script>
    var flag = [0, 0, 0, 0, 0,0,0,0];
    //		btn.addEventListener('',function(event))
    document.getElementById('subBtn').setAttribute("disabled", "disabled");
    var body = document.getElementById('container');
    var warningBox = document.createElement("div");
    body.appendChild(warningBox);
    warningBox.innerHTML = "<div class='alert alert-danger' id='warningBox'  role='alert'> !请填写内容 </div>";

    function isBtnDisabled(flag) {
        console.log(flag);
        var j = 0;
        for (var i = 0; i <= 7; i++) {
            if (flag[i] == 1) {
                j++;
            }
        }
        if (j == 8) {
            document.getElementById('subBtn').removeAttribute('disabled');
            document.getElementById('warningBox').setAttribute("style", "display:none");
        } else {
            body.appendChild(warningBox);
        };
    }
    var select = document.getElementsByTagName('select');
    console.log(select);
    for (var i = 0; i < select.length; i++) {
        var t = 0;
        select[i].addEventListener('change', function(event) {
            var val = Getvaule(this);
            var index = this.id;
            if (val != 100) {
                t = 1;
                //	   	 	alert(val);
            } else {
                t = 0;
            }
            //
            flag[index - 1] = t;
            console.log(i);
            console.log(t);
            console.log(flag);
            isBtnDisabled(flag);
        })
    }

    function Gettext(obj) {
        var txt = obj.options[obj.options.selectedIndex].text;
        return txt;
    }

    function Getvaule(obj) {
        var val = obj.options[obj.options.selectedIndex].value;
        return val;
    }

</script>
</body>
