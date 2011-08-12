<div class="cms_content">

  <h1><?php echo __('Trainers', null, 'ullCourseMessages') ?></h1>
  
  <p><?php echo __('Here is an alphabetical list of our trainers', null, 'ullCourseMessages') ?>:</p>
  
  <?php include_partial('ullCourse/trainersList', array('trainers' => $trainers)) ?>

</div>