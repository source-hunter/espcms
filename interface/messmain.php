<?php
/*
  PHP version 5
  Copyright (c) 2002-2014 ECISP.CN、EarcLink.COM
  警告：这不是一个免费的软件，请在许可范围内使用，请尊重知识产权，侵权必究，举报有奖
  作者：黄祥云 E-mail:6326420@qq.com  QQ:6326420 TEL:18665655030
  ESPCMS官网介绍：http://www.ecisp.cn	企业建站：http://www.earclink.cn
 */
class mainpage extends connector {
	function mainpage() {
		$this->softbase(false);
	}
	function in_ajaxlist() {
		parent::start_pagetemplate();
		$lng = (admin_LNG == 'big5') ? $this->CON['is_lancode'] : admin_LNG;
		$did = intval($this->fun->accept('did', 'G'));
		if (!$did) {
			return false;
		}
		$article_read = $this->get_documentview($did);
		if (!$article_read['ismess']) {
			return false;
		}
		$limit = $this->fun->accept('limit', 'R');
		$limit = isset($limit) ? intval($limit) : 5;
		$page = $this->fun->accept('nowpage', 'R');
		$page = isset($page) ? intval($page) : 1;
		$limitstard = $this->fun->accept('limitstard', 'R');
		$limitstard = isset($limitstard) ? intval($limitstard) : 0;
		$db_table = db_prefix . 'document_message';
		$db_where = " WHERE isclass=1 AND did=$did";
		$sql = "SELECT * FROM $db_table $db_where ORDER BY dmid DESC LIMIT $limitstard,$limit";
		$rs = $this->db->query($sql);
		while ($rsList = $this->db->fetch_assoc($rs)) {
			$array[] = $rsList;
		}
		$this->pagetemplate->assign('array', $array);
		$this->pagetemplate->assign('did', $did);
		$this->pagetemplate->assign('lng', $lng);
		$this->pagetemplate->assign('lngpack', $LANPACK);
		$templatesDIR = $this->get_templatesdir('article');
		$templatefilename = $lng . '/' . $templatesDIR . '/message_ajax_list';
		unset($array, $LANPACK, $this->lng);
		$this->pagetemplate->display($templatefilename, 'message_list', false, '', admin_LNG);
	}
	function in_save() {
		$linkURL = $_SERVER['HTTP_REFERER'];
		if (!$this->fun->is_token() && !$this->CON['is_html']) {
			$this->callmessage($this->lng['repeatinput'], $linkURL, $this->lng['gobackbotton']);
		}
		parent::start_pagetemplate();
		$lng = (admin_LNG == 'big5') ? $this->CON['is_lancode'] : admin_LNG;
		if ($this->CON['bbs_isseccode'] && !admin_WAP) {
			$seccode = $this->fun->accept('seccode', 'P');
			include_once admin_ROOT . 'public/class_seccode.php';
			list($new_seccode, $expiration) = explode("\t", $this->fun->eccode($_COOKIE['ecisp_home_seccode'], 'DECODE'));
			$code = new seccode();
			$code->seccodeconvert($new_seccode);
			if ($new_seccode != strtoupper($seccode)) {
				$this->callmessage($this->lng['seescodeerr'], $linkURL, $this->lng['gobackbotton']);
			}
		}
		$did = intval($this->fun->accept('did', 'P'));
		$did = empty($did) ? 0 : $did;
		if (empty($did)) {
			$this->callmessage($this->lng['db_err'], $linkURL, $this->lng['gobackbotton']);
		}
		$readinfo = $this->get_documentview($did);
		if (!$readinfo) {
			$this->callmessage($this->lng['db_err'], $_SERVER['HTTP_REFERER'], $this->lng['gobackurlbotton']);
		}
		if (!$readinfo['isclass'] || !$readinfo['ismess']) {
			$this->callmessage($this->lng['db_err'], $_SERVER['HTTP_REFERER'], $this->lng['gobackurlbotton']);
		} elseif ($readinfo['purview'] > 0) {
			parent::member_purview($readinfo['purview'], null, true);
		}
		$db_table = db_prefix . "document_message";
		$userid = intval($this->fun->accept('userid', 'P'));
		$userid = empty($userid) ? 0 : $userid;
		$name = $this->fun->accept('name', 'P', true, true);
		$name = $this->fun->substr($name, 20);
		$content = $this->fun->accept('content', 'P');
		$content = empty($content) ? '' : $this->fun->Text2Html($content, false);
		if (empty($name) || empty($content)) {
			$this->callmessage($this->lng['forum_input_err'], $linkURL, $this->lng['gobackbotton']);
		}
		$email = $this->fun->accept('email', 'P');
		if (!preg_match("/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/i", $email)) {
			$this->callmessage($this->lng['email_err'], $linkURL, $this->lng['gobackbotton']);
		}
		$isclass = $this->CON['bbs_isclass'] ? 0 : 1;
		if (!$this->CON['is_anonymous']) {
			parent::member_purview(1);
		}
		$ipadd = $this->fun->ip($_SERVER['REMOTE_ADDR']);
		$addtime = time();
		if (!empty($this->ec_member_username_id)) {
			$rsMember = $this->get_member(null, $this->ec_member_username_id);
			$lockusername = explode(',', $this->CON['bbs_username']);
			if (in_array($this->ec_member_username, $lockusername)) {
				$this->callmessage($this->lng['forum_mem_username'], $_SERVER['HTTP_REFERER'], $this->lng['gobackbotton']);
			}
		}
		if (!empty($this->CON['bbs_filter'])) {
			if ($this->fun->screening_key($content, $this->CON['bbs_filter'])) {
				$this->callmessage($this->lng['forum_input_filter'], $linkURL, $this->lng['gobackbotton']);
			}
		}
		$usersessionid = md5($ipadd + $did . 'input');
		$inputseesion = $this->fun->accept($usersessionid, 'C');
		if (empty($inputseesion) && $this->CON['bbs_inputtime']) {
			$this->fun->setcookie($usersessionid, $addtime, $this->CON['bbs_inputtime']);
		} elseif ($this->CON['bbs_inputtime']) {
			$this->callmessage($this->lng['repeatinput'], $linkURL, $this->lng['gobackurlbotton']);
		}
		$db_field = 'did,userid,adminid,ipadd,lng,name,email,content,recontent,isreply,isclass,addtime,retime,support,oppose';
		$db_values = "$did,$userid,0,'$ipadd','$lng','$name','$email','$content','',0,$isclass,$addtime,0,0,0";
		$this->db->query('INSERT INTO ' . $db_table . ' (' . $db_field . ') VALUES (' . $db_values . ')');
		$insert_id = $this->db->insert_id();
		$this->fun->setcookie('ecisp_home_seccode', null);
		if ($this->CON['bbs_ismail']) {
			$this->bbsmailsend('bbsrequest', $insert_id);
		}
		$this->callmessage($this->lng['forum_input_ok'], $linkURL, $this->lng['gobackurlbotton']);
	}
}
