<?php
/**
 * The checkUpgrade view file of client module of XXB.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     chat
 * @version     $Id$
 * @link        http://xuan.im
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/marked.html.php';?>
<div id='mainMenu' class='clearfix'><div class='btn-toolbar pull-left'><?php common::printAdminSubMenu('xuanxuan');?></div></div>
<?php js::set('downloadingText', $lang->client->downloading)?>
<?php js::set('downloadText', $lang->client->download)?>
<?php js::set('downloadFailText', $lang->client->downloadFail)?>
<?php js::set('downloadTipText', $lang->client->downloadTip)?>
<?php js::set('downloadSuccessText', $lang->client->downloadSuccess)?>
<?php js::set('currentVersionText', $lang->client->currentVersion)?>
<?php js::set('submittingText', $lang->submitting)?>
<?php js::set('serverVersions', $serverVersions)?>
<?php js::set('currentVersion', $currentVersion)?>
<div class='panel' id='infoPanel'>
  <div class='panel-heading'><strong><?php echo $lang->client->checkUpgrade?></strong></div>
  <div class='alert alert-danger with-icon hidden' id='errorBox'>
    <i class='icon icon-warning-sign'></i>
    <div class='content'>
      <h3>
        <?php echo $lang->client->cannotUseUpdateServer?>
      </h3>
      <p>
        <?php echo $lang->client->versionError?>
      </p>
    </div>
  </div>
  <div class='alert alert-success with-icon hidden' id='alreadyNewBox'>
    <i class='icon icon-check-circle'></i>
    <div class='content'>
      <h3>
        <?php echo $lang->client->alreadyLastestVersion?>
        <?php if($currentVersion) echo $currentVersion->version;?>
      </h3>
    </div>
  </div>
  <div class='alert alert-primary with-icon hidden' id='messageBox'>
    <i class='icon icon-flag text-primary'></i>
    <div class='content'>
      <h3>
        <?php echo $lang->client->foundNewVersion?> <span class='text-new-version'></span>
        <?php if($currentVersion):?>
        <small class='text-current-version'><?php echo $lang->client->currentVersion?> <?php echo $currentVersion->version?></small>
        <?php endif;?>
      </h3>
      <p class='text-version-summary space'></p>
      <p>
        <a href='#updateDetails' data-toggle='collapse' id='updateDetailsBtn'><i class='icon icon-double-angle-down'></i> <?php echo $lang->client->changeLog?></a>
      </p>
      <div class='collapse' id='updateDetails'>
        <div class='article-content'></div>
      </div>
      <p class='actions'>
        <a href='#updateForm' class='btn btn-primary' data-toggle='collapse'><?php echo $lang->client->upgradeToThisVersion?></a>
      </p>
    </div>
  </div>
  <div class='collapse' id='updateForm'>
    <div class='panel-body'>
      <div class='form-group' id='downloadTypes'>
        <label><?php echo $lang->client->downloadClientPackages?></label>
        <?php foreach($lang->client->zipList as $zipType => $name):?>
        <div class='checkbox'>
          <label>
            <input type='checkbox' checked value='<?php echo $zipType;?>' data-type='<?php echo $zipType;?>'> <?php echo $name;?>
          </label>
          &nbsp; <span class='info'></span>
          <p class='errorInfo text-error'>
            <?php 
                if($zipType == 'linux64zip')
                {
                    $zipType = 'linux.x64.tar.gz';
                }
                else if($zipType == 'linux32zip')
                {
                    $zipType = 'linux.ia32.tar.gz';
                }
                else if($zipType == 'win32zip')
                {
                    $zipType = 'win32.zip';
                }
                else if($zipType == 'win64zip')
                {
                    $zipType = 'win64.zip';
                }
                else if($zipType == 'macOSzip')
                {
                    $zipType = 'mac.zip';
                }
            ?>
            <span><?php echo $lang->client->downloadErrorTip;?></span>
            <?php $path = str_replace('\\', '/', $path);?>
            <?php echo $path . 'client' . '/';?><span class='text-new-version'></span>
            <span><?php echo $lang->client->inCatalog;?></span>
            <span><?php echo $lang->client->fileNameIs . ' xuanxuan';?><span class='text-new-version'></span><?php echo '.' . $zipType;?></span>
          </p>
        </div>
        <?php endforeach;?>
      </div>
      <p class='text-danger hidden' id='downloadErrorTip'><?php echo $lang->client->downloadErrorTip;?></p>
      <p class='text-danger hidden' id='downloadFailedTip'><?php echo $lang->client->downloadFailedTip;?></p>
      <button type='button' class='btn btn-primary' id='downloadBtn'><?php echo $lang->client->download?></button>
      <div id='updateBtns' class='hidden'>
        <div class='form-group' id='strategies'>
          <label><?php echo $lang->client->selectUpgradeStrategy?></label>
          <div>
            <?php foreach($lang->client->strategies as $value => $name):?>
            <label class='radio-inline'>
              <input type='radio' name='strategy' value='<?php echo $value?>'> <?php echo $name?>
            </label>
            <?php endforeach;?>
          </div>
        </div>
        <button data-toggle='popover' data-placement='top' data-content='<?php echo $lang->saveSuccess;?>' data-tip-class='popover-success' type='button' class='btn btn-primary' id='publishUpdateBtn'><?php echo $lang->client->publishUpdate?></button>
         &nbsp; <?php echo $lang->client->or?> &nbsp;
        <button data-toggle='popover' data-placement='top' data-content='<?php echo $lang->saveSuccess;?>' data-tip-class='popover-success' type='button' class='btn btn-primary' id='saveUpdateBtn'><?php echo $lang->client->saveUpdate?></button>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
