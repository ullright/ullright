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
