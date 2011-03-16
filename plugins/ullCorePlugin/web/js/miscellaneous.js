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

/**
 * Add double submit prevention to all forms.
 * Note that this does not handle forms which are added
 * to the DOM at runtime, e.g. the time reporting project
 * add/edit overlay
 */
$(document).ready(function()
{
  $('form').preventDoubleSubmit();
});

