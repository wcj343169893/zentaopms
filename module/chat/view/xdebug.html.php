<?php
/**
 * The debug view file of chat module of XXB.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     chat
 * @version     $Id$
 * @link        http://xuan.im
 */
?>
<?php if($this->app->user->admin != 'super'):?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <div class='notice text-center' style='font-size:24px;'>
    <?php $_SERVER['SCRIPT_NAME'] = '/index.php';?>
    <?php $link = $this->loadModel('user')->isLogon() ? $this->createLink('user', 'logout') : $this->createLink('user', 'login');?>
    <?php if(!$checkXXBConfig):?>
      <p class="text-danger"><?php echo $lang->chat->xxbConfigError;?></p>
    <?php endif;?>
    <?php printf($lang->chat->debugTips, html::a($link, $lang->login));?>
  </div>
</div>
<?php else:?>
  <?php 
    if($config->xuanxuan->backend != 'xxb')
    {
        include '../../common/view/header.modal.html.php';
        $backLink = $this->createLink('setting', 'xuanxuan');
    }
    else
    {
        include '../../common/view/header.lite.html.php';
        $backLink = $this->config->webRoot;
    }

  ?>
  <div class='panel center-block' style="width:500px;margin-top:100px;">
    <div class="panel-heading"><?php echo $lang->chat->debugInfo;?></div>
    <table class='table'>
    <?php if(!$checkXXBConfig):?>
      <tr>
        <th class='w-80px'><?php echo $lang->chat->errorInfo;?></th>
        <td class="text-danger"><?php echo $lang->chat->xxbConfigError;?></td>
      </tr>
    <?php endif;?>
      <tr>
        <th><?php echo $lang->chat->key;?></th>
        <td><?php echo $config->xuanxuan->key;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->chat->url;?></th>
        <td><?php echo commonModel::getSysURL() . $this->config->webRoot . 'x.php';?></td>
      </tr>
      <tr>
        <th class='w-80px'><?php echo $lang->chat->xxdStatus;?></th>
        <td><?php echo $xxdStatus;?></td>
      </tr>
      <?php if(!helper::isAjaxRequest()):?>
      <tr>
        <th></th>
        <td><?php echo html::a($backLink, $lang->goback, 'class="btn btn-primary"');?></td>
      </tr>
      <?php endif;?>
    </table>
  </div>
<?php endif;?>
</body>
</html>
