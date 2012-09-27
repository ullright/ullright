$(document).ready(function() {

	$(".content_element_form").hide();
	
});



function contentElementEdit(element_id) {

  // Apply darkening page cover
  $("body").prepend('<div id="pagecover"></div>');
  $("#pagecover").css({
      position: "fixed",
      width: "100%",
      height: "100%",
      "background-color": "#000",
      "z-index": 999,
      opacity: 0.5,
      top: 0,
      left: 0
  });

  var controlsClass = '#content_element_controls_' + element_id;
  var htmlClass = '#content_element_' + element_id;
  var formClass = '#content_element_form_' + element_id;
  
  $(controlsClass).fadeOut(300);
  
  $(htmlClass).fadeOut(300, function () {
    
    $(formClass).css({
      position: "relative",
      "z-index": 1000,
    });       
	
	  $(formClass).fadeIn(300);
  
  });
  
}

function contentElementSubmit(element_id, url, field_id) {
	
  var htmlClass = '#content_element_' + element_id;
  var formClass = '#content_element_form_' + element_id;
  var controlsClass = '#content_element_controls_' + element_id;
  var indicatorClass = '#content_element_indicator_' + element_id;
  
  $.ajax({  
    type: "POST",  
    url: url,
    data: $(formClass).serializeAnything(),
    beforeSend: function() {
      $(indicatorClass).show();
    },
    success: function(data) {
      
      $(indicatorClass).hide();
      
      try {
        var json = jQuery.parseJSON(data);
      
        if (json.status == 'valid')
        {
          $(htmlClass).replaceWith(json.html);
          $(formClass).replaceWith(json.form);
          
          // replace content in original form field proxy field
          var proxyClass = '#' + field_id + '_proxy' + ' ' + htmlClass;
          $(proxyClass).replaceWith(json.html);
          
          // replace actual value in original form_field
          var fieldClass = '#' + field_id;
          $(fieldClass).val($(proxyClass).parent().html());

          // fade
          $(formClass).fadeOut(300, function () {
            $(controlsClass).fadeIn(300);
            $(htmlClass).fadeIn(300);
          });
          
          $('#pagecover').remove();
        }
        else
        {
          $(formClass).replaceWith(json.form);
        }
        
      } catch (e) {
        $(htmlClass).prepend(data);
      }
    }
  });
}


function contentElementCancel(element_id) {
  
  var controlsClass = '#content_element_controls_' + element_id;
  var htmlClass = '#content_element_' + element_id;
  var formClass = '#content_element_form_' + element_id;
  
  $(formClass).fadeOut(300, function () {
    
    $('#pagecover').remove();
    $(controlsClass).fadeIn(300);
    $(htmlClass).fadeIn(300);
  
  });
  
}

function contentElementDelete(element_id, field_id) {
  
  var controlsClass = '#content_element_controls_' + element_id;
  var htmlClass = '#content_element_' + element_id;
  var formClass = '#content_element_form_' + element_id;
  var proxyClass = '#' + field_id + '_proxy'
  var proxyFieldClass = proxyClass + ' ' + htmlClass;
  var fieldClass = '#' + field_id;
  
  // delete content in original form field proxy field
  $(proxyFieldClass).remove();
  
  // replace actual value in original form_field
  $(fieldClass).val($(proxyClass).html());

  // fade out...
  $(controlsClass).fadeOut(500);
  $(htmlClass).fadeOut(500, function () {
    // ... and remove
    $(controlsClass).remove();
    $(htmlClass).remove();
    $(formClass).remove();    
  });
}

function contentElementMove(element_id, field_id, direction) {
  
  // Markup
  var markupClass = '#content_element_markup_' + element_id;
  
  if (direction == 'down') {
    var siblingElement = $(markupClass).next();
  }
  else {
    var siblingElement = $(markupClass).prev();
  }
  
  if ($(siblingElement).hasClass('content_element_markup')) {
    var removedElement = $(markupClass).remove();
    
    if (direction == 'down') {
      $(siblingElement).after(removedElement);
    }
    else {
      $(siblingElement).before(removedElement);
    }
  }
  
  // Data
  var htmlClass = '#content_element_' + element_id;
  var proxyClass = '#' + field_id + '_proxy'
  var proxyFieldClass = proxyClass + ' ' + htmlClass;
  var fieldClass = '#' + field_id;
  
  if (direction == 'down') {
    var siblingElement = $(proxyFieldClass).next();
  }
  else {
    var siblingElement = $(proxyFieldClass).prev();
  }
  
  if ($(siblingElement).hasClass('content_element')) {
    var removedElement = $(proxyFieldClass).remove();
    
    if (direction == 'down') {
      $(siblingElement).after(removedElement);
    }
    else {
      $(siblingElement).before(removedElement);
    }
  }  
  
  // replace actual value in original form_field
  $(fieldClass).val($(proxyClass).html());  
  
  
  
  
  
  
  /*
  var controlsClass = '#content_element_controls_' + element_id;
  var htmlClass = '#content_element_' + element_id;
  var formClass = '#content_element_form_' + element_id;
  var proxyClass = '#' + field_id + '_proxy'
  var proxyFieldClass = proxyClass + ' ' + htmlClass;
  var fieldClass = '#' + field_id;
  
  // delete content in original form field proxy field
  $(proxyFieldClass).remove();
  
  // replace actual value in original form_field
  $(fieldClass).val($(proxyClass).html());

  // fade out...
  $(controlsClass).fadeOut(500);
  $(htmlClass).fadeOut(500, function () {
    // ... and remove
    $(controlsClass).remove();
    $(htmlClass).remove();
    $(formClass).remove();    
  });
  */
}

/* @projectDescription jQuery Serialize Anything - Serialize anything (and not just forms!)
 * @author Bramus! (Bram Van Damme)
 * @version 1.0
 * @website: http://www.bram.us/
 * @license : BSD
*/
 
(function($) {
 
    $.fn.serializeAnything = function() {
 
        var toReturn    = [];
        var els         = $(this).find(':input').get();
 
        $.each(els, function() {
            if (this.name && !this.disabled && (this.checked || /select|textarea/i.test(this.nodeName) || /text|hidden|password/i.test(this.type))) {
                var val = $(this).val();
                toReturn.push( encodeURIComponent(this.name) + "=" + encodeURIComponent( val ) );
            }
        });
 
        return toReturn.join("&").replace(/%20/g, "+");
 
    }
 
})(jQuery);