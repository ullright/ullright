<?php $field = $sf_data->getRaw('field') ?>


<!-- ullWidgetGallery2 --> 
<div id="<?php echo $id ?>">
    
  <?php echo $field ?>
    
  <div class="ull_widget_gallery_control">
  
    <input type="button" value="' . __('Add files', null, 'ullCoreMessages') . '" 
      id="ull_widget_gallery_add_files_' . $id . '" />
      
    <span class="ull_widget_gallery_control_drop">
      ' . __('or drag and drop files here', null, 'ullCoreMessages');
      (' .  __('Currently only with Firefox', null, 'ullCoreMessages') . ')
    </span>
    
    <span class="ull_widget_gallery_control_indicator" 
      id="ull_widget_gallery_control_indicator_' . $id . '">
      
      <img src="/ullCoreThemeNGPlugin/images/indicator.gif" alt="Indicator" />
      
    </span>
    
  </div>    
    
  <ul class="ull_widget_gallery_preview">
  </ul>
  
  <?php echo javascript_tag('
      
$(document).ready(function() {
      
  ullWidgetGallery2Initialize(' . 
    '"' . $id . '", ' .
    '"' . $url . '", ' .
    '"' . $max_file_size . '"' .
  ');
      
});      

') ?>

<!-- End of ullWidgetGallery2 -->    
 </div>   
 
 


/**
 * Initialize and configure plupload uploader
 */
$(document).ready(function() {
  var uploader = new plupload.Uploader({
    preinit: attachCallbacks,
    runtimes: "html5",
    browse_button: "ull_widget_gallery_add_files_' . $id . '",
    drop_element: "' . $id . '_content",
    url: "' . url_for('ullPhoto/imageUpload?s_m=' . $this->getOption('model') .
      '&s_ccc=' . $this->getOption('columns_config_class') . 
      '&s_c=' . $this->getOption('column')) . '",
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
  

  refreshGalleryPreview();
});

//]]>
</script>
'; 