<?php

namespace Home\Controller;
use Think\Controller;

class HomeController extends WeiboController {

    public function index() {
	$userArr = self::$userArr;
	deldir($userArr['userId']);
	$this->viweBlogs();
    }
    
    //构造home页
    public function viweBlogs() {
	$unit = 'home';
	if ($_GET['memberId']) {
	    $memberId = $_GET['memberId'];
	} else {
	    $memberId = 1;
	    foreach ($_SESSION['access'] as $v) {
		$memberId = $v['t_openid'];
		break;
	    }
	}
	if ($_GET['unit']) {
	    $unit = $_GET['unit'];
	}
	$this->assign("unit", $unit);
	$this->assign("nowmember", $memberId);
	$this->assign("changetop", "tophome");
	
	$this->viewUser();
	$this->viewMember();	
	$this->viewEmotion();

	$this->display('index');
    }
    
    public function viewUser() {
	//获取登录账号姓名信息
	$userArr = self::$userArr;
	$user['nickname'] = $userArr['nickname'];
	$user['username'] = $userArr['username'];
	$this->assign('user', $user);
    }

    public function viewMember() {
	if (is_array(self::$memberArr)) {
	    $memberArr = self::$memberArr;
	    $resultCount = 0; //获取绑定帐号的数量
	    //遍历绑定帐号
	    foreach ($memberArr as $v) {
		$re = Tencent::api($v, 'user/info'); //调用腾讯微博api获得用户信息               
		$r = json_decode($re, true); //返回值为json格式，需要解码为数组
		$member[$resultCount] = $r;
		//返回的头像url为不完整url，需设置头像获取头像为100*100
		$member[$resultCount]['data']['headurl'] = "\"" . $member[$resultCount]['data']['head'] . "/100\"";
		$member[$resultCount]['data']['blogtype'] = $v['member_type'];
		$resultCount++;
	    }
	    $this->assign('member', $member);
	    $this->assign("memberCount", $resultCount);
	}else{
	    $this->error('系统出错，请重新登录!', U('Index/index'));
	}
    }

    //发布一条微博并同步，利用表单直接提交的方式,已停用
    public function sendCnt() {
	(array) $data = $_POST['selectSyn'];
	$msg = $_POST['content'];
	$params = array(
	    'content' => $msg
	);

	if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/bmp") || ($_FILES["file"]["type"] == "image/ico")) && ($_FILES["file"]["size"] < 4194304)) {
	    if ($_FILES["file"]["error"] > 0) {
		echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
	    } else {
		$pic = $_FILES["file"]["tmp_name"];
	    }
	}

