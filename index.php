<?php
/*
  PHP version 5
  Copyright (c) 2002-2014 ECISP.CN、EarcLink.COM
  警告：这不是一个免费的软件，请在许可范围内使用，请尊重知识产权，侵权必究，举报有奖
  作者：黄祥云 E-mail:6326420@qq.com  QQ:6326420 TEL:18665655030
  ESPCMS官网介绍：http://www.ecisp.cn	企业建站：http://www.earclink.cn
 */
error_reporting(0);
ini_set("magic_quotes_runtime", 0);
ini_set('memory_limit', '640M');
ini_set('default_charset', 'utf-8');
define('adminfile', 'adminsoft');
define('admin_ClassURL', 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')) . '/');
define('admin_htmlDIR', '');
define('admin_URL', str_replace(admin_htmlDIR, '', admin_ClassURL));
define('admin_ROOT', dirname(__FILE__) . '/');
define('admin_http', $_SERVER['HTTP_HOST']);
define('admin_QUERY', $_SERVER['QUERY_STRING']);
define('admin_FROM', true);
define('admin_WAP', false);
$URL_QUERY = explode('index.php', admin_ClassURL);
if (!empty($URL_QUERY[1])) {
	if (!preg_match("/^[^!@~`\'\"#\*\(\)\+\{\}\[\]\\/\<\>\;]{2,16}$/i", $URL_QUERY[1])) {
		exit('Parameter error!');
	}
}
define('admin_AGENT', $_SERVER['HTTP_USER_AGENT']);
if (!@include admin_ROOT . 'datacache/public.php') {
	header('Content-type: text/html; charset=utf-8');
	header('location:install/index.php');
	exit;
}
$archive = indexget('ac', 'R');
$action = indexget('at', 'R');
if (!@include admin_ROOT . 'datacache/command.php') {
	header('Content-type: text/html; charset=utf-8');
	header('location:' . admin_URL . adminfile);
	exit;
}
require admin_ROOT . 'public/class_connector.php';
if ($CONFIG['is_close']) {
	header('Content-type: text/html; charset=utf-8');
	exit($CONFIG['close_content']);
}
include admin_ROOT . 'public/uc_config.php';
define('admin_LNG', $CONFIG['home_lng']);
define('LANCODE', $CONFIG['is_lancode']);
$lngpack = (admin_LNG == 'big5') ? $CONFIG['is_lancode'] : admin_LNG;
define('admin_LNGDIR', $lngpack . '/');
$rootDIR = $CONFIG['http_pathtype'] ? admin_URL : str_replace('http://' . admin_http, '', admin_URL);
define('admin_rootDIR', $rootDIR);
if (empty($archive) || empty($action)) {
	include admin_ROOT . 'interface/public.php';
	$mainlist = new mainpage();
	if (method_exists($mainlist, 'in_index')) {
		$mainlist->in_index();
	} else {
		exit('Access error!');
	}
} else {
	if (in_array($archive, array('article', 'forum', 'search', 'bbssearch', 'forummain', 'messmain', 'special', 'respond', 'public',
		    'scriptout', 'enquiry', 'enquirymain', 'form', 'formmain', 'ordermain', 'membermain', 'member', 'forum', 'order', 'weixinmain'))) {
		$action = 'in_' . $action;
		if (!file_exists(admin_ROOT . "interface/$archive.php")) {
			exit('Access error!');
		}
		include admin_ROOT . "interface/$archive.php";
		$mainlist = new mainpage();
		if (method_exists($mainlist, $action)) {
			$mainlist->$action();
		} else {
			exit('Access error!');
		}
	} else {
		exit('Access error!');
	}
}
function indexget($k, $var = 'R', $htmlcode = true, $rehtml = false) {
	switch ($var) {
		case 'G':
			$var = &$_GET;
			break;
		case 'P':
			$var = &$_POST;
			break;
		case 'C':
			$var = &$_COOKIE;
			break;
		case 'R':
			$var = &$_GET;
			if (empty($var[$k])) {
				$var = &$_POST;
			}
			break;
	}
	$putvalue = isset($var[$k]) ? indexdaddslashes($var[$k], 0) : NULL;
	return $htmlcode ? indexhtmldecode($putvalue) : $putvalue;
}
function indexdaddslashes($string, $force = 0, $strip = FALSE) {
	if (!get_magic_quotes_gpc() || $force == 1) {
		if (is_array($string)) {
			foreach ($string as $key => $val) {
				$string[$key] = addslashes($strip ? stripslashes($val) : $val);
			}
		} else {
			$string = addslashes($strip ? stripslashes($string) : $string);
		}
	}
	return $string;
}
function indexhtmldecode($str) {
	if (empty($str)) {
		return $str;
	}
	if (!is_array($str)) {
		$str = htmlspecialchars(trim($str));
		$str = str_ireplace("Xss", "", $str);
	} else {
		foreach ($str as $key => $val) {
			$str[$key] = htmlspecialchars($val);
			$str[$key] = indexhtmldecode($val);
		}
	}
	return $str;
}
function is_mobile() {
	$devices = array(
	    "operaMobi" => "Opera Mobi",
	    "android" => "android",
	    "blackberry" => "blackberry",
	    "iphone" => "(iphone|ipod)",
	    "opera" => "opera mini",
	    "palm" => "(avantgo|blazer|elaine|hiptop|palm|plucker|xiino)",
	    "windows" => "windows ce; (iemobile|ppc|smartphone)",
	    "generic" => "(kindle|mobile|mmp|midp|o2|pda|pocket|psp|symbian|smartphone|treo|up.browser|up.link|vodafone|wap)"
	);
	if (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
		return TRUE;
	} elseif (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/vnd.wap.wml') > 0 || strpos($_SERVER['HTTP_ACCEPT'], 'application/vnd.wap.xhtml+xml') > 0)) {
		return TRUE;
	} else {
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			foreach ($devices as $device => $regexp) {
				if (preg_match("/" . $regexp . "/i", $_SERVER['HTTP_USER_AGENT'])) {
					return TRUE;
				}
			}
		}
	}
	return FALSE;
}
class Timer {
	var $StartTime = 0;
	var $StopTime = 0;
	var $TimeSpent = 0;
	function start() {
		$this->StartTime = microtime();
	}
	function stop() {
		$this->StopTime = microtime();
	}
	function spent() {
		if ($this->TimeSpent) {
			return $this->TimeSpent;
		} else {
			$StartMicro = substr($this->StartTime, 0, 10);
			$StartSecond = substr($this->StartTime, 11, 10);
			$StopMicro = substr($this->StopTime, 0, 10);
			$StopSecond = substr($this->StopTime, 11, 10);
			$start = doubleval($StartMicro) + $StartSecond;
			$stop = doubleval($StopMicro) + $StopSecond;
			$this->TimeSpent = $stop - $start;
			return substr($this->TimeSpent, 0, 8) . "秒";
		}
	}
}
