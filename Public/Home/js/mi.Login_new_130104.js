function resizeBgImg(a,b){
    var d=document.getElementById("lg_bg"),c=d.getElementsByTagName("img")[0];
    a&&(c=a);
    if(c){
        var e=UI.A(c,"src");
        if(""==e||"http://t.qq.com/"==e)c.src=UI.A(c,"crs");
        c.style.width="auto";
        c.style.height="auto";
        var f=document.documentElement||document.body,e=(c.width||1E3)/(c.height||1E3),g=d.offsetWidth||f.offsetWidth||1024,d=d.offsetHeight||f.offsetHeight||768;
        e<g/d?(c.style.width=g+"px",c.style.height="auto",c.style.marginLeft="0px",c.style.marginTop=-(g/e-d)/2+"px"):(c.style.height=d+"px",c.style.width="auto",c.style.marginTop="0px",c.style.marginLeft=-(d*e-g)/2+"px")
        }
        b&&b()
    }
    UI.EA(window,"resize",function(){
    resizeBgImg()
    });
UI.ready(function(){
    function a(a,b){
        var c=$$(UI.parent(a),"label")[0];
        c&&(0==b?UI.hide(c):1==b&&(a.value.length||UI.show(c)))
        }
        setTimeout(function(){
        MI.proxyEvent&&MI.proxyEvent(document.body,"mousedown",function(){
            return function(){
                if(boss=UI.A(this,"boss"))boss.hasString("{")&&(boss=MI.json(boss)),Bos(boss)
                    }
                },["*|boss="])
    },100);
var b=10,d=setInterval(function(){
    if(0!=b){
        b--;
        var a=UI.G("u");
        a&&""!=UI.trim(a.value)&&UI.hide(UI.G("wb_tips"))
        }else clearInterval(d)
        },100),c=document.getElementById("lg_bg"),e=c.getElementsByTagName("img")[0];
    UI.EA(e,"load",function(){
    resizeBgImg(e,function(){
        e.className=e.className?e.className+" on":"on";
        var a=$("master");
        UI.C(e,"opacity",1);
        UI.C(a,"opacity",1);
        UI.animate(a,"opacity",0,"",0.2)
        })
    });
UI.B.ie6?(c.style.setExpression("top","eval((document.documentElement||document.body).scrollTop) + 'px'"),c.style.setExpression("left","eval((document.documentElement||document.body).scrollLeft) + 'px'"),c.style.setExpression("height","UI.windowHeight() + 'px'"),c.style.setExpression("width","UI.windowWidth() + 'px'")):UI.B.os5&&(UI.B.iphone&&UI.B.qq)&&(c.style.position="absolute");
    UI.A(e,"src",UI.A(e,"crs"));
    var f=[],g=UI.html('<div style="display:none"></div>')[0];
    window.vJS&&f.push('<img src="http://mat1.gtimg.com/www/mb/js/mi_'+vJS+'.js">');
    window.vCSS&&f.push('<img src="http://mat1.gtimg.com/www/mb/css/style_'+vCSS+'.css">');
    UI.each(vImg,function(a){
    f.push('<img src="http://mat1.gtimg.com/www/mb/images/'+a+'">')
    });
UI.each(vImg2,function(a){
    f.push('<img src="http://mat1.gtimg.com/www/mb/img/'+a+'">')
    });
setTimeout(function(){
    g.innerHTML=f.join("");
    UI.append(g,document.body)
    },1E3);
setTimeout(function(){
    var a=UI.DC("script");
    UI.A(a,"id","l_qq_com");
    UI.A(a,"arguments","{'extension_js_src':'http://adsrich.qq.com/web/crystal/v1.8Beta06Build060/crystal_ext-min.js','lview_time_out':10,'mo_page_ratio':0,'mo_ping_ratio':0.01,'mo_ping_script':'http://adsrich.qq.com/sc/mo_ping-min.js'}");
    a.src="http://adsrich.qq.com/web/crystal/v1.8Beta08Build085/crystal-min.js";
    UI.append(a,document.body)
    },100);
var c=$("u"),i=$("wb_tips"),j=$("p"),h=$("verifycode");
    c&&""!=c.value&&UI.hide(i);
    i=[];
    c&&i.push(c);
    j&&i.push(j);
    h&&i.push(h);
    UI.each(i,function(b){
    UI.EA(b,"focus",function(){
        a(b,0)
        });
    UI.EA(b,"blur",function(){
        a(b,1)
        })
    })
});