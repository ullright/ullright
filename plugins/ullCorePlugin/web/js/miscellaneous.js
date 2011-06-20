/**
	Loaded by default
 */
$(document).ready(function()
{
  $('form').preventDoubleSubmit();
  
  markErrorFormFields();
});


function markErrorFormFields()
{
	$('table.edit_table ul.error_list').parents('tr').find('input').each(function() {
		$(this).addClass('has_error');
	});
	
	$('table.edit_table ul.error_list').parents('tr').find('textarea').each(function() {
		$(this).addClass('has_error');
	});	
	
	$('table.edit_table ul.error_list').parents('tr').find('select').each(function() {
		$(this).addClass('has_error');
	});	
}

/**
 * Unobstrusive popup
 * 
 * @param url
 * @return
 */
function popup(url, name, options) 
{
  newWindow = window.open(url, name, options);
  newWindow.focus();
  
  /*if (window.focus) { newWindow.focus(); }
    return false; */
}

/**
 * Helper function for form double submit prevention
 * see: http://henrik.nyh.se/2008/07/jquery-double-submission
 * 
 * Add double submit prevention to all forms.
 * Note that this does not handle forms which are added
 * to the DOM at runtime, e.g. the time reporting project
 * add/edit overlay *  
 */
jQuery.fn.preventDoubleSubmit = function()
{
  var alreadySubmitted = false;
  
  return $(this).submit(function()
  {
    if (alreadySubmitted)
    {
      return false;
    }
    else
    {
      alreadySubmitted = true;
    }
  });
};



