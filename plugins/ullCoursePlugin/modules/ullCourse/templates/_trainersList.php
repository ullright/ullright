<ul id="ull_course_trainers_list">

<?php foreach ($trainers as $trainer): ?>

  <li>
    <div class="ull_course_trainers_photo">
      <?php $photoWidget = new ullWidgetPhoto() ?>
      <?php echo $photoWidget->render(null, $trainer['photo']) ?>
    </div>
    <div class="ull_course_trainers_content">
      <h2><?php echo $trainer['display_name'] ?></h2>
      <p><?php echo auto_link_text($trainer['comment']) ?></p>      
    </div>
  
  </li>

<?php endforeach ?>

</ul>