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
function ajax_update(control, indicator_id, url) {
	
  var indicator = $('#' + indicator_id);
	
  $(control).fadeOut(300, function() {
    $(indicator).fadeIn(300);
  });
  
  // normalize different input types
  if ('checkbox' === $(control).attr('type')) {
    var value = $(control).is(':checked')
  }  
  else {
    var value = $(control).val();
  }
  
  $.ajax({
    url: url,
    data: {
      'value' : value
    },
    cache : false,
    success : function(data, textStatus, XMLHttpRequest) {
      $(indicator).fadeOut(300, function() {
        $(indicator).hide();
        $(control).attr("checked", !$(control).attr('checked'));
        $(control).fadeIn(300);
      });
    },
    error : function(XMLHttpRequest, textStatus, errorThrown) {
      alert('Sorry, your change could not be processed.');

      $(indicator).fadeOut(function() {
        $(control).fadeIn();
      });
    }
  });
  
}