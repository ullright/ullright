<div class="cms_content">

  <div class="ull_course_trainer_photo">
    <?php $photoWidget = new ullWidgetPhoto() ?>
    <?php echo $photoWidget->render(null, $trainer['photo']) ?>
  </div>
  <div class="ull_course_trainer_content">
    <h2><?php echo $trainer['first_name'] ?> <?php echo $trainer['last_name'] ?></h2>
    <p><?php echo auto_link_text($trainer['comment']) ?></p>      
  </div>

</div>