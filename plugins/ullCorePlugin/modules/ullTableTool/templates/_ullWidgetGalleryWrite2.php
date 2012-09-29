<?php $field = $sf_data->getRaw('field') ?>


<!-- ullWidgetGallery2 --> 
<div id="<?php echo $id ?>">
    
  <?php echo $field ?>
    
  <div class="ull_widget_gallery_control">
  
    <input type="button" 
      value="<?php echo __('Add files', null, 'ullCoreMessages') ?>" 
      id="ull_widget_gallery_add_files_<?php echo $id ?>" />
      
    <span class="ull_widget_gallery_control_drop">
      <?php echo __('or drag and drop files here', null, 'ullCoreMessages') ?>
      (<?php echo __('Currently only with Firefox', null, 'ullCoreMessages') ?>)
    </span>
    
    <span class="ull_widget_gallery_control_indicator" 
      id="ull_widget_gallery_control_indicator_<?php echo $id ?>">
      
      <img src="/ullCoreThemeNGPlugin/images/indicator.gif" alt="Indicator" />
      
    </span>
    
  </div>    
    
  <ul class="ull_widget_gallery_preview">
  </ul>
  
  <?php echo javascript_tag('
      
$(document).ready(function() {
      
  ullWidgetGallery2Initialize(' . 
    '"' . $id . '", ' .
    '"' . $upload_url . '", ' .
    '"' . $preview_url . '", ' .
    '"' . ullCoreTools::getMaxPhpUploadSize() . '", ' .
    '"' . __('Invalid file type', null, 'ullCoreMessages') . '"' .
  ');
      
});      

') ?>

<!-- End of ullWidgetGallery2 -->    
 </div>   