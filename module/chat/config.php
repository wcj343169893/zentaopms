<?php
$config->chat->logLine = 20;

$config->chat->require = new stdclass();
$config->chat->require->create = 'gid, name, type';
$config->chat->require->edit   = 'gid, name, type';

$config->chat->user = new stdclass();
$config->chat->user->canEditFields = array('avatar', 'birthday', 'gender', 'email', 'skype', 'qq', 'mobile', 'phone', 'address', 'zipcode', 'clientStatus');

$config->chat->retract = new stdclass();
$config->chat->retract->validTime = 2;

$config->chat->xxdDownloadUrl = "http://dl.cnezsoft.com/xuanxuan/";

$config->chat->xxdConfig = array('ip', 'commonPort', 'chatPort', 'https', 'uploadPath', 'uploadFileSize', 'pollingInterval', 'maxOnlineUser', 'logPath', 'certPath', 'debug', 'host', 'key');