	if ($_POST['selectSyn'] != null) {
	    foreach ($data as $value) {
		$member = explode('&', $value);
		$memberId = $member[0];
		if ($msg != null) {
		    if ($_SESSION['access'][$memberId]['t_access_token'] || ($_SESSION['access'][$memberId]['t_openid'] && $_SESSION['access'][$memberId]['t_openkey'])) {
			if ($pic == NULL) {
			    $result = Tencent::api($memberId, 't/add', $params, 'POST');
			} else {
			    $multi = array('pic' => $pic);
			    $result = Tencent::api($memberId, 't/add_pic', $params, 'POST', $multi);
			}

			$r = json_decode($result, true);
			if ($r['ret'] == 0) {
			    $Blog = M('blog');
			    $blogarr['bid'] = $r['data']['id'];
			    $blogarr['timestamp'] = $r['data']['time'];
			    $blogarr['user_Id'] = $_SESSION['userId'];
			    $blogarr['member_Id'] = $memberId;
			    $blogarr['content'] = $msg;
			    $blogarr['member_name'] = $member[1];
			    $blogarr['blog_type'] = $member[2];
			    $blogarr['image_head'] = $member[3];
			    if ($Blog->add($blogarr)) {
				
			    } else {
				$this->error("Success:微博发布成功；</br>Error:微博信息添加数据库失败");
			    }
			} else {
			    $errorMsg = "发送失败：错误代码" . $r['errcode'] . ";错误信息：" . $r['msg'];
			    $this->error($errorMsg);
			}
		    } else {
			$this->error("获取账号授权失败！");
		    }
		} else {
		    $this->error("发表微博内容不能为空！");
		}
	    }
	} else {
	    $this->error("获取发送用户信息出错！");
	}
	$this->index();
    }

    //ajax发布一条微博并同步
    public function sendwb() {
	if (!isset($_POST['selectSyn']) && !isset($_POST['content'])) {
	    echo '获取信息出错';
	} else {
	    (array) $data = $_POST['selectSyn'];
	    (array) $pics = $_POST['pics'];
	    $msg = $_POST['content'];
	    $params = array(
		'content' => $msg
	    );
	    $errorMsg = "";
	    foreach ($data as $value) {
		$member = explode('&', $value);
		$memberId = $member[0];
		if ($msg != null) {
		    if ($_SESSION['access'][$memberId]['t_access_token'] || ($_SESSION['access'][$memberId]['t_openid'] && $_SESSION['access'][$memberId]['t_openkey'])) {
			if ($pics == NULL) {
			    $result = Tencent::api($memberId, 't/add', $params, 'POST');
			} else {
			    $pic = $pics[0];
			    $multi = array('pic' => $_SERVER[DOCUMENT_ROOT] . "/" . APP_NAME . "/Public/UpLoads/" . $_SESSION['userId'] . "/" . $pic);
			    $result = Tencent::api($memberId, 't/add_pic', $params, 'POST', $multi);
			}
			$r = json_decode($result, true);
			if ($r['ret'] === 0) {
			    $Blog = M('blog');
			    $blogarr['bid'] = $r['data']['id'];
			    $blogarr['timestamp'] = $r['data']['time'];
			    $blogarr['user_Id'] = $_SESSION['userId'];
			    $blogarr['member_Id'] = $memberId;
			    $blogarr['content'] = $msg;
			    $blogarr['member_name'] = $member[1];
			    $blogarr['blog_type'] = $member[2];
			    $blogarr['image_head'] = $member[3];
			    if ($Blog->add($blogarr)) {
				
			    } else {
				$errorMsg .= $member[1] . "Success:微博发布成功；</br>Error:微博信息添加数据库失败;";
			    }
			} else {
			    $errorMsg .= $member[1] . "发送失败：错误代码" . $r['errcode'] . ";错误信息：" . $r['msg'];
			}
		    } else {
			$errorMsg .= "获取" . $member[1] . "授权失败！";
		    }
		} else {
		    $errorMsg .= $member[1] . "发表微博内容不能为空！";
		}
	    }
	    deldir($_SESSION['userId']);
	    echo $errorMsg;
	}
    }
    //显示上传的图片
    public function showPic() {
	if (!empty($_FILES['attach'])) {
	    if ((($_FILES["attach"]["type"] == "image/gif") || ($_FILES["attach"]["type"] == "image/jpeg") || ($_FILES["attach"]["type"] == "image/jpg") || ($_FILES["attach"]["type"] == "image/png") || ($_FILES["attach"]["type"] == "image/bmp") || ($_FILES["attach"]["type"] == "image/ico")) && ($_FILES["file"]["size"] < 4194304)) {
		if ($_FILES["attach"]["error"] > 0) {
		    echo '<body onload="showPic()"><script type="text/javascript">function showPic(){parent.showTipsMsg("' . $_FILES["attach"]["error"] . '")}</script></body>';
		} else {
		    $this->upLoad();
		    // $this->info[0]['name'];  返回文件的名字
		    //parent.show() 调用父框架的show()方法
		    // echo输出的在子框架也就是iframe框架中显示
		    echo '<body onload="showPic()"><script type="text/javascript">function showPic(){parent.show("' . $_SESSION['userId'] . '/s_' . $this->info[0]['savename'] . '","' . $this->info[0]['savename'] . '")}</script></body>';
		}
	    } else {
		echo '<body onload="showPic()"><script type="text/javascript">function showPic(){parent.showTipsMsg("图片类型或大小错误")}</script></body>';
	    }
	} else {
	    echo '<body onload="showPic()"><script type="text/javascript">function showPic(){parent.showTipsMsg("获取图片失败")}</script></body>';
	}
    }

    //  上传图片
    public function upLoad() {
	import("ORG.Net.UploadFile");
	// 实例化上传类
	$upload = new UploadFile ();
	// 讴置附件上传大小
	$upload->maxSize = 4194304;
	// 讴置附件上传类型
	$upload->allowExts = array('jpg', 'gif', 'png', 'jpeg', 'bmp', 'ico');
	//设置需要生成缩略图，仅对图像文件有效
	$upload->thumb = true;
	// 设置引用图片类库包路径
	$upload->imageClassPath = '@.ORG.Image';
	//创建用户文件夹
	$memberDir = "./Public/Uploads/" . $_SESSION['userId'];
	if (!file_exists($memberDir))
	    mkdir($memberDir, 0777);
	// 讴置附件上传目录
	$upload->savePath = $memberDir . "/";
	//设置需要生成缩略图，仅对图像文件有效 
	$upload->thumb = true;
	//设置需要生成缩略图的文件后缀 
	$upload->thumbPrefix = 's_';
	//设置缩略图最大宽度 
	$upload->thumbMaxWidth = '160';
	//设置缩略图最大高度 
	$upload->thumbMaxHeight = '160';

	// 上传错诣 提示错诣信息
	if (!$upload->upload()) {
	    $this->error($upload->getErrorMsg());
	} else {
	    // 上传成功 获叏上传文件信息
	    $this->info = $upload->getUploadFileInfo();
	    dump($this->info);
	}
    }


    //表情json，传入表情参数
    public function viewEmotion() {
	require_once COMMON_PATH . 'emotionJson.php';
	$emotionjsons = json_decode($emotionJson, true);
	$emotions = $emotionjsons['data']['emotions'];
	$this->assign('emotions', $emotions);
    }

    //获取微博评论内容
    public function getComment() {
	$twitterId = $_GET['twitterid'];
	$rootid = $_GET['rootid'];
	$timestamp = $_GET['timestamp'];
	$pageflag = $_GET['pageflag'];
	$params = array(
	    'flag' => 1,
	    'rootid' => $rootid,
	    'pageflag' => $pageflag,
	    'pagetime' => $timestamp,
	    'reqnum' => 10,
	    'twitterid' => $twitterId,
	);
	if ($_GET['memberId']) {
	    $memberId = $_GET['memberId'];
	} else {
	    $memberId = 1;
	}
	$relayJson = Tencent::api($memberId, 't/re_list', $params, 'GET');
	$relaysarray = json_decode($relayJson, true);
	$relays = $relaysarray['data']['info'];
	foreach ($relays as $k => $v) {
	    $relays[$k]['text'] = setEmotion($v['text']);
	    $relays[$k]['time'] = tranTime($v['timestamp']);
	}
	$relayList = json_encode($relays);
	echo $relayList;
    }

    //加载更多微博信息
    public function moreBlogs() {
	if (isset($_GET['timestamp']) && isset($_GET['memberId']) && isset($_GET['unit'])) {
	    $timestamp = (int) ($_GET['timestamp']);
	    $memberId = $_GET['memberId'];
	    $unit = $_GET['unit'];
	    $params = array(
		//设置获取微博数量
		'reqnum' => 10,
		'pagetime' => $timestamp,
		'pageflag' => 1,
		'type' => 0,
		'contenttype' => 0
	    );
	    $r = json_encode(getBlogs($params, $memberId, $unit));
	    echo $r;
	} else {
	    echo 'error';
	}
    }

    //加载最新微博
    public function newblogs() {
	if (isset($_GET['blogid']) && isset($_GET['memberId']) && isset($_GET['unit'])) {
	    $oneBlogParams = array(
		"id" => $_GET['blogid']
	    );
	    $oneBlog = Tencent::api($_GET['memberId'], 't/show', $oneBlogParams, 'GET');
	    $oneBlogArr = json_decode($oneBlog, TRUE);
	    $timestamp = $oneBlogArr['data']['timestamp'];
	    $params = array(
		//设置获取微博数量
		'reqnum' => 70,
		'pagetime' => $timestamp,
		'pageflag' => 2,
		'type' => 0,
		'contenttype' => 0
	    );
	    $r = json_encode(getBlogs($params, $_GET['memberId'], $_GET['unit']));
	    echo $r;
	} else {
	    echo 'error';
	}
    }

    //评论 转发一条微博
    public function comment() {
	if ($_GET['type']) {
	    if ($_GET['type'] == 'zf') {
		$type = "转发";
	    } else if ($_GET['type'] == "talk") {
		$type = "评论";
	    }
	    if ($_GET['memberId'] && $_GET['blogId'] && $_GET['content']) {
		$member = $_GET['memberId'];
		$params = array(
		    'content' => $_GET['content'],
		    'reid' => $_GET['blogId']
		);
		if ($type == "评论") {
		    $r = Tencent::api($member, 't/comment', $params, 'GET');
		} else if ($type == "转发") {
		    $r = Tencent::api($member, 't/re_add', $params, 'GET');
		}
		$r = json_decode($r, true);
		if ($r['data']['ret'] == 0) {
		    echo $type . '成功!';
		} else {
		    echo '连接失败!';
		}
	    } else {
		echo $type . '失败！';
	    }
	}
    }

    //收藏，取消收藏微博
    public function favBlog() {
	$value = '收藏';
	if ($_GET['id'] && $_GET['value'] && $_GET['memberId']) {
	    $member = $_GET['id'];
	    $value = $_GET['value'];
	    $params = array(
		'id' => $member
	    );
	    $memberId = $_GET['memberId'];
	    if ($value == '收藏') {
		$rjson = Tencent::api($memberId, 'fav/addt', $params, 'GET');
		$r = json_decode($rjson, true);
		if ($r['ret'] == "0") {
		    echo '取消收藏';
		} else {
		    echo '收藏';
		}
	    } else {
		$rjson = Tencent::api($memberId, 'fav/delt', $params, 'GET');
		$r = json_decode($rjson, true);
		if ($r['ret'] == "0") {
		    echo '收藏';
		} else {
		    echo '取消收藏';
		}
	    }
	} else {
	    echo $value;
	}
    }

    //删除一条微博
    public function delBlog() {
	if ($_GET['blogid'] && $_GET['memberId']) {
	    $params = array(
		'id' => $_GET['blogid']
	    );
	    $memberId = $_GET['memberId'];
	    $rjson = Tencent::api($memberId, 't/del', $params, 'GET');
	    $r = json_decode($rjson, true);
	    if ($r['ret'] == 0) {
		echo '0';
	    } else {
		echo $r['msg'];
	    }
	} else {
	    echo 'error';
	}
    }

    //获取用户个人资料
    public function otherInfo() {
	if ($_GET['otherId'] && $_GET['memberId']) {
	    $params = array(
		'fopenid' => $_GET['otherId']
	    );
	    $rjson = Tencent::api($_GET['memberId'], 'user/other_info', $params, 'GET');
	    $r = json_decode($rjson, true);
	    if ($r['ret'] == 0) {
		if ($r['data'] != NULL) {
		    $r = $r['data'];
		    //设置头像大小
		    $r['head'] = $r['head'] . '/100';
		    //设置查看发微博人的主页
		    $r['blogAdd'] = "http://t.qq.com/" . $r['name'];
		    echo json_encode($r);
		}else{
		    echo 'error';
		}
	    } else {
		echo 'error';
	    }
	} else {
	    echo 'error';
	}
    }
    
}
?>
