<h1><?php echo __('Booking confirmation', null, 'ullCourseMessages') ?></h1>

<p>
  <?php echo __('Your\'re about to book the following course', null, 'ullCourseMessages') ?>:
</p>

<h2><?php echo $course['name']?></h2>

<p>
  <?php if ($course->isMultiDay()): ?>
    <?php echo __('%units% units', array('%units%' => $course['number_of_units']), 'ullCourseMessages') ?>
  <?php endif ?>   
  
  <?php if ($course->isMultiDay()): ?>
    <?php echo __('from', null, 'common') ?>
  <?php else: ?>
    <?php echo __('on', null, 'common') ?>
  <?php endif ?>
  
  <?php echo ull_format_date($course['begin_date'], false, true) ?>
  
  <?php if ($course->isMultiDay()): ?>
    <?php echo __('to', null, 'common') ?>
    <?php echo ull_format_date($course['end_date'], false, true) ?>
  <?php endif?>, 
  
  <?php echo __('Time', null, 'common') ?>:
  <?php echo ull_format_time($course['begin_time']) ?> 
  <?php echo __('to', null, 'common') ?>
  <?php echo ull_format_time($course['end_time']) ?>  
</p>

<p>
  <?php echo __('Trainer', null, 'ullCourseMessages') ?>: 
  <?php echo $course['Trainer']['display_name'] ?>
</p>

<p>
  <?php echo __('Tariff', null, 'ullCourseMessages') ?>: 
  <?php echo $tariff['display_name'] ?>
</p>

<?php include_partial('ullTableTool/globalError', array('form' => $generator->getForm())) ?>

<?php echo form_tag('ullCourse/confirmation?slug=' . $course['slug'] . '&ull_course_tariff_id=' . $tariff['id']) ?>

<h2><?php echo __('Comment', null, 'common') ?></h2>

<p>
<?php echo $form['comment']->render() ?>
<?php echo $form['comment']->renderError() ?>
</p>
<p>
  <?php echo __('If you book a course for another person please note the name here', null, 'ullCourseMessages')?>.
</p>
<p>
  <?php echo __('For reduced prices please give the reason for the reduction', null, 'ullCourseMessages')?>.
</p>

<p>
<?php echo $form['are_terms_of_use_accepted']->render() ?> &nbsp;
<?php echo __('I confirm to have read the terms of use and comply to them', null, 'ullCourseMessages') ?>
</p>

</p>
<?php echo $form['are_terms_of_use_accepted']->renderError() ?>
</p>

<p>
<?php echo submit_tag(__('Send booking', null, 'ullCourseMessages')) ?>
</p>

</form>
