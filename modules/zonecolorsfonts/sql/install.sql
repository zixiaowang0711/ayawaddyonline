CREATE TABLE IF NOT EXISTS `PREFIX_zcolorsfonts` (
    `id_zcolorsfonts` int(11) NOT NULL AUTO_INCREMENT,
	`id_shop` int(10) unsigned NOT NULL DEFAULT 1,
	`general` text DEFAULT NULL,
	`header` text DEFAULT NULL,
	`footer` text DEFAULT NULL,
	`content` text DEFAULT NULL,
	`product` text DEFAULT NULL,
    `fonts_import` text DEFAULT NULL,
	`fonts` varchar(254) DEFAULT NULL,
	`custom_css` text DEFAULT NULL,
    PRIMARY KEY  (`id_zcolorsfonts`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;