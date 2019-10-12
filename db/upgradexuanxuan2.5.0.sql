UPDATE `zt_config` SET `key` = 'https' WHERE `owner` = 'system' AND `module` = 'common' AND `section` = 'xuanxuan' AND `key` = 'isHttps';
UPDATE `zt_config` SET `key` = 'backendLang' WHERE `owner` = 'system' AND `module` = 'common' AND `section` = 'xuanxuan' AND `key` = 'xxbLang';

UPDATE `zt_grouppriv` SET `module` = 'client', `method` = 'browse' WHERE `module` = 'setting' AND `method` = 'xxcversion';
UPDATE `zt_grouppriv` SET `module` = 'client', `method` = 'create' WHERE `module` = 'setting' AND `method` = 'createxxcversion';
UPDATE `zt_grouppriv` SET `module` = 'client', `method` = 'edit'   WHERE `module` = 'setting' AND `method` = 'editxxcversion';
UPDATE `zt_grouppriv` SET `module` = 'client', `method` = 'delete' WHERE `module` = 'setting' AND `method` = 'deletexxcversion';

RENAME TABLE `zt_im_xxcversion` TO `zt_im_client`;

ALTER TABLE `zt_im_client` CHANGE `readme` `changeLog` text NOT NULL;
ALTER TABLE `zt_im_client` ADD `status` ENUM('released','wait')  NOT NULL  DEFAULT 'wait'  AFTER `editedBy`;
