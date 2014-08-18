<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//require COMMON_PATH.'OAuth/Tencent.php';
namespace Home\Controller;
use Think\Controller;

class WeiboController extends Controller {
    
    protected static $userArr = null; //用户信息
    protected static $memberArr = null;//用户授权信息   

    /**
    * 构造函数
    *
    */
   public function __construct()
   {
	parent::__construct();
	self::$userArr = $this->getUser();
	if(self::$userArr && is_array(self::$userArr)){
	    $this->assign('userId', self::$userArr['userId']);
	    self::$memberArr = $this->getMember(self::$userArr['userId']);
	    if(!is_array(self::$memberArr)){
		$this->success("您还没有授权账号！请开始授权！", U('ContorlMember/index'));
	    }    
	}else{
	    $this->success("获取用户出错，请重新登录！", U('Index/index'));
	}  
   }
   
   //获取登录用户信息
    public function getUser(){
	$userStr = $_COOKIE[__passport__];
	if(isset($userStr)){
	    $userArr = json_decode($userStr, true);
	    $userArr['password'] = lcy_decrypt($userArr['password']);
	    return $userArr;
	}else{
	    return false;
	}
    }
   //获取登录用户授权信息
    public function getMember($userId){
	//获取登录帐号已授权的qq信息
	$Member = M("user_member");
	$memberarr['uid'] = $userId;
	$result = $Member->where($memberarr)->select();
	$memberArr = null;
	if ($result) {
	    //遍历绑定帐号
	    foreach ($result as $v) {
		$memberArr[$v['open_id']] = array();
		//获取access_token存入session
		$memberArr[$v['open_id']]['t_access_token'] = $v['access_token'];
		$memberArr[$v['open_id']]['t_openid'] = $v['open_id'];
		$memberArr[$v['open_id']]['t_openkey'] = $v['open_key'];
		$memberArr[$v['open_id']]['member_type'] = $v['member_type'];
	    }
	    return $memberArr;
	}else{
	    return false;
	}
    }
}
?>
