<?php
/* Set the error reporting. */
error_reporting(E_ALL);

/* Start output buffer. */
ob_start();

/* Set front as default mode. */
$runMode = 'front';

/* Check is api mode. */
if(preg_match('/token=[a-z0-9]{32}/i', $_SERVER["QUERY_STRING"])) $runMode = 'api';

/* Check is xuanxuan client mode. */
if(strpos($_SERVER['HTTP_USER_AGENT'], 'easysoft/xuan.im') !== false) $runMode = 'xuanxuan';

define('RUN_MODE', $runMode);

/* Load the framework. */
$routerFile = (RUN_MODE == 'api') ? '../framework/router.class.php' : '../framework/xuanxuan.class.php';
include $routerFile;

include '../framework/control.class.php';
include '../framework/model.class.php';
include '../framework/helper.class.php';

/* Log the time and define the run mode. */
$startTime = getTime();

/* Run the app. */
if(RUN_MODE == 'api') $app = router::createApp('xxb', dirname(dirname(__FILE__)));
if(RUN_MODE != 'api') $app = router::createApp('xxb', dirname(dirname(__FILE__)), 'xuanxuan');

/* Load common model. */
$common = $app->loadCommon();

/* Api mode need check entry and set default params. */
if(RUN_MODE == 'api')
{
    $common->checkEntry();
    $config->requestType   = 'GET';
    $config->default->view = 'json';
}

/* Parse request. */
$app->parseRequest();

/* Check privilege of api. */
if(RUN_MODE == 'api') $common->checkPriv();

/* Load module. */
$app->loadModule();

/* Process api response. */
if(RUN_MODE == 'api')
{
    $output = json_decode(ob_get_clean());
    $data   = new stdClass();
    $data->status = isset($output->status) ? $output->status : $output->result;
    if(isset($output->message)) $data->message = $output->message;
    if(isset($output->data))    $data->data    = json_decode($output->data);
    $output = json_encode($data);

    unset($_SESSION['entryCode']);
    unset($_SESSION['validEntry']);
}

/* Flush the buffer. */
echo helper::removeUTF8Bom(ob_get_clean());
