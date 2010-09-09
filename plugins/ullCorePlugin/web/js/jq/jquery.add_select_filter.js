/**
 * Unobstrusivly adds a filter input box to a select box
 */
jQuery.fn.addSelectFilter = function() 
{
  return this.each(function()
  {
    var originalFilterId = this.id;
    var filterId = originalFilterId + '_filter';
    var filter = $('<input type="text" id="' + filterId + '" size="3" title="Filter" class="ull_widget_select_filter" />');
    
    $(this).before(filter);
    filter.after(" ");
  
    //does not work in ie8
    //filter.attr("onkeyup","doFiltering(this.value, document.getElementById('" + this.id + "'));");
    
    //instead we use the official jquery event 
    filter.keyup(function()
    {
      doFiltering(this.value, document.getElementById(originalFilterId));
    });
  });
};

filterCache = new Array();

/**
 * Filters a given select list by a given pattern
 * 
 * @param pattern
 * @param list
 * @return
 */
function doFiltering(pattern, list) 
{
  // fill cache with original select options
  if (typeof filterCache[list.id] == "undefined")
  { 
    filterCache[list.id] = new Array();
    
    for(var i = 0; i < list.length; i++)
    {
      filterCache[list.id].push(new Array(
        list[i].text,
        list[i].value,
        list[i].getAttribute('class')
      ));
    }
  }
  // restore original select options from cache
  else
  {
    // remove all select options
    list.length = 0;
    for(var j = 0; j < filterCache[list.id].length; j++)
    {
      var option = new Option(
        filterCache[list.id][j][0],
        filterCache[list.id][j][1]
      );
      option.setAttribute('class', filterCache[list.id][j][2]);
      list[list.length] = option;
    }
  }
  
  // create list of options to be removed  
  pattern = new RegExp(pattern,"i");
  removeList = new Array();
  i = 0;
  while(i < list.options.length) 
  {
    // add options to remove list
    if (!pattern.test(list.options[i].text)) 
    {
      removeList.push(list.options[i].text);
    }
    i++;
  }
  
  // remove select options
  for(var x = 0; x < removeList.length; x++)
  {
    for(i = 0; i < list.options.length; i++)
    {
      if(list.options[i].text == removeList[x])
      {
        list.options[i] = null;
        break;
      }
    }
  }
}  
