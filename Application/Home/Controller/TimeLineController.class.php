<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TimeLineAction
 *
 * @author lichenyang
 */
class TimeLineAction extends Action {

    public function index() {
	//获取登录账号姓名信息
	$user['nickname'] = $_SESSION['nickname'];
	$user['username'] = $_SESSION['username'];
	$this->assign('user', $user);
	$this->assign("changetop", "toptimeLine");
	$this->display();
    }

    public function moreTimeLine() {
//        //ajax获取用户的发表日子
//        $Blog = M('blog');
//        //获取本次记录的开始节点
//        if( $_GET['startId'] != 0){
//            $startId = $_GET['startId'];
//            $user['id'] = array('lt',$startId);
//        }else{  
//            $startId = $Blog->order('id desc')->limit(1)->getField();
//        }
//        $user['user_id'] = $_SESSION['userId'];
//        //每次获取十条记录
//        $result = $Blog->where($user)->order('id desc')->limit(10)->select();
//        foreach ($result as $k => $i) {
//            $time = $i['timestamp'];
//            $result[$k]['time'] = tranTime($time);
//            $result[$k]['head'] = "\"" . $i['image_head'] . "/100\"";
//        }
//        $r = json_encode($result);
//        echo $r;

	$Member = M("user_member");
	$memberarr['uid'] = $_SESSION['userId'];
	$result = $Member->where($memberarr)->select();
	//获取绑定帐号的数量
	$resultCount = 0;
	//获取本次记录的开始节点
	$startId = 0;
	$_GET['startId'] && ($startId = $_GET['startId']);

	if ($result) {
	    //遍历绑定帐号
	    foreach ($result as $v) {
		//获取access_token存入session
		$memberId = $v['open_id'];
		$access[$memberId]['t_access_token'] = $v['access_token'];
		$access[$memberId]['t_openid'] = $v['open_id'];
		$access[$memberId]['t_openkey'] = $v['open_key'];
		$_SESSION['access'] = $access;
		$params = array(
		    //初始化请求参数
		    'pageflag' => 0,
		    'pagetime' => 0,
		    'reqnum' => 50,
		    'lastid' => 0,
		    'type' => 3,
		    'contenttype' => '0x80'
		);
		//调用腾讯微博api获得用户信息
		$re = Tencent::api($memberId, 'statuses/broadcast_timeline', $params, 'GET');
		//返回值为json格式，需要解码为数组
		$r = json_decode($re, true);
		$blogs = $r['data']['info'];

		$flag = 0;
		if (!isset($allblogs)) {
		    $allblogs = $blogs;
		} else {
		    $count = 0;
		    foreach ($allblogs as $v) {
			$fore = array();
			$wflag = TRUE;
			while ($wflag) {
			    if ($v['timestamp'] < $blogs[$flag]['timestamp']) {
				if ($count == 0) {
				    $fore[] = $blogs[$flag];
				    $allblogs = array_merge($fore, $allblogs);
				} else {
				    $fore = array_splice($allblogs, 0, $count);
				    $fore[] = $blogs[$flag];
				    $allblogs = array_merge($fore, $allblogs);
				}
				$count++;
				$flag++;
			    } else {
				$wflag = FALSE;
			    }
			}
			$count++;
		    }
		}
	    }
	}
	if (count($allblogs) > $startId) {
	    $allblogs = array_splice($allblogs, $startId, 10);
	    foreach ($allblogs as $k => $i) {
		$time = $i['timestamp'];
		$allblogs[$k]['time'] = tranTime($time);
		$allblogs[$k]['head'] = "\"" . $i['head'] . "/100\"";
		$allblogs[$k]['blog_type'] = 'tencent';
		$allblogs[$k]['text'] = setEmotion($i['text']);
		$allblogs[$k]['bigimage'] = $i['image'][0] . '/460';
	    }
	    $results = json_encode($allblogs);
	}
	echo $results;
    }

    //批量删除微博
    public function abatchDelBlogs() {
	if (!isset($_POST['selectSyn'])) {
	    echo '获取信息出错';
	} else {
	    (array) $data = $_POST['selectSyn'];
	    $errorMsg = "";
	    foreach ($data as $value) {
		$btnvalue = explode('&', $value);
		$memberId = $btnvalue[0];
		$blogId = $btnvalue[1];
		$blogType = $btnvalue[2];
		if($memberId == "" || $blogId == ""){
		    $errorMsg = "获取所删除微博信息出错！";
		}else{
		    if ($blogType == "tencent") {
			$params = array(
			    'id' => $blogId
			);
			$rjson = Tencent::api($memberId, 't/del', $params, 'POST');
			$r = json_decode($rjson, true);
			if ($r['ret'] != 0) {
			    $errorMsg = $r['msg'];
			}
		    }
		}
	    }
	    echo $errorMsg;
	}
	
    }

}

?>
