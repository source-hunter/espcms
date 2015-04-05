CREATE TABLE IF NOT EXISTS `esp_admin_member` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(50) default NULL,
  `sex` tinyint(1) default NULL,
  `intotime` int(11) NOT NULL,
  `intime` int(11) NOT NULL,
  `outtime` int(11) NOT NULL,
  `ipadd` int(15) NOT NULL,
  `hit` int(10) unsigned default '0',
  `powergroup` tinyint(4) NOT NULL default '1',
  `inputclassid` tinyint(1) NOT NULL default '1',
  `isclass` tinyint(1) NOT NULL default '1',
  `isremote` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_admin_powergroup` (
  `id` int(11) NOT NULL auto_increment,
  `powername` varchar(150) NOT NULL,
  `powerlist` text NOT NULL,
  `delclass` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `esp_album_file` (
  `afid` int(11) unsigned NOT NULL auto_increment,
  `pid` int(5) unsigned NOT NULL default '50',
  `amid` int(11) unsigned NOT NULL default '0',
  `fiid` int(11) unsigned NOT NULL default '0',
  `filetitle` varchar(200) NOT NULL default '',
  `filedes` text NOT NULL,
  `filename` varchar(200) NOT NULL,
  `filepath` varchar(200) NOT NULL,
  `iswidth` tinyint(1) unsigned NOT NULL default '0',
  `addtime` int(11) unsigned NOT NULL default '0',
  `isrecomm` tinyint(1) unsigned NOT NULL default '0',
  UNIQUE KEY `picid` (`afid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_album_images` (
  `amid` int(11) unsigned NOT NULL auto_increment,
  `pid` int(11) unsigned NOT NULL default '0',
  `lng` varchar(50) NOT NULL default 'cn',
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `pic` varchar(200) NOT NULL,
  `addtime` int(11) unsigned NOT NULL default '0',
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `istop` tinyint(1) unsigned NOT NULL default '0',
  UNIQUE KEY `amid` (`amid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_apply` (
  `appid` int(11) unsigned NOT NULL auto_increment,
  `applyname` varchar(200) NOT NULL,
  `applymenu` varchar(200) NOT NULL,
  `applycontent` text NOT NULL,
  `applycode` varchar(100) NOT NULL,
  `applyvol` varchar(100) NOT NULL,
  `applypic` varchar(100) NOT NULL,
  `volsn` int(11) unsigned NOT NULL default '0',
  `uservol` bigint(11) unsigned NOT NULL default '0',
  `addtime` int(11) unsigned NOT NULL default '0',
  `setuptime` int(11) unsigned NOT NULL default '0',
  `isdel` tinyint(1) unsigned NOT NULL default '0',
  `isetup` tinyint(1) unsigned NOT NULL default '0',
  `issystem` tinyint(1) unsigned NOT NULL default '0',
  `issqlbak` tinyint(1) unsigned NOT NULL default '0',
  `isfree` tinyint(1) unsigned NOT NULL default '0',
  UNIQUE KEY `appid` (`appid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_city` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `parentid` smallint(5) unsigned NOT NULL default '0',
  `cityname` varchar(120) NOT NULL,
  `regiontype` tinyint(1) NOT NULL default '2',
  `agencyid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `parentid` (`parentid`),
  KEY `regiontype` (`regiontype`),
  KEY `agencyid` (`agencyid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_config` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `pid` int(3) unsigned NOT NULL default '50',
  `valname` varchar(30) NOT NULL default '',
  `content` varchar(200) NOT NULL default '',
  `str` varchar(200) NOT NULL default '',
  `value` text NOT NULL,
  `valtype` varchar(10) NOT NULL default '',
  `bottonname` text NOT NULL,
  `groupid` smallint(3) unsigned NOT NULL default '0',
  `isline` tinyint(1) unsigned NOT NULL default '0',
  `isselect` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_document` (
  `did` int(11) unsigned NOT NULL auto_increment,
  `lng` varchar(50) NOT NULL default 'cn',
  `pid` smallint(6) unsigned NOT NULL default '50',
  `mid` smallint(6) unsigned NOT NULL default '0',
  `aid` smallint(6) unsigned NOT NULL default '0',
  `tid` int(11) unsigned NOT NULL default '0',
  `extid` varchar(200) NOT NULL,
  `sid` int(11) unsigned NOT NULL default '0',
  `fgid` int(8) unsigned NOT NULL default '0',
  `linkdid` varchar(100) NOT NULL,
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `islink` tinyint(1) unsigned NOT NULL default '0',
  `ishtml` tinyint(1) unsigned NOT NULL default '1',
  `ismess` tinyint(1) unsigned NOT NULL default '1',
  `isorder` tinyint(1) unsigned NOT NULL default '0',
  `ktid` int(6) unsigned NOT NULL default '0',
  `purview` tinyint(1) unsigned NOT NULL default '0',
  `istemplates` tinyint(1) unsigned NOT NULL default '0',
  `isbase` tinyint(1) unsigned NOT NULL default '0',
  `recommend` varchar(100) NOT NULL,
  `tsn` varchar(50) NOT NULL default '',
  `title` varchar(200) NOT NULL,
  `longtitle` varchar(200) NOT NULL,
  `color` varchar(8) NOT NULL,
  `author` char(20) NOT NULL,
  `source` char(30) NOT NULL,
  `pic` varchar(200) NOT NULL,
  `tags` varchar(250) NOT NULL,
  `headtitle` varchar(200) NOT NULL,
  `keywords` varchar(220) NOT NULL default '',
  `description` text NOT NULL,
  `summary` text NOT NULL,
  `link` varchar(250) NOT NULL,
  `oprice` decimal(10,2) NOT NULL default '0.00',
  `bprice` decimal(10,2) NOT NULL default '0.00',
  `click` smallint(6) unsigned NOT NULL default '0',
  `addtime` int(11) unsigned NOT NULL default '0',
  `uptime` int(11) unsigned NOT NULL default '0',
  `template` varchar(100) NOT NULL,
  `filename` varchar(100) NOT NULL default '',
  `filepath` varchar(200) NOT NULL,
  `filepage` smallint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`did`),
  UNIQUE KEY `tid` (`did`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_document_album` (
  `daid` int(11) NOT NULL auto_increment,
  `did` int(11) NOT NULL,
  `picname` varchar(200) NOT NULL default '',
  `filedes` text NOT NULL,
  `picfile` varchar(150) NOT NULL,
  `addtime` int(11) NOT NULL default '0',
  PRIMARY KEY  (`daid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_document_attr` (
  `datid` int(11) unsigned NOT NULL auto_increment,
  `did` int(11) NOT NULL default '0',
  `downloadurl` varchar(250) NOT NULL,
  `softsize` varchar(250) NOT NULL,
  `softsystem` varchar(250) NOT NULL,
  PRIMARY KEY  (`datid`,`did`),
  UNIQUE KEY `daid` (`datid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_document_content` (
  `dcid` int(11) NOT NULL auto_increment,
  `did` int(11) NOT NULL,
  `content` longtext NOT NULL,
  PRIMARY KEY  (`dcid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_document_label` (
  `dlid` int(10) unsigned NOT NULL auto_increment,
  `lng` varchar(50) NOT NULL,
  `mid` int(11) NOT NULL default '0',
  `labelname` varchar(100) NOT NULL,
  PRIMARY KEY  (`dlid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_document_message` (
  `dmid` int(11) unsigned NOT NULL auto_increment,
  `did` int(11) unsigned NOT NULL default '0',
  `userid` int(11) unsigned NOT NULL default '0',
  `adminid` int(11) unsigned NOT NULL default '0',
  `ipadd` varchar(11) NOT NULL default '0',
  `lng` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `recontent` text NOT NULL,
  `isreply` tinyint(1) unsigned NOT NULL default '0',
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `addtime` int(11) unsigned NOT NULL default '0',
  `retime` int(11) unsigned NOT NULL default '0',
  `support` int(7) unsigned NOT NULL default '0',
  `oppose` int(7) unsigned NOT NULL default '0',
  UNIQUE KEY `dmid` (`dmid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_enquiry` (
  `eid` int(11) unsigned NOT NULL auto_increment,
  `enquirysn` varchar(50) NOT NULL,
  `userid` int(11) unsigned NOT NULL default '0',
  `linkman` varchar(100) NOT NULL,
  `sex` tinyint(1) unsigned NOT NULL default '0',
  `country` smallint(5) unsigned NOT NULL default '0',
  `province` smallint(5) unsigned NOT NULL default '0',
  `city` smallint(5) unsigned NOT NULL default '0',
  `district` smallint(5) unsigned NOT NULL default '0',
  `address` varchar(255) NOT NULL,
  `zipcode` varchar(20) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `fax` varchar(20) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `addtime` int(11) unsigned NOT NULL default '0',
  `edittime` int(11) unsigned NOT NULL default '0',
  `editresult` text NOT NULL,
  UNIQUE KEY `eid` (`eid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_enquiry_info` (
  `eiid` int(10) unsigned NOT NULL auto_increment,
  `eid` int(11) unsigned NOT NULL default '0',
  `did` int(11) unsigned NOT NULL default '0',
  `tsn` varchar(50) NOT NULL,
  `title` varchar(250) NOT NULL,
  `amount` int(8) unsigned NOT NULL default '0',
  `comment` text NOT NULL,
  UNIQUE KEY `eiid` (`eiid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_filename` (
  `fiid` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(200) NOT NULL default '',
  `filetitle` varchar(200) NOT NULL default '',
  `filename` varchar(200) NOT NULL default '',
  `extension` varchar(80) NOT NULL,
  `filetype` varchar(50) NOT NULL default 'img',
  `filepath` varchar(200) NOT NULL default '',
  `addtime` int(11) unsigned NOT NULL default '0',
  `iswidth` tinyint(1) unsigned NOT NULL default '0',
  `pathnow` smallint(5) unsigned NOT NULL default '0',
  UNIQUE KEY `picid` (`fiid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_form_attr` (
  `faid` int(11) unsigned NOT NULL auto_increment,
  `fgid` int(11) unsigned NOT NULL default '0',
  `pid` smallint(6) NOT NULL default '50',
  `typename` varchar(200) NOT NULL default '',
  `typeremark` varchar(200) NOT NULL,
  `attrname` varchar(150) NOT NULL default '',
  `inputtype` varchar(20) NOT NULL default 'string',
  `attrvalue` text NOT NULL,
  `validatetext` varchar(150) NOT NULL default '',
  `attrsize` smallint(3) NOT NULL default '20',
  `attrrow` smallint(3) NOT NULL default '10',
  `attrlenther` smallint(3) NOT NULL default '50',
  `isclass` tinyint(1) NOT NULL default '1',
  `isvalidate` tinyint(1) NOT NULL default '0',
  `isline` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`faid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_form_group` (
  `fgid` int(11) unsigned NOT NULL auto_increment,
  `pid` smallint(6) unsigned NOT NULL default '50',
  `lng` varchar(50) NOT NULL default '',
  `formgroupname` varchar(200) NOT NULL default '',
  `formcode` varchar(80) NOT NULL default '',
  `pic` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `successtext` text NOT NULL,
  `template` varchar(150) NOT NULL,
  `emailatt` varchar(100) NOT NULL default '',
  `addtime` int(11) NOT NULL default '0',
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `ismenu` tinyint(1) unsigned NOT NULL default '0',
  `iswap` tinyint(1) unsigned NOT NULL default '1',
  `isseccode` tinyint(1) unsigned NOT NULL default '1',
  `ismail` tinyint(1) unsigned NOT NULL default '0',
  `mailcode` varchar(100) NOT NULL,
  `putmail` varchar(100) NOT NULL,
  `inputtime` int(5) unsigned NOT NULL default '300',
  `purview` tinyint(1) unsigned NOT NULL default '0',
  `issms` tinyint(1) unsigned NOT NULL default '0',
  `smscode` varchar(100) NOT NULL,
  `titlestyle` varchar(200) NOT NULL default '{typename}-{sitename}',
  PRIMARY KEY  (`fgid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_form_value` (
  `fvid` int(11) unsigned NOT NULL auto_increment,
  `fgid` int(11) unsigned NOT NULL default '0',
  `did` int(11) unsigned NOT NULL default '0',
  `userid` int(11) unsigned NOT NULL default '0',
  `addtime` int(11) unsigned NOT NULL default '0',
  `retime` int(11) unsigned NOT NULL default '0',
  `ipadd` varchar(11) NOT NULL default '0',
  `isreply` tinyint(1) unsigned NOT NULL default '0',
  `username` varchar(50) NOT NULL,
  `recontent` text NOT NULL,
  `yyname` varchar(250) NOT NULL,
  `tel` varchar(250) NOT NULL,
  `fftype` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `ffcontent` text NOT NULL,
  PRIMARY KEY  (`fvid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_keylink` (
  `kid` int(11) unsigned NOT NULL auto_increment,
  `lng` varchar(50) NOT NULL default 'cn',
  `pid` int(11) unsigned NOT NULL default '50',
  `mid` int(11) unsigned NOT NULL default '0',
  `tid` int(11) unsigned NOT NULL default '0',
  `hit` smallint(5) unsigned NOT NULL default '0',
  `keywordname` varchar(200) NOT NULL default '',
  `linkurl` varchar(220) NOT NULL default '',
  `islink` tinyint(1) unsigned NOT NULL default '0',
  `istop` tinyint(1) unsigned NOT NULL default '0',
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `isreplace` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`kid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_keylink_type` (
  `ktid` int(10) unsigned NOT NULL auto_increment,
  `lng` varchar(20) NOT NULL,
  `mid` int(11) unsigned NOT NULL default '0',
  `tid` int(11) unsigned NOT NULL default '0',
  `keytypename` varchar(200) NOT NULL default '',
  `keyworklist` text NOT NULL,
  `description` text NOT NULL,
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  UNIQUE KEY `ktid` (`ktid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_lng` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` tinyint(10) unsigned NOT NULL default '0',
  `lng` varchar(50) NOT NULL default '',
  `lngtitle` varchar(100) NOT NULL default '',
  `url` varchar(200) NOT NULL default '',
  `lockin` tinyint(1) unsigned NOT NULL default '0',
  `iswap` tinyint(1) unsigned NOT NULL default '0',
  `isopen` tinyint(1) unsigned NOT NULL default '1',
  `isuptype` tinyint(1) unsigned NOT NULL,
  `ispack` tinyint(1) unsigned NOT NULL default '0',
  `packname` varchar(100) NOT NULL,
  `sitename` varchar(100) NOT NULL default '',
  `keyword` varchar(150) NOT NULL,
  `description` varchar(150) NOT NULL,
  `copyright` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_lngpack` (
  `lpid` int(11) unsigned NOT NULL auto_increment,
  `pid` smallint(5) NOT NULL default '50',
  `lng` varchar(50) NOT NULL default 'cn',
  `title` varchar(150) NOT NULL,
  `keycode` varchar(50) NOT NULL default '',
  `langstr` text NOT NULL,
  `lockin` tinyint(1) NOT NULL default '1',
  `typeid` tinyint(1) NOT NULL default '1',
  UNIQUE KEY `lpid` (`lpid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_logs` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(100) NOT NULL,
  `onlineip` int(15) NOT NULL,
  `addtime` int(11) NOT NULL,
  `actions` varchar(150) NOT NULL,
  `remarks` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_mailinvite_list` (
  `mlvlid` int(11) unsigned NOT NULL auto_increment,
  `mlvid` int(11) unsigned NOT NULL default '0',
  `userid` int(11) unsigned NOT NULL default '0',
  `name` varchar(80) NOT NULL,
  `sex` tinyint(1) unsigned NOT NULL default '0',
  `email` varchar(200) NOT NULL,
  `tel` varchar(80) NOT NULL,
  `address` varchar(200) NOT NULL,
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `addtime` int(11) unsigned NOT NULL default '0',
  UNIQUE KEY `mlimid` (`mlvlid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_mailinvite_type` (
  `mlvid` int(11) unsigned NOT NULL auto_increment,
  `lng` varchar(50) NOT NULL default 'cn',
  `title` varchar(200) NOT NULL default '',
  `content` text NOT NULL,
  `purview` tinyint(1) unsigned NOT NULL default '0',
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `addtime` int(11) NOT NULL default '0',
  PRIMARY KEY  (`mlvid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_mailsend` (
  `msid` int(11) unsigned NOT NULL auto_increment,
  `lng` varchar(50) NOT NULL default 'cn',
  `title` varchar(200) NOT NULL default '',
  `content` text NOT NULL,
  `sendhow` int(10) unsigned NOT NULL default '0',
  `sendtime` int(11) unsigned NOT NULL default '0',
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `addtime` int(11) NOT NULL default '0',
  PRIMARY KEY  (`msid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_mailsend_log` (
  `mlid` int(11) unsigned NOT NULL auto_increment,
  `msid` int(11) unsigned NOT NULL default '0',
  `sendmail` text NOT NULL,
  `sendtime` int(11) unsigned NOT NULL default '0',
  UNIQUE KEY `mlid` (`mlid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_member` (
  `userid` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(250) NOT NULL,
  `question` varchar(250) NOT NULL,
  `answer` varchar(250) NOT NULL,
  `sex` tinyint(1) unsigned NOT NULL default '0',
  `birthday` int(11) unsigned NOT NULL default '0',
  `country` smallint(5) unsigned NOT NULL default '0',
  `province` smallint(5) unsigned NOT NULL default '0',
  `city` smallint(5) unsigned NOT NULL default '0',
  `district` smallint(5) unsigned NOT NULL default '0',
  `alias` varchar(250) NOT NULL,
  `address` varchar(250) NOT NULL,
  `zipcode` varchar(20) NOT NULL default '0',
  `tel` varchar(30) NOT NULL,
  `mobile` varchar(11) NOT NULL default '0',
  `qq` int(11) unsigned NOT NULL default '0',
  `msn` varchar(150) NOT NULL,
  `integral` int(10) unsigned NOT NULL default '0',
  `visitcount` smallint(6) unsigned NOT NULL default '0',
  `lastip` varchar(11) NOT NULL default '0',
  `addtime` int(11) unsigned NOT NULL default '0',
  `lasttime` int(11) unsigned NOT NULL default '0',
  `mcid` smallint(2) unsigned NOT NULL default '1',
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `ismoblie` tinyint(1) unsigned NOT NULL default '0',
  `mobliesn` varchar(10) NOT NULL,
  `mobliesntime` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`userid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_member_attr` (
  `maid` int(11) unsigned NOT NULL auto_increment,
  `lng` varchar(50) NOT NULL default '',
  `pid` smallint(6) unsigned NOT NULL default '50',
  `typename` varchar(150) NOT NULL default '',
  `typeremark` varchar(200) NOT NULL,
  `attrname` varchar(150) NOT NULL default '',
  `inputtype` varchar(20) NOT NULL default 'string',
  `attrvalue` text NOT NULL,
  `validatetext` varchar(150) NOT NULL default '',
  `attrsize` smallint(3) unsigned NOT NULL default '20',
  `attrrow` smallint(3) unsigned NOT NULL default '10',
  `attrlenther` smallint(3) unsigned NOT NULL default '50',
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `isvalidate` tinyint(1) unsigned NOT NULL default '0',
  `isline` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`maid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_member_class` (
  `mcid` int(10) unsigned NOT NULL auto_increment,
  `rankname` varchar(150) NOT NULL,
  `isinter` tinyint(1) NOT NULL default '0',
  `integra` int(11) NOT NULL default '100',
  `isclass` tinyint(1) NOT NULL default '1',
  `lockin` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`mcid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_member_value` (
  `mvid` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`mvid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_menulink` (
  `mlid` int(10) unsigned NOT NULL auto_increment,
  `pid` smallint(6) unsigned NOT NULL default '50',
  `type` smallint(6) unsigned NOT NULL default '0',
  `topmlid` smallint(6) unsigned NOT NULL default '0',
  `menuname` varchar(100) NOT NULL default '',
  `linkurl` varchar(200) NOT NULL default '',
  `isshow` tinyint(1) unsigned NOT NULL default '1',
  `loadfun` varchar(30) NOT NULL,
  `classname` varchar(50) NOT NULL,
  `ismenu` tinyint(1) unsigned NOT NULL default '0',
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`mlid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_moblie_list` (
  `moblid` int(11) unsigned NOT NULL auto_increment,
  `mobtid` int(11) unsigned NOT NULL default '0',
  `moblienb` varchar(11) NOT NULL default '0',
  `mobliename` varchar(30) NOT NULL,
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `addtime` int(11) unsigned NOT NULL default '0',
  UNIQUE KEY `moblid` (`moblid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_moblie_type` (
  `mobtid` int(11) unsigned NOT NULL auto_increment,
  `moblietypename` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `addtime` int(11) unsigned NOT NULL default '0',
  UNIQUE KEY `mbtid` (`mobtid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_model` (
  `mid` int(11) NOT NULL auto_increment,
  `lng` varchar(50) NOT NULL default 'cn',
  `modelname` varchar(150) NOT NULL,
  `pagemax` smallint(3) unsigned NOT NULL default '20',
  `tsnstyle` varchar(200) NOT NULL default 'SN{datetime}{s}',
  `listtitlestyle` varchar(200) NOT NULL default '{typename}-{sitename}',
  `readtitlestyle` varchar(200) NOT NULL default '{title}-{typename}-{sitename}',
  `pagesylte` tinyint(1) unsigned NOT NULL default '1',
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `lockin` tinyint(1) unsigned NOT NULL default '1',
  `isbase` tinyint(1) unsigned NOT NULL default '0',
  `istsn` tinyint(1) unsigned NOT NULL default '1',
  `isalbum` tinyint(1) unsigned NOT NULL default '1',
  `isextid` tinyint(1) unsigned NOT NULL default '1',
  `issid` tinyint(1) unsigned NOT NULL default '1',
  `isfgid` tinyint(1) unsigned NOT NULL default '1',
  `islinkdid` tinyint(1) unsigned NOT NULL default '1',
  `isorder` tinyint(1) unsigned NOT NULL default '1',
  `ismessage` tinyint(1) unsigned NOT NULL default '1',
  `ispurview` tinyint(1) unsigned NOT NULL default '1',
  `addtime` int(10) unsigned NOT NULL,
  `opid` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`mid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `esp_model_att` (
  `aid` int(11) NOT NULL auto_increment,
  `pid` smallint(5) unsigned NOT NULL default '0',
  `mid` smallint(5) unsigned NOT NULL default '0',
  `typename` varchar(100) NOT NULL default '',
  `typeremark` varchar(200) NOT NULL default '',
  `attrname` varchar(100) NOT NULL default '',
  `inputtype` varchar(15) NOT NULL default 'string',
  `attrvalue` text NOT NULL,
  `attrsize` smallint(3) unsigned NOT NULL default '20',
  `attrrow` smallint(3) unsigned NOT NULL default '5',
  `attrlenther` smallint(3) unsigned NOT NULL default '50',
  `isvalidate` tinyint(1) unsigned NOT NULL default '0',
  `validatetext` varchar(150) NOT NULL default '',
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `issearch` tinyint(1) unsigned NOT NULL default '0',
  `lockin` tinyint(1) unsigned NOT NULL default '0',
  `islockin` tinyint(1) unsigned NOT NULL default '0',
  `issys` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_order` (
  `oid` int(10) unsigned NOT NULL auto_increment,
  `ordersn` varchar(50) NOT NULL,
  `userid` int(10) unsigned NOT NULL default '0',
  `ordertype` tinyint(1) unsigned NOT NULL default '0',
  `osid` smallint(6) unsigned NOT NULL default '0',
  `opid` smallint(6) unsigned NOT NULL default '0',
  `shippingsn` varchar(50) NOT NULL default '0',
  `paysn` varchar(50) NOT NULL default '0',
  `consignee` varchar(50) NOT NULL,
  `country` smallint(5) NOT NULL default '1',
  `province` smallint(5) NOT NULL default '0',
  `city` smallint(5) NOT NULL default '0',
  `district` smallint(5) NOT NULL default '0',
  `address` varchar(255) NOT NULL,
  `zipcode` varchar(20) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sendtime` varchar(50) NOT NULL,
  `invpayee` varchar(100) NOT NULL,
  `invcontent` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `treatnote` text NOT NULL,
  `paytime` int(11) NOT NULL default '0',
  `shippingtime` int(11) NOT NULL default '0',
  `productmoney` decimal(10,2) NOT NULL default '0.00',
  `shippingmoney` decimal(10,2) NOT NULL default '0.00',
  `paymoney` decimal(10,2) NOT NULL default '0.00',
  `orderamount` decimal(10,2) NOT NULL default '0.00',
  `discount` decimal(10,2) NOT NULL default '0.00',
  `integral` int(10) NOT NULL default '0',
  `addtime` int(11) NOT NULL default '0',
  PRIMARY KEY  (`oid`),
  UNIQUE KEY `ordersn` (`ordersn`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_order_info` (
  `oiid` int(11) NOT NULL auto_increment,
  `oid` int(10) unsigned NOT NULL,
  `did` int(10) unsigned NOT NULL,
  `tsn` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `oprice` decimal(10,2) unsigned NOT NULL default '0.00',
  `bprice` decimal(10,2) unsigned NOT NULL default '0.00',
  `countprice` decimal(10,2) NOT NULL default '0.00',
  `amount` smallint(6) unsigned NOT NULL,
  `inventory` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`oiid`),
  KEY `orderid` (`oid`),
  KEY `productid` (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_order_pay` (
  `opid` int(11) NOT NULL auto_increment,
  `pid` int(11) NOT NULL default '50',
  `payname` varchar(150) NOT NULL,
  `paycontent` text NOT NULL,
  `paycode` varchar(20) NOT NULL,
  `payis` smallint(5) NOT NULL default '0',
  `payplugver` varchar(20) NOT NULL default '1.0',
  `pluglist` text NOT NULL,
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `addtime` int(11) NOT NULL default '0',
  PRIMARY KEY  (`opid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_order_payreceipt` (
  `oprid` int(10) unsigned NOT NULL auto_increment,
  `oid` int(11) NOT NULL default '0',
  `opid` int(11) NOT NULL default '0',
  `paysn` varchar(100) NOT NULL default '0',
  `ordersn` varchar(100) NOT NULL default '0',
  `orderamount` decimal(10,2) NOT NULL default '0.00',
  `bankaccount` varchar(100) NOT NULL default '',
  `bankname` varchar(100) NOT NULL default '',
  `username` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `userid` int(11) NOT NULL default '0',
  `isclass` tinyint(1) NOT NULL default '1',
  `paytime` int(11) NOT NULL default '0',
  `addtime` int(11) NOT NULL default '0',
  PRIMARY KEY  (`oprid`),
  KEY `ordersn` (`ordersn`),
  KEY `orderid` (`oid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_order_shipping` (
  `osid` int(11) NOT NULL auto_increment,
  `pid` int(10) NOT NULL default '50',
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `price` decimal(10,2) unsigned NOT NULL default '0.00',
  `markup` int(10) NOT NULL,
  `isclass` tinyint(1) unsigned NOT NULL default '0',
  `iscash` tinyint(1) unsigned NOT NULL default '0',
  `isinsure` tinyint(1) NOT NULL default '0',
  `insureper` smallint(6) NOT NULL default '0',
  `addtime` int(11) NOT NULL default '0',
  PRIMARY KEY  (`osid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_order_shipreceipt` (
  `osrid` int(10) unsigned NOT NULL auto_increment,
  `oid` int(11) NOT NULL default '0',
  `osid` int(11) NOT NULL default '0',
  `shippingsn` varchar(20) NOT NULL default '0',
  `ordersn` varchar(20) NOT NULL default '0',
  `shippingmoney` decimal(10,2) NOT NULL,
  `username` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `userid` int(11) NOT NULL,
  `isclass` tinyint(1) NOT NULL default '1',
  `addtime` int(11) NOT NULL default '0',
  PRIMARY KEY  (`osrid`),
  KEY `ordersn` (`ordersn`),
  KEY `orderid` (`oid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_site` (
  `slid` int(11) unsigned NOT NULL auto_increment,
  `pid` smallint(5) unsigned NOT NULL default '50',
  `sitename` varchar(100) NOT NULL,
  `sitehttp` varchar(100) NOT NULL,
  `sitedir` varchar(30) NOT NULL default 'adminsoft',
  `sitecode` varchar(100) NOT NULL,
  `siteip` varchar(50) NOT NULL,
  `adminusername` varchar(50) NOT NULL,
  `addtime` int(11) NOT NULL default '0',
  UNIQUE KEY `slid` (`slid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_skin` (
  `skid` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `code` varchar(20) NOT NULL,
  `lockin` tinyint(1) unsigned NOT NULL default '0',
  `isclass` tinyint(1) unsigned NOT NULL default '0',
  `addtime` int(11) unsigned NOT NULL,
  `iswap` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`skid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_smssendlist` (
  `smsid` int(11) unsigned NOT NULL auto_increment,
  `content` varchar(200) NOT NULL,
  `moblielist` text NOT NULL,
  `issendtype` tinyint(1) unsigned NOT NULL default '1',
  `mobtid` int(11) unsigned NOT NULL default '0',
  `sendhow` tinyint(1) unsigned NOT NULL default '1',
  `sendtime` int(11) unsigned NOT NULL default '0',
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `addtime` int(11) unsigned NOT NULL default '0',
  UNIQUE KEY `smsid` (`smsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `esp_subjectlist` (
  `sid` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `mid` smallint(5) unsigned NOT NULL default '0',
  `lng` varchar(50) NOT NULL default 'cn',
  `subjectname` varchar(150) NOT NULL,
  `headtitle` varchar(200) NOT NULL,
  `keywords` varchar(80) NOT NULL,
  `description` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `subpic` varchar(200) NOT NULL default '',
  `dirname` varbinary(150) NOT NULL default '',
  `purview` tinyint(1) unsigned NOT NULL default '1',
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `styleid` tinyint(1) unsigned NOT NULL default '1',
  `ishtml` tinyint(1) unsigned NOT NULL default '1',
  `iswap` tinyint(1) unsigned NOT NULL default '0',
  `waptempalte` varchar(100) NOT NULL,
  `indextemplates` varchar(100) NOT NULL,
  `template` varchar(100) NOT NULL,
  `filenamestyle` varchar(100) NOT NULL,
  `dirpath` varchar(200) NOT NULL,
  `pagemax` smallint(5) unsigned NOT NULL default '0',
  `addtime` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_templates` (
  `tmid` int(11) unsigned NOT NULL auto_increment,
  `lng` varchar(50) NOT NULL default 'cn',
  `templatename` varchar(200) NOT NULL,
  `templatecode` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL default '',
  `templatecontent` text NOT NULL,
  `pic` varchar(50) NOT NULL default '',
  `typeclass` varchar(15) NOT NULL default '',
  `styleclass` tinyint(1) unsigned NOT NULL,
  `lockin` tinyint(1) unsigned NOT NULL default '1',
  `addtime` int(11) unsigned NOT NULL,
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`tmid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `esp_typelist` (
  `tid` int(10) unsigned NOT NULL auto_increment,
  `pid` smallint(5) unsigned NOT NULL default '50',
  `mid` smallint(5) unsigned NOT NULL default '0',
  `topid` smallint(5) unsigned NOT NULL default '0',
  `upid` smallint(5) unsigned NOT NULL default '0',
  `exmid` smallint(5) unsigned NOT NULL default '0',
  `linkid` int(11) unsigned NOT NULL default '0',
  `gotoid` int(11) unsigned NOT NULL default '0',
  `lng` varchar(50) NOT NULL default 'cn',
  `typename` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `headtitle` varchar(200) NOT NULL,
  `keywords` varchar(80) NOT NULL default '',
  `description` varchar(180) NOT NULL,
  `typepic` varchar(200) NOT NULL,
  `dirname` varchar(50) NOT NULL default '',
  `purview` tinyint(1) unsigned NOT NULL default '0',
  `ismenu` tinyint(1) unsigned NOT NULL default '0',
  `isaccessory` tinyint(1) unsigned NOT NULL default '0',
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `ispart` tinyint(1) unsigned NOT NULL default '1',
  `styleid` tinyint(1) unsigned NOT NULL default '1',
  `pageclass` tinyint(1) unsigned NOT NULL default '1',
  `indextemplates` varchar(100) NOT NULL,
  `template` varchar(100) NOT NULL,
  `readtemplate` varchar(100) NOT NULL,
  `filenamestyle` varchar(100) NOT NULL default '',
  `readnamestyle` varchar(100) NOT NULL,
  `isline` tinyint(1) unsigned NOT NULL default '1',
  `gotoline` int(11) unsigned NOT NULL default '0',
  `typeurl` varchar(200) NOT NULL,
  `dirpath` varchar(150) NOT NULL,
  `iswap` tinyint(1) unsigned NOT NULL default '0',
  `waptempalte` varchar(100) NOT NULL,
  `wapreadtemplate` varchar(100) NOT NULL,
  `pagemax` smallint(5) unsigned NOT NULL default '0',
  `addtime` int(15) unsigned NOT NULL default '0',
  `isorderby` tinyint(1) unsigned NOT NULL default '1',
  `ordertype` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

