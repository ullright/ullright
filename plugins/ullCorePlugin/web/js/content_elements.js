$(document).ready(function() {

	$(".content_element_form").hide();
	
});



function contentElementEdit(id) {

  // Apply darkeing page cover
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

  var controlsClass = '#content_element_controls_' + id;
  $(controlsClass).fadeOut(300);
  
  var htmlClass = '#content_element_' + id;
  $(htmlClass).fadeOut(300, function () {
    var formClass = '#content_element_form_' + id;
    
    $(formClass).css({
      position: "relative",
      "z-index": 1000,
    });       
	
	  $(formClass).fadeIn(300);
  
  });
  
}

function contentElementSubmit(id, url) {
	
  var htmlClass = '#content_element_' + id;
  var formClass = '#content_element_form_' + id;
  var controlsClass = '#content_element_controls_' + id;
  var indicatorClass = '#content_element_indicator_' + id;
  
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
  
  /*
	
    $.ajax({  
        type: "POST",  
        url: url,
        data: $(formClass).serialize(), 
        success: function(data) {
          // A json response containing the id of the object means ok
          
          alert('ok');
          
          try {
            var json = jQuery.parseJSON(data);
            // save object id for overlay creator (=original edit page) 
            window.overlayId = json.id;
            window.overlayString = json.string;
            // save modified status for overlay creator to trigger widget reload
            window.overlayIsModified = true;
            $("#overlay").overlay().close();
          }
          
          // Otherwise a validation error occured.
          // We got the normal html markup (form with error msgs) and
          //   re-render it 
          
//          catch (e) {
//            var wrap = $("#overlay").overlay().getOverlay().find(".overlayContentWrap");
//            wrap.html(data);
//            wrap.scrollTop(0);
//          }  
        }  
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