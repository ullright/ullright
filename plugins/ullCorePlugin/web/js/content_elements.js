/**
 * Auto load on page load
 */
$(document).ready(function() {
  
  contentElementInitialize();
  
});


/**
 * Do initialisation of the content elements
 */
function contentElementInitialize ()
{
  $('.content_element_html_and_controls').hover(
    function () {
      $(this).css('border', '1px solid silver');
      $(this).css('border-radius', '8px');
      $(this).css('background', '#f9f9f9');
      
      $(this).find('.content_element_controls_edit').fadeIn(300);
      $(this).find('.content_element_controls_add_button').fadeIn(300);
      
    },
    function () {
      $(this).css('border', 'none');
      $(this).css('border-radius', 'none');
      $(this).css('background', 'inherit');
      
      $(this).find('.content_element_controls_edit').hide();
      $(this).find('.content_element_controls_add_button').hide();      
      $(this).find('.content_element_controls_add_list').hide();
    }    
  );
  
  $('.content_element_controls_edit').hover(
      function () {
        $(this).css('border-radius', '8px');
        $(this).css('background', '#eee');
      },
      function () {
        $(this).css('border-radius', 'none');
        $(this).css('background', 'inherit');
      }    
    );    
  
  $('.content_element_controls_add_button').hover(
    function () {
      $(this).css('border-radius', '8px');
      $(this).css('background', '#eee');
    },
    function () {
      $(this).css('border-radius', 'none');
      $(this).css('background', 'inherit');
    }    
  );   
}


/**
 * Switch from view to edit mode
 * 
 * @param element_id
 */
function contentElementEdit(element_id) {

  darkeningCoverEnable();

  var htmlAndControlsClass = '#content_element_html_and_controls_' + element_id;
  var formClass = '#content_element_form_' + element_id;
  
  $(htmlAndControlsClass).fadeOut(300, function () {
    
    $(formClass).css({
      position: "relative",
      "z-index": 1000,
    });       
	
	  $(formClass).fadeIn(300);
  
  });
  
}

/**
 * Cancel edit and return to view mode
 * 
 * @param element_id
 */
function contentElementCancel(element_id) {
  
  var htmlAndControlsClass = '#content_element_html_and_controls_' + element_id;
  var formClass = '#content_element_form_' + element_id;
  
  $(formClass).fadeOut(300, function () {
    
    darkeningCoverDisable();
    $(htmlAndControlsClass).fadeIn(300);
  
  });
  
}

/**
 * Submit form data
 * 
 * @param element_id
 * @param url
 * @param field_id
 */
function contentElementSubmit(element_id, url, field_id) {
	
  var elementClass = '#content_element_' + element_id;
  var formClass = '#content_element_form_' + element_id;
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
      
        if (json.status == 'valid') {
          $(formClass).fadeOut(300, function () {
            
            $(elementClass).replaceWith(json.markup);
            $('#pagecover').fadeOut(1000, function () {
              darkeningCoverDisable()  
            });
            
            
            $(elementClass).hide();
            $(elementClass).fadeIn(2000);
            
            contentElementInitialize();
            contentElementUpdateFormField(field_id);
            
          });
        }
        else {
          $(formClass).replaceWith(json.form);
        }
        
      } catch (e) {
        $(htmlClass).prepend(data);
      }
    }
  });
}


/**
 * Delete an element
 * 
 * @param element_id
 * @param field_id
 */
function contentElementDelete(element_id, field_id) {
  
  var elementClass = '#content_element_' + element_id;
  
  $(elementClass).fadeOut(500, function () {
    $(elementClass).remove();
    contentElementUpdateFormField(field_id);  
  });
    
}


/**
 * Move an element up or down
 * 
 * @param element_id
 * @param field_id
 * @param direction
 */
function contentElementMove(element_id, field_id, direction) {
  
  var elementClass = '#content_element_' + element_id;
  
  if (direction == 'down') {
    var siblingElement = $(elementClass).next();
  }
  else {
    var siblingElement = $(elementClass).prev();
  }
  
  if ($(siblingElement).hasClass('content_element')) {
    var removedElement = $(elementClass).detach();
    
    if (direction == 'down') {
      $(siblingElement).after(removedElement);
    }
    else {
      $(siblingElement).before(removedElement);
    }
  }
  
  contentElementUpdateFormField(field_id);
}


function contentElementAddList(element_id) {
  
  var controlsAddClass = '#content_element_controls_add_' + element_id;
  
  $(controlsAddClass + ' .content_element_controls_add_list').fadeIn(250);
  
}

function contentElementAdd(element, element_id, url, field_id) {
  
  $.ajax({  
    type: "POST",  
    url: url,
    success: function(data) {
      
      try {
        var json = jQuery.parseJSON(data);

        var elementClass = '#content_element_' + element_id;
        
        $(elementClass).after(json.markup);
        
        contentElementEdit(json.element_id);
      
      } catch (e) {
        $(htmlClass).prepend(data);
      }
      

    }
  });

}


/**
 * Update the parent form field with the current element's html data
 * 
 * @param field_id
 */
function contentElementUpdateFormField(field_id) {
  
  var elementsClass = '#content_elements_' + field_id;
  var formFieldClass = '#' + field_id;
  
  // empty the form field
  $(formFieldClass).val('');
  
  $(elementsClass).find('.content_element_html_container').each(function () {
    
    // Append the element's html
    $(formFieldClass).val(
      $(formFieldClass).val() + 
      "\n" + 
      $(this).html()
    );
    
  });
}


/**
 * Apply darkening page cover
 */
function darkeningCoverEnable() {
  
  $("body").prepend('<div id="pagecover"></div>');
  $("#pagecover").css({
    position: "fixed",
    width: "100%",
    height: "100%",
    "background-color": "#000",
    "z-index": 999,
    opacity: 0.5,
    top: 0,
    left: 0,
    display: "none"
  });
  
  $("#pagecover").fadeIn(1000);
  
}


/**
 * Remove darkening page cover
 */
function darkeningCoverDisable()
{
  $('#pagecover').remove();
}

/**
 * Helper function to enlarge the clickarea
 * Used on the surrounding element via onclick="enlargeClickArea(this)"
 * 
 * @param element
 */
function enlargeClickArea(element) {
//  var url = $(element).find('a').attr('href');
//  if ('#' === url) {
    $(element).find('a').triggerHandler('click');
//  }
}


/**
 * @projectDescription jQuery Serialize Anything - Serialize anything (and not just forms!)
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


