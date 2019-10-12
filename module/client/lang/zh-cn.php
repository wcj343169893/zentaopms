<?php
$lang->client->common       = '版本更新';
$lang->client->create       = '手动添加版本';
$lang->client->browse       = '版本更新列表';
$lang->client->edit         = '编辑版本';
$lang->client->delete       = '删除版本';
$lang->client->checkUpgrade = '检查更新';
$lang->client->set          = '参数设置';
$lang->client->xxdStatus    = 'XXD状态';
$lang->client->polling      = '轮询间隔';
$lang->client->xxdRunTime   = 'XXD运行时间';

$lang->client->id              = 'ID';
$lang->client->version         = '版本';
$lang->client->update          = '更新';
$lang->client->xxcVersion      = 'XXC版本';
$lang->client->main            = '重要更新内容';
$lang->client->createdDate     = '发布日期';
$lang->client->desc            = '升级描述';
$lang->client->see             = '查看';
$lang->client->changeLog       = '更新日志';
$lang->client->strategy        = '升级策略';
$lang->client->download        = '下载';
$lang->client->downloading     = '下载中...';
$lang->client->downloadLink    = '下载地址';
$lang->client->downloadFail    = '下载失败，' . $lang->client->downloadLink;
$lang->client->downloadSuccess = '下载成功';
$lang->client->releaseStatus   = '发布状态';
$lang->client->wrongVersion    = '<strong>版本</strong>格式不正确，例如：2.5 或者 2.5.0';
$lang->client->downloadTip     = '下载后请检查压缩包完整性，如果压缩包损坏，请使用其它工具下载后上传至 www/data/client 对应的目录下';
$lang->client->versionError    = '获取更新信息异常，请稍后再试，或者联系喧喧官方客服。';

$lang->client->noData                 = '暂无';
$lang->client->saveClientError        = '无法保持更新信息。';
$lang->client->downloadErrorTip       = '请使用其它工具下载后上传至';
$lang->client->downloadFailedTip      = '无法下载安装包，请稍后重新尝试下载，或者使用其它工具下载后上传至 www/data/client 对应的目录下。';
$lang->client->alreadyLastestVersion  = '当前已是最新版本';
$lang->client->cannotUseUpdateServer  = '无法获取官方版本信息';
$lang->client->foundNewVersion        = '发现新版本客户端';
$lang->client->currentVersion         = '当前版本';
$lang->client->upgradeToThisVersion   = '升级到此版本';
$lang->client->downloadClientPackages = '选择用于升级的安装包';
$lang->client->publishUpdate          = '立即发布更新';
$lang->client->saveUpdate             = '仅保存更新';
$lang->client->selectUpgradeStrategy  = '选择升级策略';
$lang->client->or                     = '或';
$lang->client->inCatalog              = '目录下。';
$lang->client->fileNameIs             = '安装包文件名为';
$lang->client->fileSize               = '附件大小';
$lang->client->goUpdate               = '去更新';
$lang->client->countUsers             = '当前在线用户数';
$lang->client->setServer              = '服务器设置';
$lang->client->totalUsers             = '总用户数';
$lang->client->totalGroups            = '讨论组数';
$lang->client->totalMessages          = '消息数量';
$lang->client->xxdStartDate           = 'XXD上次启动时间';
$lang->client->xxcNotice              = '喧喧发布新版本';

$lang->client->strategies['force']    = '强制';
$lang->client->strategies['optional'] = '可选';

$lang->client->status['wait']     = '待发布';
$lang->client->status['released'] = '已发布';

$lang->client->zipList['win64zip']   = 'Windows 64位';
$lang->client->zipList['win32zip']   = 'Windows 32位';
$lang->client->zipList['macOSzip']   = 'macOS';
$lang->client->zipList['linux64zip'] = 'Linux 64位';
$lang->client->zipList['linux32zip'] = 'Linux 32位';

$lang->client->message = array();
$lang->client->message['total'] = '总消息数'; 
$lang->client->message['hour']  = '最近一小时消息数'; 
$lang->client->message['day']   = '最近一天消息数'; 

$lang->client->sizeType = array();
$lang->client->sizeType['K'] = 1024;
$lang->client->sizeType['M'] = 1024 * 1024;
$lang->client->sizeType['G'] = 1024 * 1024 * 1024;

$lang->client->xxdStatusList = array();
$lang->client->xxdStatusList['online']  = '在线';
$lang->client->xxdStatusList['offline'] = '离线';
