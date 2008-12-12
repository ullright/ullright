<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<?php if ($generator->getRow()->exists()): ?>
  <?php include_partial('ullFlowHeader', array('doc' => $doc)) ?>
<?php endif ?>

<?php echo form_tag('ullFlow/edit?app=' . $app->slug . ($doc->id ? '&doc=' . $doc->id : '')
  , 'id=edit_form'); ?>  
  
  <div id="ull_flow_edit_main">
  
<table class="ull_flow_edit">
<tbody>

<?php foreach ($generator->getActiveColumns() as $column_name => $columns_config): ?>
  <?php echo $generator->getForm()->offsetGet($column_name)->renderRow() ?>
<?php endforeach ?>

</tbody>
</table>

</div>
<br />

<?php if ($generator->getDefaultAccess() == 'w'): ?>
  <div class='action_buttons_edit color_light_bg'>
      <h3><?php echo __('Actions', null, 'common')?></h3>
      
      <div class='action_buttons_edit_left'>
      
      
      
      <?php if ($workflow_action_access): // $step_actions and ?>
        
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
      <?php endif; ?>
  
    </div>
  
      
    <div class='action_buttons_edit_right'>
      <ul>
        
        <?php if ($generator->getDefaultAccess() == 'w'): ?>
  
          <li>
          <?php 
            echo ull_submit_tag(
              __('Save only', null, 'common'),
              array('name' => 'submit|action_slug=save_only', 'form_id' => 'edit_form', 'display_as_link' => true)
            ); 
          ?>
          </li>
          
          <li>  
          <?php 
            echo ull_submit_tag(
              __('Save and close', null, 'common'),
              array('name' => 'submit|action_slug=save_close', 'form_id' => 'edit_form', 'display_as_link' => true)
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
  
  </div>
<?php endif; ?>



<?php /* echo input_hidden_tag('ull_flow_action') ?>
<?php echo input_hidden_tag('external') ?>
<?php echo input_hidden_tag('external_field') */?>

</form>


<?php if ($doc->exists()): ?>
  <br />
  <div id="ull_flow_memories">
  <h3><?php echo __('Progress')?></h3>
  <ul>
    <?php 
      $tempdate = -1;
      foreach ($doc->UllFlowMemories as $memory): ?>
      <?php
        if ($tempdate != substr($memory->created_at, 0, 10)) {
          if ($tempdate != -1) 
            echo '</ul>';
            
          echo '<li class="ull_flow_memories_date">' .
            format_date(strtotime($memory->created_at)) .
            '</li><ul class="ull_flow_memories_day">';
        } ?>
      <li>
        <span class="ull_flow_memories_light">
        <?php echo substr($memory->created_at, 11, 5) ?>
        </span>&ndash;
        <?php echo $memory->UllFlowAction->label ?>
        <?php if ($memory->UllFlowAction->is_show_assigned_to): ?>
          <?php echo __('to') ?>
          <?php echo $memory->AssignedToUllEntity ?>
        <?php endif ?>
        <?php echo __('by'); ?>
        <?php echo '<span class="ull_flow_memories_light">' . $memory->Creator . '</span>'?>
        
        <?php if ($comment = $memory->comment): ?>
          <ul class="ull_flow_memory_comment">
            <li class="ull_flow_memories_lightsmall"> <?php echo $comment ?>
             </li>
          </ul>
        <?php endif ?>
      </li>
    <?php
      $tempdate = substr($memory->created_at, 0, 10);
    endforeach ?>
  </ul>
  <br />
  </div>
<?php endif ?>


<?php
//  echo ull_js_observer("ull_flow_form");
//  ullCoreTools::printR($ull_form);
?>   

<?php  ?>
