<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

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
 
  <?php $odd = true; ?>
  <?php foreach ($dates as $date => $day): ?>
    <tr class="
      <?php echo ($odd) ? $odd = '' : $odd = 'odd' ?>
      <?php if ($day['weekend']): ?>
        <?php echo 'weekend'; $odd = 'odd' ?>
      <?php endif ?>
    ">
      <td><?php echo $day['humanized_date'] ?></td>
      <td><?php echo link_to(__('Time reporting', null, 'ullTimeMessages'), 'ullTime/create?date=' . $day['date'] . '&username=' . $user->username) ?></td>
      <td align="right"><?php echo $day['sum_time'] ?></td>
      <td><?php echo link_to(__('Project reporting', null, 'ullTimeMessages'), 'ullTime/createProject?date=' . $day['date'] . '&username=' . $user->username) ?></td>
      <td align="right"><?php echo $day['sum_project'] ?></td>
    </tr>    
  <?php endforeach ?>
  
</table>