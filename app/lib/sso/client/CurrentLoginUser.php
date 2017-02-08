<?php

define("CURRENT_LOGIN_USER_KEY", 'SsoClientCurrentLoginUser');

define("LOGIN_USER_ID", 'id');
define("LOGIN_USER_NAME", 'name');
define("LOGIN_USER_NICK", 'nick');
define("LOGIN_USER_EMAIL", 'email');
define("LOGIN_USER_TEL", 'tel');
define("LOGIN_USER_MOBILE", 'mobile');
define("LOGIN_USER_IDCARD", 'idCard');
define("LOGIN_USER_TYPE", 'type');

define("LOGIN_USER_ORG_ID", 'dicOrgId');
define("LOGIN_USER_ORG_CODE", 'deptCode');
define("LOGIN_USER_ORG_NAME", 'deptName');

define("LOGIN_USER_STAFF_NO", 'staffNo');
define("LOGIN_USER_STUDENT_NO", 'studentNo');

define("LOGIN_USER_SSO_ACCOUNT", 'ssoAccount');

define("LOGIN_USER_LOCAL_ACCOUNT", 'localAccount');
define("LOGIN_USER_LOCAL_PASS", 'localPass');

class CurrentLoginUser {
	public function __construct($loginUserAccount){
		$this->_loginUserAccount=trim($loginUserAccount);
	}

