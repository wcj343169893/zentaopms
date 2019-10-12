<?php
$lang->chat->settings = '喧喧設置';
$lang->chat->debug    = '調試功能';

$lang->chat->version         = '版本';
$lang->chat->backendLang     = '伺服器端語言';
$lang->chat->key             = '密鑰';
$lang->chat->systemGroup     = '系統';
$lang->chat->url             = '訪問地址';
$lang->chat->pollingInterval = '輪詢間隔';
$lang->chat->createKey       = '重新生成密鑰';
$lang->chat->connector       = '、';
$lang->chat->viewDebug       = '查看調試信息';
$lang->chat->log             = '日誌';
$lang->chat->xxdStatus       = 'XXD狀態';
$lang->chat->debugInfo       = '調試信息';
$lang->chat->errorInfo       = '錯誤提示';
$lang->chat->xxbConfigError  = 'XXB參數設置不正確。';

$lang->chat->debugStatus[1] = '啟用';
$lang->chat->debugStatus[0] = '不啟用';

$lang->chat->xxdServer       = '喧喧伺服器';
$lang->chat->createKey       = '重新生成密鑰';
$lang->chat->downloadXXD     = '下載XXD服務端';
$lang->chat->listenIP        = '監聽IP';
$lang->chat->chatPort        = '客戶端通訊連接埠';
$lang->chat->uploadFileSize  = '上傳檔案大小';
$lang->chat->downloadPackage = '下載完整包';
$lang->chat->downloadConfig  = '只下載配置檔案';
$lang->chat->changeSetting   = '修改配置';

$lang->chat->day    = '天';
$lang->chat->hours  = '小時';
$lang->chat->minute = '分鐘';
$lang->chat->secs   = '秒';

$lang->chat->notAdmin         = '不是系統管理員。';
$lang->chat->notSystemChat    = '不是系統會話。';
$lang->chat->notGroupChat     = '不是多人會話。';
$lang->chat->notPublic        = '不是公開會話。';
$lang->chat->cantChat         = '沒有發言權限。';
$lang->chat->chatHasDismissed = '討論組已被解散';
$lang->chat->needLogin        = '用戶沒有登錄。';
$lang->chat->notExist         = '會話不存在。';
$lang->chat->changeRenameTo   = '將會話名稱更改為';
$lang->chat->multiChats       = '消息不屬於同一個會話。';
$lang->chat->notInGroup       = '用戶不在此討論組內。';
$lang->chat->notInChat        = '無法向與您無關的會話發送消息。';
$lang->chat->notSameUser      = '無法作為他人發送消息。';
$lang->chat->errorKey         = '<strong>密鑰</strong> 應該為數字或字母的組合，長度為32位。';
$lang->chat->defaultKey       = '請勿使用預設<strong>密鑰</strong>。';
$lang->chat->debugTips        = '<br>使用管理員賬號%s並訪問此頁面。';
$lang->chat->noLogFile        = '沒有日誌檔案。';
$lang->chat->noFopen          = '未啟用fopen函數，請按以下路逕自行查看日誌檔案：%s。';

$lang->chat->xxdServerTip   = '喧喧伺服器地址為完整的協議+地址+連接埠，示例：http://192.168.1.35 或 http://www.xxb.com ，不能使用127.0.0.1。';
$lang->chat->xxdServerEmpty = '喧喧伺服器地址為空。';
$lang->chat->xxdServerError = '喧喧伺服器地址不能為 127.0.0.1。';
$lang->chat->xxdSchemeError = '伺服器地址應該以<strong>http://</strong>或<strong>https://</strong>開頭。';
$lang->chat->xxdPortError   = '伺服器地址應該包含有效的連接埠號，預設為<strong>11443</strong>。';
$lang->chat->xxdPollIntTip  = '輪詢間隔單位為秒，最小為 5 秒，預設為 60 秒，示例：60。';
$lang->chat->xxdPollIntErr  = '輪詢間隔應為一個最小為 5 的正整數。';
$lang->chat->errorSSLCrt    = 'SSL證書內容不能為空';
$lang->chat->errorSSLKey    = 'SSL證書私鑰不能為空';
$lang->chat->errorXXCLow    = '客戶端版本太低,請在喧喧官網下載最新版。';

$lang->chat->broadcast = new stdclass();
$lang->chat->broadcast->createChat  = '@%s 創建了討論組 **[%s](#/chats/groups/%s)**。';
$lang->chat->broadcast->joinChat    = '@%s 加入了討論組。';
$lang->chat->broadcast->quitChat    = '@%s 退出了當前討論組。';
$lang->chat->broadcast->renameChat  = '@%s 將討論組名稱更改為 **[%s](#/chats/groups/%s)**。';
$lang->chat->broadcast->inviteUser  = '@%s 邀請 %s 加入了討論組。';
$lang->chat->broadcast->dismissChat = '@%s 解散了當前討論組。';

$lang->chat->xxd = new stdclass();
$lang->chat->xxd->os             = '操作系統';
$lang->chat->xxd->ip             = '監聽IP';
$lang->chat->xxd->chatPort       = '客戶端通訊連接埠';
$lang->chat->xxd->commonPort     = '通用連接埠';
$lang->chat->xxd->https          = 'Https';
$lang->chat->xxd->uploadFileSize = '上傳檔案大小';
$lang->chat->xxd->maxOnlineUser  = '最大在綫人數';
$lang->chat->xxd->sslcrt         = '證書內容';
$lang->chat->xxd->sslkey         = '證書私鑰';
$lang->chat->xxd->max            = '最大';

