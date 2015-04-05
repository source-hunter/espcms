<?php

/*
  PHP version 5
  Copyright (c) 2002-2010 ECISP.CN
  声明：这不是一个免费的软件，请在许可范围内使用

  作者：Bili E-mail:huangqyun@163.com  QQ:6326420
  http://www.ecisp.cn	http://www.easysitepm.com
 */

class lib_lng extends connector {

	function lib_lng() {
		$this->softbase();
		parent::start_pagetemplate();

		$this->pagetemplate->libfile = true;
	}

	function call_lng($lng, $para, $filename = 'lng', $outHTML = null) {
		if ($this->CON['is_alonelng']) return false;
		$para = $this->fun->array_getvalue($para);

		$lngpack = $lng ? $lng : $this->CON['is_lancode'];

		$lng = ($lng == 'big5') ? $this->CON['is_lancode'] : $lng;
		include admin_ROOT . 'datacache/' . $lng . '_pack.php';
		$db_table = db_prefix . 'lng';
		if (admin_WAP) {
			$rsList = $this->get_lng_array(null, 1, 1);
		} else {
			$rsList = $this->get_lng_array(null, 1);
		}
		if (count($rsList['list']) > 0) {
			foreach ($rsList['list'] as $key => $value) {
				$rsList['list'][$key]['link'] = $this->get_link('lng', $value, $value['lng']);
			}
		}
		$this->pagetemplate->assign('lng', $lng);
		$this->pagetemplate->assign('lngpack', $LANPACK);
		$this->pagetemplate->assign('array', $rsList['list']);
		if (!empty($outHTML)) {
			$output = $this->pagetemplate->fetch(null, null, $outHTML);
		} else {
			$output = $this->pagetemplate->fetch($lng . '/lib/' . $filename);
		}
		return $output;
	}

}

?>
