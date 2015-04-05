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

	function onfilewindow() {
		include_once admin_ROOT . adminfile . '/include/command_list.php';
		parent::start_template();
		$listfunction = $this->fun->accept('listfunction', 'G');
		if (empty($listfunction)) {
			exit('err');
		}
		$checkfrom = $this->fun->accept('checkfrom', 'R');
		$checkfrom = empty($checkfrom) ? 'edit' : $checkfrom;
		$getbyid = $this->fun->accept('getbyid', 'R');
		$fileinputid = $this->fun->accept('fileinputid', 'R');
		$iframename = $this->fun->accept('iframename', 'R');
		$iframeid = $this->fun->accept('iframeid', 'R');
		$dirlist = $this->fun->accept('dirlist', 'R');
		$filetype = $this->fun->accept('filetype', 'R');
		$filetype = empty($filetype) ? 'img' : $filetype;
		$digheight = $this->fun->accept('digheight', 'R');
		$maxselect = intval($this->fun->accept('maxselect', 'R'));
		$maxselect = empty($maxselect) ? 1 : $maxselect;
		$isurlpath = intval($this->fun->accept('isurlpath', 'R'));
		$isurlpath = empty($isurlpath) ? 0 : $isurlpath;
		$isgetback = intval($this->fun->accept('isgetback', 'R'));
		$isgetback = empty($isgetback) ? 0 : $isgetback;
		$amid = intval($this->fun->accept('amid', 'R'));
		$amid = empty($amid) ? 0 : $amid;
		$path = $this->fun->accept('path', 'R');
		$this->ectemplates->assign('loadurl', $CONLIST[$listfunction]['loadurl'] . '&dirlist=' . $dirlist . '&checkfrom=' . $checkfrom . '&fileinputid=' . $fileinputid . '&filetype=' . $filetype . '&maxselect=' . $maxselect . '&isurlpath=' . $isurlpath . '&isgetback=' . $isgetback . '&path=' . $path . '&amid=' . $amid);
		$this->ectemplates->assign('batupwindow', $CONLIST[$listfunction]['batupwindow'] . '&dirlist=' . $dirlist . '&checkfrom=' . $checkfrom . '&fileinputid=' . $fileinputid . '&filetype=' . $filetype . '&maxselect=' . $maxselect . '&isurlpath=' . $isurlpath . '&isgetback=' . $isgetback . '&path=' . $path . '&amid=' . $amid);
		if (!$isgetback) {
			$this->ectemplates->assign('picview', $CONLIST[$listfunction]['picview'] . '&dirlist=' . $dirlist . '&checkfrom=' . $checkfrom . '&fileinputid=' . $fileinputid . '&filetype=' . $filetype . '&maxselect=' . $maxselect . '&isurlpath=' . $isurlpath);
			$this->ectemplates->assign('fileview', $CONLIST[$listfunction]['fileview'] . '&dirlist=' . $dirlist . '&checkfrom=' . $checkfrom . '&fileinputid=' . $fileinputid . '&filetype=' . $filetype . '&maxselect=' . $maxselect . '&isurlpath=' . $isurlpath);
		}
		$this->ectemplates->assign('listfunction', $listfunction);
		$this->ectemplates->assign('checkfrom', $checkfrom);
		$this->ectemplates->assign('iframename', $iframename);
		$this->ectemplates->assign('fileinputid', $fileinputid);
		$this->ectemplates->assign('getbyid', $getbyid);
		$this->ectemplates->assign('iframeid', $iframeid);
		$this->ectemplates->assign('digheight', $digheight);
		$this->ectemplates->assign('maxselect', $maxselect);
		$this->ectemplates->assign('filetype', $filetype);
		$this->ectemplates->assign('isgetback', $isgetback);
		$this->ectemplates->assign('path', $path);
		$this->ectemplates->assign('amid', $amid);
		$this->ectemplates->assign('rootDIR', admin_rootDIR);
		$this->ectemplates->assign('framt', $digheight - 45);
		$this->ectemplates->display('public/public_filemanage_filewindow');
	}

	function onalbumlist() {
		parent::start_template();
		$db_table = db_prefix . 'album_images';
		$lng = $this->sitelng;
		$lng = empty($lng) ? $this->CON['default_lng'] : $lng;
		$db_where = ' WHERE lng=\'' . $lng . '\'';
		$sql = 'SELECT * FROM ' . $db_table . $db_where . ' ORDER BY amid DESC';
		$rs = $this->db->query($sql);
		while ($rsList = $this->db->fetch_assoc($rs)) {
			$array[] = $rsList;
		}
		$lnglist = $this->get_lng_array($lng);
		$this->ectemplates->assign('lnglist', $lnglist['list']);
		$maxselect = $this->fun->accept('maxselect', 'R');
		$maxselect = empty($maxselect) ? 1 : $maxselect;
		$this->ectemplates->assign('maxselect', $maxselect);
		$fheight = $this->fun->accept('fheight', 'R');
		$this->ectemplates->assign("fheight", $fheight);
		$this->ectemplates->assign('array', $array);
		$this->ectemplates->assign('loadurl', 'index.php?archive=filemanage&action=piclist');
		$this->ectemplates->assign('sql', $sql);
		$this->ectemplates->display('public/public_filename_albumlist');
	}

	function onpiclist() {
		parent::start_template();
		$amid = $this->fun->accept('amid', 'R');
		if (empty($amid)) {
			exit($this->lng['filemanage_filecheck_select_no']);
		}
		$db_table = db_prefix . 'album_file';
		if (!empty($amid)) {
			$db_where = ' WHERE amid=' . $amid;
		}
		$sql = 'SELECT * FROM ' . $db_table . $db_where . ' ORDER BY afid DESC';
		$rs = $this->db->query($sql);
		while ($rsList = $this->db->fetch_assoc($rs)) {
			$rsList['fileallpath'] = $rsList['filepath'] . $rsList['filename'];
			$array[] = $rsList;
		}
		$this->ectemplates->assign('array', $array);
		$this->ectemplates->display('public/public_filename_piclist');
	}

	function onfilelist() {
		parent::start_template();
		include_once admin_ROOT . adminfile . '/include/command_list.php';
		$checkfrom = $this->fun->accept('checkfrom', 'R');
		$checkfrom = empty($checkfrom) ? 'edit' : $checkfrom;
		$maxselect = $this->fun->accept('maxselect', 'R');
		$maxselect = empty($maxselect) ? 1 : $maxselect;
		$fileinputid = $this->fun->accept('fileinputid', 'R');
		$filetype = $this->fun->accept('filetype', 'R');
		$filetype = empty($filetype) ? 'img' : $filetype;
		$fheight = $this->fun->accept('fheight', 'R');
		$isurlpath = $this->fun->accept('isurlpath', 'R');
		$isurlpath = empty($isurlpath) ? 0 : $isurlpath;
		$updirname = $this->fun->accept('updirname', 'R');
		$filepath = $this->fun->accept('filepath', 'R');
		if (!empty($filepath)) {
			$db_where = " AND filepath='$filepath'";
		} else {
			$filepath = $this->CON['upfile_dir'];
			$db_where = " AND pathnow=0";
		}
		$loadurlDIR = $CONLIST['filelist']['fileview'] . '&isurlpath=' . $isurlpath . '&checkfrom=' . $checkfrom . '&fileinputid=' . $fileinputid . '&filetype=' . $filetype . '&maxselect=' . $maxselect . '&fheight=' . $fheight;
		if ($filetype == 'img') {
			$inputtype = $this->CON['upfile_pictype'];
		} elseif ($filetype == 'file') {
			$inputtype = $this->CON['upfile_filetype'];
		} elseif ($filetype == 'mover') {
			$inputtype = $this->CON['uifile_movertype'];
		}
		$filetypearray = explode('|', $inputtype);
		if (count($filetypearray) < 1) {
			exit($this->lng['filemanage_mode_err']);
		} else {
			$nowextension = implode("','", $filetypearray);
			$db_where .= " AND extension IN ('dir','$nowextension')";
		}
		$db_where = $this->db->wherestr($db_where);
		$db_table = db_prefix . 'filename';
		$sql = 'SELECT * FROM ' . $db_table . $db_where . ' ORDER BY filetype ASC,addtime DESC';
		$rs = $this->db->query($sql);
		while ($rsList = $this->db->fetch_assoc($rs)) {
			if ($rsList['filetype'] != 'dir') {
				$filename = admin_ROOT . $rsList['filepath'] . $rsList['filename'];
				$rsList['size'] = @filesize($filename);
			}
			if ($rsList['filetype'] == 'img') {
				$rsList['url'] = admin_URL . $rsList['filepath'] . $rsList['filename'];
				$imginfo = $this->get_imginfo($file);
				if ($imginfo) {
					$rsList['picerrid'] = $imginfo['picerrid'];
					$rsList['srcW'] = $imginfo['srcW'];
					$rsList['srcH'] = $imginfo['srcH'];
					$rsList['windowsW'] = $imginfo['windowsW'];
					$rsList['windowsH'] = $imginfo['windowsH'];
				}
			} elseif ($rsList['filetype'] == 'dir') {
				$rsList['loadurl'] = $loadurlDIR . "&filepath=" . $rsList['filepath'] . $rsList['filename'] . "/&updirname=" . $rsList['filepath'];
			}
			$array[] = $rsList;
		}
		if ($updirname != '' && $MinPageid <= 1) {
			$updirname = rtrim($updirname, '/');
			$dirarray = explode('/', $updirname);
			if (is_array($dirarray) && count($dirarray) > 0) {
				$upfilepath = null;
				$updirnamenow = null;
				for ($i = 0; $i < count($dirarray); $i++) {
					$upfilepath .= $dirarray[$i] . '/';
				}
				for ($ii = 0; $ii < count($dirarray) - 1; $ii++) {
					$updirnamenow .= $dirarray[$ii] . '/';
				}
			}
			$upfilearray = array();
			$upfilearray['filetype'] = 'dir';
			$upfilearray['filepath'] = $upfilepath;
			$upfilearray['loadurl'] = $loadurlDIR . "&filepath=" . $upfilepath . "&updirname=" . $updirnamenow;
		}
		$this->ectemplates->assign('array', $array);
		$this->ectemplates->assign('upfilearray', $upfilearray);
		$this->ectemplates->assign('maxselect', $maxselect);
		$this->ectemplates->assign('checkfrom', $checkfrom);
		$this->ectemplates->assign('fileinputid', $fileinputid);
		$this->ectemplates->assign("fheight", $fheight);
		$this->ectemplates->assign('filepath', $filepath);
		$this->ectemplates->assign('isurlpath', $isurlpath);
		$this->ectemplates->assign('loadurl', $loadurlDIR);
		if ($filetype == 'img') {
			$this->ectemplates->display('public/public_filemanage_filelist_pic');
		} else {
			$this->ectemplates->display('public/public_filemanage_filelist');
		}
	}

	function onupfile() {
		parent::start_template();
		$lng = $this->sitelng;
		$lng = empty($lng) ? $this->CON['default_lng'] : $lng;
		$path = $this->fun->accept('path', 'R');
		$path = empty($path) ? $this->CON['upfile_dir'] : $path;
		$amid = intval($this->fun->accept('amid', 'R'));
		$amid = empty($amid) ? 0 : $amid;
		$img_iswater = $this->CON['img_iswater'];
		$maxfile = $this->CON['upfile_maxsize'];
		$img_width = $this->CON['img_width'];
		$img_height = $this->CON['img_height'];
		$img_issmallpic = $this->CON['img_issmallpic'];
		$filetype = $this->fun->accept('filetype', 'R');
		$filetype = empty($filetype) ? 'img' : $filetype;
		$upfile_pictype = $filetype == 'img' ? $this->CON['upfile_pictype'] : ($filetype == 'file' ? $this->CON['upfile_filetype'] : $this->CON['uifile_movertype']);
		$upinfo = @ini_get('file_uploads') ? ini_get('upload_max_filesize') : '<font color="red">' . $this->lng['management_login_phpinfo07'] . '</font>';
		$fheight = $this->fun->accept('fheight', 'R');
		$this->ectemplates->assign("fheight", $fheight);
		if ($filetype == 'img') {
			$albumarray = $this->get_album_images_array(0, 1, $lng);
			$this->ectemplates->assign("array", $albumarray['list']);
		}
		$isgetback = intval($this->fun->accept('isgetback', 'R'));
		$isgetback = empty($isgetback) ? 0 : $isgetback;
		$this->ectemplates->assign('isgetback', $isgetback);
		$this->ectemplates->assign("path", $path);
		$this->ectemplates->assign("maxfile", $maxfile);
		$this->ectemplates->assign("maxfile2", $this->fun->format_size($maxfile));
		$this->ectemplates->assign("maxfile3", $upinfo);
		$this->ectemplates->assign("img_width", $img_width);
		$this->ectemplates->assign("img_height", $img_height);
		$this->ectemplates->assign("img_issmallpic", $img_issmallpic);
		$this->ectemplates->assign("upfile_pictype", str_replace('|', '、', $upfile_pictype));
		$this->ectemplates->assign("filetype", $filetype);
		$this->ectemplates->assign("img_iswater", $img_iswater);
		$this->ectemplates->assign("lng", $lng);
		$this->ectemplates->assign("amid", $amid);
		$this->ectemplates->display("public/public_filemanage_upfile");
	}

	function onbatupfile() {
		parent::start_template();
		$path = $this->fun->accept('path', 'R');
		$path = empty($path) ? $this->CON['upfile_dir'] : $path;
		$maxfile = $this->CON['upfile_maxsize'];
		$img_width = $this->CON['img_width'];
		$img_height = $this->CON['img_height'];
		$img_issmallpic = $this->CON['img_issmallpic'];
		$img_iswater = $this->CON['img_iswater'];
		$filetype = $this->fun->accept('filetype', 'R');
		$filetype = empty($filetype) ? 'img' : $filetype;
		$amid = intval($this->fun->accept('amid', 'R'));
		$amid = empty($amid) ? 0 : $amid;
		if ($filetype == 'img') {
			$albumarray = $this->get_album_images_array(0, 1, $lng);
			$this->ectemplates->assign("array", $albumarray['list']);
		}
		$upfile_pictype_flash = $filetype == 'img' ? $this->CON['upfile_pictype'] : ($filetype == 'file' ? $this->CON['upfile_filetype'] : $this->CON['uifile_movertype']);
		if (!empty($upfile_pictype_flash)) {
			$pictypearray = explode('|', $upfile_pictype_flash);
			$piccount = count($pictypearray) - 1;
			foreach ($pictypearray as $key => $value) {
				if ($piccount == $key) {
					$upfile_pictype.='*.' . $value;
				} else {
					$upfile_pictype.='*.' . $value . ';';
				}
			}
		} else {
			$upfile_pictype = '*.jpg;*.gif;*.png';
		}
		$upinfo = @ini_get('file_uploads') ? ini_get('upload_max_filesize') : '<font color="red">' . $this->lng['management_login_phpinfo07'] . '</font>';
		$maxselect = $this->fun->accept('maxselect', 'R');
		$maxselect = empty($maxselect) || $maxselect <= 1 ? 10 : $maxselect;
		$this->ectemplates->assign('maxselect', $maxselect);
		$isgetback = intval($this->fun->accept('isgetback', 'R'));
		$isgetback = empty($isgetback) ? 0 : $isgetback;
		$this->ectemplates->assign('isgetback', $isgetback);
		$fheight = $this->fun->accept('fheight', 'R');
		$this->ectemplates->assign("fheight", $fheight);
		$this->ectemplates->assign("ecisp_admininfo", $this->fun->accept('ecisp_admininfo', 'C'));
		$this->ectemplates->assign("esp_powerlist", $this->fun->accept('esp_powerlist', 'C'));
		$this->ectemplates->assign("maxfile", $maxfile);
		$this->ectemplates->assign("maxfile2", $this->fun->format_size($maxfile));
		$this->ectemplates->assign("img_width", $img_width);
		$this->ectemplates->assign("img_height", $img_height);
		$this->ectemplates->assign("img_issmallpic", $img_issmallpic);
		$this->ectemplates->assign("upfile_pictype", $upfile_pictype);
		$this->ectemplates->assign("img_iswater", $img_iswater);
		$this->ectemplates->assign("upfile_pictype_str", str_replace('|', '、', $upfile_pictype_flash));
		$this->ectemplates->assign("path", $path);
		$this->ectemplates->assign("amid", $amid);
		$this->ectemplates->assign("filetype", $filetype);
		$this->ectemplates->display("public/public_filemanage_batupfile");
	}

	function onbatupfilesave() {
		require_once admin_ROOT . '/public/class_upload.php';
		$temppath = $this->fun->accept('path', 'R');
		$temppath = empty($temppath) ? $this->CON['upfile_dir'] : $temppath;
		$path = admin_ROOT . $temppath;
		if (!$this->fun->filemode($path)) {
			exit('false');
		}
		$img_issmallpic = $this->fun->accept('img_issmallpic', 'P');
		$img_issmallpic = empty($img_issmallpic) ? $this->CON['img_issmallpic'] : $img_issmallpic;
		$img_width = $this->fun->accept('img_width', 'P');
		$img_width = empty($img_width) ? $this->CON['img_width'] : $img_width;
		$img_height = $this->fun->accept('img_height', 'P');
		$img_height = empty($img_height) ? $this->CON['img_height'] : $img_height;
		$img_issmallpic = ($img_width < 1 || $img_height < 1 ) ? 0 : $img_issmallpic;
		$img_iswater = $this->fun->accept('img_iswater', 'P');
		$img_iswater = empty($img_iswater) ? 0 : $img_iswater;
		$type = $this->fun->accept('filetype', 'R');
		$type = empty($type) ? 'img' : $type;
		$isgetback = intval($this->fun->accept('isgetback', 'R'));
		$isgetback = empty($isgetback) ? 0 : $isgetback;
		$dirdate = $this->fun->accept('dirdate', 'P');
		if ($isgetback == 1) {
			$dirdate = 'list';
		} else {
			$dirdate = empty($dirdate) ? $this->CON['img_dirtype'] : $dirdate;
		}
		$filenametype = $this->fun->accept('filenametype', 'P');
		$filenametype = empty($filenametype) ? $this->CON['img_cfiletype'] : $filenametype;
		$iszoom = $this->fun->accept('iszoom', 'P');
		$iszoom = empty($iszoom) ? $this->CON['img_iszoom'] : $iszoom;
		$amid = $this->fun->accept('amid', 'R');
		$filename = $_FILES['Filedata']['name'];
		$filesize = intval($_FILES['Filedata']['size']);
		$filetmpname = $_FILES['Filedata']['tmp_name'];
		$fileerror = $_FILES['Filedata']['error'];
		$filetype = $_FILES['Filedata']['type'];
		if ($filesize <= 0 || $filesize > intval($this->CON['upfile_maxsize'])) {
			exit('false');
		}
		$upfile = new uploadFile();
		$filename = $upfile->upfilebase($img_width, $img_height, $img_issmallpic, $img_iswater, $path, $filename, $filesize, $filetmpname, $fileerror, NULL, $type, $dirdate, $filenametype, $iszoom);
		if ($filename) {
			$db_table = db_prefix . 'filename';
			$date = time();
			$filename = str_replace(admin_ROOT, '', $filename);
			list($uploadfile, $toImagesFile, $outfiletype, $filepath, $iswidth, $extension) = explode('|', $filename);
			if (!empty($uploadfile)) {
				$pathnow_array = preg_split('(\\\|/)', $filepath);
				$pathnow = is_array($pathnow_array) ? count($pathnow_array) - 2 : 0;
				unset($pathnow_array[0]);
				$db_field = 'username,filetitle,filename,filetype,filepath,addtime,iswidth,extension,pathnow';
				for ($index = 0; $index < count($pathnow_array) - 1; $index++) {
					$keyid = $index + 1;
					$updirname = $pathnow_array[$index];
					$filename = $pathnow_array[$keyid];
					$dirname_len.=!empty($updirname) ? $pathnow_array[$index] . '/' : null;
					$dirfilepath = empty($updirname) ? $this->CON['upfile_dir'] : $this->CON['upfile_dir'] . $dirname_len;
					$db_where = " WHERE pathnow=$index AND filename='$filename' AND filepath='$dirfilepath'";
					$countnum = $this->db_numrows($db_table, $db_where);
					if ($countnum <= 0) {
						$db_values = "'$this->esp_username','','$filename','dir','$dirfilepath',$date,0,'dir',$index";
						$this->db->query('INSERT INTO ' . $db_table . ' (' . $db_field . ') VALUES (' . $db_values . ')');
					}
				}
				$db_values = "('$this->esp_username','','$uploadfile','$outfiletype','$filepath',$date,$iswidth,'$extension',$pathnow)";
				if ($toImagesFile) {
					$db_values.= ",('$this->esp_username','','$toImagesFile','$outfiletype','$filepath',$date,$iswidth,'$extension',$pathnow)";
				}
				$this->db->query('INSERT INTO ' . $db_table . ' (' . $db_field . ') VALUES ' . $db_values . '');
				if ($amid && $outfiletype == 'img') {
					$insert_id = $this->db->insert_id();
					$db_table = db_prefix . 'album_file';
					$db_field = 'amid,fiid,filetitle,filedes,filename,filepath,iswidth,addtime';
					$db_values = "($amid,$insert_id,'$uploadfile','','$uploadfile','$filepath',$iswidth,$date)";
					if ($toImagesFile) {
						$db_values.= ",($amid,$insert_id,'$toImagesFile','','$toImagesFile','$filepath',$iswidth,$date)";
					}
					$this->db->query('INSERT INTO ' . $db_table . ' (' . $db_field . ') VALUES ' . $db_values . '');
				}
			}
		}
		if ($uploadfile) {
			exit('true');
		} else {
			exit('false');
		}
	}

	function onupfilesave() {
		require_once admin_ROOT . '/public/class_upload.php';
		$temppath = $this->fun->accept('path', 'P');
		$temppath = empty($temppath) ? $this->CON['upfile_dir'] : $temppath;
		$path = admin_ROOT . $temppath;
		if (!$this->fun->filemode($path)) {
			exit($this->lng['filemanage_js_upfile_no']);
		}
		$amid = $this->fun->accept('amid', 'P');
		$isamid = $this->fun->accept('isamid', 'P');
		$title = $this->fun->accept('title', 'P');
		$lng = $this->fun->accept('lng', 'P');
		if ($isamid) {
			if (empty($title)) {
				exit($this->lng['download_mess_size_err6']);
			}
			$db_table = db_prefix . 'album_images';
			$date = time();
			$db_field = 'pid,lng,title,content,pic,addtime,isclass,istop';
			$db_values = "50,'$lng','$title','$title','',$date,1,0";
			$this->db->query('INSERT INTO ' . $db_table . ' (' . $db_field . ') VALUES (' . $db_values . ')');
			$amid = $this->db->insert_id();
		}
		$upfilepath = $this->fun->accept('upfilepath', 'P');
		$img_issmallpic = $this->fun->accept('img_issmallpic', 'P');
		$img_issmallpic = empty($img_issmallpic) ? 0 : $img_issmallpic;
		$img_width = $this->fun->accept('img_width', 'P');
		$img_width = empty($img_width) ? 0 : ceil($img_width);
		$img_height = $this->fun->accept('img_height', 'P');
		$img_height = empty($img_height) ? 0 : ceil($img_height);
		$img_issmallpic = ($img_width < 1 || $img_height < 1 ) ? 0 : $img_issmallpic;
		$img_iswater = $this->fun->accept('img_iswater', 'P');
		$img_iswater = empty($img_iswater) ? 0 : $img_iswater;
		$type = $this->fun->accept('filetype', 'P');
		$type = empty($type) ? 'img' : $type;
		$isgetback = intval($this->fun->accept('isgetback', 'P'));
		$isgetback = empty($isgetback) ? 0 : $isgetback;
		$dirdate = $this->fun->accept('dirdate', 'P');
		if ($isgetback == 1) {
			$dirdate = 'list';
		} else {
			$dirdate = empty($dirdate) ? $this->CON['img_dirtype'] : $dirdate;
		}
		$filenametype = $this->fun->accept('filenametype', 'P');
		$filenametype = empty($filenametype) ? $this->CON['img_cfiletype'] : $filenametype;
		$iszoom = $this->fun->accept('iszoom', 'P');
		$iszoom = empty($iszoom) ? $this->CON['img_iszoom'] : $iszoom;
		$filename = $_FILES['upfilepath']['name'];
		$filesize = intval($_FILES['upfilepath']['size']);
		$filetmpname = $_FILES['upfilepath']['tmp_name'];
		$fileerror = $_FILES['upfilepath']['error'];
		$filetype = $_FILES['upfilepath']['type'];
		if ($filesize <= 0 || $filesize > intval($this->CON['upfile_maxsize'])) {
			exit($this->lng['download_mess_size_err2']);
		}
		$upfile = new uploadFile();
		$filename = $upfile->upfilebase($img_width, $img_height, $img_issmallpic, $img_iswater, $path, $filename, $filesize, $filetmpname, $fileerror, $filetype, $type, $dirdate, $filenametype, $iszoom);
		if ($filename) {
			$db_table = db_prefix . 'filename';
			$date = time();
			$filename = str_replace(admin_ROOT, '', $filename);
			list($uploadfile, $toImagesFile, $outfiletype, $filepath, $iswidth, $extension) = explode('|', $filename);
			if (!empty($uploadfile)) {
				$pathnow_array = preg_split('(\\\|/)', $filepath);
				$pathnow = is_array($pathnow_array) ? count($pathnow_array) - 2 : 0;
				unset($pathnow_array[0]);
				$db_field = 'username,filetitle,filename,filetype,filepath,addtime,iswidth,extension,pathnow';
				for ($index = 0; $index < count($pathnow_array) - 1; $index++) {
					$keyid = $index + 1;
					$updirname = $pathnow_array[$index];
					$filename = $pathnow_array[$keyid];
					$dirname_len.=!empty($updirname) ? $pathnow_array[$index] . '/' : null;
					$dirfilepath = empty($updirname) ? $this->CON['upfile_dir'] : $this->CON['upfile_dir'] . $dirname_len;
					$db_where = " WHERE pathnow=$index AND filename='$filename' AND filepath='$dirfilepath'";
					$countnum = $this->db_numrows($db_table, $db_where);
					if ($countnum <= 0) {
						$db_values = "'$this->esp_username','','$filename','dir','$dirfilepath',$date,0,'dir',$index";
						$this->db->query('INSERT INTO ' . $db_table . ' (' . $db_field . ') VALUES (' . $db_values . ')');
					}
				}
				$db_values = "('$this->esp_username','','$uploadfile','$outfiletype','$filepath',$date,$iswidth,'$extension',$pathnow)";
				if ($toImagesFile) {
					$db_values.= ",('$this->esp_username','','$toImagesFile','$outfiletype','$filepath',$date,$iswidth,'$extension',$pathnow)";
				}
				$this->db->query('INSERT INTO ' . $db_table . ' (' . $db_field . ') VALUES ' . $db_values . '');
				if ($amid && $outfiletype == 'img') {
					$insert_id = $this->db->insert_id();
					$db_table = db_prefix . 'album_file';
					$db_field = 'amid,fiid,filetitle,filedes,filename,filepath,iswidth,addtime';
					$db_values = "($amid,$insert_id,'$uploadfile','','$uploadfile','$filepath',$iswidth,$date)";
					if ($toImagesFile) {
						$db_values.= ",($amid,$insert_id,'$toImagesFile','','$toImagesFile','$filepath',$iswidth,$date)";
					}
					$this->db->query('INSERT INTO ' . $db_table . ' (' . $db_field . ') VALUES ' . $db_values . '');
				}
			}
		}
		if ($uploadfile) {
			if ($toImagesFile) {
				exit($filepath . $uploadfile . '|' . $outfiletype . '|' . $iswidth . '|' . $filepath . $toImagesFile);
			} else {
				exit($filepath . $uploadfile . '|' . $outfiletype . '|' . $iswidth . '|');
			}
		} else {
			exit('false');
		}
	}

}
