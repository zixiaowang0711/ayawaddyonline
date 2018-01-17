CREATE TABLE IF NOT EXISTS `PREFIX_zmenu` (
	`id_zmenu` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`id_shop` int(10) unsigned NOT NULL DEFAULT 1,
	`active` tinyint(1) unsigned NOT NULL DEFAULT 1,
	`position` int(10) unsigned NOT NULL DEFAULT 0,
	`label_color` varchar(32) DEFAULT NULL,
	`drop_column` int(10) DEFAULT 0,
	`drop_bgcolor` varchar(32) DEFAULT NULL,
	`drop_bgimage` varchar(128) DEFAULT NULL,
	`bgimage_position` varchar(50) DEFAULT NULL,
	`position_x` int(10) DEFAULT 0,
	`position_y` int(10) DEFAULT 0,
	PRIMARY KEY (`id_zmenu`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_zmenu_lang` (
	`id_zmenu` int(10) unsigned NOT NULL,
	`id_lang` int(10) unsigned NOT NULL,
	`name` varchar(254) NOT NULL,
	`link` varchar(254) NOT NULL DEFAULT '',
	`label` varchar(128) DEFAULT NULL,
	PRIMARY KEY (`id_zmenu`,`id_lang`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_zdropdown` (
	`id_zdropdown` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`id_zmenu` int(10) unsigned NOT NULL,
	`active` tinyint(1) unsigned NOT NULL DEFAULT 1,
	`position` int(10) unsigned NOT NULL DEFAULT 0,
	`column` int(10) DEFAULT 0,
	`custom_class` varchar(254) DEFAULT NULL,
	`content_type` varchar(50) NOT NULL,
	`categories` text DEFAULT NULL,
	`products` text DEFAULT NULL,
	`manufacturers` text DEFAULT NULL,
	PRIMARY KEY (`id_zdropdown`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_zdropdown_lang` (
	`id_zdropdown` int(10) unsigned NOT NULL,
	`id_lang` int(10) unsigned NOT NULL,
	`static_content` text DEFAULT NULL,
	PRIMARY KEY (`id_zdropdown`,`id_lang`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;