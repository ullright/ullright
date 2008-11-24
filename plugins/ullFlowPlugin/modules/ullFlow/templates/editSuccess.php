<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<?php //var_dump($generator->getRow()->toArray()) ?>

<?php echo form_tag('ullFlow/edit?app=' . $app->slug . ($doc->id ? '&doc=' . $doc->id : '')
  , 'id=edit_form'); ?>  
  
<table class='ull_flow_edit'>
<tbody>

<?php foreach ($generator->getActiveColumns() as $column_name => $columns_config): ?>
  <?php echo $generator->getForm()->offsetGet($column_name)->renderRow() ?>
<?php endforeach ?>

</tbody>
</table>



<br />

<div class='action_buttons_edit'>
<fieldset>
  <legend><?php echo __('Actions', null, 'common') ?></legend>

  <div class='action_buttons_edit_left'>
    
    
    
    <?php //if ($step_actions and $workflow_action_access): ?>
      
      <label for="fields_memory_comment">
        <?php echo __('Comment for this action') . ':'; ?>
      </label><br />
      <?php echo $generator->getForm()->offsetGet('memory_comment')->render() ?>
      <?php echo $generator->getForm()->offsetGet('memory_comment')->renderError() ?>
      
      
      <ul>
        <?php
//        $x =  $sf_data->getRaw('doc');
//        var_dump($x->UllFlowStep->UllFlowStepActions->toArray());
        
        foreach ($doc->UllFlowStep->UllFlowStepActions as $stepAction): ?> 
          <li>
          <?php
//            var_dump($stepAction->UllFlowAction);
            $slug = $stepAction->UllFlowAction->slug;
            
            $action_handler_name = 'ullFlowActionHandler' . sfInflector::camelize($slug);
            $ull_flow_action_handler = new $action_handler_name();
            $ull_flow_action_handler->setOptions($stepAction->options);
            echo $ull_flow_action_handler->getEditWidget();
          ?>
          </li>
        <?php endforeach ?>
      </ul>
    <?php //endif; ?>

  </div>

    
  <div class='action_buttons_edit_right'>
    <ul>
      
      <?php if ($generator->getDefaultAccess() == 'w'): ?>

        <li>
        <?php 
          echo ull_submit_tag(
            __('Save only', null, 'common'),
            array('name' => 'submit_save_only', 'form_id' => 'edit_form', 'display_as_link' => true)
          ); 
        ?>
        </li>
        
        <li>  
        <?php 
          echo ull_submit_tag(
            __('Save and close', null, 'common'),
            array('name' => 'submit_save_close', 'form_id' => 'edit_form', 'display_as_link' => true)
          ); 
        ?>
        </li>
        
        <?php /*if ($doc_id): ?>
          <li>
          <?php 
            echo link_to(
              __('Delete', null, 'common'), 
              'ullFlow/delete?doc='.$doc_id, 
              'confirm='.__('Are you sure?', null, 'common')
              ); 
          ?>
          </li> 
        <?php endif; */?>
        
      <?php endif; ?>
    
      <li>
      <?php /*
        echo ull_link_to(
          __('Cancel', null, 'common') 
          , $referer_edit
          , 'ull_js_observer_confirm=true'
        );
      */?>
      </li>
    </ul>  
  </div>

  <div class="clear"></div>  
  
</fieldset>

</div>




<?php /* echo input_hidden_tag('ull_flow_action') ?>
<?php echo input_hidden_tag('external') ?>
<?php echo input_hidden_tag('external_field') */?>

</form>


<?php if ($doc->exists()): ?>
  <br />
  <h3><?php echo __('Progress')?></h3>
  <ul class='ull_flow_memories'>
    <?php foreach ($doc->UllFlowMemories as $memory): ?>
      <li>
        <?php echo $memory->UllFlowAction->label ?>
        <?php if ($memory->UllFlowAction->show_assigned_to): ?>
          <?php echo __('to') ?>
          <?php echo $memory->AssignedToUllEntity ?>
        <?php endif ?>
        <?php echo __('by'); ?>
        <?php echo $memory->Creator ?>
        <?php echo __('at'); ?>
        <?php echo /*ull_format_datetime(*/$memory->created_at/*)*/; ?>
        
        <?php if ($comment = $memory->comment): ?>
          <ul class="ull_flow_memory_comment">
            <li>
              <?php echo __('Comment') . ': ' . $comment ?>
            </li>
          </ul>
        <?php endif ?>
      </li>
    <?php endforeach ?>
  </ul>
<?php endif ?>


<?php
//  echo ull_js_observer("ull_flow_form");
//  ullCoreTools::printR($ull_form);
?>   

<?php  ?>
