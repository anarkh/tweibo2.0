<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of common
 *
 * @author lichenyang
 */
//微博内容中文字表情替换为表情图片
function setEmotion($s) {
    $esStr = "惊讶,撇嘴,色,发呆,得意,流泪,害羞,闭嘴,睡,大哭,尴尬,发怒,调皮,呲牙,微笑,难过,酷,非典,抓狂,吐,偷笑,可爱,白眼,傲慢,饥饿,困,惊恐,流汗,憨笑,大兵,奋斗,咒骂,疑问,嘘...,晕,折磨,衰,骷髅,敲打,再见,闪人,发抖,爱情,跳跳,找,美眉,猪头,小狗,钱,拥抱,灯泡,酒杯,音乐,蛋糕,闪电,炸弹,刀,足球,猫咪,便便,咖啡,饭,女,玫瑰,凋谢,男,爱心,心碎,药丸,礼物,吻,会议,电话,时间,太阳,月亮,强,弱,握手,胜利,邮件,电视,多多,美女,汉良,飞吻,怄火,毛毛,Q仔,西瓜,白酒,汽水,下雨,多云,雪人,星星,冷汗,擦汗,抠鼻,鼓掌,糗大了,坏笑,左哼哼,右哼哼,哈欠,鄙视,委屈,快哭了,阴险,亲亲,吓,可怜,菜刀,啤酒,篮球,乒乓,示爱,瓢虫,抱拳,勾引,拳头,差劲,爱你,NO,OK,转圈,磕头,回头,跳绳,挥手,激动,街舞,献吻,左太极,右太极,喜糖,红包";
    $es = explode(",", $esStr);
    for ($i = 0, $k = count($es); $i < $k; $i++) {
	$e = $es[$i];
	$ii = $i;
	if ($ii === 135) {
	    $ii = 150;
	} else if ($ii === 136) {
	    $ii = 151;
	}
	if (stripos($s, "/" . $e) > -1) {
	    $s = str_replace("/" . $e, "<img src=\"http://mat1.gtimg.com/www/mb/images/face/" . $ii .
		    ".gif\" title=\"" . $e . "\" class=\"weibo_emotion\"/>", $s);
	}
    }
    return $s;
}
//修改时间戳为时间格式
function tranTime($time) {
    $ytime = date("Y-m-d H:i", $time);
    $rtime = date("m-d H:i", $time);
    $htime = date("H:i", $time);
    $time = time() - $time;

    if ($time < 60) {
	$str = '刚刚';
    } elseif ($time < 60 * 60) {
	$min = floor($time / 60);
	$str = $min . '分钟前';
    } elseif ($time < 60 * 60 * 24) {
	$h = floor($time / (60 * 60));
	$str = $h . '小时前&nbsp;&nbsp;' . $htime;
    } elseif ($time < 60 * 60 * 24 * 3) {
	$d = floor($time / (60 * 60 * 24));
	if ($d == 1)
	    $str = '昨天 ' . $rtime;
	else
	    $str = '前天 ' . $rtime;
    }
    else {
	$str = $ytime;
    }
    return $str;
}
//获取微博
function getBlogs($params, $memberId, $unit) {
    //判断请求获取微博类型
    if ($unit == 'home') {
	$r = Tencent::api($memberId, 'statuses/home_timeline', $params, 'GET');
    } else if ($unit == 'special') {
	$r = Tencent::api($memberId, 'statuses/special_timeline', $params, 'GET');
    } else if ($unit == 'at') {
	$r = Tencent::api($memberId, 'statuses/mentions_timeline', $params, 'GET');
    } else {
	$this->error('获取微博读取类型出错');
    }
    $r = json_decode($r, true);
    if ($r['data'] != NULL){
	$r = $r['data']['info'];
	foreach ($r as $k => $i) {
	    //设置头像大小
	    $r[$k]['head'] = $i['head'] . '/50';
	    //设置查看发微博人的主页
	    $r[$k]['blogAdd'] = "http://t.qq.com/" . $i['name'];
	    //把时间戳转化为日期格式返回前台
	    $time = $i['timestamp'];
	    $r[$k]['time'] = tranTime($time);
	    //设置微博图片url               
	    if ($r[$k]['source'] != null) {
		//设置小图
		$r[$k]['source']['image'] = $i['source']['image'][0] . '/160';
		//设置大图
		$r[$k]['source']['bigimage'] = $i['source']['image'][0] . '/460';
		//设置原图
		$r[$k]['source']['orimage'] = $i['source']['image'][0] . '/2000';
		//设置源微博时间
		$r[$k]['source']['time'] = tranTime($i['source']['timestamp']);
		//设置查看发微博人的主页
		$r[$k]['source']['blogAdd'] = "http://t.qq.com/" . $i['source']['name'];
	    }
	    $r[$k]['image'] = $i['image'][0] . '/160';
	    $r[$k]['bigimage'] = $i['image'][0] . '/460';
	    $r[$k]['orimage'] = $i['image'][0] . '/2000';

	    //设置原始微博来源
	    $r[$k]['idurl'] = "http://t.qq.com/p/t/" . $i['id'];
	    //把微博内容中的表情转成图片形式
	    $r[$k]['text'] = setEmotion($r[$k]['text']);
	}
    }
    return $r;
}
//删除用户文件夹下的文件
function deldir($userId) {
  $dir = $_SERVER[DOCUMENT_ROOT] . "/" . APP_NAME . "/Public/UpLoads/" .$userId."/";  
  //先删除目录下的文件：
  $dh=opendir($dir);
  while ($file=readdir($dh)) {
    if($file!="." && $file!="..") {
      $fullpath=$dir."/".$file;
      if(!is_dir($fullpath)) {
          unlink($fullpath);
      } else {
          deldir($fullpath);
      }
    }
  }
  closedir($dh);
  //删除当前文件夹：
//  if(rmdir($dir)) {
//    return true;
//  } else {
//    return false;
//  }
}
// 过滤非法html标签
function filterHTML($text) {
    //过滤标签
    $text = nl2br($text);
    $text = real_strip_tags($text);
    $text = addslashes($text);
    $text = trim($text);
    return addslashes($text);
}

