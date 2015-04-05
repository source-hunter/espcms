
CREATE TABLE IF NOT EXISTS `esp_advert` (
  `adid` int(11) unsigned NOT NULL auto_increment,
  `pid` int(11) unsigned NOT NULL default '0',
  `atid` int(11) unsigned NOT NULL default '0',
  `lng` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `filename` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `adtype` tinyint(1) unsigned NOT NULL default '1',
  `click` int(11) unsigned NOT NULL default '0',
  `istime` tinyint(1) unsigned NOT NULL default '0',
  `starttime` int(11) unsigned NOT NULL default '0',
  `endtime` int(11) unsigned NOT NULL default '0',
  `addtime` int(11) unsigned NOT NULL default '0',
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `islink` tinyint(1) unsigned NOT NULL default '1',
  `gotoid` int(11) unsigned NOT NULL default '0',
  UNIQUE KEY `adid` (`adid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `esp_advert_type` (
  `atid` int(11) unsigned NOT NULL auto_increment,
  `lng` varchar(50) NOT NULL default '',
  `adtypename` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `width` int(4) unsigned NOT NULL default '0',
  `height` int(4) unsigned NOT NULL default '0',
  `isclass` tinyint(1) unsigned NOT NULL default '1',
  `iscode` tinyint(1) unsigned NOT NULL default '0',
  `isxml` tinyint(1) unsigned NOT NULL default '0',
  `xmltemplatefile` varchar(200) NOT NULL,
  `xmlfile` varchar(200) NOT NULL,
  `xmlpath` varchar(200) NOT NULL,
  UNIQUE KEY `adid` (`atid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `esp_advert_type` (`atid`, `lng`, `adtypename`, `content`, `width`, `height`, `isclass`, `iscode`, `isxml`, `xmltemplatefile`, `xmlfile`, `xmlpath`) VALUES
(1, 'cn', '首页幻灯片', '', 0, 0, 1, 0, 0, '', '', ''),
(2, 'cn', '首页右下角广告', '', 0, 0, 1, 0, 0, '', '', ''),
(3, 'cn', '首页文字友情链接', '', 0, 0, 1, 0, 0, '', '', ''),
(4, 'cn', '首页图片友情链接', '', 0, 0, 1, 0, 0, '', '', ''),
(5, 'cn', '手机网站BANN', '', 0, 0, 1, 0, 0, '', '', ''),
(6, 'en', '首页广告位', '', 0, 0, 1, 0, 0, '', '', ''),
(7, 'en', '首页右下解广告位', '', 0, 0, 1, 0, 0, '', '', '');

INSERT INTO `esp_advert` (`adid`, `pid`, `atid`, `lng`, `title`, `filename`, `url`, `content`, `adtype`, `click`, `istime`, `starttime`, `endtime`, `addtime`, `isclass`, `islink`, `gotoid`) VALUES
(1, 50, 1, 'cn', 'ESPCMS优秀建站平台', 'upfile/03.jpg', 'http://www.ecisp.cn', '', 1, 0, 0, 1404576000, 1404576000, 1404631468, 1, 1, 0),
(2, 50, 1, 'cn', '企业建站系统首选', 'upfile/01.jpg', 'http://www.espcms.com', '', 1, 0, 0, 1404576000, 1404576000, 1404631485, 1, 1, 0),
(3, 1, 2, 'cn', '主动关怀，价值服务', 'upfile/b2.jpg', 'http://www.espcms.com', '', 1, 0, 0, 1404576000, 1404576000, 1404643543, 1, 1, 0),
(4, 2, 2, 'cn', '联系我们', 'upfile/b1.jpg', 'http://', '', 1, 0, 0, 1404576000, 1404576000, 1404643559, 1, 2, 6),
(5, 50, 4, 'cn', '企业建站服务', 'upfile/espcmsweiwz.jpg', 'http://www.espcms.cn', '', 1, 0, 0, 1404576000, 1404576000, 1404643629, 1, 1, 0),
(6, 50, 3, 'cn', '企业建站服务', '', 'http://www.ecisp.cn', '', 3, 0, 0, 1404576000, 1404576000, 1404643682, 1, 1, 0),
(7, 50, 3, 'cn', '微信应用开发', '', 'http://www.ecisp.cn', '', 3, 0, 0, 1404576000, 1404576000, 1404643692, 1, 1, 0),
(8, 50, 3, 'cn', '企业网页设计', '', 'http://www.ecisp.cn', '', 3, 0, 0, 1404576000, 1404576000, 1404643703, 1, 1, 0),
(9, 50, 3, 'cn', 'KUBCMS门户网站', '', 'http://www.kubcms.com', '', 3, 0, 0, 1404576000, 1404576000, 1404643716, 1, 1, 0),
(10, 50, 3, 'cn', '深圳建站', '', 'http://www.linlingo.com', '', 3, 0, 0, 1404576000, 1404576000, 1404643736, 1, 1, 0),
(11, 50, 3, 'cn', '手机APP开发', '', 'http://www.ecisp.cn', '', 3, 0, 0, 1404576000, 1404576000, 1404643755, 1, 1, 0),
(12, 50, 3, 'cn', '商城开发', '', 'http://www.espcms.cn', '', 3, 0, 0, 1404576000, 1404576000, 1404643775, 1, 1, 0),
(13, 50, 3, 'cn', '微网站设计', '', 'http://www.espcms.net', '', 3, 0, 0, 1404576000, 1404576000, 1404643796, 1, 1, 0),
(14, 50, 3, 'cn', '手机网站建设', '', 'http://www.espcms.com', '', 3, 0, 0, 1404576000, 1404576000, 1404643808, 1, 1, 0),
(15, 50, 3, 'cn', 'ESPCMS软件购买', '', 'http://www.ecisp.cn', '', 3, 0, 0, 1404576000, 1404576000, 1404643836, 1, 1, 0),
(16, 50, 3, 'cn', 'ESPCMS交流论坛', '', 'http://bbs.ecisp.cn', '', 3, 0, 0, 1404576000, 1404576000, 1404643855, 1, 1, 0),
(17, 50, 4, 'cn', '令购网络', 'upfile/lengo.jpg', 'http://www.linlingo.com', '', 1, 0, 0, 1404576000, 1404576000, 1404643923, 1, 1, 0),
(18, 50, 4, 'cn', 'KUBCMS门户系统', 'upfile/kubcmslogo.jpg', 'http://www.kubcms.com', '', 1, 0, 0, 1404576000, 1404576000, 1404643937, 1, 1, 0),
(19, 50, 4, 'cn', 'ESPCMS交流论坛', 'upfile/espcmslogo.jpg', 'http://bbs.ecisp.cn', '', 1, 0, 0, 1404576000, 1404576000, 1404643959, 1, 1, 0),
(20, 50, 4, 'cn', '手机APP开发', 'upfile/espcmsapplogo.jpg', 'http://www.espcms.cn', '', 1, 0, 0, 1404576000, 1404576000, 1404643980, 1, 1, 0),
(21, 50, 5, 'cn', 'ESPCMS优秀建站平台', 'upfile/moblie/bannn002.jpg', 'http://www.ecisp.cn', '', 1, 0, 0, 1404576000, 1404576000, 1404661597, 1, 1, 0),
(22, 50, 5, 'cn', 'ESPCMS智能建站平台', 'upfile/moblie/bannn001.jpg', 'http://www.espcms.com', '', 1, 0, 0, 1404576000, 1404576000, 1404661615, 1, 1, 0),
(23, 50, 6, 'en', 'ESPCMS', 'upfile/03_en.jpg', 'http://www.ecisp.cn', '', 1, 0, 0, 1404662400, 1404662400, 1404664693, 1, 1, 0),
(24, 50, 7, 'en', 'ESPCMS', 'upfile/b1_en.jpg', 'http://www.ecisp.cn', '', 1, 0, 0, 1404662400, 1404662400, 1404667630, 1, 1, 0),
(25, 50, 7, 'en', 'ESPCMS', 'upfile/b2_en.jpg', 'http://www.espcms.com', '', 1, 0, 0, 1404662400, 1404662400, 1404667643, 1, 1, 0);

