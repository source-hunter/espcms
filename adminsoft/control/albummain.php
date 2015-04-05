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
	function onalbumadminlist() {
		parent::start_template();
		$MinPageid = intval($this->fun->accept('MinPageid', 'R'));
		$page_id = intval($this->fun->accept('page_id', 'R'));
		$countnum = intval($this->fun->accept('countnum', 'R'));
		$MaxPerPage = intval($this->fun->accept('MaxPerPage', 'R'));
		if (empty($MaxPerPage)) {
			$MaxPerPage = $this->CON['max_list'];
		}
		$lng = $this->sitelng;
		$lng = empty($lng) ? ($this->CON['is_alonelng'] && !empty($this->CON['home_lng'])) ? $this->CON['home_lng'] : $this->CON['default_lng'] : $lng;
		$db_where = ' WHERE lng=\'' . $lng . '\'';
		$isclass = intval($this->fun->accept('isclass', 'R'));
		if (!empty($isclass)) {
			if ($isclass == 2) $isclass = 0;
			$db_where.=' AND isclass=' . $isclass;
		}
		$istop = intval($this->fun->accept('istop', 'R'));
		if (!empty($istop)) {
			if ($istop == 2) $istop = 0;
			$db_where.=' AND istop=' . $istop;
		}
		$limitkey = $this->fun->accept('limitkey', 'R');
		$limitkey = empty($limitkey) ? 'amid' : $limitkey;
		$limitclass = $this->fun->accept('limitclass', 'R');
		$limitclass = empty($limitclass) ? 'DESC' : $limitclass;
		$db_table = db_prefix . 'album_images';
		if (!empty($countnum)) {
			$countnum = $this->db_numrows($db_table, $db_where);
			exit($countnum);
		}
		$sql = 'SELECT * FROM ' . $db_table . $db_where . ' ORDER BY ' . $limitkey . ' ' . $limitclass . ' LIMIT ' . $MinPageid . ',' . $MaxPerPage;
		$rs = $this->db->query($sql);
		while ($rsList = $this->db->fetch_assoc($rs)) {
			$array[] = $rsList;
		}
		$this->fun->setcookie($this->fun->noncefile() . 'pgid', $page_id, 600);
		$this->ectemplates->assign('array', $array);
		$this->ectemplates->display('file/file_album_list');
	}
	function onalbumadd() {
		parent::start_template();
		$tab = $this->fun->accept('tab', 'R');
		$tab = empty($tab) ? 'true' : $tab;
		$lng = $this->sitelng;
		$lng = empty($lng) ? ($this->CON['is_alonelng'] && !empty($this->CON['home_lng'])) ? $this->CON['home_lng'] : $this->CON['default_lng'] : $lng;
		$input_default = $this->CON;
		$this->ectemplates->assign('defaultinput', $input_default);
		$this->ectemplates->assign('lng', $lng);
		$this->ectemplates->assign('tab', $tab);
		$this->ectemplates->display('file/file_album_add');
	}
	function onalbumaedit() {
		parent::start_template();
		$tab = $this->fun->accept('tab', 'R');
		$tab = empty($tab) ? 'true' : $tab;
		$type = $this->fun->accept('type', 'R');
		$type = empty($type) ? 'edit' : $type;
		$amid = intval($this->fun->accept('amid', 'R'));
		if (empty($amid)) exit('false');
		$db_table = db_prefix . 'album_images';
		$db_where = 'amid=' . $amid;
		$read = $this->db->fetch_first('SELECT * FROM ' . $db_table . ' WHERE ' . $db_where);
		$this->ectemplates->assign('lng', $read['lng']);
		$this->ectemplates->assign('read', $read);
		$this->ectemplates->assign('type', $type);
		$this->ectemplates->assign('tab', $tab);
		$this->ectemplates->display('file/file_album_edit');
	}
	function oninfosave() {
		$inputclass = $this->fun->accept('inputclass', 'P');
		$lng = $this->fun->accept('lng', 'P');
		$title = $this->fun->accept('title', 'P');
		$content = $this->fun->accept('content', 'P');
		$content = empty($content) ? '' : $this->fun->Text2Html($content);
		$time = time();
		$db_table = db_prefix . 'album_images';
		if ($inputclass == 'add') {
			$db_field = 'pid,lng,title,content,pic,addtime,isclass,istop';
			$db_values = "50,'$lng','$title','$content','',$time,1,0";
			$this->db->query('INSERT INTO ' . $db_table . ' (' . $db_field . ') VALUES (' . $db_values . ')');
			$this->writelog($this->lng['albummain_add_log'], $this->lng['log_extra_ok']);
			$this->dbcache->clearcache('album_images_array', true);
			exit('true');
		} elseif ($inputclass == 'edit') {
			$amid = $this->fun->accept('amid', 'P');
			if (empty($amid)) exit('false');
			$db_where = 'amid=' . $amid;
			$db_set = "title='$title',content='$content'";
			$this->db->query('UPDATE ' . $db_table . ' SET ' . $db_set . ' WHERE ' . $db_where);
			$this->writelog($this->lng['albummain_edit_log'], $this->lng['log_extra_ok'] . ' id=' . $amid);
			$this->dbcache->clearcache('album_images_array', true);
			exit('true');
		}
	}
	function onalbumdel() {
		$db_table = db_prefix . 'album_images';
		$db_table1 = db_prefix . 'album_file';
		$selectinfoid = $this->fun->accept('selectinfoid', 'P');
		if (empty($selectinfoid)) exit('false');
		$infoarray = explode(',', $selectinfoid);
		$count = count($infoarray) - 1;
		if ($count <= 0) exit('false');
		for ($i = 0; $i < $count; $i++) {
			$db_where = "amid=$infoarray[$i]";
			$this->db->query('DELETE FROM ' . $db_table . ' WHERE ' . $db_where);
			$this->db->query('DELETE FROM ' . $db_table1 . ' WHERE ' . $db_where);
		}
		$this->writelog($this->lng['albummain_del_log'], $this->lng['log_extra_ok'] . ' id=' . $selectinfoid);
		exit('true');
	}
	function onsetting() {
		$db_table = db_prefix . 'album_images';
		$selectinfoid = $this->fun->accept('selectinfoid', 'P');
		$selectinfoid = $selectinfoid . '0';
		if (empty($selectinfoid)) exit('false');
		$value = $this->fun->accept('value', 'P');
		$dbname = $this->fun->accept('dbname', 'P');
		$db_set = "$dbname=$value";
		$db_where = "amid IN ( $selectinfoid )";
		$this->db->query('UPDATE ' . $db_table . ' SET ' . $db_set . ' WHERE ' . $db_where);
		$this->writelog($this->lng['albummain_edit_log'], $this->lng['log_extra_ok'] . ' id=' . $selectinfoid);
		exit('true');
	}
	function onrefreshphoto() {
		$amid = $this->fun->accept('amid', 'R');
		if (empty($amid)) exit('false');
		$db_where = " WHERE amid=$amid";
		$db_table = db_prefix . 'album_file';
		$sql = 'SELECT * FROM ' . $db_table . $db_where;
		$rs = $this->db->query($sql);
		while ($rsList = $this->db->fetch_assoc($rs)) {
			$filename = admin_ROOT . $rsList['filepath'] . $rsList['filename'];
			if (!is_file($filename)) {
				$db_where = "afid=" . $rsList['afid'];
				$this->db->query('DELETE FROM ' . $db_table . ' WHERE ' . $db_where);
			}
		}
		exit('true');
	}
	function onalbumfilelist() {
		parent::start_template();
		$MinPageid = intval($this->fun->accept('MinPageid', 'R'));
		$page_id = intval($this->fun->accept('page_id', 'R'));
		$countnum = intval($this->fun->accept('countnum', 'R'));
		$MaxPerPage = intval($this->fun->accept('MaxPerPage', 'R'));
		if (empty($MaxPerPage)) {
			$MaxPerPage = $this->CON['max_list'];
		}
		$amid = $this->fun->accept('amid', 'R');
		if (empty($amid)) exit('false');
		$db_where = " WHERE amid=$amid";
		$isrecomm = intval($this->fun->accept('isrecomm', 'R'));
		if (!empty($isrecomm)) {
			if ($isrecomm == 2) $isrecomm = 0;
			$db_where.=' AND isrecomm=' . $isrecomm;
		}
		$limitkey = $this->fun->accept('limitkey', 'R');
		$limitkey = empty($limitkey) ? 'afid' : $limitkey;
		$limitclass = $this->fun->accept('limitclass', 'R');
		$limitclass = empty($limitclass) ? 'DESC' : $limitclass;
		$db_table = db_prefix . 'album_file';
		if (!empty($countnum)) {
			$countnum = $this->db_numrows($db_table, $db_where);
			exit($countnum);
		}
		$sql = 'SELECT * FROM ' . $db_table . $db_where . ' ORDER BY ' . $limitkey . ' ' . $limitclass . ' LIMIT ' . $MinPageid . ',' . $MaxPerPage;
		$rs = $this->db->query($sql);
		while ($rsList = $this->db->fetch_assoc($rs)) {
			$array[] = $rsList;
		}
		$this->fun->setcookie($this->fun->noncefile() . 'pgid', $page_id, 600);
		$this->ectemplates->assign('array', $array);
		$this->ectemplates->display('file/file_album_file_list');
	}
	function onalbumfileadd() {
		parent::start_template();
		$tab = $this->fun->accept('tab', 'R');
		$tab = empty($tab) ? 'true' : $tab;
		$type = $this->fun->accept('type', 'R');
		$type = empty($type) ? 'edit' : $type;
		$amid = intval($this->fun->accept('amid', 'R'));
		if (empty($amid)) exit('false');
		$db_where = " WHERE amid=$amid";
		$db_table = db_prefix . 'album_images';
		$db_where = 'amid=' . $amid;
		$read = $this->db->fetch_first('SELECT * FROM ' . $db_table . ' WHERE ' . $db_where);
		$this->ectemplates->assign('read', $read);
		$this->ectemplates->assign('type', $type);
		$this->ectemplates->assign('tab', $tab);
		$this->ectemplates->display('file/file_album_file_add');
	}
	function onalbumfileedit() {
		parent::start_template();
		$tab = $this->fun->accept('tab', 'R');
		$tab = empty($tab) ? 'true' : $tab;
		$type = $this->fun->accept('type', 'R');
		$type = empty($type) ? 'edit' : $type;
		$afid = intval($this->fun->accept('afid', 'R'));
		if (empty($afid)) exit('false');
		$db_table = db_prefix . 'album_file';
		$db_where = 'afid=' . $afid;
		$read = $this->db->fetch_first('SELECT * FROM ' . $db_table . ' WHERE ' . $db_where);
		$lng = $this->sitelng;
		$albumarray = $this->get_album_images_array($read['amid'], 1, $lng);
		$this->ectemplates->assign("array", $albumarray['list']);
		$this->ectemplates->assign('lng', $lng);
		$this->ectemplates->assign('read', $read);
		$this->ectemplates->assign('type', $type);
		$this->ectemplates->assign('tab', $tab);
		$this->ectemplates->display('file/file_album_file_edit');
	}
	function onphotosave() {
		$inputclass = $this->fun->accept('inputclass', 'P');
		$lng = $this->fun->accept('lng', 'P');
		$amid = $this->fun->accept('amid', 'P');
		$time = time();
		$db_table = db_prefix . 'album_file';
		if ($inputclass == 'add') {
			$albumlist = $this->fun->accept('albumlist', 'P');
			$picname = $this->fun->accept('picname', 'P');
			$filedes = $this->fun->accept('filedes', 'P');
			$picfile = substr($albumlist, 0, strlen($albumlist) - 1);
			$picArray = explode('|', $picfile);
			$time = time();
			$db_field = 'amid,filetitle,filedes,filename,filepath,iswidth,addtime';
			$keynow = count($picArray) - 1;
			foreach ($picArray as $key => $value) {
				$filetitle_value = empty($picname[$key]) ? $value : $picname[$key];
				$filedes_value = empty($filedes[$key]) ? $value : $filedes[$key];
				$file_pathinfo = pathinfo($value);
				$filename = $file_pathinfo['basename'];
				$filepath = $file_pathinfo['dirname'] . '/';
				$db_where = " WHERE amid=$amid AND filename='$filename' AND filepath='$filepath'";
				$countnum = $this->db_numrows($db_table, $db_where);
				if ($countnum <= 0) {
					if ($key == $keynow) {
						$install.="($amid,'$filetitle_value','$filedes_value','$filename','$filepath',1,$time)";
					} else {
						$install.="($amid,'$filetitle_value','$filedes_value','$filename','$filepath',1,$time),";
					}
				}
			}
			if ($install) {
				$this->db->query('INSERT INTO ' . $db_table . ' (' . $db_field . ') VALUES ' . $install . '');
				$this->writelog($this->lng['albummain_add_log2'], $this->lng['log_extra_ok']);
				$this->dbcache->clearcache('album_images_file_array', true);
			}
			exit('true');
		} elseif ($inputclass == 'edit') {
			$filetitle = $this->fun->accept('filetitle', 'P');
			$filedes = $this->fun->accept('filedes', 'P');
			$filedes = empty($filedes) ? '' : $this->fun->Text2Html($filedes);
			$afid = $this->fun->accept('afid', 'P');
			if (empty($afid)) exit('false');
			$db_where = 'afid=' . $afid;
			$db_set = "amid=$amid,filetitle='$filetitle',filedes='$filedes'";
			$this->db->query('UPDATE ' . $db_table . ' SET ' . $db_set . ' WHERE ' . $db_where);
			$this->writelog($this->lng['albummain_edit_log2'], $this->lng['log_extra_ok'] . ' id=' . $afid);
			$this->dbcache->clearcache('album_images_file_array', true);
			exit('true');
		}
	}
	function onalbumfiledel() {
		$db_table = db_prefix . 'album_file';
		$selectinfoid = $this->fun->accept('selectinfoid', 'P');
		if (empty($selectinfoid)) exit('false');
		$infoarray = explode(',', $selectinfoid);
		$count = count($infoarray) - 1;
		if ($count <= 0) exit('false');
		for ($i = 0; $i < $count; $i++) {
			$db_where = "afid=$infoarray[$i]";
			$this->db->query('DELETE FROM ' . $db_table . ' WHERE ' . $db_where);
		}
		$this->writelog($this->lng['albummain_del_log2'], $this->lng['log_extra_ok'] . ' id=' . $selectinfoid);
		exit('true');
	}
	function onsort() {
		$db_table = db_prefix . 'album_file';
		$id = $this->fun->accept('infoid', 'P');
		$pid = $this->fun->accept('pid', 'P');
		$idArray = explode(',', $id);
		$pidArray = explode(',', $pid);
		foreach ($idArray as $key => $value) {
			if (!empty($value)) {
				$db_where = "afid=$value";
				$db_set = "pid=$pidArray[$key]";
				$this->db->query('UPDATE ' . $db_table . ' SET ' . $db_set . ' WHERE ' . $db_where);
			}
		}
		$this->writelog($this->lng['albummain_sort_log'], $this->lng['log_extra_ok']);
		exit('true');
	}
	function onfilesetting() {
		$db_table = db_prefix . 'album_file';
		$selectinfoid = $this->fun->accept('selectinfoid', 'P');
		$selectinfoid = $selectinfoid . '0';
		if (empty($selectinfoid)) exit('false');
		$value = $this->fun->accept('value', 'P');
		$dbname = $this->fun->accept('dbname', 'P');
		$db_set = "$dbname=$value";
		$db_where = "afid IN ( $selectinfoid )";
		$this->db->query('UPDATE ' . $db_table . ' SET ' . $db_set . ' WHERE ' . $db_where);
		$this->writelog($this->lng['albummain_edit_log2'], $this->lng['log_extra_ok'] . ' id=' . $selectinfoid);
		exit('true');
	}
}
