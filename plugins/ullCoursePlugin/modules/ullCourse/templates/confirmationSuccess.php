<div class="cms_content">

  <h1><?php echo __('Booking confirmation', null, 'ullCourseMessages') ?></h1>
  
  <p>
    <?php echo __('Your\'re about to book the following course', null, 'ullCourseMessages') ?>:
  </p>
  
  <h2><?php echo $course['name']?></h2>
  
  <p>
    <?php echo __('Trainer', null, 'ullCourseMessages') ?>: 
    <?php echo $course['Trainer']['first_name'] ?> <?php echo $course['Trainer']['last_name'] ?>
    
    <br />
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
    <?php endif?>
    
    <?php echo __('Time', null, 'common') ?>:
    <?php echo ull_format_time($course['begin_time']) ?> 
    <?php echo __('to', null, 'common') ?>
    <?php echo ull_format_time($course['end_time']) ?>
    
    <br />
      
    <?php echo __('Tariff', null, 'ullCourseMessages') ?>: 
    <?php echo $tariff['display_name'] ?>
  </p>
  
  <?php include_partial('ullTableTool/globalError', array('form' => $generator->getForm())) ?>
  
  <?php echo form_tag('ullCourse/confirmation?slug=' . $course['slug'] . '&ull_course_tariff_id=' . $tariff['id']) ?>
  
  <h2><?php echo __('Are you booking for another person', null, 'ullCourseMessages') ?>?</h2>
  
  <p><?php echo __('If you book a course for another person please note the name here', null, 'ullCourseMessages') ?>:</p>
  <p>
  <?php echo $form['participant']->render() ?>
  <?php echo $form['participant']->renderError() ?>
  </p>  
    
  <h2><?php echo __('Other comments', null, 'ullCourseMessages') ?></h2>
  
  <p>
  <?php echo $form['comment']->render() ?>
  <?php echo $form['comment']->renderError() ?>
  </p>
  
  <h2><?php echo __('Please note', null, 'ullCourseMessages') ?></h2>
  
  <ul>
    <li><?php echo __('Please transfer the amount as soon as possible, as your spot is only fixed after payment', null, 'ullCourseMessages')?>.</li>
    <li><?php echo __('The spots are assigned in order of the payment date', null, 'ullCourseMessages')?>.</li>
    <li><b><?php echo __('Please create a separate booking for every single person!', null, 'ullCourseMessages')?></b></li>
  </ul>
  
  <p>
  <?php echo $form['are_terms_of_use_accepted']->render() ?> &nbsp;
  <?php echo __('I hereby accept the', null, 'ullCourseMessages') ?> 
  <?php echo ull_link_to(
    __('terms of use', null, 'ullCourseMessages'),
    sfConfig::get('app_ull_course_terms_of_use_link', 'ullAdmin/about'),
    array('link_new_window' => true )
  ) ?>.
  </p>
  
  </p>
  <?php echo $form['are_terms_of_use_accepted']->renderError() ?>
  </p>
  
  <p>
  <?php echo submit_tag(__('Send booking', null, 'ullCourseMessages')) ?>
  </p>
  
  </form>

</div>