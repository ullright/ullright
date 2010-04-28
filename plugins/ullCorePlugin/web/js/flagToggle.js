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