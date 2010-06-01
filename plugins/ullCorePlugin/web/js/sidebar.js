/**
 * Hideable sidebar code
 * 
 * Registers click handlers to hide and show controls,
 * which animate the hiding and showing of the sidebar
 * and canvas divs.
 */

var buttonFadeTime = 700;
var sidebarFadeTime = 500;
var sidebarHiddenMargin = "1em";
var sidebarVisibleMargin = "13em";
 
var toggleUrl = "/ullUser/toggleSidebar";
 
function enableSidebar(startHidden, hideControl, showControl, sidebarDiv, canvasDiv)
{
  if (startHidden)
  {
    $(hideControl).addClass('invisible');
    $(sidebarDiv).addClass('invisible');
    $(canvasDiv).css('margin-left', '1em');
  }
  else
  {
    $(showControl).addClass('invisible');
  }
  
  $(hideControl).click(function()
  {
    //Cache needs to be disabled for IE
    $.ajax({ url: toggleUrl, cache: false });
    $(sidebarDiv).hide('slide', null, sidebarFadeTime, function()
    {
      $(canvasDiv).animate( { marginLeft: sidebarHiddenMargin }, sidebarFadeTime);
    });

    $(hideControl).fadeOut(buttonFadeTime, function()
    {
      $(showControl).fadeIn(buttonFadeTime);
    });

    return false;
  });

  $(showControl).click(function ()
  {
    $.ajax({ url: toggleUrl, cache: false });
    
    $(canvasDiv).animate( { marginLeft: sidebarVisibleMargin }, sidebarFadeTime, 'linear', function()
    {
      $(sidebarDiv).show('slide', null, (sidebarFadeTime / 2));
    });

    $(showControl).fadeOut(buttonFadeTime, function()
    {
      $(hideControl).fadeIn(buttonFadeTime);
    });

    return false;
  });
}
