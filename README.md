INFORMATION TRACKER


-- 主机: 59.188.181.137
-- 生成日期: 2014 年 09 月 05 日 17:01
-- 服务器版本: 5.0.95
-- PHP 版本: 5.2.6
-- 
-- 数据库: `sq_jooime`
-- 

-- --------------------------------------------------------

-- 
-- 表的结构 `imgt_project`
-- 

CREATE TABLE `imgt_project` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` text NOT NULL,
  `type` int(11) NOT NULL default '0',
  `description` text NOT NULL,
  `datetime` datetime NOT NULL,
  `value` longtext NOT NULL,
  `authid` text NOT NULL,
  `filter` int(11) NOT NULL default '0',
  `img_type` text NOT NULL,
  `height` int(11) NOT NULL default '1',
  `width` int(11) NOT NULL default '1',
  `bg_color` text NOT NULL,
  `logging` int(11) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `id_2` (`id`),
  KEY `id_3` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

-- 
-- 导出表中的数据 `imgt_project`
-- 


-- --------------------------------------------------------

-- 
-- 表的结构 `imgt_trackinfo`
-- 

CREATE TABLE `imgt_trackinfo` (
  `id` bigint(20) NOT NULL auto_increment,
  `proj_id` bigint(20) NOT NULL,
  `content` longtext NOT NULL,
  `track_url` text,
  `domain` text,
  `ip` text NOT NULL,
  `datetime` datetime NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1533 DEFAULT CHARSET=latin1 AUTO_INCREMENT=1533 ;

-- 
-- 导出表中的数据 `imgt_trackinfo`
-- 
