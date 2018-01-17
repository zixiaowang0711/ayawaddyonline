CREATE TABLE IF NOT EXISTS `PREFIX_zthememanager` (
    `id_zthememanager` int(11) NOT NULL AUTO_INCREMENT,
	`id_shop` int(10) unsigned NOT NULL DEFAULT 1,
	`general_settings` text DEFAULT NULL,
	`category_settings` text DEFAULT NULL,
	`product_settings` text DEFAULT NULL,
	`svg_logo` text DEFAULT NULL,
	`header_top_bg_color` varchar(50) DEFAULT NULL,
	`footer_cms_links` varchar(254) DEFAULT NULL,
	`header_save_date` int(10) DEFAULT 0,
    PRIMARY KEY  (`id_zthememanager`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_zthememanager_lang` (
	`id_zthememanager` int(10) unsigned NOT NULL,
	`id_lang` int(10) unsigned NOT NULL,
	`header_top` text DEFAULT NULL,
	`header_phone` text DEFAULT NULL,
	`footer_about_us` text DEFAULT NULL,
	`footer_static_links` text DEFAULT NULL,
	`footer_bottom` text DEFAULT NULL,
	PRIMARY KEY (`id_zthememanager`,`id_lang`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;