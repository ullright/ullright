<?php echo $breadcrumb_tree ?>

<h3>
  <?php echo __('Overview', null, 'common')?> <?php echo $period->name ?> / <?php echo $user->display_name ?>
</h3>

<table class="list_table" id="ull_time_list">
  
  <tr class="color_dark_bg">
    <th class="color_dark_bg"><?php echo __('Day', null, 'common') ?></th>
    <th class="color_dark_bg"><?php echo __('Time reporting', null, 'ullTimeMessages') ?></th>
    <th class="color_dark_bg"><?php echo __('Total', null, 'common') ?></th>
    <th class="color_dark_bg"><?php echo __('Project reporting', null, 'ullTimeMessages') ?></th>
    <th class="color_dark_bg"><?php echo __('Total', null, 'common') ?></th>
  </tr>
 
  <?php
    $odd = true;
    $colspan = 5;
    $timeDurationWidget = new ullWidgetTimeDurationRead();
    $dateWidget = new ullWidgetDateRead(array('show_weekday' => true));
  ?>
  
  <?php foreach ($periodTable as $calendarWeekKey => $calendarWeek): ?>
  
    <tr>
      <td colspan="<?php echo $colspan ?>">
      <?php       
        echo '<b>' . __('Week', null, 'common'). ' ' . $calendarWeekKey . '</b>';
      ?>
      </td>
    </tr>
  
    <?php foreach ($calendarWeek['dates'] as $date => $day): ?>
        
      <tr class="
        <?php echo ($odd) ? $odd = '' : $odd = 'odd' ?>
        <?php if ($day['weekend']): ?>
          <?php echo 'ull_time_weekend'; $odd = 'odd' ?>
        <?php endif ?>
        <?php if ($day['date'] == date('Y-m-d')): ?>
          <?php echo 'ull_time_today'?>
        <?php endif?>
      ">
        <td><?php echo $dateWidget->render(null, $day['date']) ?></td>
        <td><?php echo ull_link_to(
            __('Time reporting', null, 'ullTimeMessages'), 
            array('action' => 'create', 'date' => $day['date'], 'period' => null)) ?></td>
        <td class='ull_time_list_time_column'><?php echo $timeDurationWidget->render(null, $day['sum_time']) ?></td> 
        <td><?php echo ull_link_to(
            __('Project reporting', null, 'ullTimeMessages'), 
            array('action' => 'createProject', 'date' => $day['date'], 'period' => null)) ?></td>
        <td class='ull_time_list_time_column'><?php echo $timeDurationWidget->render(null, $day['sum_project']) ?></td>
      </tr>    
    <?php endforeach ?>
  
    <tr class="list_table_sum">
      <td></td><td></td>
      <td>
      <?php       
        echo $timeDurationWidget->render(null, $calendarWeek['sum_time']);
      ?>
      </td><td></td>
      <td>
      <?php       
        echo $timeDurationWidget->render(null, $calendarWeek['sum_project']);
      ?>
      </td>
    </tr>
  
  <?php endforeach ?>
  
</table>