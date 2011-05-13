<?php

class ullWidgetGalleryWrite extends sfWidgetFormTextarea
{
  
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('path');
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $this->setAttribute('name', $name);
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');   

    $return = parent::render($name, $value, $attributes, $errors);
    
    
    $return .= self::renderPreview($value);

    $return .= '<input type="button" value="Click to add images" id="add_files" />';
    $return .= '<div id="drag_drop">Or drag and drop files here</div>';
    
//    $return .= '
//<div id="uploader">
//</div>
//    ';    

    $returnxxx = '

<script type="text/javascript">
// Convert divs to queue widgets when the DOM is ready
$(function() {
  $("#uploader").plupload({
    // General settings
    preinit: attachCallbacks,
    runtimes : "html5",
    url : "' . url_for('main/test') . '",
    max_file_size : "10mb",
    chunk_size : "1mb",
    unique_names : true,

    // Resize images on clientside if we can
    resize : {width : 320, height : 240, quality : 90},

    // Specify what files to browse for
    filters : [
      {title : "Image files", extensions : "jpg,gif,png"},
      {title : "Zip files", extensions : "zip"}
    ],
  });
  
//  // Client side form validation
//  $("#form").submit(function(e) {
//    var uploader = $("#uploader").pluploadQueue();
//    
//    uploader.bind("FileUploaded", function(up, file, res) {
//      alert("whoo");
//    });
//    
//    /*
//    uploader.bind("FileUploaded", function(up, file, response) {
//      alert(response.response);
//      var obj = jQuery.parseJSON(response.response);
//    });
//    */     
//
//    // Validate number of uploaded files
//    if (uploader.total.uploaded == 0) {
//      // Files in queue upload them first
//      if (uploader.files.length > 0) {
//        // When all files are uploaded submit form
//        uploader.bind("UploadProgress", function() {
//          if (uploader.total.uploaded == uploader.files.length)
//            $("form").submit();
//        });
//
//        uploader.start();
//      } else
//        alert("You must at least upload one file.");
//
//      e.preventDefault();
//    }
//
//  });

});
</script>
';
    
    $return .= '
<script type="text/javascript">

function attachCallbacks(Uploader) {
  Uploader.bind("FileUploaded", function(up, file, response) {
    // Add new image to the form field
    $("#' . $id . '").val($("#' . $id . '").val() + "\n" + response.response);
    
    // Re-render gallery preview
    var images = $("#' . $id . '").val();
    var url = "' . url_for('main/renderGalleryPreview') . '";
    
    $("#' . $id . '").parent("td").find(".ull_widget_gallery_preview").load(
      url,
      { images:  images }
    );
  });
}

</script>
';    
    
      $return .= '  
<script type="text/javascript">
//<![CDATA[

    var uploader = new plupload.Uploader({
      preinit: attachCallbacks,
      runtimes: "html5",
      browse_button: "add_files",
      drop_element: "drag_drop",
      url: "' . url_for('main/test') . '"
    });
    
    uploader.init();
    
    uploader.bind("FilesAdded", function () {
      uploader.start();
    });
    
//    document.getElementById("uploadfiles").onclick = function() {
//        uploader.start();
//    };
//]]>
</script>
';    



     $return .= '
<script type="text/javascript">   

$(document).ready(function() {
  $(".ull_widget_gallery_preview").sortable({
    stop: function(event, ui) {
      window.ull_widget_gallery_' . $id . '_content = "";
      $(".ull_widget_gallery_preview").find("img").each(function() {
        window.ull_widget_gallery_' . $id . '_content = window.ull_widget_gallery_' . $id . '_content + "\n" + $(this).attr("src");   
      });
      $("#' . $id . '").val(window.ull_widget_gallery_' . $id . '_content);
    }
  });
});


</script>
';


    
    
    /*
    $return = '';
    
    $return .= ullWidgetSimpleUploadRead::renderFile($value, $this->getOption('path'), $this->getAttribute('alt'));
    

    
    $return .= parent::render(
      $name, 
      $value, 
      array_merge($attributes, array('class' =>'ull_widget_simple_upload_image_input')), 
      $errors
    );
    */
    
    return $return;
  }
  
  
  /** 
   * Render the gallery preview 
   * 
   * @param string $images separated by newlines \n
   * @param boolean $renderUl
   * @return string
   */
  public static function renderPreview($images, $renderUl = true)
  {
    $return = '';
    
    $images = explode("\n", $images);
    
    if ($renderUl)
    {
      $return .= '<ul class="ull_widget_gallery_preview">';
    }
    
    foreach ($images as $image)
    {
      if (trim($image))
      {
        $return .= '<li class="ull_widget_gallery_preview_image_container">';
        $return .= '<div class="ull_widget_gallery_preview_image">';
        $return .= '<img src="' . $image .'" alt="preview image" />';
        $return .= '</div>';
        $return .= '</li>';
      }
    }
    
    if ($renderUl)
    {
      $return .= '</ul>';
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
      '/ullCorePlugin/js/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css' => 'all',
    );
  }
  
}

