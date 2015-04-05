<?php

/*
  PHP version 5
  Copyright (c) 2002-2014 ECISP.CN、EarcLink.COM
  警告：这不是一个免费的软件，请在许可范围内使用，请尊重知识产权，侵权必究，举报有奖
  作者：黄祥云 E-mail:6326420@qq.com  QQ:6326420 TEL:18665655030
  ESPCMS官网介绍：http://www.ecisp.cn	企业建站：http://www.earclink.cn

 */
define('CONFIG', admin_ROOT . 'datacache/public.php');
define('CHARSET', 'utf-8');
define('DBCHARSET', 'utf8');
define('ORIG_TABLEPRE', 'esp_');
define('SOFT_NAME', 'espcms_v6');
define('SOFT_VERSION', '6.3.15.03.21 UTF8');
define('SOFT_RELEASE', '6300150321');
define('db_prefix', 'espcms_');
define('db_lan', 'cn');
define('db_keycode', '587252ED6125A6A88B37C56F451A21A5');
define('SOFT_TITLE', '易思ESPCMS企业网站管理系统 V6');
$sqlfile = admin_ROOT . './install/dbmysql/db.sql';
$sqlfile_dbinstall = admin_ROOT . './install/dbmysql/db_install.sql';
$sqlfile_demodb = admin_ROOT . './install/dbmysql/demodb.sql';
$installlock = admin_ROOT . './datacache/install.lock';
$cp_items = array(
    0 => array('name' => '操作系统', 'list' => 'os', 'c' => 'PHP_OS', 'r' => '不限', 'b' => 'Linux'),
    1 => array('name' => 'PHP', 'list' => 'php', 'c' => 'PHP_VERSION', 'r' => '5.2', 'b' => '5.2/5.3'),
    2 => array('name' => '上传配置', 'list' => 'upload', 'r' => '不限', 'b' => '不低于2M'),
    3 => array('name' => 'GD库', 'list' => 'gdversion', 'r' => '2.0', 'b' => '2.0'),
    4 => array('name' => '磁盘空间', 'list' => 'disk', 'r' => '10M', 'b' => '不限'),
);
$dir_items = array(
    0 => array('type' => 'file', 'path' => './datacache'),
    1 => array('type' => 'file', 'path' => './datacache/admin'),
    2 => array('type' => 'file', 'path' => './datacache/admin/cache'),
    3 => array('type' => 'file', 'path' => './datacache/admin/templates'),
    4 => array('type' => 'file', 'path' => './datacache/backup'),
    5 => array('type' => 'file', 'path' => './datacache/dbcache'),
    6 => array('type' => 'file', 'path' => './datacache/main'),
    7 => array('type' => 'file', 'path' => './datacache/main/cache'),
    8 => array('type' => 'file', 'path' => './datacache/main/templates'),
    9 => array('type' => 'file', 'path' => './datacache/pic'),
    10 => array('type' => 'file', 'path' => './public/app'),
    11 => array('type' => 'file', 'path' => './upfile'),
    12 => array('type' => 'file', 'path' => './sitemap'),
    13 => array('type' => 'file', 'path' => './html'),
    14 => array('type' => 'file', 'path' => './templates'),
);

$func_items = array(
    0 => array('name' => 'mysql_connect'),
    1 => array('name' => 'fsockopen'),
    2 => array('name' => 'gethostbyname'),
    3 => array('name' => 'file_get_contents'),
    4 => array('name' => 'xml_parser_create'),
    5 => array('name' => 'simplexml_load_file'),
    6 => array('name' => 'imagecopymerge'),
    7 => array('name' => 'ImageCreateFromGIF'),
    8 => array('name' => 'ImageCreateFromJPEG'),
    9 => array('name' => 'ImageCreateFromPNG'),
    10 => array('name' => 'imagejpeg'),
    11 => array('name' => 'imagettfbbox'),
    12 => array('name' => 'mcrypt_encrypt'),
    13 => array('name' => 'zip_open'),
    14 => array('name' => 'zip_read'),
);
$dblist = array();

$func_app = array(
    0 => array(
	'appname' => '广告友情链接组件',
	'sqlfile' => 'bann.sql',
	'appcode' => 'bann',
	'dbsql' => ',esp_advert,esp_advert_type',
	'isdel' => '1',
	'menuid' => array('155', '152', '153', '154', '156', '157', '158'),
	'file' => array('interface/lib_bann.php', 'control/advertmain.php', 'adminsoft/control/adverttypemain.php')
    ),
    1 => array(
	'appname' => '自助表单管理',
	'appcode' => 'form',
	'isdel' => '0',
    ),
    2 => array(
	'appname' => '上传文件管理',
	'appcode' => 'upfile',
	'isdel' => '0',
    ),
    3 => array(
	'appname' => '图片相册管理',
	'appcode' => 'photo',
	'isdel' => '0',
    ),
);
