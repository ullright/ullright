/**
 * Initial setup for a many to many write widget
 * Reads the already selected option/value pairs into
 * backing arrays and recreates an empty select box
 * It then adds the 'jQuery UI MultiSelect Widget'
 * functionality and adds a faked filter + handlers.
 * 
 * @param widget our widget object
 * @param $multiselectOptions options for the MultiSelect widget
 */
function manyToMany_setup(widget, $multiselectOptions)
{
  //save originally selected options
  var selectedOptions = [];
  var selectedValues = [];
  var i = 0;
    
  widget.selectBox.children('option:selected').each(
    function(i, selected)
    {
      selectedOptions[i] = selected.value;
      selectedValues[i] = selected.text;
      i++;
    }
  );
    
  widget.selectedOptions = selectedOptions;
  widget.selectedValues = selectedValues;
  
  
  //purge all items from the select tag
  //this can be removed if we adapt the widget
  //to deliver an empty list if JS is detected
  var parentNode = widget.selectBox.parent();
  var selectName = widget.selectBox.attr('name');
  var selectId = widget.selectBox.attr('id');
  
  var oldSelectBox = document.getElementById(widget.selectBox[0].id);
  oldSelectBox.parentNode.removeChild(oldSelectBox);
  var newSelectBox = $('<select multiple="true" id="' + selectId + '" name="' + selectName + '"></select>');
  widget.selectBox = newSelectBox; 
  parentNode.prepend(newSelectBox);
  
  //enable 'jQuery UI MultiSelect Widget'
  widget.selectBox.multiselect($multiselectOptions);
  
  //add fake filterbox
  var header = widget.selectBox.next('button').next('div.ui-multiselect-menu');
  var filterInput = $('<div class="ui-multiselect-filter">Search:<input placeholder="Enter keywords"></div>');
  header.children('div.ui-widget-header').addClass('ui-multiselect-hasfilter');
  header.children('div.ui-widget-header').prepend(filterInput);
  filterInput.append('<span class="ui-multiselect-result-text"></span>');

  manyToMany_registerCheckboxHandler(widget);
  manyToMany_rebuildBackingSelect(widget);

  filterInput.children('input').keyup(function(event)
  {
    window.clearTimeout(widget.timeoutId);
    widget.timeoutId = window.setTimeout(function() {
      manyToMany_filter(widget);
    }, 300);
  });
}

/**
 * Recreates the option list for the select box
 * based on the backing array. Needed so that a submit
 * includes these values. It also sets the 'currently
 * selected' text.
 * 
 * TODO: performance
 * 
 * @param widget our widget object
 */
function manyToMany_rebuildBackingSelect(widget)
{
  var optionString = '';
  
  $.each(widget.selectedOptions, function(id, item) {
    optionString += '<option selected="selected" value="' + item + '"></option>';
  });
  
  
  //Note: make this faster. IE does NOT support setting innerHTML on select (!)
  
  //do we need this (does this remove click handlers?)
  widget.selectBox.html('');
  //var element = document.getElementById(widget.selectBox[0].id);
  //element.innerHTML = optionString;
  //element.options = optionString;
  //var newSelect = element.outerHTML.substring(0, element.outerHTML.length - 9) + optionString + '</select>';
  
  //alert(newSelect);
  //element.outerHTML = newSelect;
  //element.outerHTML = '<select style="display:none">' + optionString + '</select>';
  
  widget.selectBox.append(optionString);
  
  if (widget.selectedOptions.length == 0)
  {
    var buttonText = '&nbsp;'
  }
  else if (widget.selectedOptions.length > 5)
  {
    var buttonText = widget.selectedOptions.length + ' elements selected';
  }
  else
  {
    var buttonText = widget.selectedValues.slice().sort().join(', ');
  }

  widget.selectBox.next('button.ui-multiselect').html(buttonText);
}

/**
 * Registers a 'clicked' event to all option boxes in the
 * 'MultiSelect widget' (not the original select box) which
 * keeps the backing arrays in sync
 * 
 * @param widget our widget object
 */
function manyToMany_registerCheckboxHandler(widget)
{
  widget.selectBox.bind('multiselectclick', function(event, ui)
  {
    if (ui.checked)
    {
      //if the newly checked option is not already in the
      //backing array, add it
      if ($.inArray(ui.value, widget.selectedOptions) === -1)
      {
        widget.selectedOptions.push(ui.value);
        widget.selectedValues.push(ui.text);
        manyToMany_rebuildBackingSelect(widget);
       }
     }
     else
     {
       //if the newly checked option is in the backing array,
       //remove it
       var index = $.inArray(ui.value, widget.selectedOptions);
       if (index !== -1)
       {
         widget.selectedOptions.splice(index, 1);
         //use same index, this requires that .each
         //(used during original creation of selectedValues)
         //respects order
         //we could do an additional search instead (performance?)
         widget.selectedValues.splice(index, 1);
         manyToMany_rebuildBackingSelect(widget);
       }
     }
  });
}

/**
 * This function is called after the user changes the text of the
 * search field. If the trimmed text has changed, we request
 * the new contents for the 'MultiSelect widget' from the server,
 * based on this filter string.
 * 
 * @param widget our widget object
 */
function manyToMany_filter(widget)
{
  //why doesn't selectBox.next('div.ui-multiselect-menu') work?
  var filterBox = widget.selectBox.next('button').next('div.ui-multiselect-menu').find('div.ui-multiselect-filter');
  var filterValue = $.trim(filterBox.children('input').val());
  
  if (widget.oldFilterValue !== filterValue)
  {
    widget.oldFilterValue = filterValue;
    if (widget.xhr !== null)
    {
      widget.xhr.abort();
    }
    widget.xhr = $.getJSON(widget.ajaxUrl,
    {
      table: widget.ownerModel,
      column: widget.ownerRelationName,
      filter: filterValue
    },
    function(data)
    {
      if (data !== null) //could be null if request was aborted
      {
        filterBox.children('span.ui-multiselect-result-text').text(data.resultText);
        var optionString = '';
        $.each(data.choices, function(id, item) {
          var selected = ($.inArray(id, widget.selectedOptions) !== -1) ? 'selected="selected"' : '';
          optionString += '<option value="' + id + '" ' + selected + '>' + item + '</option>';
        });
        
        widget.selectBox.html(''); //.empty() is too slow
        widget.selectBox.append(optionString);
        widget.selectBox.multiselect('refresh');
        
        //now that the multiselect box has rebuilt itself, let's
        //fake a backing select with all selected values
        //so that the server receives them all, not just
        //the currently displayed (=filtered) ones
        manyToMany_rebuildBackingSelect(widget);
      }
    });
  }
}