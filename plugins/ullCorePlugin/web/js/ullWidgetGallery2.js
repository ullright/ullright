/**
 * Initialize and configure plupload uploader
 */

function ullWidgetGallery2Initialize(id, upload_url, preview_url, max_file_size, invalid_file_type_msg) {

  var uploader = new plupload.Uploader({
    preinit: ullWidgetGallery2PluploadAttachCallbacks(id, preview_url),
    runtimes: "html5",
    browse_button: "ull_widget_gallery_add_files_" + id,
    drop_element: id + "_content",
    url: upload_url,
    max_file_size: max_file_size + "mb"
  });
  
  uploader.init();
  
  // Bindings
  
  uploader.bind("FilesAdded", function () {
    $("#ull_widget_gallery_control_indicator_" + id).show();
    uploader.start();
  });
  
  uploader.bind("Error", function(up, err) {
//    $("#filelist").append("<div>Error: " + err.code +
//      ", Message: " + err.message +
//      (err.file ? ", File: " + err.file.name : "") +
//      "</div>"
//    );
    
    $("#" + id + "_content").append("<div class=\"form_error\">" + 
        invalid_file_type_msg + ": " +
      (err.file ? err.file.name : "") + "</div>"
    );
    
//    alert("' . __('Fehler: ungültiger Dateityp', null, 'ullCoreMessages') . ': " +
//      (err.file ? err.file.name : "") 
//    );
    
    $("#ull_widget_gallery_control_indicator_" + id).hide();
    
    //up.refresh(); // Reposition Flash/Silverlight
  });  
  

  ullWidgetGallery2RefreshPreview(id, preview_url);
};


/**
 * Plupload uploader callbacks
 */
function ullWidgetGallery2PluploadAttachCallbacks(Uploader, id, preview_url) {
  Uploader.bind("FileUploaded", function(up, file, response) {
    
    // Add new image to the form field
    $("#" + id).val($("#" + id).val() + "\n" + response.response);
    
    refreshGalleryPreview(id, preview_url);

    if ((Uploader.total.uploaded + 1) == Uploader.files.length)
    {
      $("#ull_widget_gallery_control_indicator_" + id).hide();
    }
    
  });
}


/**
 * Re-render gallery preview
 */
function ullWidgetGallery2RefreshPreview(id, preview_url) {
  
  var images = $("#" + id).val();
  
  $("#" + id).parents("td").find(".ull_widget_gallery_preview").load(
    preview_url,
    { images:  images },
    function () {
      ullWidgetGallery2Sortable(id);
      ullWidgetGallery2ImageActionHover();
      imageDelete(id);
    }
  );
  
}
 
/**
 * Drag'n'drop sort 
 * 
 */
function ullWidgetGallery2Sortable(id) {
  
  // TODO idize ?!?
  $(".ull_widget_gallery_preview").sortable({
  
    // Update form field after sort action
    stop: function(event, ui) {
      var content = "window.ull_widget_gallery_" + id + "_content";
      content = "";
      $(".ull_widget_gallery_preview_image").find("img").each(function() {
        content = content + "\n" + $(this).attr("rel");   
      });
      $("#" + id).val(content);
    }
  });
  
};


/**
 * Hover actions
 */
function ullWidgetGallery2ImageActionHover() {
  
  // TODO idize ?!?
  
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

/**
 * Add delete bindung
 * 
 * @param id
 * @param preview_url
 * @returns
 */
function ullWidgetGallery2ImageDelete(id, preview_url) {
  
  $(".ull_widget_gallery_actions a").each(function(index, element) {
    $(element).click(function(){
      $("#ull_widget_gallery_control_indicator_" + id).show();
      $.ajax({
        url: $(element).attr("href"),
        success: function(){
          // delete image from form field
          var path = $(element).parents("li").find("img").attr("rel");
          var value = $("#" + id ).val();
          value = value.split(path).join("");
          $("#" + id).val(value);
          
          refreshGalleryPreview(id, preview_url);
          $("#ull_widget_gallery_control_indicator_" + id).hide();
        }
      });
      
      return false;
    });
  });
  
}


