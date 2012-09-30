/**
 * Initialize and configure plupload uploader
 */

function ullWidgetGallery2Initialize(
    id, 
    upload_url, 
    preview_url, 
    max_file_size, 
    invalid_file_type_msg 
  ) {

  var uploader = new plupload.Uploader({
    runtimes: "html5",
    browse_button: "ull_widget_gallery_add_files_" + id,
    drop_element: id + "_content",
    url: upload_url,
    max_file_size: max_file_size + "mb",
  });
  
  uploader.init();
  
  // Bindings
  
  uploader.bind("FilesAdded", function () {
    
    $("#ull_widget_gallery_indicator_" + id).show();
    uploader.start();
    
  });
  
  uploader.bind('FileUploaded', function(uploader, file, response) {
    
    if (200 === response.status)
    {
      // Add new image to the form field
      $("#" + id).val(
        $("#" + id).val() + 
        "\n" + response.response
      );
      
      ullWidgetGallery2RefreshPreview(id, preview_url);      
    }
    else {
      alert('Sorry, an error has occured');
    }
    
  });
  
  uploader.bind('UploadComplete', function(uploader, files) {
    
    $("#ull_widget_gallery_indicator_" + id).hide();
    
  });
  
  uploader.bind("Error", function(up, err) {
    
    $("#" + id + "_content").append("<div class=\"form_error\">" + 
        invalid_file_type_msg + ": " +
      (err.file ? err.file.name : "") + "</div>"
    );
    
    $("#ull_widget_gallery_indicator_" + id).hide();
    
  });  
  
  ullWidgetGallery2RefreshPreview(id, preview_url);  
  
};


/**
 * (Re-)render gallery preview
 */
function ullWidgetGallery2RefreshPreview(id, url) {
  
  //Load preview only if there are images/files
  if ("" !== $("#" + id).val()) {
  
    var images = $("#" + id).val();
  
    $.ajax({
      url: url,
      data: { images:  images },
      success: function(data) {
        
        $("#ull_widget_gallery_preview_" + id).html(data);
        
        ullWidgetGallery2Sortable(id);
        ullWidgetGallery2ImageActionHover(id);
        ullWidgetGallery2ImageDelete(id, url);      
      }
    });
  } 
  else {
    // Empty the preview
    $("#ull_widget_gallery_preview_" + id).html();
  }
}
 
/**
 * Drag'n'drop sort 
 * 
 */
function ullWidgetGallery2Sortable(id) {
  
  // TODO idize ?!?
  $("#ull_widget_gallery_preview_" + id).sortable({
  
    // Update form field after sort action
    stop: function(event, ui) {
//      var content = "window.ull_widget_gallery_" + id + "_content";
      var content = "";
      var selector = "#ull_widget_gallery_preview_" + id +
        " .ull_widget_gallery_preview_image"; 
        
      $(selector).find("img").each(function() {
        content = content + "\n" + $(this).attr("rel");   // TODO: why rel?
      });
      
      $("#" + id).val(content);
    }
  });
  
};


/**
 * Hover actions
 */
function ullWidgetGallery2ImageActionHover(id) {
  
  var selector = "#ull_widget_gallery_preview_" + id + " li";
  
  $(selector).each(function(index, element) {
    $(element).mouseenter(function() {
      $(element).find(".ull_widget_gallery_actions").show();
    });
  });
  
  $(selector).each(function(index, element) {
    $(element).mouseleave(function() {
      $(element).find(".ull_widget_gallery_actions").hide();
    });
  });
  
}   


/**
 * Add delete binding
 * 
 * @param id
 * @param preview_url
 * @returns
 */
function ullWidgetGallery2ImageDelete(id, preview_url) {
  
  var selector = "#ull_widget_gallery_preview_" + id +
    " .ull_widget_gallery_actions a";
  
  $(selector).each(function(index, element) {
    
    $(element).click(function(){
      
      $("#ull_widget_gallery_indicator_" + id).show();
      
      $.ajax({
        url: $(element).attr("href"),
        success: function(){
          // delete image from form field
          var path = $(element).parents("li").find("img").attr("rel"); //TODO: why rel?
          var value = $("#" + id ).val();
          value = value.split(path).join("");
          $("#" + id).val(value);
          
          ullWidgetGallery2RefreshPreview(id, preview_url);

          $("#ull_widget_gallery_indicator_" + id).hide();
        }
      });
      
      return false;
    });
  });
  
}


