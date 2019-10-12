<?php
/**
 * The create client view file of setting module of XXB.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Sun Hao <sunhao@cnezsoft.com>
 * @package     client
 * @version     $Id$
 * @link        http://xuan.im
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<?php $viewId = 'clientView-' . $client->id; ?>
<?php js::set('client_' . $client->id, $client)?>
<div id='<?php echo $viewId;?>' class='article-content'>
</div>
<style>#<?php echo $viewId;?>{padding: 0}</style>
<script>
$(function()
{
    $('#<?php echo $viewId;?>').html(window.marked(v['client_<?php echo $client->id;?>'].changeLog));
});
</script>
<?php include '../../common/view/footer.modal.html.php';?>