<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>tweibo 2.0</title>
        <meta name="keywords" content="tweibo"/>
        <link href="/tweibo2.0/Public/Home/css/login.css" rel="stylesheet" type="text/css" />
        <!-- jQuery - the core -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js" type="text/javascript"></script>
        <!--加载jquery-unslider图像滑块插件-->
        <script src="/tweibo2.0/Public/Home/js/unslider.min.js"></script>
    </head>
    <body id="L">
        <div class="lg_bg" id="lg_bg">
            <div class="lg_bg_in"><img src = "/tweibo2.0/Public/Home/images/login/dandelionAqua.jpg" /></div>          
        </div>

        <table class="lg_wtb" style="position: relative; z-index: 10000000">
            <tr>
                <td class="lg_wtb_bd">
                    <div class="lg_wtb_bd_in">
                        <div class="lg_w">
                            <div class="lg_hd"><div class="lg_hd_in"><div class="lg_logo"><h1></h1></div></div></div>
                            <div class="lg_bd">
                                <div class="mod_mch">
                                    <div class="tit"><h2 style="color: #f3f3f3">新世界，我们来啦</h2></div>                                 
                                    <div class="banner">
                                        <ul>
                                            <li><img src="/tweibo2.0/Public/Home/images/login/op1.jpg"/></li>
                                            <li><img src="/tweibo2.0/Public/Home/images/login/op2.jpg"/></li>
                                            <li><img src="/tweibo2.0/Public/Home/images/login/op3.jpg"/></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td  class="lg_wtb_bd2">  
                    <div id ="panel1">
                        <div class="lg_bd_c1 clear">
                            <div id="errHolder_1" class="err_holder" style="display: block;"></div>
                            <div class="loginBox">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>
                                            <div class="login_form" name="Login_Frame" id="Login_Frame">
                                                <div id="login" class="main">
                                                    <div style="display:none" id="qlogin"></div>
                                                    <div style="display:block" id="web_login">
                                                        <form id="loginform" onsubmit="return checkVerify();" method="post" name="loginform" action="<?php echo U('Index/login');?>">
                                                            <div id="err_m" class="err_m"></div>
                                                            <ul id="g_list" class="lg_list clear">
                                                                <li id="g_u" class="item">
                                                                    <div class="input_w">
                                                                        <label id="wb_tips" for="u">账号</label><br />
                                                                        <input  name="u" value="" tabindex="1" class="inputstyle" id="u" onfocus="UnameFocus()" onblur="UnameBlur()" />
                                                                    </div>
                                                                </li>
                                                                <li id="g_p" class="item">
                                                                    <div class="input_w">
                                                                        <label id="label_pwd" for="p">密码</label><br />
                                                                        <input id="p" class="inputstyle"  tabindex="2" value="" maxlength="16" type="password" name="p"  onfocus="PFocus()" onblur="PBlur()" />
                                                                    </div>
                                                                </li>                                                                 
                                                                <li class="item">
                                                                    <div class="login_button_w">
                                                                        <div class="login_button">
                                                                            <input type="submit" tabindex=5 value="登 录" id="login_btn" />
                                                                        </div>
                                                                        <div class="login_enable" title="为了确保您的信息安全，不建议在网吧等公共环境勾选此项">
                                                                            <label class="c_tx6" for="low_login_enable" title="为了确保您的信息安全，不建议在网吧等公共环境勾选此项">
                                                                                <input id="low_login_enable" tabindex=4 value=1 type=checkbox name="low_login_enable" class="check1" checked="checked" /><span id="label_remember_pwd">下次自动登录</span>
                                                                            </label>
                                                                            <label class=low_login style="display:none"><select disabled name=low_login_hour><option id=label_1_month selected value=720>一个月</option></select></label>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="more_link clear">
                                                <span class="spacer">|</span>
                                                <a class="c_tx6" id=label_forget_pwd tabindex=6 onclick="onClickForgetPwd()">忘记密码？</a>
                                                <span class="spacer">|</span>                                                          
                                                <a id="reg" href="javascript:void(0)" >立即注册</a>
                                            </div>
					</td>
                                    </tr>
                                </table>
                            </div>
                        </div> 
                    </div>
                    <div id="panel2">
                        <div class="lg_bd_c1 clear">
                            <div id="errHolder_2" class="err_holder" style="display: block;"></div>
                            <div class="loginBox">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>
                                            <div class="login_form" name="Login_Frame" id="Login_Frame">
                                                <div id="login" class="main">
                                                    <div style="display:none" id="qlogin"></div>
                                                    <div style="display:block" id="web_login">
                                                        <form id="registerform" onsubmit="return checkall();" method="post" name="registerform" autocomplete="off" action="<?php echo U('Index/register');?>">
                                                            <div id="err_m" class="err_m"></div>
                                                            <ul id="g_list" class="lg_list clear">
                                                                <li id="g_u" class="item">
                                                                    <div name="key" id="vo"><?php echo ($vo['is']); ?></div>
                                                                    <div class="input_w">
                                                                        <label id="wb_tips_reg" for="regu">账号</label><br />
                                                                        <input  name="regu" value="" tabindex="1" class="inputstyle" id="regu" onfocus="reguFocus()" onBlur="checkBlurAll()"/>
                                                                    </div>
                                                                </li>
                                                                <li id="g_p" class="item">
                                                                    <div class="input_w">
                                                                        <label id="label_pwd_reg" for="regp">密码</label><br />
                                                                        <input id="regp" class="inputstyle"  tabindex="2" value="" maxlength="16" type="password" name="regp" onfocus="regpFocus()"  onBlur="checkBlurAll()" />

                                                                    </div>
                                                                </li>
                                                                <li id="g_p" class="item">
                                                                    <div class="input_w">
                                                                        <label id="label_pwd2_reg" for="regp2">确认密码</label><br />
                                                                        <input id="regp2" class="inputstyle"  tabindex="2" value="" maxlength="16" type="password" name="regp2" onfocus="regp2Focus()" onBlur="checkBlurAll()" />

                                                                    </div>
                                                                </li>
                                                                <li id="g_p" class="item">
                                                                    <div class="input_w">
                                                                        <label id="label_n_reg" for="regn">昵称</label><br />
                                                                        <input id="regn" class="inputstyle"  tabindex="2" value="" maxlength="16" type="text" name="regn" onfocus="regnFocus()" onBlur="checkBlurAll()" />

                                                                    </div>
                                                                </li>
                                                                <li class="item">
                                                                    <div class="login_button_w">
                                                                        <div class="login_button">
                                                                            <input type="submit" tabindex=5 value="注 册" id="reg_btn" />
                                                                        </div>                                           
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="more_link clear">
                                                <span class="spacer">|</span>                                                          
                                                <a id="lo" href="javascript:void(0)" >返回登录</a>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div> 
                    </div>
                </td>
            </tr>
        </table>
    </body>
    <script>
	$(function() {
	    $('.banner').unslider({
		speed: 500, //  The speed to animate each slide (in milliseconds)
		delay: 3000, //  The delay between slide animations (in milliseconds)
		complete: function() {
		}, //  A function that gets called after every slide animation
		keys: true, //  Enable keyboard (left, right) arrow shortcuts
		dots: true, //  Display dot navigation
		fluid: false              //  Support responsive design. May break non-responsive designs
	    });

	    $("#reg").click(function() {
		$("div#panel1").slideUp("slow");
		setTimeout("$(\"div#panel2\").slideDown(\"slow\")", 800);
	    });

	    $("#lo").click(function() {
		$("div#panel2").slideUp("slow");
		setTimeout("$(\"div#panel1\").slideDown(\"slow\")", 800);
	    });
	});
    </script>
    <!--  Sliding effect -->
    <script src="/tweibo2.0/Public/Home/js/check.js" type="text/javascript"></script>  
</html>