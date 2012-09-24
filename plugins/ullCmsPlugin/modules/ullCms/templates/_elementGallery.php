<div class="gallery">
  <?php foreach (ullWidgetGalleryWrite::getImagesAsArray($values['gallery']) as $image): ?>
    <?php $thumbnail = ullCoreTools::calculateThumbnailPath($image) ?>
    <div class="gallery_image">
      <?php echo link_to(image_tag($thumbnail), $image) ?>
    </div>
  <?php endforeach ?>
</div>