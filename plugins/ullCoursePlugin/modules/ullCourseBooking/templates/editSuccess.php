<?php require_once sfConfig::get('sf_plugins_dir') . '/ullCorePlugin/modules/ullTableTool/templates/editSuccess.php' ?>

<?php echo javascript_tag('

$(document).ready(function() {
  window.ull_tariffs = $("#fields_ull_course_tariff_id").html();
  
  $("#fields_ull_course_tariff_id").after(
    \'<span id="fields_ull_course_tariff_id_indicator">' . ull_image_tag_indicator() . '</span>\'
  );
  $("#fields_ull_course_tariff_id_indicator").hide();
  
  filterTariffs($("#fields_ull_course_id"));
});


$("#fields_ull_course_id").change(function (){

  filterTariffs($(this));

});
  
function filterTariffs(object) {

  $("#fields_ull_course_tariff_id").hide();
  $("#fields_ull_course_tariff_id_indicator").show();

  $("#fields_ull_course_tariff_id").html(window.ull_tariffs);
  
  var course_id = $(object).val();
  
  $.ajax({
    url: "' . url_for('ullCourseBooking/findTariffsForCourse') . '" + "/course_id/" + course_id,
    success: function( data ) {
    
      var valid_tariff_ids = data.split(",");
    
      $("#fields_ull_course_tariff_id option").each( function() {
        
        if ($(this).val() != "" && jQuery.inArray($(this).val(), valid_tariff_ids) == -1) {
          $(this).remove();
        }
        
      });
      
      $("#fields_ull_course_tariff_id_indicator").hide();
      $("#fields_ull_course_tariff_id").show();
      
    }
  });

}

  


') ?>
