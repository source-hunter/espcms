<?php

/*
  PHP version 5
  Copyright (c) 2002-2010 ECISP.CN
  声明：这不是一个免费的软件，请在许可范围内使用

  作者：Bili E-mail:huangqyun@163.com  QQ:6326420
  http://www.ecisp.cn	http://www.easysitepm.com
 */

class lib_form extends connector {

	function lib_form() {
		$this->softbase();
		parent::start_pagetemplate();

		$this->pagetemplate->libfile = true;
	}

	function call_form($lng, $para, $filename = 'form', $outHTML = null) {
		$para = $this->fun->array_getvalue($para);
		$lngpack = $lng ? $lng : $this->CON['is_lancode'];
		$lng = ($lng == 'big5') ? $this->CON['is_lancode'] : $lng;
		include admin_ROOT . 'datacache/' . $lng . '_pack.php';
		$fgid = intval($para['fgid']);
		if (empty($fgid)) {
			return false;
		}

		$did = intval($para['did']);
		$did = empty($did) ? 0 : $did;
		$form = $this->get_form_purview($fgid);
		$form['action'] = $this->get_link('acform', $form, $lngpack);
		$attrread = $this->get_formatt($fgid);
		$this->pagetemplate->assign('form', $form);
		$this->pagetemplate->assign('array', $attrread);

		$this->pagetemplate->assign('seccodelink', $this->get_link('seccode'));
		$this->pagetemplate->assign('lng', $lng);
		$this->pagetemplate->assign('did', $did);
		$this->pagetemplate->assign('tokenkey', $this->fun->token());
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
