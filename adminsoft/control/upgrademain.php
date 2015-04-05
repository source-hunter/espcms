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
	function oncheckvol() {
		$data = array(
		    'ac' => 'sitejsondb',
		    'at' => 'softvol',
		    'dbosn' => $this->CON['dbosn'],
		    'isdbo' => $this->CON['isdbo'],
		    'vol' => $this->CON['softvol'],
		    'siteurl' => urlencode(admin_ClassURL),
		    'sitename' => urlencode($this->CON['sitename']),
		    'iplong' => $this->fun->ip($_SERVER['REMOTE_ADDR']),
		    'email' => urlencode($this->CON['admine_mail']),
		    'dbcode' => db_pscode,
		    'db_keycode' => db_keycode
		);
		$getval = convert_uudecode($this->CON['getnetval']);
		$posthttp = $getval . 'index.php';
		$postout = trim($this->fun->postdb($posthttp, $data));
		$postout_array = json_decode($postout, true);
		if (is_array($postout_array) || count($postout_array) > 0) {
			return $postout_array;
		} else {
			return false;
		}
	}
	function onupgrade() {
		parent::start_template();
		$softvolstr = $this->CON['softvolstr'];
		$softvol = $this->CON['softvol'];
		$volarray = $this->oncheckvol();
		$digheight = $this->fun->accept('digheight', 'R');
		$this->ectemplates->assign('digheight', $digheight);
		$this->ectemplates->assign('softvol', $softvol);
		$this->ectemplates->assign('array', $volarray);
		$this->ectemplates->assign('softvolstr', $softvolstr);
		$this->ectemplates->display('admin/admin_softvol');
	}
	function onupsafe() {
		if (!function_exists('zip_open')) {
			$resturnjson = '{"num":"0","vol":"' . $this->lng['management_update_zip_err'] . '"}';
			exit($resturnjson);
		}
		$commandfile = admin_ROOT . 'datacache/command.php';
		if (!@is_writable($commandfile) || !is_file($commandfile)) {
			$resturnjson = '{"num":"0","vol":"' . $this->lng['management_update_zip_str10'] . '"}';
			exit($resturnjson);
		}
		$iscount = intval($this->fun->accept('iscount', 'R'));
		$iscount = empty($iscount) ? 0 : $iscount;
		$ordertype = intval($this->fun->accept('ordertype', 'R'));
		$ordertype = empty($ordertype) ? 0 : $ordertype;
		$data = array(
		    'ac' => 'sitejsondb',
		    'at' => 'softupdatelist',
		    'ordertype' => $ordertype,
		    'dbosn' => $this->CON['dbosn'],
		    'isdbo' => $this->CON['isdbo'],
		    'vol' => $this->CON['softvol'],
		    'siteurl' => urlencode(admin_ClassURL),
		    'sitename' => urlencode($this->CON['sitename']),
		    'iplong' => $this->fun->ip($_SERVER['REMOTE_ADDR']),
		    'email' => urlencode($this->CON['admine_mail']),
		    'dbcode' => db_pscode,
		    'db_keycode' => db_keycode
		);
		$getval = convert_uudecode($this->CON['getnetval']);
		$posthttp = $getval . 'index.php';
		$postout = trim($this->fun->postdb($posthttp, $data));
		$inforss = json_decode($postout, true);
		if ($iscount) {
			if (!is_array($inforss) || count($inforss) <= 0) {
				$resturnjson = '{"num":"0","vol":"' . $this->lng['management_update_zip_newsstr'] . '"}';
			} else {
				$resturnjson = '{"num":"' . count($inforss) . '","vol":"' . $inforss[0]['volstr'] . '"}';
			}
			exit($resturnjson);
		}
		if (is_array($inforss) && $inforss[0]['uservol'] && $inforss[0]['downloadurl']) {
			if ($inforss[0]['isadmindir'] == 1) {
				if (adminfile != 'adminsoft') {
					$resturnjson = '{"setuptype":"2","vol":"' . $inforss[0]['volstr'] . '","magessage":"' . $this->lng['management_update_zip_str11'] . adminfile . '"}';
					exit($resturnjson);
				}
			}
			$retrunvol = $this->update($inforss[0]);
			if ($retrunvol) {
				$db_table = db_prefix . 'config';
				$db_set = "value='" . $inforss[0]['vol'] . "'";
				$db_where = " WHERE valname='softvol'";
				$this->db->query('UPDATE ' . $db_table . ' SET ' . $db_set . $db_where);
				$db_set = "value='" . $inforss[0]['volstr'] . "'";
				$db_where = " WHERE valname='softvolstr'";
				$this->db->query('UPDATE ' . $db_table . ' SET ' . $db_set . $db_where);
				$this->systemfile(true);
				$resturnjson = '{"setuptype":"1","vol":"' . $inforss[0]['volstr'] . '","isreset":"' . $inforss[0]['isreset'] . '"}';
				exit($resturnjson);
			} else {
				$resturnjson = '{"setuptype":"0","vol":"' . $inforss[0]['volstr'] . '","isreset":"' . $inforss[0]['isreset'] . '"}';
				exit($resturnjson);
			}
		} else {
			$resturnjson = '{"num":"0","vol":"' . $this->lng['management_update_zip_newsstr'] . '"}';
			exit($resturnjson);
		}
	}
	function update($updatearray = array()) {
		if (!$updatearray['vol']) {
			return false;
		}
		if ($updatearray['uservol'] != $this->CON['softvol']) {
			return false;
		}
		if ($updatearray['issqlbak']) {
			if (!$this->sqlbak($updatearray['vol'])) {
				return false;
			}
		}
		$tempDir = admin_ROOT . 'updatetemp';
		if ((!is_dir($tempDir)) && (!mkdir($tempDir, 0777, true))) {
			return false;
		}
		if (!is_writable($tempDir)) {
			return false;
		}
		$updateFile = $tempDir . '/' . $updatearray['vol'] . '.zip';
		$updateUrl = $updatearray['downloadurl'];
		if (!is_file($updateFile)) {
			if (!$this->downloadUpdate($updateUrl, $updateFile)) {
				return false;
			}
		}
		$installresturn = $this->install($updateFile, $updatearray);
		if ($updatearray['issqlbak'] && $installresturn) {
			$sqlfile = admin_ROOT . 'updatesql/' . $updatearray['vol'] . '.sql';
			if (is_file($sqlfile)) {
				$updatesql = file_get_contents($sqlfile);
				if ($updatesql) {
					$updatesql = str_replace("\r\n", "\n", $updatesql);
					$ret = array();
					$num = 0;
					foreach (explode(";\n", trim($updatesql)) as $query) {
						$ret[$num] = '';
						$queries = explode("\n", trim($query));
						foreach ($queries as $query) {
							$ret[$num] .= ( isset($query[0]) && $query[0] == '#') || (isset($query[1]) && isset($query[1]) && $query[0] . $query[1] == '--') ? '' : $query;
						}
						$num++;
					}
					unset($updatesql);
					foreach ($ret as $query) {
						$query = trim($query);
						if ($query) {
							$query = str_replace('esp_', db_prefix, $query);
							$this->db->query($query);
						}
					}
					$this->fun->delfile($sqlfile);
					return true;
				} else {
					return false;
				}
			}
		}
		return $installresturn;
	}
	function downloadUpdate($updateUrl, $updateFile) {
		$update = @file_get_contents($updateUrl);
		if (!$update) {
			return false;
		}
		$handle = fopen($updateFile, 'w');
		if (!fwrite($handle, $update)) {
			return false;
		}
		fclose($handle);
		return true;
	}
	function install($updateFile, $updatearray = array()) {
		$zip = zip_open($updateFile);
		if (is_resource($zip)) {
			while (($zip_entry = zip_read($zip)) !== false) {
				$filename = zip_entry_name($zip_entry);
				$foldername = admin_ROOT . $filename;
				if (strpos(zip_entry_name($zip_entry), '/') !== false) {
					$last = strrpos(zip_entry_name($zip_entry), '/');
					$dir = admin_ROOT . substr(zip_entry_name($zip_entry), 0, $last);
					$file = substr(zip_entry_name($zip_entry), strrpos(zip_entry_name($zip_entry), '/') + 1);
					if (!is_dir($dir)) {
						@mkdir($dir, 0755, true);
					}
					if (strlen(trim($file)) > 0) {
						$return = @file_put_contents($foldername, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
						if (!$return) {
							return false;
						}
					}
				} else {
					$return = @file_put_contents($foldername, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
					if (!$return) {
						return false;
					}
				}
			}
			zip_close($zip);
			$this->fun->delfile($updateFile);
			return true;
		} else {
			return false;
		}
	}
	function sqlbak($sqlfilename, $volRun = 0, $savasize = 0) {
		$path = admin_ROOT . $this->CON['file_sqlbakdir'];
		if (!$this->fun->filemode($path, 'w')) {
			return false;
		}
		@set_time_limit(300);
		$sql_runlogfile = $path . 'run.log';
		$upload_max_filesize = intval(@ini_get('upload_max_filesize'));
		$savasize = empty($savasize) ? $upload_max_filesize * 1000 : $savasize;
		if ($upload_max_filesize > 0 && $savasize > ($upload_max_filesize * 1024)) $savasize = $upload_max_filesize * 1024;
		if ($savasize > 0) $this->outsize = $savasize * 1024;
		$tables = array();
		$vol = empty($volRun) ? 1 : intval($volRun);
		if (empty($volRun)) {
			$str = '';
			$rs = $this->db->query("SHOW TABLES LIKE '" . $this->db->like_quote(db_prefix) . "%'");
			while ($dbList = $this->db->fetch_array($rs)) {
				$tables[$dbList[0]] = -1;
			}
			foreach ($tables as $key => $val) {
				$str.= $key . ':' . $val . ";\r\n";
			}
			if (!$this->fun->filewrite($sql_runlogfile, $str)) {
				return false;
			}
		}
		$tables = $this->outsqllink($sql_runlogfile, $vol);
		if ($tables === false) {
			return false;
		}
		if (empty($tables)) {
			if ($vol > 1) {
				$this->fun->filewrite($path . '/' . $sqlfilename . '_' . $vol . '.sql', $this->makesqlText);
			} else {
				$this->fun->filewrite($path . '/' . $sqlfilename . '_' . $vol . '.sql', $this->makesqlText);
			}
			@unlink($sql_runlogfile);
			return true;
		} else {
			$this->fun->filewrite($path . '/' . $sqlfilename . '_' . $vol . '.sql', $this->makesqlText);
			$vol = $vol + 1;
			$this->sqlbak($sqlfilename, $vol, $savasize);
		}
		return true;
	}
	function outsqllink($sql_runlogfile, $vol) {
		$tables = $this->fun->fileArrayet($sql_runlogfile);
		if ($tables === false) {
			return false;
		}
		if (empty($tables)) {
			return $tables;
		}
		$this->makesqlText = $this->make_sqlhead($vol);
		foreach ($tables as $table => $pos) {
			if ($pos == -1) {
				$table_df = $this->get_createsql($table, true);
				if (strlen($this->makesqlText) + strlen($table_df) > $this->outsize - 32) {
					if ($this->sql_num == 0) {
						$this->makesqlText .= $table_df;
						$this->sql_num +=2;
						$tables[$table] = 0;
					}
					break;
				} else {
					$this->makesqlText .= $table_df;
					$this->sql_num +=2;
					$pos = 0;
				}
			}
			$post_pos = $this->get_insertsql($table, $pos);
			if ($post_pos == -1) {
				unset($tables[$table]);
			} else {
				$tables[$table] = $post_pos;
				break;
			}
		}
		$this->makesqlText.='-- END EasySitePM SQL Dump Program ';
		if (is_array($tables)) {
			$str = '';
			foreach ($tables as $key => $val) {
				$str .= $key . ':' . $val . ";\r\n";
			}
			$this->fun->filewrite($sql_runlogfile, $str);
		}
		return $tables;
	}
	function make_sqlhead($vol) {
		$head = "-- ESPCMS SQL Dump\r\n" .
			"-- version 5.0.0.0\r\n" .
			"-- \r\n" .
			"-- HOST:" . admin_ClassURL . "\r\n" .
			"-- DATE:" . $this->fun->formatdate(time(), 3) . "\r\n" .
			"-- SQLVER:" . $this->db->version() . "\r\n" .
			"-- PHPVER:" . PHP_VERSION . "\r\n" .
			"-- Vol:" . $vol . "\r\n";
		return $head;
	}
	function get_createsql($table, $add_drop = false) {
		if ($add_drop) {
			$table_df = "DROP TABLE IF EXISTS `$table`;\r\n";
		} else {
			$table_df = '';
		}
		$tmp_arr = $this->db->getRow("SHOW CREATE TABLE `$table`");
		$tmp_sql = $tmp_arr['Create Table'];
		$tmp_sql = substr($tmp_sql, 0, strrpos($tmp_sql, ")") + 1); //去除行尾定义。
		if ($this->db->version() >= '4.1') {
			$table_df.=$tmp_sql . " ENGINE=MyISAM DEFAULT CHARSET=" . str_replace('-', '', db_charset) . ";\r\n";
		} else {
			$table_df.=$tmp_sql . " TYPE=MyISAM;\r\n";
		}
		return $table_df;
	}
	function get_insertsql($table, $pos) {
		$post_pos = $pos;
		$this->offset = 300;
		$total = $this->db->getOne("SELECT COUNT(*) FROM $table");
		if ($total == 0 || $pos >= $total) {
			return -1;
		}
		$cycle_time = ceil(($total - $pos) / $this->offset);
		for ($i = 0; $i < $cycle_time; $i++) {
			$array = array();
			$data = $this->db->getAll("SELECT * FROM $table LIMIT " . ($this->offset * $i + $pos) . ', ' . $this->offset);
			$data_count = count($data);
			$fields = array_keys($data[0]);
			$start_sql = "INSERT INTO `$table` ( `" . implode("`, `", $fields) . "` ) VALUES ";
			for ($j = 0; $j < $data_count; $j++) {
				$record = array_map("dump_escape_string", $data[$j]);
				$record = array_map("dump_null_string", $record);
				$tmp_dump_sql = $start_sql . " ('" . implode("', '", $record) . "');\r\n";
				$tmp_str_pos = strpos($tmp_dump_sql, 'NULL');
				$tmp_dump_sql = empty($tmp_str_pos) ? $tmp_dump_sql : substr($tmp_dump_sql, 0, $tmp_str_pos - 1) . 'NULL' . substr($tmp_dump_sql, $tmp_str_pos + 5);
				if (strlen($this->makesqlText) + strlen($tmp_dump_sql) > $this->outsize - 32) {
					if ($this->sql_num == 0) {
						$this->makesqlText .= $tmp_dump_sql;
						$this->sql_num++;
						$post_pos++;
						if ($post_pos == $total) {
							return -1;
						}
					}
					return $post_pos;
				} else {
					$this->makesqlText.= $tmp_dump_sql;
					$this->sql_num++;
					$post_pos++;
				}
			}
		}
		return -1;
	}
	function ondirchecklist() {
		include_once admin_ROOT . adminfile . '/include/inc_filelist.php';
		$percheck_dir = $percheck_dir . '|' . adminfile;
		$percheck_dir_array = explode('|', $percheck_dir);
		$dirArray = array();
		foreach ($percheck_dir_array as $key => $value) {
			$listDIR = admin_ROOT . $value . '/';
			$dirArray['listdir'] = $this->fun->list_dir($listDIR, array('php'));
		}
		$perfile_dir_array = explode('|', $perfile_dir);
		foreach ($perfile_dir_array as $value_a) {
			array_push($dirArray['listdir'], admin_ROOT . $value_a);
		}
		if (is_array($dirArray['listdir']) && count($dirArray['listdir']) > 0) {
			$filenum = count($dirArray['listdir']);
			$fileid = 1;
			$outstr = "<input type=\"hidden\" id=\"filenum\" name=\"filenum\" value=\"$filenum\"><ul>";
			foreach ($dirArray['listdir'] as $key => $value) {
				$filenamepath = str_replace(admin_ROOT, '', $value);
				$outstr.="<li>$filenamepath <span id=\"f$fileid\"></span><input type=\"hidden\" id=\"filenamepath$fileid\" name=\"filenamepath$fileid\" value=\"$filenamepath\"></li>";
				$fileid++;
			}
			$outstr .= "</ul>";
			exit($outstr);
		} else {
			exit('<span style="padding:20px 0px;font-size:14px;font-weight: bold;">' . $this->lng['mangerlist_filecheck_htmlstr1'] . '</span>');
		}
	}
	function oncheckfile() {
		$filename = $this->fun->accept('filename', 'R');
		if (empty($filename)) {
			exit('false');
		}
		$filename = admin_ROOT . $filename;
		if (!$this->fun->filemode($filename)) {
			exit('<span style="color:#FB0E0E;font-weight: bold;color: #DE2417;">' . $this->lng['mangerlist_filecheck_htmlstr2'] . '</span>');
		} else {
			exit('<span style="color:#37BE45;">' . $this->lng['mangerlist_filecheck_htmlstr3'] . '</span>');
		}
	}
}
function dump_escape_string($str) {
	return mysql_real_escape_string($str);
}
function dump_null_string($str) {
	if (!isset($str) || is_null($str)) {
		$str = 'NULL';
	}
	return $str;
}
