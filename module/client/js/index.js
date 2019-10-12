// index block init.
$(function()
{
    $('#dashboard').dashboard(
    {   
        height            : 245,
        draggable         : true,
        shadowType        : false,
        afterOrdered      : sortBlocks,
        sensitive         : true,
        onResize          : resizeBlock,
        panelRemovingTip  : config.confirmRemoveBlock
    }); 

    /**
     * refresh index block  
     * @access public
     * @return void
     */
    var refreshTimer = 1000 * 60 * 5;
    setInterval(function ()
    {
        $('.refresh-panel').trigger('click');
    }, refreshTimer);
});

/**
 * Sort blocks.
 * 
 * @param  object $orders  format is {'block2' : 1, 'block1' : 2, oldOrder : newOrder} 
 * @access public
 * @return void
 */
function sortBlocks(orders)
{
    var oldOrder = new Array();
    var newOrder = new Array();
    for(i in orders)
    {
       oldOrder.push(i.replace('block', ''));
       newOrder.push(orders[i]);
    }

    $.getJSON(createLink('block', 'sort', 'oldOrder=' + oldOrder.join(',') + '&newOrder=' + newOrder.join(',')), function(data)
    {
        if(data.result != 'success') return false;

        $('#home .panels-container .panel:not(.panel-dragging-holder)').each(function()
        {
            var $this = $(this);
            var index = $this.data('order');
            var url = createLink('entry', 'printBlock', 'index=' + index);
            /* Update new index for block id edit and delete. */
            $this.attr('id', 'block' + index).data('id', index).attr('data-url', url).data('url', url);
            $this.find('.panel-actions .edit-block').attr('href', createLink('block', 'admin', 'index=' + index));
        });
    });
}

/**
 * Resize block
 * @param  object $event
 * @access public
 * @return void
 */
function resizeBlock(event)
{
  var blockID = event.element.find('.panel').data('blockid');
  var data = event.type == 'vertical' ? event.height : event.grid;
  $.getJSON(createLink('block', 'resize', 'id=' + blockID + '&type=' + event.type + '&data=' + data), function(data)
  {
      if(data.result !== 'success') event.revert();
  });
}
