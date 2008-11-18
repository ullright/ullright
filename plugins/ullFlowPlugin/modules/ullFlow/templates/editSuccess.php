<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>


<?php echo form_tag('ullFlow/edit?app=' . $app->slug, 'id=edit_form'); ?>
  
<?php /* echo form_tag('ullFlow/update?app=' . $app->slug . (!empty($doc->id)) ? '&doc=' . $doc->id : ''
  , 'id=ull_flow_form'); */ ?>  
  
<table class='ull_flow_edit'>
<tbody>

<? echo $generator->getForm() ?>

</tbody>
</table>



<br />

<div class='action_buttons_edit'>
<fieldset>
  <legend><?php echo __('Actions', null, 'common') ?></legend>

  <div class='action_buttons_edit_left'>
    
    
    <?php /* ?>
    <?php if ($step_actions and $workflow_action_access): ?>
      
      <label for="ull_flow_action_comment">
        <?php echo __('Comment for this action') . ':'; ?>
      </label><br />
      <?php
        echo input_tag('ull_flow_action_comment', $sf_request->getParameter('ull_flow_action_comment'), 'size=80');
        echo form_error('ull_flow_action_comment');
      ?>
      
      <ul>
        <?php 
          foreach ($step_actions as $step_action) {
            
            echo '<li>';
            $ull_flow_action = $step_action->getUllFlowAction();
            
            $action_slug = $ull_flow_action->getSlug();
            $action_handler_name = 'ullFlowActionHandler' . sfInflector::camelize($action_slug);
            
            $ull_flow_action_handler = new $action_handler_name();
            $ull_flow_action_handler->setOptions($step_action->getOptions());
            echo $ull_flow_action_handler->getEditWidget();
            
            echo '</li>';
              
          }
        ?>
      </ul>
    <?php endif; ?>

	<?php */?>
    
  </div>

    
  <div class='action_buttons_edit_right'>
    <ul>
      
      <?php if ($generator->getDefaultAccess() == 'w'): ?>

        <li>
        <?php 
          echo ull_submit_tag(
            __('Save only', null, 'common'),
            array('name' => 'save_only', 'form_id' => 'edit_form', 'display_as_link' => true)
          ); 
        ?>
        </li>
        
        <li>  
        <?php 
          echo ull_submit_tag(
            __('Save and close', null, 'common'),
            array('name' => 'save_close', 'form_id' => 'edit_form', 'display_as_link' => true)
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
        <?php /* $ull_flow_action = UllFlowActionPeer::retrieveByPK($memory->getUllFlowActionId()); 
        echo $ull_flow_action->__toString(); */ ?>
        
        <?php /*// show assigned to
        if ($ull_flow_action->getShowAssignedTo()) {
          if ($group_id = $memory->getAssignedToUllGroupId()) {
            echo ' ' . __('to') . ' ' . __('group') . ' ';
            echo UllGroupPeer::retrieveByPK($group_id)->__toString();
          } elseif ($user_id = $memory->getAssignedToUllUserId()) {
    //        echo ' ' . __('to') . ' ';
            echo ' ';
            echo UllUserPeer::retrieveByPK($user_id)->getShortName();
            echo ' ';
          }
        } 
        */ ?>
        <?php echo $memory->AssignedToUllEntity ?>
        <?php echo __('by'); ?>
        <?php echo $memory->Creator ?>
        <?php echo __('at'); ?>
        <?php echo ull_format_datetime($memory->created_at); ?>
        
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
