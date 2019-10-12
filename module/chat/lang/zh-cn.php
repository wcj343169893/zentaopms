<?php
$lang->chat->settings = '喧喧设置';
$lang->chat->debug    = '调试功能';

$lang->chat->version         = '版本';
$lang->chat->backendLang     = '服务器端语言';
$lang->chat->key             = '密钥';
$lang->chat->systemGroup     = '系统';
$lang->chat->url             = '访问地址';
$lang->chat->pollingInterval = '轮询间隔';
$lang->chat->createKey       = '重新生成密钥';
$lang->chat->connector       = '、';
$lang->chat->viewDebug       = '查看调试信息';
$lang->chat->log             = '日志';
$lang->chat->xxdStatus       = 'XXD状态';
$lang->chat->debugInfo       = '调试信息';
$lang->chat->errorInfo       = '错误提示';
$lang->chat->xxbConfigError  = 'XXB参数设置不正确。';

$lang->chat->debugStatus[1] = '启用';
$lang->chat->debugStatus[0] = '不启用';

$lang->chat->xxdServer       = '喧喧服务器';
$lang->chat->createKey       = '重新生成密钥';
$lang->chat->downloadXXD     = '下载XXD服务端';
$lang->chat->listenIP        = '监听IP';
$lang->chat->chatPort        = '客户端通讯端口';
$lang->chat->uploadFileSize  = '上传文件大小';
$lang->chat->downloadPackage = '下载完整包';
$lang->chat->downloadConfig  = '只下载配置文件';
$lang->chat->changeSetting   = '修改配置';

$lang->chat->day    = '天';
$lang->chat->hours  = '小时';
$lang->chat->minute = '分钟';
$lang->chat->secs   = '秒';

$lang->chat->notAdmin         = '不是系统管理员。';
$lang->chat->notSystemChat    = '不是系统会话。';
$lang->chat->notGroupChat     = '不是多人会话。';
$lang->chat->notPublic        = '不是公开会话。';
$lang->chat->cantChat         = '没有发言权限。';
$lang->chat->chatHasDismissed = '讨论组已被解散';
$lang->chat->needLogin        = '用户没有登录。';
$lang->chat->notExist         = '会话不存在。';
$lang->chat->changeRenameTo   = '将会话名称更改为';
$lang->chat->multiChats       = '消息不属于同一个会话。';
$lang->chat->notInGroup       = '用户不在此讨论组内。';
$lang->chat->notInChat        = '无法向与您无关的会话发送消息。';
$lang->chat->notSameUser      = '无法作为他人发送消息。';
$lang->chat->errorKey         = '<strong>密钥</strong> 应该为数字或字母的组合，长度为32位。';
$lang->chat->defaultKey       = '请勿使用默认<strong>密钥</strong>。';
$lang->chat->debugTips        = '<br>使用管理员账号%s并访问此页面。';
$lang->chat->noLogFile        = '没有日志文件。';
$lang->chat->noFopen          = '未启用fopen函数，请按以下路径自行查看日志文件：%s。';

$lang->chat->xxdServerTip   = '喧喧服务器地址为完整的协议+地址+端口，示例：http://192.168.1.35 或 http://www.xxb.com ，不能使用127.0.0.1。';
$lang->chat->xxdServerEmpty = '喧喧服务器地址为空。';
$lang->chat->xxdServerError = '喧喧服务器地址不能为 127.0.0.1。';
$lang->chat->xxdSchemeError = '服务器地址应该以<strong>http://</strong>或<strong>https://</strong>开头。';
$lang->chat->xxdPortError   = '服务器地址应该包含有效的端口号，默认为<strong>11443</strong>。';
$lang->chat->xxdPollIntTip  = '轮询间隔单位为秒，最小为 5 秒，默认为 60 秒，示例：60。';
$lang->chat->xxdPollIntErr  = '轮询间隔应为一个最小为 5 的正整数。';
$lang->chat->errorSSLCrt    = 'SSL证书内容不能为空';
$lang->chat->errorSSLKey    = 'SSL证书私钥不能为空';
$lang->chat->errorXXCLow    = '客户端版本太低,请在喧喧官网下载最新版。';

$lang->chat->broadcast = new stdclass();
$lang->chat->broadcast->createChat  = '@%s 创建了讨论组 **[%s](#/chats/groups/%s)**。';
$lang->chat->broadcast->joinChat    = '@%s 加入了讨论组。';
$lang->chat->broadcast->quitChat    = '@%s 退出了当前讨论组。';
$lang->chat->broadcast->renameChat  = '@%s 将讨论组名称更改为 **[%s](#/chats/groups/%s)**。';
$lang->chat->broadcast->inviteUser  = '@%s 邀请 %s 加入了讨论组。';
$lang->chat->broadcast->dismissChat = '@%s 解散了当前讨论组。';