	/**
	 * 登录帐号
	 */
	private $_loginUserAccount;
	public static function getLoginUserAccount(){
		return $_SESSION[CURRENT_LOGIN_USER_KEY]['user'];//$this->_loginUserAccount;
	}
	public static function setLoginUserAccount($loginUserAccount){
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]))
			$_SESSION[CURRENT_LOGIN_USER_KEY] = array();
		$_SESSION[CURRENT_LOGIN_USER_KEY]['user'] = trim($loginUserAccount);
		//$this->_loginUserAccount=trim($loginUserAccount);
	}

	/**
	 * 统一认证系统帐号
	 */
	private $_loginUserSSOAccount;
	public static function getLoginUserSSOAccount(){
		return $_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_SSO_ACCOUNT];//$this->_loginUserSSOAccount;
	}
	public static function setLoginUserSSOAccount($loginUserSSOAccount){
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]))
			$_SESSION[CURRENT_LOGIN_USER_KEY] = array();
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]['attributes']))
			$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'] = array();
		$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_SSO_ACCOUNT] = trim($loginUserSSOAccount);
		//$this->_loginUserSSOAccount=trim($loginUserSSOAccount);
	}
	
	
	/**
	 * 本地系统帐号名称
	 */
	private $_loginUserLocalAccount;
	public static function getLoginUserLocalAccount(){
		return $_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_LOCAL_ACCOUNT];//$this->_loginUserLocalAccount;
	}
	public static function setLoginUserLocalAccount($loginUserLocalAccount){
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]))
			$_SESSION[CURRENT_LOGIN_USER_KEY] = array();
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]['attributes']))
			$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'] = array();
		$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_LOCAL_ACCOUNT] = trim($loginUserLocalAccount);
		//$this->_loginUserLocalAccount=trim($loginUserLocalAccount);
		
	}
	/**
	 * 本地系统帐号密码
	 */
	private $_loginUserLocalPass;
	public static function getLoginUserLocalPass(){
		return $_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_LOCAL_PASS];//$this->_loginUserLocalPass;
	}
	public static function setLoginUserLocalPass($loginUserLocalPass){
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]))
			$_SESSION[CURRENT_LOGIN_USER_KEY] = array();
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]['attributes']))
			$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'] = array();
		$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_LOCAL_PASS] = trim($loginUserLocalPass);
		//$this->_loginUserLocalPass=trim($loginUserLocalPass);
	}

	/**
	 * 用户Id
	 */
	private $_loginUserId;
	public static function getLoginUserId(){
		return $_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_ID];//$this->_loginUserId;
	}
	public static function setLoginUserId($loginUserId){
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]))
			$_SESSION[CURRENT_LOGIN_USER_KEY] = array();
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]['attributes']))
			$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'] = array();
		$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_ID] = trim($loginUserId);
		//$this->_loginUserId=trim($loginUserId);
	}

	/**
	 * 姓名
	 */
	private $_loginUserName;
	public static function getLoginUserName() {
		return $_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_NAME];//$this->_loginUserName;
	}
	public static function setLoginUserName($loginUserName) {
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]))
			$_SESSION[CURRENT_LOGIN_USER_KEY] = array();
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]['attributes']))
			$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'] = array();
		$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_NAME] = trim($loginUserName);
		//$this->_loginUserName = trim($loginUserName);
	}

	/**
	 * 昵称
	 */
	private $_loginUserNick;
	public static function getLoginUserNick() {
		return $_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_NICK];//$this->_loginUserNick;
	}
	public static function setLoginUserNick($loginUserNick) {
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]))
			$_SESSION[CURRENT_LOGIN_USER_KEY] = array();
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]['attributes']))
			$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'] = array();
		$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_NICK] = trim($loginUserNick);
		//$this->_loginUserNick = trim($loginUserNick);
	}
	
	private $_loginUserEmail;
	private $_loginUserTel;
	private $_loginUserMobile;
	private $_loginUserIDCard;
	private $_loginUserType;
	private $_loginUserOrgId;
	private $_loginUserOrgCode;
	private $_loginUserOrgName;

	public static function getLoginUserEmail() {
		return $_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_EMAIL];//$this->_loginUserEmail;
	}
	public static function setLoginUserEmail($loginUserEmail) {
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]))
			$_SESSION[CURRENT_LOGIN_USER_KEY] = array();
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]['attributes']))
			$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'] = array();
		$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_EMAIL] = trim($loginUserEmail);
		//$this->_loginUserEmail = trim($loginUserEmail);
	}
	public static function getLoginUserTel() {
		return $_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_TEL];//$this->_loginUserTel;
	}
	public static function setLoginUserTel($loginUserTel) {
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]))
			$_SESSION[CURRENT_LOGIN_USER_KEY] = array();
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]['attributes']))
			$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'] = array();
		$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_TEL] = trim($loginUserTel);
		//$this->_loginUserTel = trim($loginUserTel);
	}
	public static function getLoginUserMobile() {
		return $_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_MOBILE];//$this->_loginUserMobile;
	}
	public static function setLoginUserMobile($loginUserMobile) {
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]))
			$_SESSION[CURRENT_LOGIN_USER_KEY] = array();
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]['attributes']))
			$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'] = array();
		$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_MOBILE] = trim($loginUserMobile);
		//$this->_loginUserMobile = trim($loginUserMobile);
	}
	public static function getLoginUserIDCard() {
		return $_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_IDCARD];//$this->_loginUserIDCard;
	}
	public static function setLoginUserIDCard($loginUserIDCard) {
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]))
			$_SESSION[CURRENT_LOGIN_USER_KEY] = array();
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]['attributes']))
			$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'] = array();
		$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_IDCARD] = trim($loginUserIDCard);
		//$this->_loginUserIDCard = trim($loginUserIDCard);
	}
	public static function getLoginUserType() {
		return $_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_TYPE];//$this->_loginUserType;
	}
	public function setLoginUserType($loginUserType) {
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]))
			$_SESSION[CURRENT_LOGIN_USER_KEY] = array();
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]['attributes']))
			$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'] = array();
		$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_TYPE] = trim($loginUserType);
		//$this->_loginUserType = trim($loginUserType);
	}
	public static function getLoginUserOrgId() {
		return $_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_ORG_ID];//$this->_loginUserOrgId;
	}
	public static function setLoginUserOrgId($loginUserOrgId) {
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]))
			$_SESSION[CURRENT_LOGIN_USER_KEY] = array();
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]['attributes']))
			$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'] = array();
		$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_ORG_ID] = trim($loginUserOrgId);
		//$this->_loginUserOrgId = trim($loginUserOrgId);
	}
	public static function getLoginUserOrgCode() {
		return $_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_ORG_CODE];//$this->_loginUserOrgCode;
	}
	public static function setLoginUserOrgCode($loginUserOrgCode) {
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]))
			$_SESSION[CURRENT_LOGIN_USER_KEY] = array();
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]['attributes']))
			$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'] = array();
		$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_ORG_CODE] = trim($loginUserOrgCode);
		//$this->_loginUserOrgCode = trim($loginUserOrgCode);
	}
	public static function getLoginUserOrgName() {
		return $_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_ORG_NAME];//$this->_loginUserOrgName;
	}
	public static function setLoginUserOrgName($loginUserOrgName) {
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]))
			$_SESSION[CURRENT_LOGIN_USER_KEY] = array();
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]['attributes']))
			$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'] = array();
		$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_ORG_NAME] = trim($loginUserOrgName);
		//$this->_loginUserOrgName = trim($loginUserOrgName);
	}
	
	private $_loginUserStaffNo;
	public static function getLoginUserStaffNo() {
		return $_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_STAFF_NO];//$this->_loginUserStaffNo;
	}
	public static function setLoginUserStaffNo($loginUserStaffNo) {
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]))
			$_SESSION[CURRENT_LOGIN_USER_KEY] = array();
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]['attributes']))
			$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'] = array();
		$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_STAFF_NO] = trim($loginUserStaffNo);
		//$this->_loginUserStaffNo = trim($loginUserStaffNo);
	}
	private $_loginUserStudentNo;
	public static function getLoginUserStudentNo() {
		return $_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_STUDENT_NO];//$this->_loginUserStudentNo;
	}
	public static function setLoginUserStudentNo($loginUserStudentNo) {
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]))
			$_SESSION[CURRENT_LOGIN_USER_KEY] = array();
		if (!isset($_SESSION[CURRENT_LOGIN_USER_KEY]['attributes']))
			$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'] = array();
		$_SESSION[CURRENT_LOGIN_USER_KEY]['attributes'][LOGIN_USER_STUDENT_NO] = trim($loginUserStudentNo);
		//$this->_loginUserStudentNo = trim($loginUserStudentNo);
	}
	
	/*
	private $_loginUserIsTeacher;
	public function getLoginUserIsTeacher() {
		return $this->_loginUserIsTeacher;
	}
	public function setLoginUserIsTeacher($loginUserIsTeacher) {
		$this->_loginUserIsTeacher = $loginUserIsTeacher;
	}
	*/
}

?>