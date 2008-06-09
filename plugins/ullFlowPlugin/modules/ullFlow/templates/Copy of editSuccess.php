<?php
?>

<?php echo $breadcrumbTree->getHtml() ?>


<?php
  if (!$new) {  
    include_component('ullFlow', 'ullFlowHeader', array(
      'ull_flow_doc' => $doc
    , 'app_slug'    => $app_slug
    )); 
  }
?>


<?php if ($sf_request->hasErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  </div>  
  <br /><br />
<?php endif; ?>


<?php echo form_tag('ullFlow/update', 'id=ull_flow_form'); ?>
<?php echo input_hidden_tag('app', @$app_slug); ?>
<?php echo input_hidden_tag('doc', @$doc_id); ?>

  
<table class='ull_flow_edit'>
<tbody>

<?php 
//  weflowTools::printR($column_info);
//  weflowTools::printR($tt_row);
//ullCoreTools::printR($sf_params->getAll());
?>

<?php $odd = false; ?>
<?php foreach ($ull_form->getFieldsInfo() as $field_name => $field_info): ?>
  <?php if (isset($field_info['access'])): ?>
  
    <?php
      if ($odd) {
        $odd_style = ' class=\'odd\'';
        $odd = false;
      } else {
        $odd_style = '';
        $odd = true;
      }
    ?>
    <tr<?php echo $odd_style; ?>>
      <td>
        <b><?php echo $field_info['name_humanized'] . ':'; ?></b>
      </td>
      <td>  
        <?php 
        
          $fields_data  = $ull_form->getFieldsDataOne();
          $field_data   = $fields_data[$field_name]; 
        
          if (isset($field_data['function'])) {
            echo call_user_func_array($field_data['function'], $field_data['parameters']);
            
          } else {
            if (isset($field_data['value'])) {
              echo $field_data['value'];
            }            

          }

          echo form_error($field_name);
          
          ?>
  
      </td>
    </tr>
  <?php endif; // end of enabled ?>
<?php endforeach; ?>

</tbody>
</table>



<br />

<?php if ($step_actions and $workflow_action_access): ?>
  <h3><?php echo __('Workflow actions'); ?>:</h3>
  
  <?php 
    echo __('Comment for this action') . ': ';
    echo input_tag('ull_flow_action_comment', $sf_request->getParameter('ull_flow_action_comment'), 'size=50');
    echo form_error('ull_flow_action_comment');
  ?>
  
  <ul class="ull_flow_actions">
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


<h3><?php echo __('Common actions', null, 'common'); ?>:</h3>

<div class='action_buttons'>
    
  <div class='action_buttons_left'>
  
    <?php
      echo ull_button_to(
        __('Cancel', null, 'common') 
        , $referer_edit
//        , 'confirm='.__('You will loose unsaved changes! Are you sure?', null, 'common')
        , 'ull_js_observer_confirm='.__('You will loose unsaved changes! Are you sure?', null, 'common')
      );
      
//      echo ull_button_to('cancel', 'ull_js_observer_confirm("' . __('You will loose unsaved changes! Are you sure?', null, 'common') . '")');
    ?> &nbsp;
    
    <?php if ($ull_form->getAccessDefault() == 'w'): ?>
      <?php if ($doc_id): ?>
        <?php 
          echo button_to(
            __('Delete', null, 'common'), 
            'ullFlow/delete?doc='.$doc_id, 
            'confirm='.__('Are you sure?', null, 'common')
            ); 
        ?> &nbsp; 
      <?php endif; ?>
      
    </div>
    
    <div class='action_buttons_right'>
      
      &nbsp; <?php 
        echo submit_tag(
          __('Save only', null, 'common')
          , array('onclick' => 'document.getElementById("ull_flow_action").value = "save_only"')
        ); ?>
        
      &nbsp; <?php 
        echo submit_tag(
          __('Save and close', null, 'common')
          , array('onclick' => 'document.getElementById("ull_flow_action").value = "save_close"')
        ) ?>


  <?php endif; ?>        
    </div>
  

  
  <div class="clear"></div>
</div>




<?php echo input_hidden_tag('ull_flow_action') ?>
<?php echo input_hidden_tag('external') ?>
<?php echo input_hidden_tag('external_field') ?>

</form>


<?php
  // == memories
  if (!$new) {
    echo '<br />';
    echo '<h3>' . __('Progress') . ':</h3>';
    echo '<ul>';
    foreach ($memories as $memory) {
      if ($user = UllUserPeer::retrieveByPK($memory->getCreatorUserId())) {
          $name = $user->getShortName();
        } else {
          $name = __('Unknown user'); 
        }
      echo '<li>';
      $ull_flow_action = UllFlowActionPeer::retrieveByPK($memory->getUllFlowActionId()); 
      echo $ull_flow_action->__toString();
      
      // show assigned to
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
      echo ' ' . __('by') . ' ';
      echo $name;
      echo ' ' . __('at') . ' ';
      echo ull_format_datetime($memory->getCreatedAt());
      
      if ($comment = $memory->getComment()) {
        echo '<ul class="ull_flow_memory_comment"><li>' . __('Comment') . ': ' . $comment . '</li></ul>';
      }
      echo '</li>'; 
    }
    echo '</ul>';
  }  
?>


<?php
  echo ull_js_observer("ull_flow_form");
//  ullCoreTools::printR($ull_form);
?>   
