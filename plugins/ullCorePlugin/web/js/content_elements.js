$(document).ready(function() {

	$(".content_element_form").hide();
	
});

function contentElementEdit(id) {
  var htmlClass = '#content_element_' + id;
  
  $(htmlClass).fadeOut(function () {
	var editClass = '#content_element_form_' + id;
	$(editClass).fadeIn();	  
  });
  

  
}