

<div class="edit_action_buttons color_light_bg">
<h3><?php echo __('Add search criterion', null, 'common') ?></h3>

  <div class='edit_action_buttons_left'>
    <?php $widget = $addCriteriaForm->offsetGet('columnSelect') ?>
    <?php //echo '<td>' . $widget->renderLabel() . '</td>'; ?>
    <?php  echo $widget->render() ?>
    <?php echo $widget->renderError() ?>
    <?php echo submit_tag(__('Add', null, 'common'), array('name' => 'addSubmit', 'id' => 'addSubmit')) ?>
  </div>
  
  <div class="clear"></div>
</div>

