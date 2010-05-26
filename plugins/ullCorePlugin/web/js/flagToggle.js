/**
 * Handler function for ajax-enabled checkboxes which can change
 * their state and report it to the server without a postback.
 * 
 * The state is backed by an UllFlaggable behavior-generated
 * flag, so this function needs a model, a record id and a flag name.
 * 
 * It supports fadeOut/In for a separate element which indicates
 * the processing of the state change.
 *  
 * @param control - The id of the checkbox element
 * @param indicator - The id of a separate element which indicates loading
 * @param urlToFlagHandler - The URL to the server action which handles state changes
 * @param model - The model for which this state change is occuring (e.g. UllClimbingRoute) 
 * @param recordId - The id of the record to which the flag belongs 
 * @param flag - The name of the flag
 * @return nothing
 */
function flagToggle(control, indicator, urlToFlagHandler, model, recordId, flag)
{
  $(control).fadeOut(function()
  {
    $(indicator).fadeIn(function()
    {
      var flagValue = $(control).attr('checked');
      flagValue = (flagValue) ? 'false' : 'true';

      $.ajax(
      {
        url : urlToFlagHandler,
        data :
        {
          'table' : model,
          'id' : recordId,
          'flag' : flag,
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