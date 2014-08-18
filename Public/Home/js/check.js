
function  checkVerify(){
    var name = document.getElementById("u").value;
    var password = document.getElementById("p").value;
    if(name == ""){
        // $('#copyverify').html("帐号不能为空！");                   
        return false;
    }
    if(password == ''){
        // $('#copyverify').html("密码不能为空！");
        return false;
    }

}
            
function UnameFocus(){
    var wb_tips = document.getElementById("wb_tips");               
    wb_tips.innerHTML = "";                            
}
            
function UnameBlur(){
    var wb_tips = document.getElementById("wb_tips");
    var name = document.getElementById("u").value;
    var errHolder_1 = document.getElementById("errHolder_1");
    var password = document.getElementById("p").value;
    if(name == ""){
        wb_tips.innerHTML = "账号";
        errHolder_1.innerHTML = "帐号不能为空！";
    }else if(password != ""){
        errHolder_1.innerHTML = "";
    }  
}
            
function PFocus(){
    var label_pwd = document.getElementById("label_pwd");               
    label_pwd.innerHTML = "";                            
}
            
function PBlur(){
    var label_pwd = document.getElementById("label_pwd");
    var name = document.getElementById("u").value;
    var password = document.getElementById("p").value;
    var errHolder_1 = document.getElementById("errHolder_1");               
    if(password == ""){
        label_pwd.innerHTML = "密码";
        errHolder_1.innerHTML =  "密码不能为空！";
    }else if(name != "" && password != ""){                    
        errHolder_1.innerHTML =  "";
    } 
    if(name == ""){
        errHolder_1.innerHTML = "帐号不能为空！";
    }
}
            
function checkBlurAll(){
    var name=document.getElementById("regu").value;
    var password=document.getElementById("regp").value;
    var password2=document.getElementById("regp2").value;
    var nickname = document.getElementById("regn").value;
                
    var wb_tips = document.getElementById("wb_tips_reg");
    var label_pwd = document.getElementById("label_pwd_reg");
    var label_pwd2 = document.getElementById("label_pwd2_reg");
    var label_n = document.getElementById("label_n_reg");
                
    if(name == ""){
        wb_tips.innerHTML = "账号";                   
    }
    if(password == ""){
        label_pwd.innerHTML = "密码";
    }
    if(password2 == ""){
        label_pwd2.innerHTML = "确认密码";
    }
    if(nickname == ""){
        label_n.innerHTML = "昵称";
    }
}
            
function reguFocus(){
    var wb_tips = document.getElementById("wb_tips_reg");
    wb_tips.innerHTML = "";
}
            
function regpFocus(){
    var label_pwd = document.getElementById("label_pwd_reg");
    label_pwd.innerHTML = "";
}
            
function regp2Focus(){
    var label_pwd2 = document.getElementById("label_pwd2_reg");
    label_pwd2.innerHTML = "";
}
            
function regnFocus(){
    var label_n = document.getElementById("label_n_reg");
    label_n.innerHTML = "";
}
function checkname(){  //检查用户名
    var myname=document.getElementById("regu").value;
    var errHolder_2=document.getElementById("errHolder_2");
    if(myname=="")
    {   
        errHolder_2.innerHTML="用户名不能为空!";
        return false;
    }
    for(var i=0;i<myname.length;i++)
    {
        var text=myname.charAt(i);
        if(!(text<=9&&text>=0)&&!(text>='a'&&text<='z')&&!(text>='A'&&text<='Z')&&text!="_")
        {
            errHolder_2.innerHTML="用户名只能是数字、字母、下划线组成！";
            break;
        }
    }
    if(i>=myname.length)
    {
        errHolder_2.innerHTML="√";
        return true;
    }
}

function checkuserpassword()  //检查密码
{
    var mypassword=document.getElementById("regp").value;
    var errHolder_2=document.getElementById("errHolder_2");
    if(mypassword=="")
    {              
        errHolder_2.innerHTML="密码不能为空!";
        return false;
    }
    else if(mypassword.length<6)
    {
        errHolder_2.innerHTML="密码至少应为六位!";
        return false;
    }
    else
    {
        errHolder_2.innerHTML="√";
        return true;
    }
}

function checkpwdagin()  //检查确认密码
{
    var myispassword=document.getElementById("regp2").value;
    var errHolder_2=document.getElementById("errHolder_2");
    if(myispassword=="")
    {
        errHolder_2.innerHTML="确认密码不能为空!";
        return false;
    }
    else if(document.getElementById("regp").value!=document.getElementById("regp2").value)
    {
        errHolder_2.innerHTML="确认密码与密码不一致!";
        return false;
    }
    else
    {
        errHolder_2.innerHTML="√";
        return true;
    }
}
            
function checknickname()  //检查昵称
{
    var myisnickname=document.getElementById("regn").value;
    var errHolder_2=document.getElementById("errHolder_2");
    if(myisnickname=="")
    {
        errHolder_2.innerHTML="昵称不能为空!";
        return false;
    }             
    else
    {
        errHolder_2.innerHTML="√";
        return true;
    }
}

function checktelephone()  //检查电话号码
{
    var mytelephone=document.getElementById("txttelephone").value;
    var myDivtelephone=document.getElementById("telephone");
    if(mytelephone!="")
    {
        var reg = /^[0-9]{11}$/i;
        if(!reg.test(mytelephone))
        {
            myDivtelephone.innerHTML="<font color='red'>只能输入11位数字！例：13595144582或08514785214</font>";
            return false;
        }
        else
        {
            myDivtelephone.innerHTML="<font color='red'>√</font>";
            return true;
        }
    }
    else
    {
        myDivtelephone.innerHTML="<font color='red'>√</font>";
        return true;
    }
}

function checkemail()  //检查E-mail
{
    var myemail=document.getElementById("txtemail").value;
    var myDivemail=document.getElementById("email");
    if(myemail!="")
    {
        if(myemail.indexOf("@")==-1||myemail.indexOf(".")==-1||(myemail.indexOf("@")>myemail.indexOf(".")))
        {
            myDivemail.innerHTML="<font color='red'>E-mail格式不正确！例：jiie@163.com</font>";
            return false;
        }
        else
        {
            myDivemail.innerHTML="<font color='red'>√</font>";
            return true;
        }
    }
    else
    {
        myDivemail.innerHTML="<font color='red'>√</font>";
        return true;
    }
}

function checkqq()  //检查QQ号码
{
    var myqq=document.getElementById("txtqq").value;
    var myDivqq=document.getElementById("divqq");
    if(myqq!="")
    {
        if(myqq.match(/\D/)!=null)
        {
            myDivqq.innerHTML="<font color='red'>QQ号码只能输入数字！</font>";
            return false;
        }
        else
        {
            myDivqq.innerHTML="<font color='red'>√</font>";
            return true;
        }
    }
    else
    {
        myDivqq.innerHTML="<font color='red'>√</font>";
        return true;
    }
}

function checkall()  //检查所有
{
    if(checkname()&&checkuserpassword()&&checkpwdagin()&&checknickname())
    {
        return true;
    }
    return false;
}

function onClickForgetPwd(){
    alert("这么2！密码都能忘，活该！");
}


