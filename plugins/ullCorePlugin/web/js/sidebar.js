/**
 * Hideable sidebar code
 * 
 * Registers click handlers to #tab_button_out and #tab_button_in,
 * which animate the hiding and showing of the sidebar div.
 */

  var buttonFadeTime = 700;
  var sidebarFadeTime = 500;
  var sidebarHiddenMargin = "1em";
  var sidebarVisibleMargin = "13em";
 
  var toggleUrl = "/ullUser/toggleSidebar";
 
  $(function()
  {
    $("#tab_button_out").click(function ()
    {
      //Cache needs to be disabled for IE
      $.ajax({ url: toggleUrl, cache: false });

      $("div#sidebar").hide('slide', null, sidebarFadeTime, function()
      {
        $("div#canvas").animate( { marginLeft: sidebarHiddenMargin }, sidebarFadeTime);
      });

      $("#tab_button_out").fadeOut(buttonFadeTime, function()
      {
        $("#tab_button_in").fadeIn(buttonFadeTime);
      });

      return false;
    });

    $("#tab_button_in").click(function ()
    {
      $.ajax({ url: toggleUrl, cache: false });
      
      $("div#canvas").animate( { marginLeft: sidebarVisibleMargin }, sidebarFadeTime, 'linear', function()
      {
        $("div#sidebar").show('slide', null, (sidebarFadeTime / 2));
      });

      $("#tab_button_in").fadeOut(buttonFadeTime, function()
      {
        $("#tab_button_out").fadeIn(buttonFadeTime);
      });

      return false;
    });
  });
