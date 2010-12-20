<?php echo $breadcrumb_tree ?>
<?php $list_generator = $sf_data->getRaw('list_generator') ?>

<?php include_partial('ullTableTool/flash', array('name' => 'message')) ?>
<?php $timeDurationWidgetWithOptions = new ullWidgetTimeDurationRead(array('show_negative_red' => true, 'show_zero' => true)); ?>
<?php $timeDurationWidget = new ullWidgetTimeDurationRead(); ?>

<h3>
  <?php echo __('Project efforts', null, 'ullTimeMessages')?>
  <?php echo ull_format_date($date); ?> /
  <?php echo $user_widget->render('ull_user_id', $user_id); ?>
</h3>

<?php if ($list_generator->getRow()->exists()):?>
  <table class='list_table' id='ull_time_edit_list'>
  
      <?php include_partial('ullTableTool/ullResultListHeader', array(
        'generator' => $list_generator,
        'order'     => 'created_at',
        'order_dir' => 'ASC',
        )); 
      ?>
  
  <!-- data -->
  <tbody>
  
  <?php $odd = true; ?>
  <?php foreach($list_generator->getForms() as $row => $form): ?>
    <?php $idAsArray = (array) $list_generator->getIdentifierUrlParamsAsArray($row); ?>
    <tr <?php echo ($odd) ? $odd = '' : $odd = 'class="odd"' ?>>
      
      <td class='no_wrap'>
      <?php if ($edit_generator->getDefaultAccess() == 'w'): ?>          
          <?php
              echo ull_link_to(ull_image_tag('edit'), array('action' => 'editProject') + $idAsArray);
              echo ull_link_to(ull_image_tag('delete'), array('action' => 'deleteProject') +$idAsArray,
                  'confirm=' . __('Are you sure?', null, 'common')); 
          ?>
        <?php endif; ?>
      </td>
      
     <?php foreach ($list_generator->getAutoRenderedColumns() as $column_name => $column_config): ?>
        <td>
          <?php echo $form[$column_name] ?>
        </td>
      <?php endforeach ?>
      
    </tr>
  <?php endforeach; ?>

  <?php if ($list_generator->getCalculateSums()): ?>
    <tr class="list_table_sum bold">
    	<td></td>
      <td> <?php echo __('Total', null, 'common') ?></td>
      <td><?php echo $list_generator->getSumForm()->offsetGet('duration_seconds') ?></td>
      <td></td>
    </tr>
  <?php endif ?>
  
  </tbody>
  </table>
<?php endif ?>
<?php if ($sum_time): ?>
	<p class="ull_time_working_delta_time">
	<?php echo __('Work time', null, 'ullTimeMessages') ?>: <?php echo $timeDurationWidgetWithOptions->render(null, $sum_time); ?>
	<?php if ($list_generator->getRow()->exists()):?>
		<?php echo 
			' (' . 
		  __('Difference', null, 'ullTimeMessages') . 
		  ': ' . 
		  $timeDurationWidgetWithOptions->render(null, $diff_time, array('show_negative_red' => true, 'show_zero' => true)) . 
		  ')'
		  ; ?>
	<?php endif?>
	</p>
<?php endif ?>
  

<?php if ($edit_generator->getDefaultAccess() == 'w'): ?>

  <h3>
    <?php if ($edit_generator->getRow()->exists()): ?>
      <?php echo __('Edit project effort', null, 'ullTimeMessages')?>
    <?php else: ?>
      <?php echo __('Enter new project effort', null, 'ullTimeMessages')?>
    <?php endif ?>
  </h3>
  
  <?php include_partial('ullTableTool/globalError', array('form' => $edit_generator->getForm())) ?>
  
  <?php echo form_tag($form_uri, array('id' => 'ull_time_form', 'name' => 'edit_form')) ?>
  
  <div class="edit_container">
  <table class="edit_table">
  <tbody>
  
  <?php
    foreach ($edit_generator->getForm()->getWidgetSchema()->getPositions() as $column_name)
    {
      $hide_rows = ($edit_generator->getRow()->exists()) ?
        array('ull_user_id') : array('ull_user_id', 'date');
      if (!in_array($column_name, $hide_rows))
      {
        echo $edit_generator->getForm()->offsetGet($column_name)->renderRow();
      }
    }
  ?>
  
  </tbody>
  </table>
  
  <div class='edit_action_buttons color_light_bg'>
    <h3><?php echo __('Actions', null, 'common')?></h3>
    
    <div class='edit_action_buttons_left'>
      <ul>
        <li>
          <?php             
            echo ull_submit_tag(
              __('Save and create another entry', null, 'common'),
              array('name' => 'submit|action_slug=save_new')
            );  
          ?>         
        </li>
        <li>
          <?php             
            echo ull_submit_tag(
              __('Save and return to list', null, 'common'),
              array('name' => 'submit|action_slug=save_close')
            );  
          ?>       
        </li>      
      </ul>
    </div>
  
    <div class='edit_action_buttons_right'>
    	<ul>
        <li>
          <?php  
            echo ull_link_to(
              __('Cancel and return to list', null, 'common') 
              , $cancel_link
              , 'ull_js_observer_confirm=true'
            );
          ?>
        </li>
      </ul>
    </div>
    
  </div>
  
<?php elseif (!$list_generator->getRow()->exists()) : // else of if edit_generator ?>  
  <?php echo __('No results found', null, 'common') . '.'; ?>
<?php endif; // end of if edit_generator ?>
  
  
</div>
</form>   

<?php
  //TODO: automate loading and calling
  use_javascript('/ullCorePlugin/js/formHideAdvancedOptions.js')
?>
<script type="text/javascript">
//<![CDATA[
  formHideAdvancedOptions('<?php echo __('Show advanced options', null, 'common'); ?>');
//]]>
</script>

<?php echo ull_js_observer("ull_time_form") ?>
<?php echo hide_advanced_form_fields() ?>

<?php use_javascripts_for_form($list_generator->getForm()) ?>
<?php use_stylesheets_for_form($list_generator->getForm()) ?>
<?php use_javascripts_for_form($edit_generator->getForm()) ?>
<?php use_stylesheets_for_form($edit_generator->getForm()) ?>
<?php use_javascripts_for_widget('ullWidgetManyToManyWrite') ?>
<?php use_stylesheets_for_widget('ullWidgetManyToManyWrite') ?>
