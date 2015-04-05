<?php

/*
  PHP version 5
  Copyright (c) 2002-2013 ECISP.CN、EarcLink.COM
  警告：这不是一个免费的软件，请在许可范围内使用，请尊重知识产权，侵权必究，举报有奖！
  作者：黄祥云 E-mail:6326420@qq.com  QQ:6326420 TEL:18665655030
  ESPCMS官网介绍：http://www.ecisp.cn 企业建站：http://www.earclink.cn
 */

class BCGColor {

	protected $r, $g, $b;

	public function __construct() {
		$args = func_get_args();
		$c = count($args);
		if ($c === 3) {
			$this->r = intval($args[0]);
			$this->g = intval($args[1]);
			$this->b = intval($args[2]);
		} elseif ($c === 1) {
			if (is_string($args[0]) && strlen($args[0]) === 7 && $args[0]{0} === '#') {  // Hex Value in String
				$this->r = intval(substr($args[0], 1, 2), 16);
				$this->g = intval(substr($args[0], 3, 2), 16);
				$this->b = intval(substr($args[0], 5, 2), 16);
			} else {
				if (is_string($args[0])) {
					$args[0] = self::getColor($args[0]);
				}
				$args[0] = intval($args[0]);
				$this->r = ($args[0] & 0xff0000) >> 16;
				$this->g = ($args[0] & 0x00ff00) >> 8;
				$this->b = ($args[0] & 0x0000ff);
			}
		} else {
			$this->r = $this->g = $this->b = 0;
		}
	}

	public function r() {
		return $this->r;
	}

	public function g() {
		return $this->g;
	}

	public function b() {
		return $this->b;
	}

	public function allocate(&$im) {
		return imagecolorallocate($im, $this->r, $this->g, $this->b);
	}

	public static function getColor($code, $default = 'white') {
		switch (strtolower($code)) {
			case '':
			case 'white':
				return 0xffffff;
			case 'black':
				return 0x000000;
			case 'maroon':
				return 0x800000;
			case 'red':
				return 0xff0000;
			case 'orange':
				return 0xffa500;
			case 'yellow':
				return 0xffff00;
			case 'olive':
				return 0x808000;
			case 'purple':
				return 0x800080;
			case 'fuchsia':
				return 0xff00ff;
			case 'lime':
				return 0x00ff00;
			case 'green':
				return 0x008000;
			case 'navy':
				return 0x000080;
			case 'blue':
				return 0x0000ff;
			case 'aqua':
				return 0x00ffff;
			case 'teal':
				return 0x008080;
			case 'silver':
				return 0xc0c0c0;
			case 'gray':
				return 0x808080;
			default:
				return self::getColor($default, '');
		}
	}

}

;
?>