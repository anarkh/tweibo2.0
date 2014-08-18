<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of contorlMember
 *
 * @author lichenyang
 */
class ContorlMemberAction extends Action {

    public function index() {
        //获取登录账号姓名信息
        $user['nickname'] = $_SESSION['nickname'];
        $user['username'] = $_SESSION['username'];
        $this->assign('user', $user);
        $this->assign("changetop", "topcontorlMember");
        $this->display();
    }

    public function viewmymember() {
        $_GET['blogtype'] && ($blogtype = $_GET['blogtype']);
        //获取授权微博类型
        $_SESSION['blogtype'] = $blogtype;
        //获取登录帐号已授权的qq信息
        $Member = M("user_member");
        $memberarr['uid'] = $_SESSION['userId'];
        $memberarr['member_type'] = $blogtype;
        $result = $Member->where($memberarr)->select();
        //获取绑定帐号的数量
        $resultCount = 0;
        if ($result) {
            //遍历绑定帐号
            foreach ($result as $v) {
                //获取access_token存入session
                $memberId = $v['open_id'];
                $access[$memberId]['t_access_token'] = $v['access_token'];
                $access[$memberId]['t_openid'] = $v['open_id'];
                $access[$memberId]['t_openkey'] = $v['open_key'];
                $_SESSION['access'] = $access;
                //调用腾讯微博api获得用户信息
                $re = Tencent::api($memberId, 'user/info');
                //返回值为json格式，需要解码为数组
                $r = json_decode($re, true);
                $member[$resultCount] = $r;
                //返回的头像url为不完整url，需设置头像获取头像为100*100
                $member[$resultCount]['data']['headurl'] = "\"" . $member[$resultCount]['data']['head'] . "/100\"";
                $member[$resultCount]['data']['blogtype'] = $result[$resultCount]['member_type'];

                $resultCount++;
            }
        }
        $ajaxreturn = json_encode($member);
        echo $ajaxreturn;
    }

    public function authUser() {
        //获得user_member模型用于sql操作，$memberarr放入userId,用于sql判断
        $Member = M("user_member");
        $memberarr['uid'] = $_SESSION['userId'];

        header('Content-Type: text/html; charset=utf-8');

        if ($_SESSION['t_access_token'] != '' || ($_SESSION['t_openid'] != '' && $_SESSION['t_openkey'] != '')) {//用户已授权
            $_SESSION['t_access_token'] = '';
            $_SESSION['t_openid'] = '';
            $_SESSION['t_openkey'] = '';
            $this->assign("jumpUrl", U('ContorlMember/index'));
            $this->success("授权成功！");
        } else {//未授权
            $callback = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . ContorlMember / index; //回调url
            if ($_GET['code']) {//已获得code
                $code = $_GET['code'];
                $openid = $_GET['openid'];
                $openkey = $_GET['openkey'];
                //判断此qq是否已经被当前帐号授权过
//                $memberarr['open_id'] = $openid;
//                $result = $Member->where($memberarr)->select();
//                $accessTime = $result[0]['access_time'];
//                $expires_in = $result[0]['expires_in'];
//                $nowtime = time();
//                if ($accessTime + $expires_in > $nowtime) {
//                    exit('<h3>重复授权</h3>');
//                }
                //获取授权token
                $url = OAuth::getAccessToken($code, $callback);
                //以curl方式根据获取的code,openid与openkey访问腾讯微博开放平台，获取access_token
                $r = Http::request($url);
                //解析返回的字符串，以数组方式显示
                parse_str($r, $out);
                //存储授权数据
                if ($out['access_token']) {
                    $_SESSION['t_access_token'] = $out['access_token'];
                    $_SESSION['t_refresh_token'] = $out['refresh_token'];
                    $_SESSION['t_expires_in'] = $out['expires_in'];
                    $_SESSION['t_code'] = $code;
                    $_SESSION['t_openid'] = $openid;
                    $_SESSION['t_openkey'] = $openkey;
                    //存储用户授权信息                                       
                    $memberarr['uid'] = $_SESSION['userId'];
                    $memberarr['open_id'] = $openid;
                    $memberarr['open_key'] = $openkey;
                    $memberarr['access_token'] = $out['access_token'];
                    $memberarr['expires_in'] = $out['expires_in'];
                    $memberarr['refresh_token'] = $out['refresh_token'];
                    $memberarr['access_time'] = (string) time();
                    $memberarr['member_type'] = $_SESSION['blogtype'];
                    //刷新session
                    $_SESSION['access'][$memberId]['t_access_token'] = $out['access_token'];
                    $_SESSION['access'][$memberId]['t_openid'] = $openid;
                    $_SESSION['access'][$memberId]['t_openkey'] = $openkey;
                    //存取openid判断是否重复授权
                    $selectId['open_id'] = $openid;
                    $selectId['uid'] = $_SESSION['userId'];
                    //判断是否存在此用户
                    $repeat = $Member->where($selectId)->select();
                    //如果存在则刷新数据库，不存在则添加数据
                    if ($repeat) {
                        $isUpdate = $Member->where($selectId)->save($memberarr);
                        if ($isUpdate === FALSE) {
                            $_SESSION['t_access_token'] = '';
                            $_SESSION['t_openid'] = '';
                            $_SESSION['t_openkey'] = '';
                            $this->error("刷新授权失败,请重试");
                        }
                    } else {
//                        print_r($memberarr);
                        if (!$Member->add($memberarr)) {
                            $_SESSION['t_access_token'] = '';
                            $_SESSION['t_openid'] = '';
                            $_SESSION['t_openkey'] = '';
                            $this->error("授权添加数据库失败,请重试");
                        }
                    }

                    //验证授权
                    $r = OAuth::checkOAuthValid($memberId);
                    if ($r) {
                        header('Location: ' . $callback); //刷新页面
                    } else {
                        $_SESSION['t_access_token'] = '';
                        $_SESSION['t_openid'] = '';
                        $_SESSION['t_openkey'] = '';
                        $this->error("授权验证失败,请重试");
                    }
                } else {
                    exit($r);
                }
            } else {//获取授权code
                if ($_GET['openid'] && $_GET['openkey']) {//应用频道
                    $_SESSION['t_openid'] = $_GET['openid'];
                    $_SESSION['t_openkey'] = $_GET['openkey'];
                    //验证授权
                    $r = OAuth::checkOAuthValid($memberId);
                    if ($r) {
                        header('Location: ' . $callback); //刷新页面
                    } else {
                        $_SESSION['t_access_token'] = '';
                        $_SESSION['t_openid'] = '';
                        $_SESSION['t_openkey'] = '';
                        $this->error("授权失败,请重试");
                    }
                } else {
                    $url = OAuth::getAuthorizeURL($callback);
                    header('Location: ' . $url);
                }
            }
        }
    }

    //移除授权信息
    public function removeMember() {
        $Member = M("user_member");
        $memberarr['uid'] = $_SESSION['userId'];
        $_GET['openid'] && ($openid = $_GET['openid']);
        $memberarr['open_id'] = $openid;
        $isdelete = $Member->where($memberarr)->delete();
        if ($isdelete === FALSE) {
            echo "移除授权失败";
        } else {
            echo "移除授权成功";
        }
    }

}

?>
