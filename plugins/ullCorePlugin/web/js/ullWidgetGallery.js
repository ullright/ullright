/**
 * Initialize and configure plupload uploader
 */

function ullWidgetGalleryInitialize(
    id, 
    single,
    upload_url, 
    preview_url, 
    max_file_size, 
    invalid_file_type_msg,
    single_msg
  ) {
  
  var uploader = new plupload.Uploader({
    runtimes: "html5",
    browse_button: "ull_widget_gallery_add_files_" + id,
    drop_element: "ull_widget_gallery_container_" + id,
    url: upload_url,
    max_file_size: max_file_size + "mb",
    multi_selection: !single 
  });
  
  uploader.init();
  
  // Bindings
  
  uploader.bind("FilesAdded", function (uploader, files) {
    
    var count = files.length;
    
    if (single && count > 1)
    {
      alert(single_msg);
      
      $.each(files, function (index, file) {
        uploader.removeFile(file)
      });
      
      return false;
    }
    
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
      
      ullWidgetGalleryRefreshPreview(id, preview_url, single);      
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
  
  ullWidgetGalleryRefreshPreview(id, preview_url, single);  
  
};


/**
 * (Re-)render gallery preview
 */
function ullWidgetGalleryRefreshPreview(id, url, single) {
  
  //Load preview only if there are images/files
  if ("" !== $("#" + id).val()) {
  
    var images = $("#" + id).val();
  
    $.ajax({
      url: url,
      data: { images:  images },
      success: function(data) {
        
        $("#ull_widget_gallery_preview_" + id).html(data);
        
        ullWidgetGallerySortable(id);
        ullWidgetGalleryImageActionHover(id);
        ullWidgetGalleryImageDelete(id, url, single);      
        ullWidgetGalleryHideControls(id, single);
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
function ullWidgetGallerySortable(id) {
  
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
function ullWidgetGalleryImageActionHover(id) {
  
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
function ullWidgetGalleryImageDelete(id, preview_url, single) {
  
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
          
          ullWidgetGalleryRefreshPreview(id, preview_url, single);

          $("#ull_widget_gallery_indicator_" + id).hide();
        }
      });
      
      return false;
    });
  });
  
}

/**
 * Handle controlls visibility
 * 
 * @param id
 * @param single
 */
function ullWidgetGalleryHideControls(id, single) {
  
  var selector = "#ull_widget_gallery_control_" + id;
  
  if (!single) {
    $(selector).show();
    return true;
  }
  
  var count = $("#ull_widget_gallery_preview_" + id).children().length;
  
  if (count > 0) {
    $(selector).hide();
  }
  else {
    $(selector).show();
  }
}



