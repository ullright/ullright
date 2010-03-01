<?php echo $breadcrumb_tree ?>

<h3>
  <?php echo __('Overview', null, 'common')?> <?php echo $period->name ?> / <?php echo $user->display_name ?>
</h3>

<table class="list_table" id="ull_time_list">

	<col class="col_period_list_day"/>
	<col class="col_period_list_time_reporting"/>
	<col class="col_period_list_time_total"/>
	<col class="col_period_list_project_reporting"/>
	<col class="col_period_list_project_total"/>
  <col class="col_period_list_delta"/>
  
	<thead>
    <tr class="color_dark_bg">
      <th class="color_dark_bg"><?php echo __('Day', null, 'common') ?></th>
      <th class="color_dark_bg"><?php echo __('Time reporting', null, 'ullTimeMessages') ?></th>
      <th class="color_dark_bg"><?php echo __('Total', null, 'common') ?></th>
      <th class="color_dark_bg"><?php echo __('Project reporting', null, 'ullTimeMessages') ?></th>
      <th class="color_dark_bg"><?php echo __('Total', null, 'common') ?></th>
      <th class="color_dark_bg"><?php echo __('Difference', null, 'ullTimeMessages')?></th>
    </tr>
  </thead>
 
  <tbody>
    <?php
      $odd = true;
      $colspan = 6;
      $time_duration_widget = new ullWidgetTimeDurationRead();
      $time_duration_widget_with_zero = new ullWidgetTimeDurationRead(array('show_zero' => 1));
      $date_widget = new ullWidgetDateRead(array('show_weekday' => true));
    ?>
    
    <?php foreach ($period_table as $calendar_week_key => $calendar_week): ?>
    
      <tr <?php echo ($calendar_week['future']) ? 'class="ull_time_list_future"' : '' ?>>
        <td colspan="<?php echo $colspan ?>">
        <h4><?php echo __('Week', null, 'common'). ' ' . $calendar_week_key ?></h4>
        </td>
      </tr>
    
      <?php foreach ($calendar_week['dates'] as $date => $day): ?>
          
        <tr class="
          <?php echo ($odd) ? $odd = '' : $odd = 'odd' ?>
          <?php if ($day['weekend']): ?>
            <?php echo 'ull_time_weekend'; $odd = 'odd' ?>
          <?php endif ?>
          <?php if ($day['date'] == date('Y-m-d')): ?>
            <?php echo 'ull_time_today'?>
          <?php endif?>
          <?php if ($day['future']): ?>
            <?php echo 'ull_time_list_future'?>
          <?php endif?>        
        ">
          <td><?php echo $date_widget->render(null, $day['date']) ?></td>
          <td><?php echo ull_link_to(
              __('Time reporting', null, 'ullTimeMessages'), 
              array('action' => 'create', 'date' => $day['date'], 'period' => null)) ?></td>
          <td class='ull_time_list_time_column'><?php echo $time_duration_widget->render(null, $day['sum_time']) ?></td> 
          <td><?php echo ull_link_to(
              __('Project reporting', null, 'ullTimeMessages'), 
              array('action' => 'createProject', 'date' => $day['date'], 'period' => null)) ?></td>
          <td class='ull_time_list_time_column'><?php echo $time_duration_widget->render(null, $day['sum_project']) ?></td>
          <td class='ull_time_list_time_column'><?php echo $time_duration_widget->render(null, $day['sum_delta']) ?></td>
        </tr>    
      <?php endforeach ?>
    
      <tr class="list_table_sum<?php echo ($calendar_week['future']) ? ' ull_time_list_future' : '' ?>">
        <td></td>
        <td></td>
        <td>
          <?php       
            echo $time_duration_widget_with_zero->render(null, $calendar_week['sum_time']);
          ?>
        </td>
        <td></td>
        <td>
          <?php       
            echo $time_duration_widget_with_zero->render(null, $calendar_week['sum_project']);
          ?>
        </td>
        <td>
          <?php       
            echo $time_duration_widget_with_zero->render(null, $calendar_week['sum_delta']);
          ?>
        </td>
      </tr>
    
    <?php endforeach ?>
    
    <!-- Totals -->
    
    <tr>
      <td colspan="<?php echo $colspan ?>">
      <h4><?php echo __('Grand total', null, 'common') ?></h4>
      </td>
    </tr>  
  
    <tr class="list_table_sum list_table_total_sum">
      <td></td>
      <td></td>
      <td><?php echo $time_duration_widget_with_zero->render(null, $totals['time']) ?></td>
      <td></td>
      <td><?php echo $time_duration_widget_with_zero->render(null, $totals['project']) ?></td>
      <td><?php echo $time_duration_widget_with_zero->render(null, $totals['delta']) ?></td>
    </tr> 
     
  </tbody>
  
</table>

<?php echo javascript_tag('

/*
 * Hide the future periods and display a link instead
 */
$(document).ready(function() 
{
  // Check if we have future days at all
  if ($("tr.ull_time_list_future").length > 0)
  {
    $("tr.ull_time_list_future").hide();

    $("#ull_time_list tbody").prepend(
      "<tr id=\"ull_time_show_future_days_message\"><td colspan=' . $colspan . '><a href=\"#\" onclick=\"showFutureDays(); return false;\">' . __('Show future days', null, 'ullTimeMessages') . '</a></td></tr>"
    );
  }
  
});

/*
 * Unhide future periods
 */ 
function showFutureDays()
{
  $("tr.ull_time_list_future").fadeIn(500);
  document.getElementById("ull_time_show_future_days_message").style.display = "none"; 
}

')?>