$lang->chat->httpsOptions['on']  = '啟用';
$lang->chat->httpsOptions['off'] = '不啟用';

$lang->chat->osList['win_i386']      = 'Windows 32位';
$lang->chat->osList['win_x86_64']    = 'Windows 64位';
$lang->chat->osList['linux_i386']    = 'Linux 32位';
$lang->chat->osList['linux_x86_64']  = 'Linux 64位';
$lang->chat->osList['darwin_x86_64'] = 'macOS';

$lang->chat->placeholder = new stdclass();
$lang->chat->placeholder->xxd = new stdclass();
$lang->chat->placeholder->xxd->ip             = '監聽的伺服器ip地址，沒有特殊需要直接填寫0.0.0.0';
$lang->chat->placeholder->xxd->chatPort       = '與聊天客戶端通訊的連接埠';
$lang->chat->placeholder->xxd->commonPort     = '通用連接埠，該連接埠用於客戶端登錄時驗證，以及檔案上傳下載使用';
$lang->chat->placeholder->xxd->https          = '啟用https';
$lang->chat->placeholder->xxd->uploadFileSize = '上傳檔案的大小';
$lang->chat->placeholder->xxd->maxOnlineUser  = '最大在綫人數';
$lang->chat->placeholder->xxd->sslcrt         = '請將證書內容複製到此處';
$lang->chat->placeholder->xxd->sslkey         = '請將證書密鑰複製到此處';

$lang->chat->notify = new stdclass();
$lang->chat->notify->setReceiver = '沒有設置接收者類型，只能是用戶或者是某個討論組。';
$lang->chat->notify->setGroup    = '應當設置接收討論組。';
$lang->chat->notify->setUserList = '應當設置接收者用戶列表。';
$lang->chat->notify->setSender   = '應當設置發送方信息。';

$lang->chat->xxdConfigNote = array();
$lang->chat->xxdConfigNote['zh']['ip'] = '# 監聽的IP地址，不要使用127.0.0.1。';
$lang->chat->xxdConfigNote['en']['ip'] = '# The ip listened. Do not use 127.0.0.1.';

$lang->chat->xxdConfigNote['zh']['commonPort'] = '# 登錄和附件上傳介面(http)，確保防火牆開放此連接埠。';
$lang->chat->xxdConfigNote['en']['commonPort'] = '# Port for user login and file uploaded(http)';

$lang->chat->xxdConfigNote['zh']['chatPort'] = '# 聊天消息通訊連接埠(websocket)，確保防火牆開放此連接埠。';
$lang->chat->xxdConfigNote['en']['chatPort'] = '# Port for chat(websocket).';

$lang->chat->xxdConfigNote['zh']['https'] = '# 是否啟用Https(on|off)。使用Https可以保證消息全程加密。';
$lang->chat->xxdConfigNote['en']['https'] = '# on|off. Use https to encryt all messages.';

$lang->chat->xxdConfigNote['zh']['uploadPath'] = '# 附件保存的目錄。預設存放在xxd/files/。';
$lang->chat->xxdConfigNote['en']['uploadPath'] = '# Default upload path is xxd/files.';

$lang->chat->xxdConfigNote['zh']['uploadFileSize'] = '# 上傳檔案的大小，以M為單位。';
$lang->chat->xxdConfigNote['en']['uploadFileSize'] = '# The Max size for uploaded files(M).';

$lang->chat->xxdConfigNote['zh']['pollingInterval'] = '# 輪詢時間，單位為秒，最小值為 5。';
$lang->chat->xxdConfigNote['en']['pollingInterval'] = '# Interval of polling, should be a number equal to or greater than 5.';

$lang->chat->xxdConfigNote['zh']['maxOnlineUser'] = '# 在綫用戶上限，0為無限制。';
$lang->chat->xxdConfigNote['en']['maxOnlineUser'] = '# Max online users, 0 means no limit.';

$lang->chat->xxdConfigNote['zh']['logPath'] = '# 程序運行日誌的保存路徑。';
$lang->chat->xxdConfigNote['en']['logPath'] = '# Path of saved log files.';

$lang->chat->xxdConfigNote['zh']['certPath'] = '# 證書的保存路徑。';
$lang->chat->xxdConfigNote['en']['certPath'] = '# Path of saved certificate.';

$lang->chat->xxdConfigNote['zh']['debug'] = '# Debug級別，可設置0|1|2';
$lang->chat->xxdConfigNote['en']['debug'] = '# Debug level，0|1|2';

$lang->chat->xxdConfigNote['zh']['backend'] = "# xxd可以對接多個後台程序。每一個後台程序由入口檔案 + 私鑰組成。\n# 客戶端登錄時如果沒有指定後台程序，會預設登錄到第一個後台程序。";
$lang->chat->xxdConfigNote['en']['backend'] = "# xxd can integrate with multi backends. Every backend has an entry and a key. \n# The client will login to the first backend if the user doesn't specify the backend.";