$lang->chat->xxd = new stdclass();
$lang->chat->xxd->os             = '操作系统';
$lang->chat->xxd->ip             = '监听IP';
$lang->chat->xxd->chatPort       = '客户端通讯端口';
$lang->chat->xxd->commonPort     = '通用端口';
$lang->chat->xxd->https          = 'Https';
$lang->chat->xxd->uploadFileSize = '上传文件大小';
$lang->chat->xxd->maxOnlineUser  = '最大在线人数';
$lang->chat->xxd->sslcrt         = '证书内容';
$lang->chat->xxd->sslkey         = '证书私钥';
$lang->chat->xxd->max            = '最大';

$lang->chat->httpsOptions['on']  = '启用';
$lang->chat->httpsOptions['off'] = '不启用';

$lang->chat->osList['win_i386']      = 'Windows 32位';
$lang->chat->osList['win_x86_64']    = 'Windows 64位';
$lang->chat->osList['linux_i386']    = 'Linux 32位';
$lang->chat->osList['linux_x86_64']  = 'Linux 64位';
$lang->chat->osList['darwin_x86_64'] = 'macOS';

$lang->chat->placeholder = new stdclass();
$lang->chat->placeholder->xxd = new stdclass();
$lang->chat->placeholder->xxd->ip             = '监听的服务器ip地址，没有特殊需要直接填写0.0.0.0';
$lang->chat->placeholder->xxd->chatPort       = '与聊天客户端通讯的端口';
$lang->chat->placeholder->xxd->commonPort     = '通用端口，该端口用于客户端登录时验证，以及文件上传下载使用';
$lang->chat->placeholder->xxd->https          = '启用https';
$lang->chat->placeholder->xxd->uploadFileSize = '上传文件的大小';
$lang->chat->placeholder->xxd->maxOnlineUser  = '最大在线人数';
$lang->chat->placeholder->xxd->sslcrt         = '请将证书内容复制到此处';
$lang->chat->placeholder->xxd->sslkey         = '请将证书密钥复制到此处';

$lang->chat->notify = new stdclass();
$lang->chat->notify->setReceiver = '没有设置接收者类型，只能是用户或者是某个讨论组。';
$lang->chat->notify->setGroup    = '应当设置接收讨论组。';
$lang->chat->notify->setUserList = '应当设置接收者用户列表。';
$lang->chat->notify->setSender   = '应当设置发送方信息。';

$lang->chat->xxdConfigNote = array();
$lang->chat->xxdConfigNote['zh']['ip'] = '# 监听的IP地址，不要使用127.0.0.1。';
$lang->chat->xxdConfigNote['en']['ip'] = '# The ip listened. Do not use 127.0.0.1.';

$lang->chat->xxdConfigNote['zh']['commonPort'] = '# 登录和附件上传接口(http)，确保防火墙开放此端口。';
$lang->chat->xxdConfigNote['en']['commonPort'] = '# Port for user login and file uploaded(http)';

$lang->chat->xxdConfigNote['zh']['chatPort'] = '# 聊天消息通讯端口(websocket)，确保防火墙开放此端口。';
$lang->chat->xxdConfigNote['en']['chatPort'] = '# Port for chat(websocket).';

$lang->chat->xxdConfigNote['zh']['https'] = '# 是否启用Https(on|off)。使用Https可以保证消息全程加密。';
$lang->chat->xxdConfigNote['en']['https'] = '# on|off. Use https to encryt all messages.';

$lang->chat->xxdConfigNote['zh']['uploadPath'] = '# 附件保存的目录。默认存放在xxd/files/。';
$lang->chat->xxdConfigNote['en']['uploadPath'] = '# Default upload path is xxd/files.';

$lang->chat->xxdConfigNote['zh']['uploadFileSize'] = '# 上传文件的大小，以M为单位。';
$lang->chat->xxdConfigNote['en']['uploadFileSize'] = '# The Max size for uploaded files(M).';

$lang->chat->xxdConfigNote['zh']['pollingInterval'] = '# 轮询时间，单位为秒，最小值为 5。';
$lang->chat->xxdConfigNote['en']['pollingInterval'] = '# Interval of polling, should be a number equal to or greater than 5.';

$lang->chat->xxdConfigNote['zh']['maxOnlineUser'] = '# 在线用户上限，0为无限制。';
$lang->chat->xxdConfigNote['en']['maxOnlineUser'] = '# Max online users, 0 means no limit.';

$lang->chat->xxdConfigNote['zh']['logPath'] = '# 程序运行日志的保存路径。';
$lang->chat->xxdConfigNote['en']['logPath'] = '# Path of saved log files.';

$lang->chat->xxdConfigNote['zh']['certPath'] = '# 证书的保存路径。';
$lang->chat->xxdConfigNote['en']['certPath'] = '# Path of saved certificate.';

$lang->chat->xxdConfigNote['zh']['debug'] = '# Debug级别，可设置0|1|2';
$lang->chat->xxdConfigNote['en']['debug'] = '# Debug level，0|1|2';

$lang->chat->xxdConfigNote['zh']['backend'] = "# xxd可以对接多个后台程序。每一个后台程序由入口文件 + 私钥组成。\n# 客户端登录时如果没有指定后台程序，会默认登录到第一个后台程序。";
$lang->chat->xxdConfigNote['en']['backend'] = "# xxd can integrate with multi backends. Every backend has an entry and a key. \n# The client will login to the first backend if the user doesn't specify the backend.";
