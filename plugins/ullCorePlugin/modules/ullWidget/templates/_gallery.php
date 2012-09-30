<?php $field = $sf_data->getRaw('field') ?>


<!-- ullWidgetGallery --> 
<div id="ull_widget_gallery_container_<?php echo $id ?>" 
  class="ull_widget_gallery_container">
    
  <div id="ull_widget_gallery_control_<?php echo $id ?>" 
    class="ull_widget_gallery_control">
  
    <?php if ($single): ?>
      <?php $button_msg = __('Upload file', null, 'ullCoreMessages') ?>
      <?php $drop_msg = __('or drag and drop a file here', null, 'ullCoreMessages') ?>
    <?php else: ?>
      <?php $button_msg = __('Add files', null, 'ullCoreMessages') ?>
      <?php $drop_msg = __('or drag and drop files here', null, 'ullCoreMessages') ?>
    <?php endif ?>    
  
    <input type="button" 
      value="<?php echo $button_msg ?>" 
      id="ull_widget_gallery_add_files_<?php echo $id ?>"
      class=" ull_widget_gallery_add_files"
    />
      
    <span class="ull_widget_gallery_control_drop">
      <?php echo $drop_msg ?>
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
    '"' . $single . '", ' .
    '"' . $upload_url . '", ' .
    '"' . $preview_url . '", ' .
    '"' . ullCoreTools::getMaxPhpUploadSize() . '", ' .
    '"' . __('Invalid file type', null, 'ullCoreMessages') . '", ' .
      '"' . __('Please select only one file', null, 'ullCoreMessages') . '"' .
  ');
      
});      

') ?>
  
  <?php echo $field ?>

<!-- End of ullWidgetGallery -->    
 </div>   