<?php

class ullWidgetGalleryWrite extends sfWidgetFormInputHidden
{
  
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('model');
    $this->addOption('column');
    
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $this->setAttribute('name', $name);
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');   

    $return = '';
    
    $return .= '<div id="' . $id . '_content">';
    
    $return .= parent::render($name, $value, $attributes, $errors);
    
    $return .= '  <div class="ull_widget_gallery_control">';
    $return .= '    <input type="button" value="' . __('Add files', null, 'ullCoreMessages') . '" id="ull_widget_gallery_add_files_' . $id . '" />';
    $return .= '    <span class="ull_widget_gallery_control_drop">';
    $return .= '      ' . __('or drag and drop files here', null, 'ullCoreMessages');
    $return .= '      (' .  __('Currently only with Firefox', null, 'ullCoreMessages') . ')';
    $return .= '    </span>';
    $return .= '    <span class="ull_widget_gallery_control_indicator" id="ull_widget_gallery_control_indicator_' . $id . '">';
    $return .= '      <img src="/ullCoreThemeNGPlugin/images/indicator.gif" alt="Indicator" />';
    $return .= '    </span>';
    $return .= '  </div>';    
    
    $return .= '  <ul class="ull_widget_gallery_preview">';
    $return .= '  </ul>';
    
    $return .= '
<script type="text/javascript">
//<![CDATA[

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


/**
 * Initialize and configure plupload uploader
 */
$(document).ready(function() {
  var uploader = new plupload.Uploader({
    preinit: attachCallbacks,
    runtimes: "html5",
    browse_button: "ull_widget_gallery_add_files_' . $id . '",
    drop_element: "' . $id . '_content",
    url: "' . url_for('ullPhoto/imageUpload?model=' . $this->getOption('model') . '&column=' . $this->getOption('column')) . '",
    max_file_size: "' . ullCoreTools::getMaxPhpUploadSize() . 'mb"
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
  
});


$(document).ready(function()
{
  refreshGalleryPreview();
});

//]]>
</script>
';

    $return .= '</div><!-- end of widget content -->';
    
    return $return;
  }
  
  
  /** 
   * Render the gallery preview 
   * 
   * @param string $images separated by newlines \n
   * @param boolean $renderUl
   * @return string
   */
  public static function renderPreview($images)
  {
    $return = '';
    
    $images = explode("\n", $images);
    
    foreach ($images as $image)
    {
      // ignore empty lines
      if (trim($image))
      {
        // ignore invalid stuff
        if (file_exists(ullCoreTools::webToAbsolutePath($image)))
        {
          // Check for thumbnails
          $thumbnail = ullCoreTools::calculateThumbnailPath($image);
          $thumbnailAbsolutePath = ullCoreTools::webToAbsolutePath($thumbnail);
          if (!file_exists(ullCoreTools::webToAbsolutePath($thumbnail)))
          {
            $thumbnail = $image;
          }
          
          $return .= '<li>';
          $return .= '<div class="ull_widget_gallery_preview_image_container">';
          $return .= '  <div class="ull_widget_gallery_preview_image">';
          $return .= '    <a href="'. $image . '" target="_blank"><img src="' . $thumbnail .'" alt="preview image" rel="' . $image . '" /></a>';
          $return .= '  </div>';
          $return .= '</div>';
          $return .= '  <div class="ull_widget_gallery_actions">';
          $return .= '    ' . ull_icon('ullPhoto/imageDelete?s_image=' . $image, 'delete');
          $return .= '  </div>';        
          $return .= '</li>';
        }
      }
    }
    
    return $return;
  }
  
  
  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    return array(
      '/ullCorePlugin/js/jq/jquery-min.js',
      '/ullCorePlugin/js/jq/jquery-ui-min.js',
      '/ullCorePlugin/js/plupload/plupload.full.js',
      '/ullCorePlugin/js/plupload/i18n/de.js',
      '/ullCorePlugin/js/plupload/jquery.ui.plupload/jquery.ui.plupload.js',
    );
  }
  
  
  public function getStylesheets()
  {
    return array(
      '/ullCorePlugin/css/jqui/jquery-ui.css' => 'all',
      '/ullCorePlugin/css/ull_gallery.css' => 'all',
      '/ullCorePlugin/js/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css' => 'all',
    );
  }
  
}

