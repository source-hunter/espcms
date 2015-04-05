<?php

/*
  PHP version 5
  Copyright (c) 2002-2010 ECISP.CN
  声明：这不是一个免费的软件，请在许可范围内使用

  作者：Bili E-mail:huangqyun@163.com  QQ:6326420
  http://www.ecisp.cn	http://www.easysitepm.com
 */

class lib_path extends connector {
	
	function lib_path() {
		$this->softbase();
		parent::start_pagetemplate();

		$this->pagetemplate->libfile = true;
	}

	function call_path($lng, $para, $filename = 'path', $outHTML = null) {
		$para = $this->fun->array_getvalue($para);
		$lngpack = $lng ? $lng : $this->CON['is_lancode'];
		$lng = ($lng == 'big5') ? $this->CON['is_lancode'] : $lng;
		include admin_ROOT . 'datacache/' . $lng . '_pack.php';

		$pathtype = empty($para['type']) ? 'type' : $para['type'];
		$id = intval($para['id']);
		if (empty($id)) {
			return false;
		}
		switch ($pathtype) {
			case 'type':
				$typeview = $this->get_type($id);
				$path = $this->get_path($typeview, $lngpack);
				break;
			case 'forum':
				$typeview = $this->get_bbstype_view($id);
				$typeview['title'] = $typeview['typename'];
				$typeview['link'] = $this->get_link('forumlist', $typeview, $lngpack);
				$path[] = $typeview;
				break;
			case 'sub':
				$subview = $this->get_subjectlist_purview($id);
				$subview['title'] = $subview['subjectname'];
				$subview['link'] = $this->get_link('subtype', $subview, $lngpack);
				$path[] = $subview;
				break;
		}
		$homelink = $this->get_link('home', '', $lngpack);
		$this->pagetemplate->assign('homelink', $homelink);
		$this->pagetemplate->assign('array', $path);
		$this->pagetemplate->assign('lng', $lng);
		$this->pagetemplate->assign('lngpack', $LANPACK);
		if (!empty($outHTML)) {
			$output = $this->pagetemplate->fetch(null, null, $outHTML);
		} else {
			$output = $this->pagetemplate->fetch($lng . '/lib/' . $filename);
		}
		return $output;
	}

}

?>
