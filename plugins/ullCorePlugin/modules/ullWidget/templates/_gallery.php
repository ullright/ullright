<?php $field = $sf_data->getRaw('field') ?>


<!-- ullWidgetGallery --> 
<div id="ull_widget_gallery_container_<?php echo $id ?>" 
  class="ull_widget_gallery_container">
    
  <div id="ull_widget_gallery_control_<?php echo $id ?>" 
    class="ull_widget_gallery_control">
  
    <input type="button" 
      value="<?php echo __('Add files', null, 'ullCoreMessages') ?>" 
      id="ull_widget_gallery_add_files_<?php echo $id ?>"
      class=" ull_widget_gallery_add_files"
    />
      
    <span class="ull_widget_gallery_control_drop">
      <?php echo __('or drag and drop files here', null, 'ullCoreMessages') ?>
      (<?php echo __('With Firefox/Chrome/Safari', null, 'ullCoreMessages') ?>)
    </span>
    
    <span class="ull_widget_gallery_indicator" 
      id="ull_widget_gallery_indicator_<?php echo $id ?>">
      
      <img src="/ullCoreThemeNGPlugin/images/indicator.gif" alt="Indicator" />
      
    </span>
    
  </div>    
    
  <ul class="ull_widget_gallery_preview"
    id="ull_widget_gallery_preview_<?php echo $id ?>">
  </ul>
  
  <?php echo javascript_tag('
      
$(document).ready(function() {
      
  ullWidgetGalleryInitialize(' . 
    '"' . $id . '", ' .
    '"' . $upload_url . '", ' .
    '"' . $preview_url . '", ' .
    '"' . ullCoreTools::getMaxPhpUploadSize() . '", ' .
    '"' . __('Invalid file type', null, 'ullCoreMessages') . '"' .
  ');
      
});      

') ?>
  
  <?php echo $field ?>

<!-- End of ullWidgetGallery -->    
 </div>   