

CREATE TABLE `db_access_token` (
  `id` int(4) NOT NULL auto_increment,
  `access_token` varchar(255) collate utf8_unicode_ci NOT NULL,
  `create_time` int(10) NOT NULL,
  `date` date NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


