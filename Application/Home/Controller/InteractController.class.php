<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InteractAction
 *
 * @author lichenyang
 */
class InteractAction extends Action {

    public function index() {
        $this->viewuser();
        $this->viewmymember();
    }

    public function viewuser() {
        //获取登录账号姓名信息
        $user['nickname'] = $_SESSION['nickname'];
        $user['username'] = $_SESSION['username'];
        $this->assign('user', $user);
        $this->assign("changetop", "topinteract");
    }

    public function viewmymember() {
        $Member = M("user_member");
        $memberarr['uid'] = $_SESSION['userId'];
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
            $this->assign('firstmember', $result[0]['open_id']);
        }
        $this->assign('sopenid', $r);
        $this->assign('member', $member);
        $this->display('index');
    }

    public function structList() {
        $mymember = $_SESSION['member'];
        $this->assign('mymember', $mymember);
        $this->assign('nowchose', $mymember[0]['data']['openid']);
        $nowmember = 'interact1';
        $relation = 'fanslist';
        $_GET['nowmember'] && $nowmember = $_GET['nowmember'];
        $_GET['relation'] && $relation = $_GET['relation'];

        $params = array(
            //初始化请求参数
            'reqnum' => 10,
            'startindex' => 0
        );
        $results = Tencent::api($nowmember, 'user/' . $relation, $params, 'POST');

        //测试用本地json串
        require 'jsonStr.php';
        $results = $myfans;

        $results = json_decode($results, true);
        $results = $results['data']['info'];
        foreach ($results as $k => $i) {
            if ($results[$k]['head'] == '') {
                $results[$k]['head'] = IMAGES_PATH . '/head_normal_50.png';
            } else {
                $results[$k]['head'] = $i['head'] . '/50';
            }
            empty($results[$k]['location']) && ($results[$k]['location'] = "异次元裂缝");
        }
        $this->assign('friends', $results);
        $this->display('index');
    }

    public function viewFriends() {
        $_GET['nowmember'] && $nowmember = $_GET['nowmember'];
        $_GET['relation'] && $relation = $_GET['relation'];
        $_GET['startindex'] && $startindex = $_GET['startindex'];
        $params = array(
            //设置获取微博数量
            'reqnum' => 10,
            'startindex' => $startindex
        );
        $r = Tencent::api($nowmember, 'friends/' . $relation, $params, 'POST');
        $results = json_decode($r, true);
        if ($results['data'] != NULL) {
            $result = $results['data']['info'];
            foreach ($result as $k => $i) {
                if ($result[$k]['head'] == '') {
                    $result[$k]['head'] = IMAGES_PATH . '/head_normal_50.png';
                } else {
                    $result[$k]['head'] = $i['head'] . '/50';
                }
                empty($result[$k]['location']) && ($result[$k]['location'] = "异次元裂缝");
            }
            $results['data']['info'] = $result;
        }
        $myinfo = Tencent::api($nowmember, 'user/info', $params, 'POST');
        $info = json_decode($myinfo, true);
        if($relation == 'fanslist'){
            $num = $info['data']['fansnum'];
        }else if($relation == 'idollist'){
            $num = $info['data']['idolnum'];
        }else if($relation == 'mutual_list'){
            $num = $info['data']['mutual_fans_num'];
        }else{
            $num = 0;
        }
        $results['num'] = $num;
        $returnjson = json_encode($results);
        echo $returnjson;
    }
    
    public function delFriend(){
        $_GET['nowmember'] && $nowmember = $_GET['nowmember'];
        $_GET['fopenid'] && $fopenid = $_GET['fopenid'];
        $params = array(
            fopenid => $fopenid
        );
        $r = Tencent::api($nowmember, 'friends/del', $params, 'POST');
        $result = json_decode($r,TRUE);
        echo $result['msg'];
    }
}

?>
