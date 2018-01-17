CREATE TABLE IF NOT EXISTS `PREFIX_zcolumnblock` (
	`id_zcolumnblock` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`id_shop` int(10) unsigned NOT NULL DEFAULT 1,
	`active` tinyint(1) unsigned NOT NULL DEFAULT 1,
	`position` int(10) unsigned NOT NULL DEFAULT 0,
	`block_type` varchar(128) NOT NULL,
	`custom_class` varchar(50) DEFAULT NULL,
	`product_filter` varchar(128) NOT NULL,
	`product_options` text DEFAULT NULL,
	PRIMARY KEY (`id_zcolumnblock`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_zcolumnblock_lang` (
	`id_zcolumnblock` int(10) unsigned NOT NULL,
	`id_lang` int(10) unsigned NOT NULL,
	`title` varchar(254) DEFAULT NULL,
	`static_html` text DEFAULT NULL,
	PRIMARY KEY (`id_zcolumnblock`,`id_lang`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;