/**
 * Iterates all forms in the current DOM and hides table rows
 * with a class attribute containing 'advanced_form_field'.
 * To each form with at least one hidden field a new link
 * gets appended which shows the hidden fields on activation.
 * @param message the (translated) link text (e.g. 'show adv. options')
 */
function formHideAdvancedOptions(message)
{
  $('form').each(function()
  {
    var advancedFields = $(this).find('.advanced_form_field').parents('tr');
    if (advancedFields.length > 0 && !hasErrorInRows(advancedFields))
    {
      advancedFields.hide();
      var linkId = $(this).attr('id') + '_show_advanced';
      var showLink = '<a href="#" id="' + linkId + '">' + message + '</a>';
      $(this).find('table:first > tbody').append('<tr><td>' + showLink + '</td></tr>');
      $('#' + linkId).click(function()
      {
        $(this).hide();
        advancedFields.fadeIn(500);
        
        return false;
      });
    }
  });
}


/**
 * Helper function which returns true if at least one
 * of the given elements has an error attached
 */
function hasErrorInRows(rows)
{
  var result = false;
  rows.each(function()
  {
    if ($(this).find('td.form_error > ul.error_list').length > 0)
    {
      result = true;
      return false; //ends the each()
    }
  });

  return result;
}
