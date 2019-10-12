<?php
/**
 * The create client view file of setting module of XXB.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Memory <lvtao@cnezsoft.com>
 * @package     client
 * @version     $Id$
 * @link        http://xuan.im
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<form id='ajaxForm' method='post' action='<?php echo inlink('create')?>'>
  <table class='table table-form'>
    <tbody>
    <tr>
      <th class='w-100px'><?php echo $lang->client->version?></th>
      <td class='w-500px'>
        <div class='required required-wrapper'></div>
        <?php echo html::input('version', '', "class='form-control'")?>
      </td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->client->desc?></th>
      <td><?php echo html::input('desc', '', "class='form-control'")?></td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->client->changeLog?></th>
      <td><?php echo html::textarea('changeLog', '', "class='form-control'")?></td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->client->strategy?></th>
      <td><?php echo html::radio('strategy', $lang->client->strategies, 'optional')?></td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->client->download?></th>
      <td>
        <?php foreach($lang->client->zipList as $os => $name):?>
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-addon w-130px"><?php echo $name?>：</span>
            <?php echo html::input("downloads[{$os}]", '', "id='{$os}' class='form-control'")?>
          </div>
        </div>
        <?php endforeach;?>
      </td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->client->releaseStatus?></th>
      <td><?php echo html::radio('status', $lang->client->status, 'wait')?></td>
      <td></td>
    </tr>
    <tr>
      <th></th>
      <td><?php echo html::submitButton();?></td>
      <td></td>
    </tr>
    </tbody>
  </table>
</form>
<?php include '../../common/view/footer.modal.html.php';?>
