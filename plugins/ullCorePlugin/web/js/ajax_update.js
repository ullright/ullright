/**
 * Handler function for ajax-enabled fields (currently only checkboxes) which can change
 * their state and report it to the server without a postback.
 *  
 * It supports fadeOut/In for a separate element which indicates
 * the processing of the state change.
 *  
 * @param control - The input object
 * @param indicator_id - The id of a separate element which indicates loading
 * @param url - Ajax processing url
 * 
 * @return nothing
 */
function ajax_update(control, indicator_id, url)
{
  $(control).fadeOut(function()
  {
	var indicator = $('#' + indicator_id);  
    $(indicator).fadeIn(function()
    {
      var flagValue = $(control).attr('checked');
      flagValue = (flagValue) ? 'false' : 'true';

      $.ajax(
      {
        url : url,
        data :
        {
          'value' : flagValue
        },
        cache : false,
        success : function(data, textStatus, XMLHttpRequest)
        {
          $(indicator).fadeOut(function()
          {
            $(control).attr("checked", !$(control).attr('checked'));
            $(control).fadeIn();
          });
        },
        error : function(XMLHttpRequest, textStatus, errorThrown)
        {
          alert('Sorry, your change could not be processed.');

          $(indicator).fadeOut(function()
           {
            $(control).fadeIn();
          });
        }
      });
    });
  });
}