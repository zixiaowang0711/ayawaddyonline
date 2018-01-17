CREATE TABLE IF NOT EXISTS `PREFIX_zpopupnewsletter` (
    `id_zpopupnewsletter` int(11) NOT NULL AUTO_INCREMENT,
	`id_shop` int(10) unsigned NOT NULL DEFAULT 1,
	`active` tinyint(1) unsigned NOT NULL DEFAULT 1,
	`width` int(10) unsigned NOT NULL DEFAULT 0,
	`height` int(10) unsigned NOT NULL DEFAULT 0,
	`bg_color` varchar(50) DEFAULT NULL,
	`bg_image` varchar(100) DEFAULT NULL,
	`cookie_time` int(10) unsigned NOT NULL DEFAULT 0,
	`save_time` int(10) DEFAULT 0,
    PRIMARY KEY  (`id_zpopupnewsletter`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_zpopupnewsletter_lang` (
	`id_zpopupnewsletter` int(10) unsigned NOT NULL,
	`id_lang` int(10) unsigned NOT NULL,
	`content` text DEFAULT NULL,
	PRIMARY KEY (`id_zpopupnewsletter`,`id_lang`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;