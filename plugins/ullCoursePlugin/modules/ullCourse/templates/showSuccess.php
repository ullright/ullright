<h1><?php echo $doc['name'] ?></h1>

<h2><?php echo __('Date', null, 'common')?></h2>

<p>
  <?php echo ull_format_date($doc['begin_date'], false, true) ?>
  <?php if ($isMultiDay): ?>
    <?php echo __('to', null, 'common') ?>
    <?php echo ull_format_date($doc['end_date'], false, true) ?>
  <?php endif?>
</p>

<p>
  <?php if ($isMultiDay): ?>
    <?php echo __('%units% units', array('%units%' => $doc['number_of_units']), 'ullCourseMessages') ?>
  <?php endif ?>    
</p>

<p>
  <?php echo __('Time', null, 'common') ?>:
  <?php echo ull_format_time($doc['begin_time']) ?> 
  <?php echo __('to', null, 'common') ?>
  <?php echo ull_format_time($doc['end_time']) ?>
</p>
