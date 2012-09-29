/**
 * Initialize and configure plupload uploader
 */

function ullWidgetGallery2Initialize(id, url, max_file_size) {

  var uploader = new plupload.Uploader({
    preinit: attachCallbacks,
    runtimes: "html5",
    browse_button: "ull_widget_gallery_add_files_" + id,
    drop_element: id + "_content",
    url: url,
    max_file_size: max_file_size + 'mb"
  });
  
  uploader.init();
  
  uploader.bind("FilesAdded", function () {
    $("#ull_widget_gallery_control_indicator_' . $id . '").show();
    uploader.start();
  });
  
  uploader.bind("Error", function(up, err) {
//    $("#filelist").append("<div>Error: " + err.code +
//      ", Message: " + err.message +
//      (err.file ? ", File: " + err.file.name : "") +
//      "</div>"
//    );
    
    $("#' . $id . '_content").append("<div class=\"form_error\">" + 
      "' . __('Invalid file type', null, 'ullCoreMessages') . ': " +
      (err.file ? err.file.name : "") + "</div>"
    );
    
//    alert("' . __('Fehler: ungültiger Dateityp', null, 'ullCoreMessages') . ': " +
//      (err.file ? err.file.name : "") 
//    );
    
    $("#ull_widget_gallery_control_indicator_' . $id . '").hide();
    
  //up.refresh(); // Reposition Flash/Silverlight
  });  
  

  refreshGalleryPreview();
});





/**
 * Re-render gallery preview
 */
 
function refreshGalleryPreview() {
  var images = $("#' . $id . '").val();
  var url = "' . url_for('ullPhoto/renderGalleryPreview') . '";
  $("#' . $id . '").parents("td").find(".ull_widget_gallery_preview").load(
    url,
    { images:  images },
    function () {
      sortable();
      imageActionHover();
      imageDelete();
    }
  );
}
 
function sortable() {
  $(".ull_widget_gallery_preview").sortable({
  
    // Update form field after sort action
    stop: function(event, ui) {
      window.ull_widget_gallery_' . $id . '_content = "";
      $(".ull_widget_gallery_preview_image").find("img").each(function() {
        window.ull_widget_gallery_' . $id . '_content = window.ull_widget_gallery_' . $id . '_content + "\n" + $(this).attr("rel");   
      });
      $("#' . $id . '").val(window.ull_widget_gallery_' . $id . '_content);
    }
  });
};

function imageActionHover() {
  $(".ull_widget_gallery_preview li").each(function(index, element) {
    $(element).mouseenter(function() {
      $(element).find(".ull_widget_gallery_actions").show();
    });
  });
  
  $(".ull_widget_gallery_preview li").each(function(index, element) {
    $(element).mouseleave(function() {
      $(element).find(".ull_widget_gallery_actions").hide();
    });
  });
}   

function imageDelete() {
  $(".ull_widget_gallery_actions a").each(function(index, element) {
    $(element).click(function(){
      $("#ull_widget_gallery_control_indicator_' . $id . '").show();
      $.ajax({
        url: $(element).attr("href"),
        success: function(){
          // delete image from form field
          var path = $(element).parents("li").find("img").attr("rel");
          var value = $("#' . $id . '").val();
          value = value.split(path).join("");
          $("#' . $id . '").val(value);
          
          refreshGalleryPreview();
          $("#ull_widget_gallery_control_indicator_' . $id . '").hide();
        }
      });
      return false;
    });
  });
}


/**
 * Plupload uploader callbacks
 */
function attachCallbacks(Uploader) {
  Uploader.bind("FileUploaded", function(up, file, response) {
    // Add new image to the form field
    $("#' . $id . '").val($("#' . $id . '").val() + "\n" + response.response);
    
    refreshGalleryPreview();

    if ((Uploader.total.uploaded + 1) == Uploader.files.length)
    {
      $("#ull_widget_gallery_control_indicator_' . $id . '").hide();
    }
    
  });
}