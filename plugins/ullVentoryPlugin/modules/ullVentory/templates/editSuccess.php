<?php use_javascript('/ullVentoryPlugin/js/editSuccess.js') ?>

<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<?php if ($generator->getForm()->hasErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  <?php echo $generator->getForm()->renderGlobalErrors() ?>
  </div>  
  <br /><br />
<?php endif; ?>


<?php
  // get the correct action to use the correct route (create/edit) 
  if ($sf_params->get('action') == 'createWithType')
  {
    $url = url_for('ullVentory/createWithType') . '/' . $sf_params->get('type');
  }
  else
  {
    $url = url_for('ull_ventory_edit', $generator->getRow());
  }
  
  echo form_tag($url, 
    array('id' => 'ull_ventory_form', 'name' => 'edit_form'))    
?>

<div class="edit_container">

<h3><?php echo __('Item of', null, 'ullVentoryMessages') . ' ' . $entity ?></h3>

<table class="edit_table" id="ull_ventory_item">
<tbody>

<?php // TODO: the action could already provide a ready-to-use list of fields to render...?>
<?php foreach ($generator->getForm()->getWidgetSchema()->getPositions() as $column_name): ?>
    <?php if (in_array($column_name, array('ull_ventory_item_manufacturer_id', 'ull_ventory_item_model_id'))): ?>
      <tr>
        <td>
          <?php echo $generator->getForm()->offsetGet($column_name)->renderLabel() ?>
          <?php if (in_array($column_name, array('ull_ventory_item_manufacturer_id', 'ull_ventory_item_model_id'))): ?>
            <label>*</label>
          <?php endif ?>
        </td>
        <td>
          <?php if ($column_name == 'ull_ventory_item_model_id'): ?>
            <?php echo image_tag('/ullCoreThemeNGPlugin/images/indicator.gif', array('id' => 'ull_ventory_item_model_id_ajax_indicator', 'style' => 'display: none; vertical-align: middle;')) ?>
          <?php endif ?>        
        
          <?php echo $generator->getForm()->offsetGet($column_name)->render() ?>
          <?php echo __('or create', null, 'common') ?>:
          <?php echo $generator->getForm()->offsetGet($column_name . '_create')->render() ?>
          <?php if ($column_name == 'ull_ventory_item_model_id'): ?>
            <?php             
              echo ull_submit_tag(
                __('Load presets', null, 'ullVentoryMessages'),
                array('name' => 'submit|action_slug=load_presets', 'id' => 'load_presets')
              );  
            ?>
          <?php endif ?>
        </td>
        <td class="form_error"><?php echo $generator->getForm()->offsetGet($column_name)->renderError() ?></td>
      </tr>
    <?php // don't render some specific fields ?>
    <?php //TODO: it shouldn't be neccessary to hide "id" and "ull_entity_id" manually -> refactor into generator (getActiveColumns() ?) ?>
    <?php elseif ($generator->getForm()->offsetGet($column_name)->getWidget() instanceof sfWidgetFormInputHidden): ?>
      <?php continue ?>
    <?php elseif (in_array($column_name, array('ull_ventory_item_manufacturer_id_create', 'ull_ventory_item_model_id_create', 'save_preset', 'add_software'))): ?>
      <?php continue ?>
    <?php //don't render embeded forms (attributes, software) ?>
    <?php elseif ($generator->getForm()->offsetGet($column_name) instanceof sfFormFieldSchema): ?>
      <?php continue ?>
    <?php else: ?>      
      <?php echo $generator->getForm()->offsetGet($column_name)->renderRow() ?>
    <?php endif ?>
<?php endforeach ?>

</tbody>
</table>


<?php if (count($generator->getForm()->offsetGet('attributes'))): ?>
<table class="edit_table" id="ull_ventory_attributes">
<thead>
  <tr>
    <th class="color_medium_bg"><?php echo __('Attribute', null, 'ullVentoryMessages') ?></th>
    <th class="color_medium_bg"><?php echo __('Value', null, 'common') ?></th>
    <th class="color_medium_bg"><?php echo __('Comment', null, 'common') ?></th>
  </tr>
</thead>
<tbody>
<?php foreach ($generator->getForm()->offsetGet('attributes') as $attribute): ?>
  <?php $values = $attribute->getValue(); ?>
      <tr>
        <td class="label_column">
          <label for="<?php echo $attribute->offsetGet('value')->renderId() ?>">
          <?php

            foreach ($doc->UllVentoryItemModel->UllVentoryItemType->UllVentoryItemTypeAttribute as $docTypeAttribute)
            {
              if ($docTypeAttribute->id == $values['ull_ventory_item_type_attribute_id'])
              {
                $typeAttribute = $docTypeAttribute;
              }
            }
            $itemAttribute = $typeAttribute->UllVentoryItemAttribute;
            echo $itemAttribute->name;
            echo ($typeAttribute->is_mandatory) ? ' *' : '';
          ?>
          </label>          
        </td>
        <td>
          <?php echo $attribute->offsetGet('value')->render() ?>
          <div class="form_help"><dfn><?php echo $itemAttribute->help ?></dfn></div>
          <div class="form_error"><?php echo $attribute->offsetGet('value')->renderError(); ?></div>
        </td>
        <td>
          <?php echo $attribute->offsetGet('comment')->render() ?>
          <div class="form_error"><?php echo $attribute->offsetGet('comment')->renderError(); ?></div>
        </td>
      </tr>
<?php endforeach ?>

  <!-- Save attributes as preset -->
  <?php if ($sf_params->get('action') == 'createWithType'): ?>
  <tr>
    <?php $save_preset = $generator->getForm()->offsetGet('save_preset') ?>
    <td class="label_column">
        <?php echo $save_preset->renderLabel() ?>
    </td>
    <td colspan="2">
      <?php echo $save_preset->render() ?>
      <?php echo $save_preset->renderHelp() ?>
      <div class="form_error"><?php echo $save_preset->renderError(); ?></div>
    </td>
  </tr>
  <?php endif ?>

</tbody>
</table>
<?php endif ?>


<?php if ($generator->getForm()->offsetExists('software')): ?>
  <table class="edit_table" id="ull_ventory_software">
  <thead>
    <tr>
      <th class="color_light_bg"><?php echo __('Software', null, 'common') ?></th>
      <th class="color_light_bg"></th>
      <th class="color_light_bg"><?php echo __('License', null, 'common') ?></th>
      <th class="color_light_bg"><?php echo __('Comment', null, 'common') ?></th>
    </tr>
  </thead>
  <tbody>
  
    <?php foreach ($generator->getForm()->offsetGet('software') as $software): ?>
      <?php $values = $software->getValue(); ?>
      <tr>
        <td class="label_column">
          <label for="<?php echo $software->offsetGet('enabled')->renderId() ?>"><?php echo Doctrine::getTable('UllVentorySoftware')->findOneById($values['ull_ventory_software_id'])->name ?></label>          
        </td>
        <td>
          <?php echo $software->offsetGet('enabled')->render() ?>
          <div class="form_error"><?php echo $software->offsetGet('enabled')->renderError() ?></div>
        </td>
        <td>
          <?php echo $software->offsetGet('ull_ventory_software_license_id')->render() ?>
          <div class="form_error"><?php echo $software->offsetGet('ull_ventory_software_license_id')->renderError() ?></div>        
        </td>
        <td>
          <?php echo $software->offsetGet('comment')->render() ?>
          <div class="form_error"><?php echo $software->offsetGet('comment')->renderError() ?></div>
        </td>
      </tr>
    <?php endforeach ?>
    
  <!-- Add software -->
  <tr>
    <?php $add_software = $generator->getForm()->offsetGet('add_software') ?>
    <td class="label_column">
        <?php echo $add_software->renderLabel() ?>
    </td>
    <td colspan="3">
      <?php echo $add_software->render() ?>
      <?php echo $add_software->renderHelp() ?>
      <?php echo ull_submit_tag(
                __('Add software', null, 'ullVentoryMessages'),
                array('name' => 'submit|action_slug=save_only', 'id' => 'add_software')
              ) ?>       
      <div class="form_error"><?php echo $add_software->renderError(); ?></div>
    </td>
  </tr>
    
  </tbody>
  </table>    
<?php endif ?>




<div class="ull_memory_background" id="ull_ventory_memory">
<table class="edit_table ull_memory_background">
<thead>
  <tr>
    <th colspan="3">
      <?php if ($doc->exists()): ?>
        <?php echo __('Change owner', null, 'common')?>
      <?php else: ?>
        <?php echo __('Origin', null, 'common')?>
      <?php endif ?>        
    </th>
  </tr>
</thead>
<tbody>
<?php foreach ($generator->getForm()->offsetGet('memory') as $widget): ?>
    <?php if ($widget instanceof sfWidgetFormInputHidden): ?>
      <?php continue ?>
    <?php else: ?>      
      <?php echo $widget->renderRow() ?>
    <?php endif ?>
<?php endforeach ?>
</tbody>
</table>
</div>




<?php echo $generator->getForm()->renderHiddenFields() ?>

  <div class='edit_action_buttons color_light_bg'>
    <h3><?php echo __('Actions', null, 'common')?></h3>
      <div class='edit_action_buttons_left'>

        <ul>

          <!-- 
		      <li>
            <?php
              /*             
              echo ull_submit_tag(
                __('Save and show', null, 'common'),
                array('name' => 'submit|action_slug=save_show')
              );
              */  
            ?>
	        </li>
	         -->
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
          echo ull_submit_tag(
            __('Save only', null, 'common'), 
            array('name' => 'submit|action_slug=save_only', 'form_id' => 'ull_ventory_form', 'display_as_link' => true)
          ); 
        ?>
      </li>
      <!-- 
      <li>
		    <?php /* if ($doc->id): ?>    
		      <?php 
		        echo link_to(
		          __('Delete', null, 'common'), 
		          'ullVentory/delete?id=' . $doc->id, 
		          'confirm='.__('Are you sure?', null, 'common')
		          ); 
		      ?>
		    <?php endif;  */?>
      </li>
      -->

	    </ul>
	
	  </div>
	
	  <div class="clear"></div>  
  </div>
  
  
<?php if ($doc->exists()): ?>
  <div id="ull_memory" class="ull_memory_background">
  <h3><?php echo __('History', null, 'common')?></h3>
  <ul>
    <?php 
      $tempdate = -1;
      
      foreach ($doc->findMemoriesOrderedByDate() as $memory): ?>
        <?php
        if ($tempdate != substr($memory->transfer_at, 0, 10)) 
        {
          if ($tempdate != -1)
          { 
            echo '</ul></li>';
          }
            
          echo '<li class="ull_memory_date">' . ull_format_date($memory->transfer_at) . '</li>' .
                  '<li class="ull_memory_day"><ul class="ull_memory_day">';
        } 
        ?>
        
        <li>
          <?php if ($memory->TargetUllEntity instanceof UllVentoryOriginDummyUser):?>
            <?php echo __('Source', null, 'ullVentoryMessages') ?>:
          <?php else: ?>
            <?php echo __('Owner', null, 'common') ?>:
          <?php endif ?>
          <span class="ull_memory_light"><?php echo $memory->TargetUllEntity ?></span>
          &ndash;
          <?php echo __('Updated by', null, 'common') ?>
          <span class="ull_memory_light"><?php echo $memory->Updator ?></span>
          <?php echo __('at', null, 'common') ?>
          <?php echo ull_format_datetime($memory->updated_at) ?>
        
          <?php if ($comment = $memory->comment): ?>
            <ul class="ull_memory_comment">
              <li class="ull_memory_lightsmall"><?php echo $comment ?></li>
            </ul>
          <?php endif ?>
        </li>
    
        <?php $tempdate = substr($memory->transfer_at, 0, 10); ?>
      <?php endforeach ?>
      </ul></li>
    </ul>
  </div>
<?php endif ?>  
  
  
  
</div> <!-- end of edit_container -->


</form>

<?php
  echo ull_js_observer("ull_ventory_form");
?>
