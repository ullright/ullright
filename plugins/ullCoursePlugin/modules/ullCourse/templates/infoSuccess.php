<h1><?php echo $course['name']?></h1>

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
  <?php endif?>
  
  <?php echo __('Time', null, 'common') ?>:
  <?php echo ull_format_time($course['begin_time']) ?> 
  <?php echo __('to', null, 'common') ?>
  <?php echo ull_format_time($course['end_time']) ?>  
</p>

<p>
  <?php echo __('Trainer', null, 'ullCourseMessages') ?>: 
  <?php echo $course['Trainer']['display_name'] ?>, 
  <?php echo $course['Trainer']['email'] ?>, 
  <?php echo $course['Trainer']['mobile_number'] ?>, 
</p>



<h2><?php echo __('Participants', null, 'ullCourseMessages') ?></h2>

<?php if ($generator->getRow()->exists()): ?>
  <table class='list_table'>
  
  <?php include_partial('ullTableTool/ullResultListHeaderNoOrder', array(
      'generator' => $generator,
      'add_icon_th' => false
  )); ?>
  
  <!-- data -->
  
  <tbody>
  <?php $odd = true; ?>
  <?php foreach($generator->getForms() as $row => $form): ?>
    <?php $id_url_params = $generator->getIdentifierUrlParams($row); ?>
    <tr <?php echo ($odd) ? $odd = '' : $odd = 'class="odd"' ?>>
      <?php /* 
      <td class='no_wrap'>          
        <?php echo ull_link_to(ull_image_tag('edit'), ullCoreTools::appendParamsToUri($edit_base_uri, $id_url_params)); ?>
        <?php if ($generator->getAllowDelete()): ?>
          <?php echo ull_link_to(ull_image_tag('delete'), ullCoreTools::appendParamsToUri($delete_base_uri, $id_url_params), 
                'confirm=' . __('Are you sure?', null, 'common')); ?>
        <?php endif ?>
      </td>
      */ ?>
      <?php echo $form ?>
      
    </tr>
  <?php endforeach; ?>
  
  </tbody>
  </table>
  
<?php endif ?>