<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<h3>
  <?php echo $period->name ?> / <?php echo $user->display_name ?>
</h3>

<table class="list_table" id="ull_time_list">
  
  <tr class="color_dark_bg">
    <th class="color_dark_bg"><?php echo __('Day', null, 'ullVentoryMessages') ?></th>
    <th class="color_dark_bg"><?php echo __('Total time', null, 'ullVentoryMessages') ?></th>
  </tr>
 
  <?php foreach ($dates as $date => $day): ?>
    <?php $class = ($day['weekend']) ? 'class="weekend"' : null ?>
    <tr class="ull_time_weekday">
      <td colspan=2" <?php echo $class ?>>Weekday <?php echo $day['humanized_date'] ?></td>
    </tr>
    <tr>
      <td <?php echo $class ?>><ul><li><?php echo link_to(__('Time reporting', null, 'ullVentoryMessages'), 'ullTime/create') ?></li></ul></td>
      <td <?php echo $class ?>><?php echo $day['sum_time'] ?></td>
    </tr>
    <tr>
      <td <?php echo $class ?>><ul><li><?php echo link_to(__('Project reporting', null, 'ullVentoryMessages'), 'ullProject/create') ?></li></ul></td>
      <td <?php echo $class ?>><?php echo $day['sum_project'] ?></td>
    </tr>    
  <?php endforeach ?>
  
</table>