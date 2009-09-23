<?php //use_javascript('/ullVentoryPlugin/js/editSuccess.js') ?>

<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>
<?php $list_generator = $sf_data->getRaw('list_generator') ?>

<h3><?php echo __('Project efforts for user %user% on %date%', array(
  '%user%' => $edit_generator->getForm()->offsetGet('ull_user_id')->render(), 
  '%date%' => $edit_generator->getForm()->offsetGet('date')->render()
), 'ullTimeMessages')?></h3>

<?php if ($edit_generator->getForm()->hasErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  <?php echo $edit_generator->getForm()->renderGlobalErrors() ?>
  </div>  
  <br /><br />
<?php endif; ?>



<?php if ($list_generator->getRow()->exists()): ?>
  <table class='list_table' id='ull_time_edit_list'>
  
    <?php include_partial('ullTableTool/ullResultListHeader', array(
      'generator' => $list_generator,
      'order'     => 'created_at',
      'order_dir' => 'ASC',
  )); ?>
  
  <!-- data -->
  <tbody>
  <?php $odd = true; ?>
  <?php foreach($list_generator->getForms() as $row => $form): ?>
    <?php $idAsArray = (array) $list_generator->getIdentifierUrlParamsAsArray($row); ?>
    <tr <?php echo ($odd) ? $odd = '' : $odd = 'class="odd"' ?>>
      <td class='no_wrap'>          
        <?php
            echo ull_link_to(ull_image_tag('edit'), array('action' => 'editProject') + $idAsArray);
            echo ull_link_to(ull_image_tag('delete'), array('action' => 'deleteProject') +$idAsArray,
                'confirm=' . __('Are you sure?', null, 'common')); 
        ?>
      </td>
      <?php echo $form ?>
    </tr>
  <?php endforeach; ?>
  
  </tbody>
  </table>
<?php endif ?>


<h3>
  <?php if ($edit_generator->getRow()->exists()): ?>
    <?php echo __('Edit project effort', null, 'ullTimeMessages')?>
  <?php else: ?>
    <?php echo __('Enter new project effort', null, 'ullTimeMessages')?>
  <?php endif ?>
</h3>


<?php echo form_tag(ull_url_for(), array('id' => 'ull_time_form', 'name' => 'edit_form')) ?>

<div class="edit_container">
<table class="edit_table">
<tbody>

<?php
  foreach ($edit_generator->getForm()->getWidgetSchema()->getPositions() as $column_name)
  {
    if (!in_array($column_name, array('ull_user_id', 'date')))
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
  </div>

  <div class="clear"></div>  
  
</div>
</div>
</form>   

<?php 
  echo ull_js_observer("ull_time_form");
?>  
