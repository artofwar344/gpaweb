
<?php
include_once (dirname(__FILE__).'/config.php');

require_once (dirname(__FILE__).'/CAS.php');

include_once (dirname(__FILE__).'/client/CurrentLoginUser.php');

include_once (dirname(__FILE__).'/client/util/CommonUtil.php');

include_once (dirname(__FILE__).'/client/filter/SSOAuthenticationFilter.php');

include_once (dirname(__FILE__).'/client/filter/SSOClientFilter.php');

class SSOFilter {
	public static function doFilter() {
		header("content-type: text/html; charset=UTF-8");
		header("P3P: CP=CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR");
		
		$Filter_SSOAuthenticationFilter = new SSOAuthenticationFilter();
		$Filter_SSOAuthenticationFilter->init();
		$Filter_SSOAuthenticationFilter->doFilter();
		
		$Filter_SSOClientFilter = new SSOClientFilter();
		$Filter_SSOClientFilter->init();
		$Filter_SSOClientFilter->doFilter();
	}
}

SSOFilter :: doFilter();

?>