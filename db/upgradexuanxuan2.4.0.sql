ALTER TABLE `zt_im_messagestatus` ADD `message` INT(11)  UNSIGNED  NOT NULL  AFTER `user`;

CREATE TABLE IF NOT EXISTS `zt_im_xxcversion` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `version` char(10) NOT NULL DEFAULT '',
  `desc` varchar(100) NOT NULL DEFAULT '',
  `readme` text NOT NULL,
  `strategy` varchar(10) NOT NULL DEFAULT '',
  `downloads` text NOT NULL,
  `createdDate` datetime NOT NULL,
  `createdBy` varchar(30) NOT NULL DEFAULT '',
  `editedDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
