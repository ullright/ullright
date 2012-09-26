$(document).ready(function() {

//	$(".content_element_form").hide();
	
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
	
  var formClass = '#content_element_form_' + id;
  
//  alert($(formClass).serializeAnything());
  
	
//	alert($(formClass).serialize());
	
  $.ajax({  
    type: "POST",  
    url: url,
    data: $(formClass).serializeAnything(), 
    success: function(data) {
      // A json response containing the id of the object means ok
      
      $(formClass).html(data);
      
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