<?php

/*
  PHP version 5
  Copyright (c) 2002-2014 ECISP.CN、EarcLink.COM
  警告：这不是一个免费的软件，请在许可范围内使用，请尊重知识产权，侵权必究，举报有奖
  作者：黄祥云 E-mail:6326420@qq.com  QQ:6326420 TEL:18665655030
  ESPCMS官网介绍：http://www.ecisp.cn	企业建站：http://www.earclink.cn
 */

class important extends connector {

	function important() {
		$this->softbase(true);
	}

	function onsitelist() {
		parent::start_template();
		$MinPageid = $this->fun->accept('MinPageid', 'R');
		$page_id = $this->fun->accept('page_id', 'R');
		$countnum = $this->fun->accept('countnum', 'R');
		$MaxPerPage = $this->fun->accept('MaxPerPage', 'R');
		if (empty($MaxPerPage)) {
			$MaxPerPage = $this->CON['max_list'];
		}
		$limitkey = $this->fun->accept('limitkey', 'R');
		$limitkey = empty($limitkey) ? 'slid' : $limitkey;
		$limitclass = $this->fun->accept('limitclass', 'R');
		$limitclass = empty($limitclass) ? 'DESC' : $limitclass;
		$db_where = " ";
		$db_table = db_prefix . 'site';
		if (!empty($countnum)) {
			$countnum = $this->db_numrows($db_table, $db_where);
			exit($countnum);
		}
		$sql = 'SELECT slid,pid,sitename,sitehttp,sitedir,sitecode,siteip,adminusername,addtime FROM ' . $db_table . $db_where . ' ORDER BY ' . $limitkey . ' ' . $limitclass . ' LIMIT ' . $MinPageid . ',' . $MaxPerPage;
		$rs = $this->db->query($sql);
		while ($rsList = $this->db->fetch_assoc($rs)) {
			$codelist = md5($rsList['sitecode'] . '_' . $rsList['siteip'] . '_' . $rsList['sitedir']);
			$rsList['link'] = $rsList['sitehttp'] . '/' . $rsList['sitedir'] . "/index.php?archive=adminuser&action=sitecode&siteid=$rsList[slid]&code=$codelist&adminid=$rsList[adminusername]";
			$array[] = $rsList;
		}
		$this->fun->setcookie($this->fun->noncefile() . 'pgid', $page_id, 600);
		$this->ectemplates->assign('array', $array);
		$this->ectemplates->assign('sql', $sql);
		$this->ectemplates->display('admin/admin_site_list');
	}

	function onsiteadd() {
		parent::start_template();
		$tab = $this->fun->accept('tab', 'G');
		$tab = empty($tab) ? 'true' : $tab;
		$this->ectemplates->assign('tab', $tab);
		$this->ectemplates->display('admin/admin_site_add');
	}

	function onedit() {
		parent::start_template();
		$db_table = db_prefix . 'site';
		$slid = $this->fun->accept('slid', 'G');
		$db_where = 'slid=' . $slid;
		$read = $this->db->fetch_first('SELECT * FROM ' . $db_table . ' WHERE ' . $db_where);
		$this->ectemplates->assign('tab', 'true');
		$this->ectemplates->assign('read', $read);
		$this->ectemplates->display('admin/admin_site_edit');
	}

	function onsave() {
		parent::start_template();
		$inputclass = $this->fun->accept('inputclass', 'P');
		$sitename = $this->fun->accept('sitename', 'P');
		$sitehttp = $this->fun->accept('sitehttp', 'P');
		$sitedir = $this->fun->accept('sitedir', 'P');
		$sitecode = $this->fun->accept('sitecode', 'P');
		$siteip = $this->fun->accept('siteip', 'P');
		$adminusername = $this->fun->accept('adminusername', 'P');
		$time = time();
		$db_table = db_prefix . 'site';
		if ($inputclass == 'add') {
			$db_field = 'pid,sitename,sitehttp,sitedir,sitecode,siteip,adminusername,addtime';
			$db_values = "50,'$sitename','$sitehttp','$sitedir','$sitecode','$siteip','$adminusername',$time";
			$this->db->query('INSERT INTO ' . $db_table . ' (' . $db_field . ') VALUES (' . $db_values . ')');
			$this->writelog($this->lng['sitemain_log_add'], $this->lng['log_extra_ok'] . ' 群点名称=' . $sitename);
			exit('true');
		} else {
			$slid = $this->fun->accept('slid', 'P');
			$db_where = 'slid=' . $slid;
			$db_set = "sitename='$sitename',sitehttp='$sitehttp',sitedir='$sitedir',sitecode='$sitecode',siteip='$siteip',adminusername='$adminusername'";
			$this->db->query('UPDATE ' . $db_table . ' SET ' . $db_set . ' WHERE ' . $db_where);
			$this->writelog($this->lng['sitemain_log_edit'], $this->lng['log_extra_ok'] . ' 群点名称=' . $sitename . ' id=' . $slid);
			exit('true');
		}
	}

	function ondel() {
		$db_table = db_prefix . 'site';
		$selectinfoid = $this->fun->accept('selectinfoid', 'P');
		if (empty($selectinfoid)) exit('false');
		$infoarray = explode(',', $selectinfoid);
		$count = count($infoarray) - 1;
		if ($count <= 0) exit('false');
		for ($i = 0; $i < $count; $i++) {
			$db_where = "slid=$infoarray[$i]";
			$this->db->query('DELETE FROM ' . $db_table . ' WHERE ' . $db_where);
		}
		$this->writelog($this->lng['sitemain_log_del'], $this->lng['log_extra_ok'] . ' id=' . $selectinfoid);
		exit('true');
	}

	function onsort() {
		$db_table = db_prefix . 'site';
		$id = $this->fun->accept('infoid', 'P');
		$pid = $this->fun->accept('pid', 'P');
		$idArray = explode(',', $id);
		$pidArray = explode(',', $pid);
		foreach ($idArray as $key => $value) {
			if (!empty($value)) {
				$db_where = "slid=$value";
				$pid = intval($pidArray[$key]);
				$db_set = "pid=$pid";
				$this->db->query('UPDATE ' . $db_table . ' SET ' . $db_set . ' WHERE ' . $db_where);
			}
		}
		$this->writelog($this->lng['sitemain_log_sort'], $this->lng['log_extra_ok']);
		exit('true');
	}

}
