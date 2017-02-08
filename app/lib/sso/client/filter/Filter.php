<?php

//define("CURRENT_URI", "SsoClientCurrentURI");
//define("SERVICE_URI", "SsoClientServiceURI");

interface Filter {
	public function init();
	public function doFilter();
}

abstract class AbstractFilter implements Filter {
	
	//abstract public function doFilter();
	
	protected function startsWith($haystack, $needle)
	{
	    $length = strlen($needle);
	    return (substr($haystack, 0, $length) === $needle);
	}
	
	protected function endsWith($haystack, $needle)
	{
	    $length = strlen($needle);
	    $start  = $length * -1; //negative
	    return (substr($haystack, $start) === $needle);
	}
	
	protected function getRequestURI() {
		$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);
		return $request_uri[0];
	}
	protected function getQueryString() {
		$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);
		
		if (isset($request_uri[1]) && $request_uri[1])
		{
			$query_string	= $this->removeParameterFromQueryString('ticket', $request_uri[1]);

			// If the query string still has anything left, append it to the final URI
			if ($query_string !== '')
				return "?".$query_string;
		}
		
		return "";
	}
	
	
	protected function getCurrentURL()
	{
		$final_uri = '';
		// remove the ticket if present in the URL
		$final_uri = ($this->isHttps()) ? 'https' : 'http';
		$final_uri .= '://';

		$final_uri .= $this->getServerUrl();
		$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);
		$final_uri .= $request_uri[0];

		return $final_uri;
	}
	
	private $_url = '';
	
	/**
	 * This method returns the URL of the current request (without any ticket
	 * CGI parameter).
	 *
	 * @return The URL
	 */
	protected function getURL()
	{
		// the URL is built when needed only
		if ( empty($this->_url) ) {
			$final_uri = '';
			// remove the ticket if present in the URL
			$final_uri = ($this->isHttps()) ? 'https' : 'http';
			$final_uri .= '://';

			$final_uri .= $this->getServerUrl();
			$request_uri	= explode('?', $_SERVER['REQUEST_URI'], 2);
			$final_uri		.= $request_uri[0];
				
			if (isset($request_uri[1]) && $request_uri[1])
			{
				$query_string	= $this->removeParameterFromQueryString('ticket', $request_uri[1]);

				// If the query string still has anything left, append it to the final URI
				if ($query_string !== '')
				$final_uri	.= "?".$query_string;

			}

			$this->setURL($final_uri);
		}
		return $this->_url;
	}
	

	/**
	 * Try to figure out the server URL with possible Proxys / Ports etc.
	 * @return Server URL with domain:port
	 */
	protected function getServerUrl(){
		$server_url = '';
		if(!empty($_SERVER['HTTP_X_FORWARDED_HOST'])){
			// explode the host list separated by comma and use the first host
			$hosts = explode(',', $_SERVER['HTTP_X_FORWARDED_HOST']);
			$server_url = $hosts[0];
		}else if(!empty($_SERVER['HTTP_X_FORWARDED_SERVER'])){
			$server_url = $_SERVER['HTTP_X_FORWARDED_SERVER'];
		}else{
			if (empty($_SERVER['SERVER_NAME'])) {
				$server_url = $_SERVER['HTTP_HOST'];
			} else {
				$server_url = $_SERVER['SERVER_NAME'];
			}
		}
		if (!strpos($server_url, ':')) {
			if ( ($this->isHttps() && $_SERVER['SERVER_PORT']!=443)
			|| (!$this->isHttps() && $_SERVER['SERVER_PORT']!=80) ) {
				$server_url .= ':';
				$server_url .= $_SERVER['SERVER_PORT'];
			}
		}
		return $server_url;
	}
	
	/**
	 * This method checks to see if the request is secured via HTTPS
	 * @return true if https, false otherwise
	 */
	protected function isHttps() {
		if ( isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
			return true;
		} else {
			return false;
		}
	}
	
	private function removeParameterFromQueryString($parameterName, $queryString)
	{
		$parameterName = preg_quote($parameterName);
		return preg_replace("/&$parameterName(=[^&]*)?|^$parameterName(=[^&]*)?&?/", '', $queryString);
	}
}

?>