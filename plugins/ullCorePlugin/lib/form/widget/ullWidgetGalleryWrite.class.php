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

    $return .= '
    <form id="foo">
<div id="uploader">
    <!--
    <p>Upload Files:</p>
    
    <a id="pickfiles" href="javascript:;">Add Files</a>
    <a id="uploadfiles" href="javascript:;">Start Upload</a>
    --> 

</div>
</form>

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

  // Client side form validation
  $("#form").submit(function(e) {
    var uploader = $("#uploader").pluploadQueue();
    
    uploader.bind("FileUploaded", function(up, file, res) {
      alert("whoo");
    });
    
    /*
    uploader.bind("FileUploaded", function(up, file, response) {
      alert(response.response);
      var obj = jQuery.parseJSON(response.response);
    });
    */     

    // Validate number of uploaded files
    if (uploader.total.uploaded == 0) {
      // Files in queue upload them first
      if (uploader.files.length > 0) {
        // When all files are uploaded submit form
        uploader.bind("UploadProgress", function() {
          if (uploader.total.uploaded == uploader.files.length)
            $("form").submit();
        });

        uploader.start();
      } else
        alert("You must at least upload one file.");

      e.preventDefault();
    }

  });
  
});


function attachCallbacks(Uploader) {
  Uploader.bind("FileUploaded", function(up, file, response) {
    //alert(response.response);
     $("#' . $id . '").val($("#' . $id . '").val() + "\n" + response.response);
  });
}


</script>


<script type="text/javascript">
//<![CDATA[
/*

    var uploader = new plupload.Uploader({
        runtimes: "html5",
        browse_button: "pickfiles",
        url: "' . url_for('main/test') . '"
    });
    
    uploader.init();
    
    document.getElementById("uploadfiles").onclick = function() {
        uploader.start();
    };
*/
//]]>
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
      '/ullCorePlugin/js/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css' => 'all',
    );
  }
  
}

