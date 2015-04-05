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

	function onrefreshfiledb() {
		$dirlist = $this->fun->accept('dirlist', 'R');
		$upfile_dir = empty($dirlist) ? admin_ROOT . $this->CON['upfile_dir'] : admin_ROOT . $dirlist;
		if (!$this->fun->filemode($upfile_dir)) {
			exit($this->lng['filemanage_mode_err']);
		}
		$db_table = db_prefix . 'filename';
		$where = " WHERE filepath LIKE '$dirlist%'";
		$sql = 'SELECT * FROM ' . $db_table . $where . ' ORDER BY fiid DESC';
		$rs = $this->db->query($sql);
		while ($rsList = $this->db->fetch_assoc($rs)) {
			$checkfile = admin_ROOT . $rsList['filepath'] . $rsList['filename'];
			if (!is_file($checkfile)) {
				$db_where_dc = 'fiid=' . $rsList['fiid'];
				$this->db->query('DELETE FROM ' . $db_table . ' WHERE ' . $db_where_dc);
			}
		}
		$arrayfile = $this->fun->list_dir($upfile_dir);
		if (is_array($arrayfile) && count($arrayfile) > 0) {
			foreach ($arrayfile as $key => $filename) {
				$this->updatefile($filename);
			}
		}
		exit('true');
	}

	function updatefile($file) {
		if (empty($file)) {
			return false;
		}
		$db_table = db_prefix . 'filename';
		$db_field = 'username,filetitle,filename,filetype,filepath,addtime,iswidth,extension,pathnow';
		$mime = $this->getMimeType($file);
		if ($mime == 'dir') {
			$dir = pathinfo($file);
			$filepath = str_replace(admin_ROOT, '', $dir['dirname']) . '/';
			$pathnow_array = preg_split('(\\\|/)', $filepath);
			$pathnow = is_array($pathnow_array) ? count($pathnow_array) - 2 : 0;
			$filetime = filectime($file);
			$filename = $dir['basename'];
			$extension = 'dir';
			$iswidth = 0;
			$filetype = 'dir';
			$db_where = " WHERE pathnow=$pathnow AND filename='$filename' AND filepath='$filepath'";
			$countnum = $this->db_numrows($db_table, $db_where);
			if ($countnum <= 0) {
				$db_values = "'$this->esp_username','','$filename','$filetype','$filepath',$filetime,$iswidth,'$extension',$pathnow";
				$this->db->query('INSERT INTO ' . $db_table . ' (' . $db_field . ') VALUES (' . $db_values . ')');
			}
		} else {
			if (!is_file($file)) {
				return false;
			}
			$filepath = str_replace(admin_ROOT, '', $mime['dirname']) . '/';
			$pathnow_array = preg_split('(\\\|/)', $filepath);
			$pathnow = is_array($pathnow_array) ? count($pathnow_array) - 2 : 0;
			$filepath = str_replace('\\', '/', $filepath);
			$filename = $mime['basename'];
			$extension = $mime['extension'];
			$filetime = filectime($file);
			if ($extension == 'jpg' || $extension == 'gif' || $extension == 'jpeg' || $extension == 'png') {
				$filetype = 'img';
				$imginfo = $this->get_imginfo($file);
				if ($imginfo) {
					$iswidth = $imginfo['srcW'] > $imginfo['srcH'] ? 1 : 2;
				} else {
					$iswidth = 0;
				}
			} else {
				$iswidth = 3;
				$filetype = 'file';
			}
			$db_where = " WHERE pathnow=$pathnow AND filename='$filename' AND filepath='$filepath'";
			$countnum = $this->db_numrows($db_table, $db_where);
			if ($countnum <= 0) {
				$db_values = "'$this->esp_username','','$filename','$filetype','$filepath',$filetime,$iswidth,'$extension',$pathnow";
				$this->db->query('INSERT INTO ' . $db_table . ' (' . $db_field . ') VALUES (' . $db_values . ')');
			}
		}
		return true;
	}

	function onfileadminlist() {
		parent::start_template();
		$MinPageid = intval($this->fun->accept('MinPageid', 'R'));
		$page_id = intval($this->fun->accept('page_id', 'R'));
		$countnum = intval($this->fun->accept('countnum', 'R'));
		$MaxPerPage = intval($this->fun->accept('MaxPerPage', 'R'));
		if (empty($MaxPerPage)) {
			$MaxPerPage = $this->CON['max_list'];
		}
		$updirname = $this->fun->accept('updirname', 'R');
		$filepath = $this->fun->accept('filepath', 'R');
		if (!empty($filepath)) {
			$db_where = " AND filepath='$filepath'";
		} else {
			$filepath = $this->CON['upfile_dir'];
			$db_where = " AND pathnow=0";
		}
		$db_where = $this->db->wherestr($db_where);
		$limitkey = $this->fun->accept('limitkey', 'R');
		$limitkey = empty($limitkey) ? 'filetype ASC,addtime' : $limitkey;
		$limitclass = $this->fun->accept('limitclass', 'R');
		$limitclass = empty($limitclass) ? 'DESC' : $limitclass;
		$db_table = db_prefix . 'filename';
		$MaxPerPage = $updirname != '' ? $MaxPerPage - 1 : $MaxPerPage;
		if (!empty($countnum)) {
			$countnum = $this->db_numrows($db_table, $db_where);
			exit($countnum);
		}
		$sql = 'SELECT * FROM ' . $db_table . $db_where . ' ORDER BY ' . $limitkey . ' ' . $limitclass . ' LIMIT ' . $MinPageid . ',' . $MaxPerPage;
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
				$rsList['loadurl'] = "index.php?archive=filemain&action=fileadminlist&filepath=" . $rsList['filepath'] . $rsList['filename'] . "/&updirname=" . $rsList['filepath'];
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
			$upfilearray['loadurl'] = "index.php?archive=filemain&action=fileadminlist&filepath=" . $upfilepath . "&updirname=" . $updirnamenow;
		}
		$this->fun->setcookie($this->fun->noncefile() . 'pgid', $page_id, 600);
		$this->ectemplates->assign('array', $array);
		$this->ectemplates->assign('upfilearray', $upfilearray);
		$this->ectemplates->assign('filepath', $filepath);
		$this->ectemplates->display('file/file_list');
	}

	function onrenamedir() {
		parent::start_template();
		$dir = $this->fun->accept('dir', 'R');
		$filename = $this->fun->accept('filename', 'R');
		$type = $this->fun->accept('type', 'R');
		$this->ectemplates->assign("dir", $dir);
		$this->ectemplates->assign("filename", $filename);
		$this->ectemplates->assign("type", $type);
		$this->ectemplates->display("file/file_rename");
	}

	function onrenamesave() {
		$dirlist = $this->fun->accept('dir', 'P');
		$filename = $this->fun->accept('filename', 'P');
		$type = $this->fun->accept('type', 'P');
		$upfile_dir = admin_ROOT;
		$db_table = db_prefix . 'filename';
		if (!empty($dirlist)) {
			$upfile_dir = $upfile_dir . $dirlist;
		}
		if ($type != 'dir') {
			$oldfilename = $upfile_dir . $filename;
			if (!is_file($oldfilename)) {
				exit('false');
			}
			$mime = $this->getMimeType($oldfilename);
			$extension = $mime['extension'] ? $mime['extension'] : $type;
		}
		$newfilename = $this->fun->accept('newfilename', 'P');
		$oldfile = @realpath($upfile_dir . $filename);
		$newfile = $type == 'dir' ? $upfile_dir . $newfilename : $upfile_dir . $newfilename . '.' . $extension;
		if (stristr($filename, ".php")) {
			exit('false');
		}
		if (!@rename($oldfile, $newfile)) {
			exit('false');
		} else {
			$db_where = "filename='$filename' AND filepath='$dirlist'";
			$db_set = $type == 'dir' ? "filename='$newfilename'" : "filename='$newfilename.$extension'";
			$this->db->query('UPDATE ' . $db_table . ' SET ' . $db_set . ' WHERE ' . $db_where);
			if ($type == 'dir') {
				$dellikefile = $dirlist . $filename . '/';
				$db_where_dc = "filepath like '%$dellikefile%'";
				$this->db->query('DELETE FROM ' . $db_table . ' WHERE ' . $db_where_dc);
				$arrayfile = $this->fun->list_dir($newfile);
				if (is_array($arrayfile) && count($arrayfile) > 0) {
					foreach ($arrayfile as $key => $filename) {
						$this->updatefile($filename);
					}
				}
			}
			exit('true');
		}
	}

	function onmkdir() {
		parent::start_template();
		$path = $this->fun->accept('path', 'R');
		$this->ectemplates->assign("path", $path);
		$this->ectemplates->display("file/file_mkdir");
	}

	function onmadirsave() {
		$dirname = $this->fun->accept('dirname', 'P');
		$dirlist = $this->fun->accept('path', 'P');
		if (!empty($dirlist)) {
			$filedir = admin_ROOT . $dirlist;
		} else {
			exit('false');
		}
		if (!$this->fun->filemode($filedir)) {
			exit($filedir);
		}
		$file = $filedir . $dirname;
		if (is_dir($file)) {
			exit('false');
		} else {
			if (@mkdir($file, 0777)) {
				$this->updatefile($file);
			}
			exit('true');
		}
	}

	function ondelfile() {
		$db_table = db_prefix . 'filename';
		$selectinfoid = $this->fun->accept('selectinfoid', 'P');
		if (empty($selectinfoid)) exit('false');
		$infoarray = explode(',', $selectinfoid);
		$count = count($infoarray) - 1;
		if ($count <= 0) exit('false');
		for ($i = 0; $i < $count; $i++) {
			$db_where = "fiid=$infoarray[$i]";
			$rsType = $this->db->fetch_first('SELECT * FROM ' . $db_table . ' WHERE ' . $db_where);
			$delfile = admin_ROOT . $rsType['filepath'] . $rsType['filename'];
			if ($rsType['filetype'] != 'dir') {
				$this->db->query('DELETE FROM ' . $db_table . ' WHERE ' . $db_where);
				$this->fun->delfile($delfile);
			} else {
				$this->db->query('DELETE FROM ' . $db_table . ' WHERE ' . $db_where);
				$dellikefile = $rsType['filepath'] . $rsType['filename'] . '/';
				$db_where_dc = "filepath like '%$dellikefile%'";
				$this->db->query('DELETE FROM ' . $db_table . ' WHERE ' . $db_where_dc);
				$this->fun->delfile($delfile);
			}
		}
		exit('true');
	}

	function onimagecrop() {
		parent::start_template();
		$dirlist = $this->fun->accept('dir', 'R');
		$upfile_dir = admin_ROOT . $this->CON['upfile_dir'];
		if (!empty($dirlist)) {
			$upfile_dir = admin_ROOT . $dirlist;
		}
		$imgname = $this->fun->accept('filename', 'R');
		$type = $this->fun->accept('type', 'R');
		$imgpath = @realpath($upfile_dir . $imgname);
		if (!$imgpath) {
			$this->calldialogmessage($this->lng ['filemanage_file_picerrupload'], $this->lng['botton_close']);
		}
		$mime = $this->getMimeType($imgpath);
		$imagesize = @getimagesize($imgpath);
		if (!$imagesize) {
			$this->calldialogmessage($this->lng ['filemanage_file_picerrupload'], $this->lng['botton_close']);
		}
		switch ($imagesize[2]) {
			case 1:
				$im = @ImageCreateFromGIF($imgpath);
				$mime['extension'] = 'gif';
				break;
			case 2:
				$im = @ImageCreateFromJPEG($imgpath);
				$mime['extension'] = 'jpg';
				break;
			case 3:
				$im = @ImageCreateFromPNG($imgpath);
				$mime['extension'] = 'png';
				break;
		}
		if (!$im) {
			$this->calldialogmessage($this->lng ['filemanage_file_picerrupload'], $this->lng['botton_close']);
		}
		$srcW = $imagesize[0];
		$srcH = $imagesize[1];
		$newfilename = $mime['filename'] . '_imagecrop' . '.' . $mime['extension'];
		$this->ectemplates->assign('url', admin_URL . $dirlist);
		$this->ectemplates->assign("path", $dirlist);
		$this->ectemplates->assign("imgname", $imgname);
		$this->ectemplates->assign("imgpath", $imgpath);
		$this->ectemplates->assign("newfilename", $newfilename);
		$this->ectemplates->assign("srcW", $srcW);
		$this->ectemplates->assign("srcH", $srcH);
		$this->ectemplates->display("file/file_imagecrop"
		);
	}

	function onimgagecut() {
		parent::start_template();
		$path = $this->fun->accept('path', 'P');
		$imgname = $this->fun->accept('imgname', 'P');
		$selecton = $this->fun->accept('selecton', 'P');
		$newimagename = $this->fun->accept('newimagename', 'P');
		$w = $this->fun->accept('w', 'P');
		$h = $this->fun->accept('h', 'P');
		$x = $this->fun->accept('x', 'P');
		$y = $this->fun->accept('y', 'P');
		$imgpath = @realpath(admin_ROOT . $path . $imgname);
		if (!$imgpath) {
			exit('false');
		}
		if ($selecton == 0) {
			$newfiletype = admin_ROOT . $path . $newimagename;
		} else {
			$newfiletype = $imgpath;
		}
		$imagetype = @getimagesize($imgpath);
		if (!$imagetype) {
			exit('false');
		}
		switch ($imagetype[2]) {
			case 1:
				$image = @ImageCreateFromGIF($imgpath);
				break;
			case 2:
				$image = @ImageCreateFromJPEG($imgpath);
				break;
			case 3:
				$image = @ImageCreateFromPNG($imgpath);
				break;
		}
		$image_p = ImageCreateTrueColor($w, $h);
		imagecopyresampled($image_p, $image, 0, 0, $x, $y, $w, $h, $w, $h);
		switch ($imagetype[2]) {
			case 1:
				@imagegif($image_p, $newfiletype);
				break;
			case 2:
				@imagejpeg($image_p, $newfiletype);
				break;
			case 3:
				@imagepng($image_p, $newfiletype);
				break;
		}
		@ImageDestroy($image);
		if ($selecton == 0) {
			$this->updatefile($newfiletype);
		}
		exit('true');
	}

	function onzoomimage() {
		parent::start_template();
		$path = $this->fun->accept('path', 'R');
		$imgname = $this->fun->accept('imgname', 'R');
		$dstW = $this->fun->accept('dstW', 'R');
		$dstH = $this->fun->accept('dstH', 'R');
		$imgpath = @realpath(admin_ROOT . $path . $imgname);
		if (!$imgpath) {
			exit('false');
		}
		if (!is_writable($imgpath)) {
			exit('false');
		}
		$mime = $this->getMimeType($imgpath);
		$imagesize = @getimagesize($imgpath);
		if (!$imagesize) {
			exit('false');
		}
		switch ($imagesize[2]) {
			case 1:
				$im = @ImageCreateFromGIF($imgpath);
				$mime['extension'] = 'gif';
				break;
			case 2:
				$im = @ImageCreateFromJPEG($imgpath);
				$mime['extension'] = 'jpg';
				break;
			case 3:
				$im = @ImageCreateFromPNG($imgpath);
				$mime['extension'] = 'png';
				break;
		}
		if (!$im) {
			exit('false');
		}
		$srcW = $imagesize[0];
		$srcH = $imagesize[1];
		$dstWH = $dstW / $dstH;
		$srcWH = $srcW / $srcH;
		if ($dstWH <= $srcWH) {
			$ftoW = $dstW;
			$ftoH = $ftoW * ($srcH / $srcW);
		} else {
			$ftoH = $dstH;
			$ftoW = $ftoH * ($srcW / $srcH);
		}
		$url = admin_URL . $path;
		$newimgpath = $url . $imgname;
		$img_quality = intval($this->CON['img_quality']);
		if ($srcW > $dstW || $srcH > $dstH) {
			if (function_exists('imagecreatetruecolor')) {
				$ni = ImageCreateTrueColor($ftoW, $ftoH);
				ImageCopyResampled($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
			} else {
				$ni = ImageCreate($ftoW, $ftoH);
				ImageCopyResized($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
			}
			switch ($mime['extension']) {
				case 'gif':
					@imagegif($ni, $imgpath);
					break;
				case 'jpg':
					@imagejpeg($ni, $imgpath, $img_quality);
					break;
				case 'png':
					@imagepng($ni, $imgpath);
					break;
			}
			$upresultid = '<span id="imagelist"><img id="cropbox" width="' . $ftoW . '" height="' . $ftoH . '" src="' . $newimgpath . '"></span>';
			ImageDestroy($ni);
		} else {
			$upresultid = '<span id="imagelist"><img id="cropbox" width="' . $dstW . '" height="' . $ftoH . '" src="' . $newimgpath . '"></span>';
			ImageDestroy($im);
		}
		exit($upresultid);
	}

}
