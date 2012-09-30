<?php foreach ($images as $image): ?>

  <?php // Ignore invalid stuff ?>
  <?php if (file_exists(ullCoreTools::webToAbsolutePath($image))): ?>

    <?php // Check for thumbnails ?>
    <?php $thumbnail = ullCoreTools::calculateThumbnailPath($image) ?>
    <?php $thumbnailAbsolutePath = ullCoreTools::webToAbsolutePath($thumbnail) ?>
    <?php if (!file_exists($thumbnailAbsolutePath)): ?>
      <?php $thumbnail = $image ?>
    <?php endif ?>
          
    <li>
      <div class="ull_widget_gallery_preview_image_container">
        <div class="ull_widget_gallery_preview_image">
          <a href="<?php echo $image ?>" target="_blank">
            <img src="<?php echo $thumbnail ?>" alt="preview image" 
              rel="<?php echo $image ?>" />
          </a>
        </div>
      </div>
      <div class="ull_widget_gallery_actions">
        <?php echo ull_icon('ullWidget/galleryDelete?s_image=' . $image, 'delete') ?>
      </div>        
    </li>
    
  <?php endif ?>
    
<?php endforeach ?>    