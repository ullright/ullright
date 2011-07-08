<h1><?php echo __('Booking confirmation', null, 'ullCourseMessages') ?></h1>

<p>
  <?php echo __('Your\'re about to book the following course', null, 'ullCourseMessages') ?>:
</p>

<h2><?php echo $doc['name']?></h2>

<p>
  <?php if ($doc->isMultiDay()): ?>
    <?php echo __('%units% units', array('%units%' => $doc['number_of_units']), 'ullCourseMessages') ?>
  <?php endif ?>   
  
  <?php if ($doc->isMultiDay()): ?>
    <?php echo __('from', null, 'common') ?>
  <?php else: ?>
    <?php echo __('on', null, 'common') ?>
  <?php endif ?>
  
  <?php echo ull_format_date($doc['begin_date'], false, true) ?>
  
  <?php if ($doc->isMultiDay()): ?>
    <?php echo __('to', null, 'common') ?>
    <?php echo ull_format_date($doc['end_date'], false, true) ?>
  <?php endif?>, 
  
  <?php echo __('Time', null, 'common') ?>:
  <?php echo ull_format_time($doc['begin_time']) ?> 
  <?php echo __('to', null, 'common') ?>
  <?php echo ull_format_time($doc['end_time']) ?>  
</p>

<p>
  <?php echo __('Trainer', null, 'ullCourseMessages') ?>: 
  <?php echo $doc['Trainer']['display_name'] ?>
</p>

<p>
  <?php echo __('Trainer', null, 'ullCourseMessages') ?>: 
  <?php echo $doc['Trainer']['display_name'] ?>
</p>

<div class="ull_course_payment_online">
  <?php echo link_to(
    __('I want to book online using "Sofortüberweisung"'),
    'ullCourse/paymentSofortueberweisung?slug=' . $doc['slug']
  ) ?>

</div>
