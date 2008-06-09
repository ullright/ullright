<?php
/**
 * ull_flow header/footer docinfo partial
 *
 * expexts a ull_flow object and prints the show footer
 * 
 * @package    ull_at
 * @subpackage ull_flow
 * @author     Klemens Ullmann
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
?>

<?php 



?> 

<div class='ull_flow_headfoot_float_left'>
  <ul class='ull_flow_headfoot_ul'>
    <li><?php
      if ($user = UllUserPeer::retrieveByPK($ull_flow_doc->getCreatorUserId())) {
          $name = $user->getShortName();
        } else {
          $name = __('Unknown user'); 
        }
      echo __('Created by', null, 'common')
        . ': '
        . $name
        . ' (' . ull_format_datetime($ull_flow_doc->getCreatedAt()) . ')'
      ; 
    ?></li>
    <li><?php 
      if ($user = UllUserPeer::retrieveByPK($ull_flow_doc->getUpdatorUserId())) {
          $name = $user->getShortName();
        } else {
          $name = __('Unknown user'); 
        }
        
      echo __('Last action') . ': ';
      $ull_flow_action = UllFlowActionPeer::retrieveByPK($ull_flow_doc->getUllFlowActionId());
      echo $ull_flow_action->__toString(); // php 5.1 compat
      
      // assigend to
      if ($ull_flow_action->getShowAssignedTo()) {  
        if ($group_id = $ull_flow_doc->getAssignedToUllGroupId()) {
          echo ' ' . __('to') . ' ' . __('group') . ' ';
          echo UllGroupPeer::retrieveByPK($group_id);
        } elseif ($user_id = $ull_flow_doc->getAssignedToUllUserId()) {
  //        echo ' ' . __('to') . ' ';
          echo ' ';
          echo UllUserPeer::retrieveByPK($user_id)->getShortName();
          echo ' ';
        } 
      }
      
      echo ' ' . __('by') . ' '
        . $name
        . ' (' . ull_format_datetime($ull_flow_doc->getUpdatedAt()) . ')'
      ;   
    ?></li> 
    <?php

      if (
        $ull_flow_doc->getUllFlowActionId() <> UllFlowActionPeer::getActionIdBySlug('close')
        and $step_id = $ull_flow_doc->getAssignedToUllFlowStepId()
      ) {
        
        if ($user = UllUserPeer::retrieveByPK($ull_flow_doc->getAssignedToUllUserId())) {
          $name = $user->getShortName();
        } else {
          $name = __('Unknown user'); 
        }
         
        echo '<li>';
        echo __('Next one') . ': ';
        if ($assigned_group = $ull_flow_doc->getAssignedToUllGroupId()) {
          echo __('group', null, 'common') . ': ' . UllGroupPeer::retrieveByPK($assigned_group)->__toString(); // php 5.1 compat
        } else {
          echo $name; 
        }
        echo ' (' . __('Step') . ': ';
        echo ullCoreTools::getI18nField(
          UllFlowStepPeer::retrieveByPK($step_id),
          'caption'
        );
        echo ')'; 
        echo '</li>';
        
      }
    ?>
       

  </ul>
</div>

<div class='ull_flow_headfoot_float_left'>
  <ul class='ull_flow_headfoot_ul'>
    

       
  </ul>
</div>
