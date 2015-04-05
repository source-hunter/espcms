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
	function onmodenlist() {
		parent::start_template();
		$db_table = db_prefix . 'apply';
		$sql = 'SELECT * FROM ' . $db_table . ' WHERE isetup=1 ORDER BY appid ASC';
		$rs = $this->db->query($sql);
		while ($rsList = $this->db->fetch_assoc($rs)) {
			$rsList['updatevol'] = $this->appupdate($rsList['applycode']);
			$array[] = $rsList;
		}
		$this->ectemplates->assign('array', $array);
		$sql = 'SELECT * FROM ' . $db_table . ' WHERE isetup=0 ORDER BY appid DESC';
		$rs = $this->db->query($sql);
		while ($rsList = $this->db->fetch_assoc($rs)) {
			$noinstallarray[] = $rsList;
		}
		$this->ectemplates->assign('noinstallarray', $noinstallarray);
		$loadurl = convert_uudecode($this->CON['getnetval']);
		$this->ectemplates->assign('loadurl', $loadurl);
		$this->ectemplates->assign('sql', $sql);
		$this->ectemplates->display('admin/admin_app_list');
	}
	function appupdate($appcode) {
		if (empty($appcode)) {
			return false;
		}
		$data = array(
		    'ac' => 'sitejsondb',
		    'at' => 'appupgrade',
		    'dbosn' => $this->CON['dbosn'],
		    'isdbo' => $this->CON['isdbo'],
		    'vol' => $this->CON['softvol'],
		    'siteurl' => urlencode(admin_ClassURL),
		    'sitename' => urlencode($this->CON['sitename']),
		    'iplong' => $this->fun->ip($_SERVER['REMOTE_ADDR']),
		    'email' => urlencode($this->CON['admine_mail']),
		    'dbcode' => db_pscode,
		    'db_keycode' => db_keycode,
		    'applycode' => $appcode,
		);
		$getval = convert_uudecode($this->CON['getnetval']);
		$posthttp = $getval . 'index.php';
		$postout = trim($this->fun->postdb($posthttp, $data));
		$inforss = json_decode($postout, true);
		if (empty($inforss['volsn'])) {
			return false;
		} else {
			return $inforss['volsn'];
		}
	}
	function onsynchronization() {
		$data = array(
		    'ac' => 'sitejsondb',
		    'at' => 'appupdate',
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
		$db_table = db_prefix . "apply";
		$addtime = time();
		if (count($inforss) > 0) {
			$outhtml = null;
			foreach ($inforss as $key => $value) {
				if (empty($value['applyname']) || empty($value['applycode']) || empty($value['vol'])) continue;
				$db_where = " WHERE applycode='$value[applycode]'";
				$countnum = $this->db_numrows($db_table, $db_where);
				if ($countnum > 0) {
					continue;
				}
				$applyname = $value['applyname'];
				$applymenu = $value['applymenu'];
				$applycontent = $value['applycontent'];
				$applycode = $value['applycode'];
				$applyvol = $value['applyvol'];
				$applypic = $value['applypic'];
				$volsn = !preg_match("/^[0-9]+$/i", $value['vol']) ? 0 : $value['vol'];
				$uservol = !preg_match("/^[0-9]+$/i", $uservol['uservol']) ? 0 : $uservol['uservol'];
				$issqlbak = intval($value['issqlbak']);
				$isfree = intval($value['isfree']);
				$db_field = 'applyname,applymenu,applycontent,applycode,applyvol,applypic,volsn,uservol,addtime,setuptime,isdel,isetup,issystem,issqlbak,isfree';
				$db_values = "'$applyname','$applymenu','$applycontent','$applycode','$applyvol','$applypic',$volsn,$uservol,$addtime,0,0,0,0,$issqlbak,$isfree";
				$this->db->query('INSERT INTO ' . $db_table . ' (' . $db_field . ') VALUES (' . $db_values . ')');
				$insert_id = $this->db->insert_id();
				$modenmaienLan = $value['isfree'] == 1 ? $this->lng['modenmain_text_setup6'] : $this->lng['modenmain_text_setup7'];
				if ($this->powercheck('system', 'appsetup', false)) {
					$setupicon = '<a disabled="true" href="javascript:appsetup(' . $insert_id . ');" class="button three" id="appbotton' . $insert_id . '">' . $this->lng['modenmain_text_setup12'] . '</a>';
				}
				$outhtml.= '<li id="installdiv_' . $insert_id . '">
						<img src="' . $getval . $value['applypic'] . '" title="' . $value['applyname'] . '" class="pic"/>
						<p class="title">' . $value['applyname'] . '</p>
						<p class="desc">' . $modenmaienLan . '</p>
						<p class="setupicon">' . $setupicon . '</p>
					</li>';
			}
			exit($outhtml);
		} else {
			exit('false');
		}
	}
	function onappuninstall() {
		$appid = intval($this->fun->accept('appid', 'R'));
		if (empty($appid)) {
			$resturnjson = '{"setuptype":"0","message":"' . $this->lng['modenmain_text_setup14'] . '"}';
			exit($resturnjson);
		}
		$db_table = db_prefix . "apply";
		$db_where = 'appid=' . $appid . ' AND isetup=1 AND isdel=0';
		$appread = $this->db->fetch_first('SELECT * FROM ' . $db_table . ' WHERE ' . $db_where);
		if (empty($appread['applycode'])) {
			$resturnjson = '{"setuptype":"0","message":"' . $this->lng['modenmain_text_setup14'] . '"}';
			exit($resturnjson);
		}
		if (!file_exists(admin_ROOT . "public/app/" . $appread['applycode'] . ".php")) {
			$resturnjson = '{"setuptype":"0","message":"' . $this->lng['modenmain_text_setup15'] . ' FileERR：public/app/' . $appread['applycode'] . '.php' . '"}';
			exit($resturnjson);
		}
		include admin_ROOT . "public/app/" . $appread['applycode'] . ".php";
		if (count($app_sql_table) > 0 || count($app_menu_install) > 0) {
			if (!$this->sqlbak($this->CON['softvol'])) {
				$resturnjson = '{"setuptype":"0","message":"' . $this->lng['modenmain_text_setup16'] . '"}';
				exit($resturnjson);
			}
		}
		foreach ($app_file_interface as $value) {
			if (empty($value)) {
				continue;
			}
			$interfile = admin_ROOT . $value;
			$this->fun->delfile($interfile);
		}
		foreach ($app_file_admin as $value) {
			if (empty($value)) {
				continue;
			}
			$adminfile = admin_ROOT . adminfile . '/' . $value;
			$this->fun->delfile($adminfile);
		}
		if (count($app_sql_table) > 0) {
			$app_sql_table = implode(',', $app_sql_table);
			$app_sql_table = str_replace('esp_', db_prefix, $app_sql_table);
			if (!empty($app_sql_table)) {
				$this->db->query("DROP TABLE IF EXISTS $app_sql_table");
			}
		}
		if (count($app_menu_install) > 0) {
			$db_table2 = db_prefix . "menulink";
			$app_sql_menuid = implode(',', $app_menu_install);
			$db_where2 = "mlid IN ($app_sql_menuid)";
			$this->db->query('UPDATE ' . $db_table2 . ' SET isclass=0 WHERE ' . $db_where2);
		}
		$db_set = "isetup=0,setuptime=0";
		$this->db->query('UPDATE ' . $db_table . ' SET ' . $db_set . ' WHERE ' . $db_where);
		$modenmaienLan = $appread['isfree'] == 1 ? $this->lng['modenmain_text_setup6'] : $this->lng['modenmain_text_setup7'];
		if ($this->powercheck('system', 'appsetup', false)) {
			$setupicon = '<a disabled="true" href="javascript:appsetup(' . $appread['appid'] . ');" class="button three" id="appbotton' . $appread['appid'] . '">' . $this->lng['modenmain_text_setup12'] . '</a>';
		}
		$getval = convert_uudecode($this->CON['getnetval']);
		$posthttp = $getval . 'index.php';
		$appread['outhtml'] = '<li id="installdiv_' . $appid . '">
						<img src="' . $getval . $appread['applypic'] . '" title="' . $appread['applyname'] . '" class="pic"/>
						<p class="title">' . $appread['applyname'] . '</p>
						<p class="desc">' . $modenmaienLan . '</p>
						<p class="setupicon">' . $setupicon . '</p>
					</li>';
		$appread['setuptype'] = 1;
		$appread['message'] = $this->lng['modenmain_text_setup17'] . $this->lng['modenmain_text_setup27'] . '：' . $appread['applyname'];
		exit(json_encode($appread));
	}
	function onappupdate() {
		if (!function_exists('zip_open')) {
			$resturnjson = '{"setuptype":"0","message":"' . $this->lng['management_update_zip_err'] . '"}';
			exit($resturnjson);
		}
		$appid = intval($this->fun->accept('appid', 'R'));
		if (empty($appid)) {
			$resturnjson = '{"setuptype":"0","message":"' . $this->lng['modenmain_text_setup14'] . '"}';
			exit($resturnjson);
		}
		$addtime = time();
		$db_table = db_prefix . "apply";
		$db_where = 'appid=' . $appid . ' AND isetup=1';
		$appread = $this->db->fetch_first('SELECT * FROM ' . $db_table . ' WHERE ' . $db_where);
		if (empty($appread['applycode'])) {
			$resturnjson = '{"setuptype":"0","message":"' . $this->lng['modenmain_text_setup14'] . '"}';
			exit($resturnjson);
		}
		$data = array(
		    'ac' => 'sitejsondb',
		    'at' => 'appdownload',
		    'filetype' => 1,
		    'applycode' => $appread['applycode'],
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
		if (empty($inforss['appfileurl'])) {
			$resturnjson = '{"setuptype":"0","message":"' . $this->lng['modenmain_text_setup20'] . $this->lng['modenmain_text_setup27'] . '：' . $appread['applyname'] . '"}';
			exit($resturnjson);
		}
		$retrunvol = $this->update($inforss);
		if ($retrunvol) {
			$db_set = "isetup=1,setuptime=$addtime,isdel=0,applyname='$inforss[applyname]',applymenu='$inforss[applymenu]',applyvol='$inforss[applyvol]',applypic='$inforss[applypic]',volsn=$inforss[vol],uservol='$inforss[uservol]'";
			$this->db->query('UPDATE ' . $db_table . ' SET ' . $db_set . ' WHERE ' . $db_where);
			if ($appread['isdel'] == 1) {
				$setupicon = '<a href="#" class="button" id="setupicon_' . $appread['appid'] . '">' . $this->lng['modenmain_text_setup8'] . '</a>';
			} else {
				if ($this->powercheck('system', 'appuninstall', false)) {
					$setupicon = '<a href="javascript:if (confirm(\'' . $this->lng['modenmain_text_setup9'] . '\')) appuninstall(' . $appread['appid'] . ');" class="button orange" id="appbotton' . $appread['appid'] . '">' . $this->lng['modenmain_text_setup10'] . '</a>';
				}
			}
			$getval = convert_uudecode($this->CON['getnetval']);
			$posthttp = $getval . 'index.php';
			$appread['outhtml'] = $setupicon;
			$appread['setuptype'] = 1;
			$appread['message'] = $this->lng['modenmain_text_setup25'] . $this->lng['modenmain_text_setup27'] . '：' . $appread['applyname'];
			exit(json_encode($appread));
		} else {
			$resturnjson = '{"setuptype":"0","message":"' . $this->lng['modenmain_text_setup26'] . $this->lng['modenmain_text_setup27'] . '：' . $appread['applyname'] . '"}';
			exit($resturnjson);
		}
	}
	function onappsetup() {
		if (!function_exists('zip_open')) {
			$resturnjson = '{"setuptype":"0","message":"' . $this->lng['management_update_zip_err'] . '"}';
			exit($resturnjson);
		}
		$appid = intval($this->fun->accept('appid', 'R'));
		if (empty($appid)) {
			$resturnjson = '{"setuptype":"0","message":"' . $this->lng['modenmain_text_setup14'] . '"}';
			exit($resturnjson);
		}
		$addtime = time();
		$db_table = db_prefix . "apply";
		$db_where = 'appid=' . $appid . ' AND isetup=0';
		$appread = $this->db->fetch_first('SELECT * FROM ' . $db_table . ' WHERE ' . $db_where);
		if (empty($appread['applycode'])) {
			$resturnjson = '{"setuptype":"0","message":"' . $this->lng['modenmain_text_setup14'] . '"}';
			exit($resturnjson);
		}
		$data = array(
		    'ac' => 'sitejsondb',
		    'at' => 'appdownload',
		    'applycode' => $appread['applycode'],
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
		if (empty($inforss['appfileurl'])) {
			$resturnjson = '{"setuptype":"0","message":"' . $this->lng['modenmain_text_setup20'] . $this->lng['modenmain_text_setup27'] . '：' . $appread['applyname'] . '"}';
			exit($resturnjson);
		}
		$retrunvol = $this->update($inforss);
		if ($retrunvol) {
			$applyvol = $inforss['applyvol'];
			$volsn = preg_match("/^[0-9]{2,15}$/i", $inforss['vol']) ? $inforss['vol'] : $appread['volsn'];
			$uservol = preg_match("/^[0-9]{9,15}$/i", $inforss['uservol']) ? $inforss['uservol'] : $appread['uservol'];
			$db_set = "isetup=1,setuptime=$addtime,isdel=0,applyvol='$applyvol',volsn=$volsn,uservol=$uservol";
			$this->db->query('UPDATE ' . $db_table . ' SET ' . $db_set . ' WHERE ' . $db_where);
			$modenmaienLan = $appread['isfree'] == 1 ? $this->lng['modenmain_text_setup6'] : $this->lng['modenmain_text_setup7'];
			if ($appread['isdel'] == 1) {
				$setupicon = '<a href="#" class="button" id="setupicon_' . $appread['appid'] . '">' . $this->lng['modenmain_text_setup8'] . '</a>';
			} else {
				if ($this->powercheck('system', 'appuninstall', false)) {
					$setupicon = '<a href="javascript:if (confirm(\'' . $this->lng['modenmain_text_setup9'] . '\')) appuninstall(' . $appread['appid'] . ');" class="button orange" id="appbotton' . $appread['appid'] . '">' . $this->lng['modenmain_text_setup10'] . '</a>';
				}
			}
			$getval = convert_uudecode($this->CON['getnetval']);
			$posthttp = $getval . 'index.php';
			$appread['outhtml'] = '<li id="installdiv_' . $appid . '">
						<img src="' . $getval . $appread['applypic'] . '" title="' . $appread['applyname'] . '" class="pic"/>
						<p class="title">' . $appread['applyname'] . '</p>
						<p class="desc">' . $modenmaienLan . '</p>
						<p class="setupicon" id="installbotton_' . $appid . '">' . $setupicon . '</p>
					</li>';
			$appread['setuptype'] = 1;
			$appread['message'] = $this->lng['modenmain_text_setup21'] . $this->lng['modenmain_text_setup27'] . '：' . $appread['applyname'];
			exit(json_encode($appread));
		} else {
			$resturnjson = '{"setuptype":"0","message":"' . $this->lng['modenmain_text_setup22'] . $this->lng['modenmain_text_setup27'] . '：' . $appread['applyname'] . '"}';
			exit($resturnjson);
		}
	}
	function update($updatearray = array()) {
		if ($updatearray['uservol'] > $this->CON['softvol']) {
			return false;
		}
		if ($updatearray['issqlbak']) {
			if (!$this->sqlbak($this->CON['softvol'])) {
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
		$updateFile = $tempDir . '/' . $updatearray['applycode'] . '.zip';
		$updateUrl = $updatearray['appfileurl'];
		if (!is_file($updateFile)) {
			if (!$this->downloadUpdate($updateUrl, $updateFile)) {
				return false;
			}
		}
		$installresturn = $this->install($updateFile, $updatearray);
		if ($updatearray['issqlbak'] && $installresturn) {
			$sqlfile = admin_ROOT . $updatearray['applycode'] . '.sql';
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
		$vol = empty($volRun) ? 1 : intval($volRun);
		$tables = array();
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
