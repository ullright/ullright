<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<h3>
  <?php echo __('Time report', null, 'ullTimeMessages')?>
  <?php echo $generator->getForm()->offsetGet('date')->render() ?> /
  <?php echo $generator->getForm()->offsetGet('ull_user_id')->render() ?>
</h3>

<?php if ($generator->getForm()->hasErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  <?php echo $generator->getForm()->renderGlobalErrors() ?>
  </div>  
<?php endif; ?>


<?php echo form_tag(ull_url_for(), array('id' => 'ull_time_form', 'name' => 'edit_form')) ?>

<div class="edit_container">
<table class="edit_table ull_time_edit_table_worktime">
  <thead>
    <tr class="color_dark_bg">
      <th class="color_dark_bg"> &nbsp; </th>
      <th class="color_dark_bg"><?php echo __('Begin', null, 'common') ?></th>
      <th class="color_dark_bg"><?php echo __('End', null, 'common') ?></th>
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
    </tr>
    
  </tbody>
</table>

<table class="edit_table">
  <thead>
    <tr class="color_dark_bg">
      <th class="color_dark_bg"> &nbsp; </th>
      <th class="color_dark_bg"><?php echo __('Begin', null, 'common') ?></th>
      <th class="color_dark_bg"><?php echo __('End', null, 'common') ?></th>
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
    </tr>      
    
  </tbody>
</table>

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

  <div class="clear"></div>  
  
</div>
</div>
</form>   

<?php 
  echo ull_js_observer("ull_time_form");
?>  
