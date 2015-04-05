<?php

/*
  PHP version 5
  Copyright (c) 2002-2010 ECISP.CN
  声明：这不是一个免费的软件，请在许可范围内使用

  作者：Bili E-mail:huangqyun@163.com  QQ:6326420
  http://www.ecisp.cn	http://www.easysitepm.com
 */

class lib_subtype extends connector {

	function lib_subtype() {
		$this->softbase();
		parent::start_pagetemplate();

		$this->pagetemplate->libfile = true;
	}

	function call_subtype($lng, $para, $filename = 'subtype', $outHTML = null) {
		$para = $this->fun->array_getvalue($para);
		$lngpack = $lng ? $lng : $this->CON['is_lancode'];
		$lng = ($lng == 'big5') ? $this->CON['is_lancode'] : $lng;
		include admin_ROOT . 'datacache/' . $lng . '_pack.php';
		$mid = intval($para['mid']);
		if (empty($mid)) {
			return null;
		}
		$limit = intval($para['max']);
		$limit = empty($limit) ? 0 : $limit;
		$subtype = $this->get_subjectlist_array(0, $mid, $lng, 1, $limit);
		if ($subtype['num'] > 0 && is_array($subtype['list'])) {
			$subtypelist = $subtype['list'];
			foreach ($subtypelist as $key => $value) {
				$value['link'] = $this->get_link('subtype', $value, $lngpack);
				$value['title'] = $value['subjectname'];
				$subtypelist[$key] = $value;
			}
		}
		$this->pagetemplate->assign('array', $subtypelist);
		$this->pagetemplate->assign('lng', $lng);
		$this->pagetemplate->assign('lngpack', $LANPACK);
		if (!empty($outHTML)) {
			$output = $this->pagetemplate->fetch(null, null, $outHTML);
		} else {
			$output = $this->pagetemplate->fetch($lng . '/lib/' . $filename);
		}
		return $output;
	}

	function field_subtype($sid, $returnname = 'subjectname', $lng = '') {
		if (empty($sid)) return false;
		$lngpack = $lng ? $lng : $this->CON['is_lancode'];
		$lng = ($lng == 'big5') ? $this->CON['is_lancode'] : $lng;

		$returnname = empty($returnname) ? 'subjectname' : $returnname;
		$read = $this->get_subjectlist_purview($sid);
		$read['link'] = $this->get_link('subtype', $read, $lngpack);
		$read['title'] = $value['subjectname'];
		return $read[$returnname];
	}

}

?>
