<?php

/*
  PHP version 5
  Copyright (c) 2002-2014 ECISP.CN、EarcLink.COM
  警告：这不是一个免费的软件，请在许可范围内使用，请尊重知识产权，侵权必究，举报有奖
  作者：黄祥云 E-mail:6326420@qq.com  QQ:6326420 TEL:18665655030
  ESPCMS官网介绍：http://www.ecisp.cn	企业建站：http://www.earclink.cn
 */

class lib_photo extends connector {

	function lib_photo() {
		$this->softbase();
		parent::start_pagetemplate();
		$this->pagetemplate->libfile = true;
	}
	function call_photo($lng, $para, $filename = 'photo', $outHTML = null) {
		$para = $this->fun->array_getvalue($para);
		$lngpack = $lng ? $lng : $this->CON['is_lancode'];
		$lng = ($lng == 'big5') ? $this->CON['is_lancode'] : $lng;
		include admin_ROOT . 'datacache/' . $lng . '_pack.php';
		$amid = intval($para['amid']);
		if (empty($amid)) {
			return false;
		}
		$typeread = $this->get_images_type_view($amid);
		$limit = empty($para['max']) ? 10 : intval($para['max']);
		$db_table = db_prefix . 'album_file';
		$db_where = ' WHERE isrecomm=1 AND amid=' . $amid;
		$sql = "SELECT * FROM $db_table $db_where ORDER BY pid,afid DESC LIMIT 0,$limit";
		$rs = $this->db->query($sql);
		while ($rsList = $this->db->fetch_assoc($rs)) {
			$rsList['pic'] = $rsList['filepath'] . $rsList['filename'];
			$array[] = $rsList;
		}
		$this->pagetemplate->assign('max', $limit);
		$this->pagetemplate->assign('typeread', $typeread);
		$this->pagetemplate->assign('array', $array);
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