/** 
 * 用于生成URL地址
 * @param string $url URL标识符
 * @param array $params URL附加参数
 * @param bool $redirect 是否自动跳转到生成的URL
 * @return string 输出URL
 */
function UR($url,$params=false,$redirect=false) {

    //普通模式
    if(false==strpos($url,'/')){
        $url    .='//';
    }

    //填充默认参数
    $urls   =   explode('/',$url);
    $app    =   isset($urls[0]) && !empty($urls[0]) ? $urls[0] : APP_NAME;
    $mod    =   isset($urls[1]) && !empty($urls[1]) ? ucfirst($urls[1]) : 'Index';
    $act    =   isset($urls[2]) && !empty($urls[2]) ? $urls[2] : 'index';

    //组合默认路径
    $site_url   =   SITE_URL.'/index.php?app='.$app.'&mod='.$mod.'&act='.$act;

    //填充附加参数
    if($params){
        if(is_array($params)){
            $params =   http_build_query($params);
            $params =   urldecode($params);
        }
        $params     =   str_replace('&amp;','&',$params);
        $site_url   .=  '&'.$params;
    }

    //开启路由和Rewrite
    if(C('URL_ROUTER_ON')){

        //载入路由
        $router_ruler   =   C('router');
        $router_key     =   $app.'/'.$mod.'/'.$act;

        //路由命中
        if(isset($router_ruler[$router_key])){

            //填充路由参数
            if(false==strpos($router_ruler[$router_key],'://')){
                $site_url   =   SITE_URL.'/'.$router_ruler[$router_key];
            }else{
                $site_url   =   $router_ruler[$router_key];
            }

            //填充附加参数
            if($params){

                //解析替换URL中的参数
                parse_str($params,$r);
                foreach($r as $k=>$v){
                    if(strpos($site_url,'['.$k.']')){
                        $site_url   =   str_replace('['.$k.']',$v,$site_url);
                    }else{
                        $lr[$k] =   $v;
                    }
                }

                //填充剩余参数
                if(isset($lr) && is_array($lr) && count($lr)>0){
                    $site_url   .=  '?'.http_build_query($lr);
                }

            }
        }
    }

    //输出地址或跳转
    if($redirect){
        redirect($site_url);
    }else{
        return $site_url;
    }
}

/**
 * 通用加密
 * @param String $string 需要加密的字串
 * @param String $skey 加密EKY
 * @return String
 */
 function lcy_encrypt($string = '') {
    $codeKey = __codeKey__;
    $ckey = array_reverse(str_split($codeKey));
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach ($ckey as $key => $value) {
        $key < $strCount && $strArr[$key].=$value;
    }
    return str_replace('=', 'O0O0O', join('', $strArr));
 }
 /**
 * 通用解密
 * @param String $string 需要解密的字串
 * @param String $skey 解密KEY
 * @return String
 */
 function lcy_decrypt($string = '') {
    $codeKey = __codeKey__;
    $ckey = array_reverse(str_split($codeKey));
    $strArr = str_split(str_replace('O0O0O', '=', $string), 2);
    $strCount = count($strArr);
    foreach ($ckey as $key => $value) {
        $key < $strCount && $strArr[$key] = $strArr[$key][0];
    }
    return base64_decode(join('', $strArr));
 }

?>
