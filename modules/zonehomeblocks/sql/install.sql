CREATE TABLE IF NOT EXISTS `PREFIX_zhomeblock` (
	`id_zhomeblock` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`id_shop` int(10) unsigned NOT NULL DEFAULT 1,
	`active` tinyint(1) unsigned NOT NULL DEFAULT 1,
	`position` int(10) unsigned NOT NULL DEFAULT 0,
	`hook` varchar(128) DEFAULT NULL,
	`block_type` varchar(128) DEFAULT NULL,
	`custom_class` varchar(50) DEFAULT NULL,
	`product_filter` varchar(128) DEFAULT NULL,
	`product_options` text DEFAULT NULL,
	PRIMARY KEY (`id_zhomeblock`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_zhomeblock_lang` (
	`id_zhomeblock` int(10) unsigned NOT NULL,
	`id_lang` int(10) unsigned NOT NULL,
	`title` varchar(254) DEFAULT NULL,
	`static_html` text DEFAULT NULL,
	PRIMARY KEY (`id_zhomeblock`,`id_lang`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_zhometab` (
	`id_zhometab` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`id_zhomeblock` int(10) unsigned NOT NULL,
	`active` tinyint(1) unsigned NOT NULL DEFAULT 1,
	`position` int(10) unsigned NOT NULL DEFAULT 0,
	`block_type` varchar(128) DEFAULT NULL,
	`product_filter` varchar(128) DEFAULT NULL,
	`product_options` text DEFAULT NULL,
	PRIMARY KEY (`id_zhometab`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_zhometab_lang` (
	`id_zhometab` int(10) unsigned NOT NULL,
	`id_lang` int(10) unsigned NOT NULL,
	`title` varchar(254) DEFAULT NULL,
	`static_html` text DEFAULT NULL,
	PRIMARY KEY (`id_zhometab`,`id_lang`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;