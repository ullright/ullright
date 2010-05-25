<?php echo $breadcrumb_tree ?>

<?php include_partial('ullTableTool/flash', array('name' => 'message')) ?>
<?php $timeDurationWidget = new ullWidgetTimeDurationRead(); ?>

<h3>
  <?php echo __('Time report', null, 'ullTimeMessages')?>
  <?php echo $generator->getForm()->offsetGet('date')->render() ?> /
  <?php echo $generator->getForm()->offsetGet('ull_user_id')->render() ?>
</h3>

<?php include_partial('ullTableTool/globalError', array('form' => $generator->getForm())) ?>

<?php echo form_tag($form_uri, array('id' => 'ull_time_form', 'name' => 'edit_form')) ?>

<div class="edit_container">
<table class="edit_table ull_time_edit_table_worktime">

	<col class="col_edit_list_name"/>
	<col class="col_edit_list_begin"/>
	<col class="col_edit_list_end"/>
	<col class="col_edit_list_sum"/>
	
  <thead>
    <tr class="color_dark_bg">
      <th class="color_dark_bg"> &nbsp; </th>
      <th class="color_dark_bg"><?php echo __('Begin', null, 'common') ?></th>
      <th class="color_dark_bg"><?php echo __('End', null, 'common') ?></th>
      <th class="color_dark_bg"><?php echo __('Total', null, 'common') ?></th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <td class="label_column"><label><?php echo __('Work time', null, 'ullTimeMessages')?></label></td>
      <td>
        <?php echo $generator->getForm()->offsetGet('begin_work_at')->render() ?>
        <?php echo $generator->getForm()->offsetGet('begin_work_at')->renderError() ?>
      </td>
      <td>
        <?php echo $generator->getForm()->offsetGet('end_work_at')->render() ?>
        <?php echo $generator->getForm()->offsetGet('end_work_at')->renderError() ?>
      </td>
      <td>
        <?php echo $timeDurationWidget->render(null, $up_to_now); ?>
      </td>
    </tr>
    
  </tbody>
</table>


<table class="edit_table">
	
	<col class="col_edit_list_name"/>
	<col class="col_edit_list_begin"/>
	<col class="col_edit_list_end"/>
	<col class="col_edit_list_sum"/>
	
  <thead>
    <tr class="color_dark_bg">
      <th class="color_dark_bg"> &nbsp; </th>
      <th class="color_dark_bg"><?php echo __('Begin', null, 'common') ?></th>
      <th class="color_dark_bg"><?php echo __('End', null, 'common') ?></th>
      <th class="color_dark_bg"><?php echo __('Total', null, 'common') ?></th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <td class="label_column"><label><?php echo __('Break', null, 'ullTimeMessages')?> 1</label></td>
      <td>
        <?php echo $generator->getForm()->offsetGet('begin_break1_at')->render() ?>
        <?php echo $generator->getForm()->offsetGet('begin_break1_at')->renderError() ?>
      </td>
      <td>
        <?php echo $generator->getForm()->offsetGet('end_break1_at')->render() ?>
        <?php echo $generator->getForm()->offsetGet('end_break1_at')->renderError() ?>
      </td>
      <td>
        <?php echo $timeDurationWidget->render(null, $break_1_duration); ?>
      </td>
    </tr>   
    <tr>
      <td class="label_column"><label><?php echo __('Break', null, 'ullTimeMessages')?> 2</label></td>
      <td>
        <?php echo $generator->getForm()->offsetGet('begin_break2_at')->render() ?>
        <?php echo $generator->getForm()->offsetGet('begin_break2_at')->renderError() ?>
      </td>
      <td>
        <?php echo $generator->getForm()->offsetGet('end_break2_at')->render() ?>
        <?php echo $generator->getForm()->offsetGet('end_break2_at')->renderError() ?>
      </td>
      <td>
        <?php echo $timeDurationWidget->render(null, $break_2_duration); ?>
      </td>
    </tr>       
    <tr>
      <td class="label_column"><label><?php echo __('Break', null, 'ullTimeMessages')?> 3</label></td>
      <td>
        <?php echo $generator->getForm()->offsetGet('begin_break3_at')->render() ?>
        <?php echo $generator->getForm()->offsetGet('begin_break3_at')->renderError() ?>
      </td>
      <td>
        <?php echo $generator->getForm()->offsetGet('end_break3_at')->render() ?>
        <?php echo $generator->getForm()->offsetGet('end_break3_at')->renderError() ?>
      </td>
      <td>
        <?php echo $timeDurationWidget->render(null, $break_3_duration); ?>
      </td>
    </tr>      
    
  </tbody>
</table>

<?php if ($generator->getDefaultAccess() == 'w'): ?>

  <div class='edit_action_buttons color_light_bg'>
    <h3><?php echo __('Actions', null, 'common')?></h3>
    
    <div class='edit_action_buttons_left'>
      <ul>
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
    </div>
  
   <!-- <div class="clear"></div>  -->
    
  </div>

<?php endif ?>

</div>
</form>   

<?php echo ull_js_observer("ull_time_form") ?>  

<?php use_javascripts_for_form($generator->getForm()) ?>
<?php use_stylesheets_for_form($generator->getForm()) ?>