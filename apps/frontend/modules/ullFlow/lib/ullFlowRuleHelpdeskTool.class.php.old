<?php

class ullFlowRuleHelpdeskTool extends ullFlowRule {
   
  public function getParams() {
    
    
    if ($this->step_id == $this->getStepIdBySlug('creator')) {
      
      if ($this->action_slug == 'send_to_helpdesk') {
        
        $this->params['next_step'] = $this->getStepIdBySlug('it-helpdesk'); 
        $this->params['next_group'] = UllGroupPeer::getIdByCaption('IT-Helpdesk');          
          
      } elseif ($this->action_slug == 'assign_to_user') {
        
        $this->params['next_step'] = $this->getStepIdBySlug('helpdesk_agent'); 
        $this->params['next_user'] =          
          $this->request->getParameter('ull_flow_action_handler_assign_to_user');
          
      } elseif ($this->action_slug == 'send_to_caller') {
        
        $this->setParamsForCaller();         
          
      } elseif ($this->action_slug == 'close') {
        
        $this->params['next_step'] = $this->getStepIdBySlug('closed') ;
        
      }
      
      
      
    } elseif ($this->step_id == $this->getStepIdBySlug('it-helpdesk')) {
      
      if ($this->action_slug == 'assign_to_user') {
        
        $this->params['next_step'] = $this->getStepIdBySlug('helpdesk_agent'); 
        $this->params['next_user'] =          
          $this->request->getParameter('ull_flow_action_handler_assign_to_user');
          
      } elseif ($this->action_slug == 'send_to_caller') {
        
        $this->setParamsForCaller();     
          
      } elseif ($this->action_slug == 'close') {
        
        $this->params['next_step'] = $this->getStepIdBySlug('closed') ;
        
      }

      
      
    } elseif ($this->step_id == $this->getStepIdBySlug('helpdesk_agent')) {
      
//      if ($this->action_slug == 'send_to_helpdesk') {
//        
//        $this->params['next_step'] = $this->getStepIdBySlug('it-helpdesk'); 
//        $this->params['next_group'] = UllGroupPeer::getIdByCaption('IT-Helpdesk');          
//          
//      } else

      if ($this->action_slug == 'assign_to_user') {
        
        $this->params['next_step'] = $this->getStepIdBySlug('helpdesk_agent'); 
        $this->params['next_user'] =          
          $this->request->getParameter('ull_flow_action_handler_assign_to_user');
          
      } elseif ($this->action_slug == 'send_to_caller') {
        
        $this->setParamsForCaller();
          
      } elseif ($this->action_slug == 'close') {
        
        $this->params['next_step'] = $this->getStepIdBySlug('closed') ;
        
      }      
      
      
      
//    } elseif ($this->step_id == $this->getStepIdBySlug('question_for_caller')) {
//      
//      if ($this->action_slug == 'send_to_helpdesk') {
//        
//        $this->params['next_step'] = $this->getStepIdBySlug('it-helpdesk'); 
//        $this->params['next_group'] = UllGroupPeer::getIdByCaption('IT-Helpdesk');          
//          
//      }     
      
      
    } elseif ($this->step_id == $this->getStepIdBySlug('closed')) {
      
      $this->params['next_step'] = $this->getStepIdBySlug('it-helpdesk');
      $this->params['next_user'] = sfContext::getInstance()->getUser()->getAttribute('user_id');
      
    }
    
  return $this->params;
    
  }
  
  
  protected function setParamsForCaller() {
    
    $this->params['next_step'] = $this->getStepIdBySlug('question_for_caller ');
    
    if (!$value = $this->getFieldValue(3)) { // get caller field
      $value = $this->doc->getCreatorUserId(); // default to creator if no caller is set
    }
    $this->params['next_user'] = $value; 
    
  }
  
}
?>