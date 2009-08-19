// javascripts for the ullVentory createSuccess template

$(document).ready(function()
{
  $("#type_submit").hide();

  $("#fields_type").change(
    function() 
    { 
      $("#ull_ventory_form").submit();
    }
  );
});